<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingScanController extends Controller
{
    public function index()
    {
        return view('admin.booking-scan');
    }

    public function store(Request $request)
    {
        $request->merge(['reference_no' => trim((string) $request->input('reference_no'))]);
        $data = $request->validate(['reference_no' => ['required', 'string', 'max:255']]);
        $booking = Booking::where('reference_no', $data['reference_no'])->first();
        if (!$booking) {
            return response()->json(['message' => 'Booking reference not found.'], 404);
        }
        if ($booking->status === 'cancelled') {
            return response()->json(['message' => 'This booking is cancelled.'], 422);
        }

        $updated = Booking::whereKey($booking->id)->whereNotIn('status', ['cancelled', 'attended', 'completed'])
            ->whereNull('attended_at')->update(['status' => 'attended', 'attended_at' => now()]);
        $booking->refresh();

        return response()->json([
            'message' => $updated ? 'Attendance recorded.' : 'This booking is already attended.',
            'name' => $booking->customer_name,
            'reference' => $booking->reference_no,
            'attended_at' => $booking->attended_at?->format('M j, Y g:i A'),
        ]);
    }
}
