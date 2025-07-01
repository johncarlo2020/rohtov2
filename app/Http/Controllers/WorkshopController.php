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
            ->where('status', 'pending')
            ->exists();
            if($check) {
                return redirect()->route('workshop.congrats');
            }
        return view('workshop.index', compact('workshops'));
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

        $availableSlots = 20 - $attendeeCount;

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

        $availableSlots = 20 - $attendeeCount;

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

    public function congrats()
    {
        $appointment = Appointment::with(['appointmentDate', 'workshop'])
            ->where('user_id', auth()->id())
            ->where('status', 'pending')
            ->first();

        return view('workshop.congrats', compact('appointment'));
    }
}
