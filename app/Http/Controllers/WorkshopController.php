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
        $qrCodeMessage = $request->input('qrCodeMessage');

        if (!$qrCodeMessage) {
            return response()->json(['status' => 'invalid', 'message' => 'Missing QR code message'], 400);
        }

        // Extract user ID from the QR code message URL
        $parsedUrl = parse_url($qrCodeMessage);
        parse_str($parsedUrl['query'] ?? '', $queryParams);

        $userId = $queryParams['id'] ?? null;

        if (!$userId) {
            return response()->json(['status' => 'invalid', 'message' => 'User ID not found in QR code'], 400);
        }

        // Find the appointment
        $appointment = Appointment::where('user_id', $userId)->first();

        if (!$appointment) {
            return response()->json(['status' => 'invalid', 'message' => 'Appointment not found'], 404);
        }

        if ($appointment->status === 'confirmed') {
            return response()->json(['status' => 'already_redeemed', 'message' => 'Appointment already confirmed']);
        }

        // Confirm the appointment
        $appointment->status = 'confirmed';
        $appointment->save();

        return response()->json(['status' => 'success', 'message' => 'Appointment confirmed']);
    }


    public function congrats()
    {
        $appointment = Appointment::with(['appointmentDate', 'workshop'])
            ->where('user_id', auth()->id())
            ->first();

        return view('workshop.congrats', compact('appointment'));
    }
}
