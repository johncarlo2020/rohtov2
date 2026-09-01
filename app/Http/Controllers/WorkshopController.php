<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Appointment;
use App\Models\AppointmentDate;
use App\Models\Workshop;


class WorkshopController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $check = Appointment::where('user_id', $user->id)
            ->exists();
            if($check) {

                return redirect()->route('workshop.congrats');
            }

        return view('workshop.index' );
    }

    public function register()
    {
        $workshops = Workshop::all();
        $appointmentDates = AppointmentDate::all();
        $appointments = Appointment::all();
        return view('workshop.register', compact('workshops', 'appointmentDates', 'appointments'));
    }

    public function check(Request $request)
    {
        $attendeeCount = Appointment::where('workshop_id', $request->id)
            ->where('appointment_date_id', $request->date)
            ->sum('attendee'); //  sum instead of count

        if($request->id == 2){
            $availableSlots = 14 - $attendeeCount;
        }else{
            $availableSlots = 20 - $attendeeCount;
        }

        return response()->json(['slots' => $availableSlots]);
    }

    public function submit(Request $request)
    {
        $request->validate([
            'guardian' => 'required|string|max:255',
            'workshop' => 'required|exists:workshops,id',
            'date' => 'required|exists:appointment_dates,id',
            'attendee' => 'required|integer|min:1|max:3',
        ]);
        $attendeeCount = Appointment::where('workshop_id', $request->workshop)
            ->where('appointment_date_id', $request->date)
            ->sum('attendee'); //  sum instead of count

        if($request->workshop == 2){
            $availableSlots = 14 - $attendeeCount;
        }else{
            $availableSlots = 20 - $attendeeCount;
        }

        if($availableSlots < $request->attendee) {
            return response()->json(['error' => 'Not enough slots available'], 400);
        }

        $appointment = new Appointment();
        $appointment->guardian = $request->guardian;
        $appointment->user_id = auth()->id();
        $appointment->workshop_id = $request->workshop;
        $appointment->appointment_date_id = $request->date;
        $appointment->attendee = $request->attendee;
        $appointment->status = 'pending';
        $appointment->save();

        return response()->json(['data' => $appointment]);
    }

    public function scan(Request $request)
    {
        $qrCodeMessage = trim($request->input('qrCodeMessage', ''));

        if (!$qrCodeMessage) {
            return response()->json(['status' => 'invalid', 'message' => 'Missing QR code value.'], 400);
        }

        // Handle URL inputs or raw reference codes
        $refNo = $qrCodeMessage;
        if (filter_var($qrCodeMessage, FILTER_VALIDATE_URL)) {
            $parsedUrl = parse_url($qrCodeMessage);
            parse_str($parsedUrl['query'] ?? '', $queryParams);
            if (!empty($queryParams['ref'])) {
                $refNo = $queryParams['ref'];
            } elseif (!empty($queryParams['id'])) {
                $refNo = $queryParams['id'];
            }
        }

        // 1. Search by Booking reference_no, customer_email, or customer_phone
        $booking = \App\Models\Booking::with(['bookingDate', 'bookingSlot'])
            ->where('reference_no', $refNo)
            ->orWhere('customer_email', $qrCodeMessage)
            ->orWhere('customer_phone', $qrCodeMessage)
            ->first();

        // 2. If not found directly, check if user exists by ID/email/phone
        if (!$booking) {
            $user = \App\Models\User::where('id', $refNo)
                ->orWhere('email', $qrCodeMessage)
                ->orWhere('number', $qrCodeMessage)
                ->first();

            if ($user) {
                $booking = \App\Models\Booking::with(['bookingDate', 'bookingSlot'])
                    ->where('customer_email', $user->email)
                    ->orWhere('customer_phone', $user->number)
                    ->latest()
                    ->first();
            }
        }

        if (!$booking) {
            return response()->json([
                'status' => 'invalid',
                'message' => '❌ Invalid QR Code or Booking Reference ("' . $refNo . '") not found.'
            ]);
        }

        // Check if already attended
        if ($booking->status === 'attended' || $booking->status === 'completed' || !is_null($booking->attended_at)) {
            $attendedTime = $booking->attended_at 
                ? \Carbon\Carbon::parse($booking->attended_at)->format('M d, Y h:i A')
                : 'earlier';

            return response()->json([
                'status' => 'already_redeemed',
                'message' => '⚠️ ALREADY ATTENDED! Customer ' . $booking->customer_name . ' was verified on ' . $attendedTime . '.',
                'booking' => [
                    'name' => $booking->customer_name,
                    'email' => $booking->customer_email,
                    'phone' => $booking->customer_phone,
                    'ref' => $booking->reference_no,
                    'date' => $booking->bookingDate->display_date ?? 'N/A',
                    'time' => $booking->bookingSlot->display_time ?? 'N/A',
                    'venue' => $booking->venue,
                    'status' => 'ATTENDED',
                    'attended_at' => $attendedTime,
                ]
            ]);
        }

        // Mark Attendance
        $booking->status = 'attended';
        $booking->attended_at = now();
        $booking->save();

        return response()->json([
            'status' => 'success',
            'message' => '✅ ATTENDANCE VERIFIED! Welcome ' . $booking->customer_name . '.',
            'booking' => [
                'name' => $booking->customer_name,
                'email' => $booking->customer_email,
                'phone' => $booking->customer_phone,
                'ref' => $booking->reference_no,
                'date' => $booking->bookingDate->display_date ?? 'N/A',
                'time' => $booking->bookingSlot->display_time ?? 'N/A',
                'venue' => $booking->venue,
                'status' => 'ATTENDED',
                'attended_at' => now()->format('M d, Y h:i A'),
            ]
        ]);
    }


    public function congrats()
    {
        $appointment = Appointment::with(['appointmentDate', 'workshop'])
            ->where('user_id', auth()->id())
            ->first();

        return view('workshop.congrats', compact('appointment'));
    }
}
