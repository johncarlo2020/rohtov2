<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;
use App\Models\BookingDate;
use App\Models\BookingSlot;
use Illuminate\Support\Str;
use Carbon\Carbon;

class VipController extends Controller
{
    public function index()
    {
        // 1. VIP users list for dropdown
        $users = User::whereDoesntHave('roles', function ($q) {
            $q->where('name', 'admin');
        })->orderBy('fname')->get();

        // 2. Dates & Slots for form selection (Sept 30 to Oct 17)
        $bookingDates = BookingDate::with('slots')
            ->where('is_available', true)
            ->whereBetween('date', ['2026-09-30', '2026-10-17'])
            ->get();
        $bookingSlots = BookingSlot::with('bookingDate')->where('is_available', true)->get();

        // 3. Public Count Table (Grouped by Date and Time Slot)
        $publicCounts = BookingSlot::with(['bookingDate', 'bookings' => function ($q) {
            $q->where('is_vip', false);
        }])
        ->whereHas('bookingDate', function ($q) {
            $q->where('is_available', true);
        })
        ->get()
        ->map(function ($slot) {
            $publicPax = $slot->bookings->sum('pax');
            $vipPax = Booking::where('booking_slot_id', $slot->id)->where('is_vip', true)->sum('pax');
            return [
                'id' => $slot->id,
                'date' => $slot->bookingDate->display_date ?? 'N/A',
                'raw_date' => $slot->bookingDate->date ?? '',
                'time' => $slot->display_time,
                'capacity' => $slot->capacity,
                'public_pax' => $publicPax,
                'vip_pax' => $vipPax,
                'total_pax' => $publicPax + $vipPax,
                'remaining' => max(0, $slot->capacity - ($publicPax + $vipPax)),
            ];
        });

        // 4. VIP Bookings Table
        $vipBookings = Booking::with(['bookingDate', 'bookingSlot'])
            ->where('is_vip', true)
            ->latest()
            ->get();

        return view('admin.vip.index', compact('users', 'bookingDates', 'bookingSlots', 'publicCounts', 'vipBookings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'vip_name' => 'required_without:user_id|nullable|string|max:255',
            'booking_date_id' => 'required|exists:booking_dates,id',
            'booking_slot_id' => 'required|exists:booking_slots,id',
            'pax' => 'required|integer|min:1|max:50',
        ]);

        $customerName = 'VIP Guest';
        $customerEmail = 'vip@longchamp.com';
        $customerPhone = '-';

        if ($request->filled('user_id')) {
            $user = User::find($request->user_id);
            if ($user) {
                $customerName = trim(($user->fname ?? '') . ' ' . ($user->lname ?? ''));
                if (empty($customerName)) {
                    $customerName = $user->name ?? 'VIP Guest';
                }
                $customerEmail = $user->email;
                $customerPhone = $user->number ?? '-';
            }
        } elseif ($request->filled('vip_name')) {
            $customerName = $request->vip_name;
        }

        $refNo = 'VIP-' . strtoupper(Str::random(6));

        $booking = Booking::create([
            'booking_date_id' => $request->booking_date_id,
            'booking_slot_id' => $request->booking_slot_id,
            'reference_no' => $refNo,
            'customer_name' => $customerName,
            'vip_name' => $customerName,
            'customer_email' => $customerEmail,
            'customer_phone' => $customerPhone,
            'venue' => 'LONGCHAMP POP UP STORE THE GARDENS MALL',
            'is_vip' => true,
            'pax' => (int) $request->pax,
            'status' => 'Not Yet Attended',
        ]);

        // Increment booked_count on the slot
        $slot = BookingSlot::find($request->booking_slot_id);
        if ($slot) {
            $slot->increment('booked_count', (int) $request->pax);
        }

        return redirect()->back()->with('success', 'VIP Reservation created successfully!');
    }

    public function markAttended($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->status = 'Attended';
        $booking->attended_at = now();
        $booking->save();

        return redirect()->back()->with('success', 'VIP marked as Attended!');
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        if ($booking->bookingSlot) {
            $booking->bookingSlot->decrement('booked_count', max(1, $booking->pax));
        }
        $booking->delete();

        return redirect()->back()->with('success', 'VIP Booking deleted successfully.');
    }
}
