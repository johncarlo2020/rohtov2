<?php

namespace App\Http\Controllers;

use App\Models\BookingDate;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RsvpDashboardController extends Controller
{
    public const VENUE = '1278 Songwat Road, Bangkok';
    public const TIME = '2:00PM - 3:00PM';

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(['date' => ['required', 'date_format:Y-m-d', 'exists:booking_dates,date']]);
        $user = $request->user();

        $booking = DB::transaction(function () use ($data, $user) {
            // Serialize reservations for this account and for the selected date.
            $user->newQuery()->whereKey($user->id)->lockForUpdate()->firstOrFail();
            $existing = Booking::where('customer_email', $user->email)
                ->where('status', '!=', 'cancelled')->latest()->first();
            if ($existing) {
                return $existing;
            }

            $date = BookingDate::where('date', $data['date'])->lockForUpdate()->firstOrFail();
            if (!$date->is_available || $date->date->isPast() && !$date->date->isToday()
                || $date->bookings()->where('status', '!=', 'cancelled')->count() >= $date->capacity) {
                throw ValidationException::withMessages(['date' => 'This date is no longer available. Please choose another date.']);
            }

            return Booking::create([
                'booking_date_id' => $date->id,
                'reference_no' => 'RSVP-' . strtoupper((string) Str::uuid()),
                'customer_name' => trim(($user->fname ?? '') . ' ' . ($user->lname ?? '')) ?: ($user->name ?: 'Guest'),
                'customer_email' => $user->email,
                'customer_phone' => $user->number ?? $user->phone_number ?? '',
                'venue' => self::VENUE,
                'status' => 'confirmed',
            ]);
        });

        if ($booking->wasRecentlyCreated) {
            try {
                app(\App\Services\RsvpConfirmationEmailService::class)->send($booking);
            } catch (\Throwable $exception) {
                report($exception);
                $request->session()->flash('email_warning', 'Your booking is confirmed, but we could not send your email. Please save your pass below.');
            }
        }

        return redirect()->route('rsvp.confirmed', $booking->reference_no);
    }

    public function confirmed(Request $request, string $reference): View
    {
        $booking = Booking::with('bookingDate')->where('reference_no', $reference)
            ->where('customer_email', $request->user()->email)->firstOrFail();

        return view('rsvpConfirmed', ['booking' => $booking, 'time' => self::TIME]);
    }

    public function index(Request $request): View|RedirectResponse
    {
        $booking = Booking::where('customer_email', $request->user()->email)
            ->where('status', '!=', 'cancelled')->latest()->first();

        if ($booking) {
            return redirect()->route('rsvp.confirmed', $booking->reference_no);
        }

        $bookingDates = BookingDate::withCount([
            'bookings as active_bookings_count' => fn ($query) => $query->where('status', '!=', 'cancelled'),
        ])->orderBy('date')->get();

        $bookingDates->each(function (BookingDate $bookingDate) {
            $bookingDate->setAttribute('remaining_slots', $bookingDate->is_available
                ? max(0, $bookingDate->capacity - $bookingDate->active_bookings_count)
                : 0);
        });

        return view('rsvpDashboard', compact('bookingDates'));
    }
}
