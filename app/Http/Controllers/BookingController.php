<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingDate;
use App\Models\BookingSlot;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource with FullCalendar events & metrics.
     */
    public function index()
    {
        $bookings = Booking::with(['bookingDate', 'bookingSlot'])
            ->orderBy('id', 'desc')
            ->get();

        $totalBookings = $bookings->count();
        $attendedCount = $bookings->filter(function ($b) {
            return $b->status === 'attended' || $b->status === 'completed' || !is_null($b->attended_at);
        })->count();
        $confirmedCount = $bookings->where('status', 'confirmed')->count();
        $attendanceRate = $totalBookings > 0 ? round(($attendedCount / $totalBookings) * 100, 1) : 0;

        // Map events for FullCalendar JS
        $calendarEvents = $bookings->map(function ($b) {
            $dateStr = ($b->bookingDate && $b->bookingDate->date) 
                ? Carbon::parse($b->bookingDate->date)->format('Y-m-d') 
                : now()->format('Y-m-d');

            $timeStr = ($b->bookingSlot && $b->bookingSlot->start_time)
                ? Carbon::parse($b->bookingSlot->start_time)->format('H:i:s')
                : '10:00:00';

            $startIso = $dateStr . 'T' . $timeStr;
            $isAttended = ($b->status === 'attended' || $b->status === 'completed' || !is_null($b->attended_at));

            $bgColor = $isAttended ? '#10b981' : ($b->status === 'cancelled' ? '#ef4444' : '#e86034');

            $timeSlotText = ($b->bookingSlot && $b->bookingSlot->display_time) ? ' (' . $b->bookingSlot->display_time . ')' : '';

            return [
                'id' => $b->id,
                'title' => $b->customer_name . $timeSlotText,
                'start' => $startIso,
                'backgroundColor' => $bgColor,
                'borderColor' => $bgColor,
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'ref' => $b->reference_no,
                    'name' => $b->customer_name,
                    'email' => $b->customer_email,
                    'phone' => $b->customer_phone,
                    'date' => $b->bookingDate->display_date ?? 'N/A',
                    'time' => $b->bookingSlot->display_time ?? 'N/A',
                    'venue' => $b->venue ?? 'LONGCHAMP POP UP STORE THE GARDENS MALL',
                    'status' => $isAttended ? 'ATTENDED' : strtoupper($b->status),
                    'attended_at' => $b->attended_at ? Carbon::parse($b->attended_at)->format('M d, Y h:i A') : 'Not yet',
                    'is_attended' => $isAttended
                ]
            ];
        });

        $firstBooking = $bookings->filter(function ($b) {
            return $b->bookingDate && $b->bookingDate->date;
        })->first();

        $initialCalendarDate = $firstBooking 
            ? Carbon::parse($firstBooking->bookingDate->date)->format('Y-m-d')
            : now()->format('Y-m-d');

        return view('admin.booking.index', compact(
            'bookings',
            'calendarEvents',
            'totalBookings',
            'attendedCount',
            'confirmedCount',
            'attendanceRate',
            'initialCalendarDate'
        ));
    }

    /**
     * Mark a booking attendance state (toggle or set attended)
     */
    public function markAttended($id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->status === 'attended' || !is_null($booking->attended_at)) {
            $booking->status = 'confirmed';
            $booking->attended_at = null;
            $message = 'Booking marked as NOT ATTENDED.';
        } else {
            $booking->status = 'attended';
            $booking->attended_at = now();
            $message = 'Booking verified & marked as ATTENDED!';
        }

        $booking->save();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'status' => $booking->status,
                'attended_at' => $booking->attended_at ? Carbon::parse($booking->attended_at)->format('M d, Y h:i A') : null
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Booking deleted successfully.']);
        }

        return redirect()
            ->route('bookings')
            ->with('success', 'Booking deleted successfully.');
    }
}
