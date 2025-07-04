<?php

namespace App\Http\Controllers;
use App\Models\Appointment;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $appointments = Appointment::with(['workshop', 'appointmentDate', 'user'])->get();

        $bookingSummary = Appointment::select(
            'appointment_date_id',
            'workshop_id',
            DB::raw('sum(attendee) as booked_slots')
        )
            ->groupBy('appointment_date_id', 'workshop_id')
            ->with(['appointmentDate', 'workshop'])
            ->get();

        $summary = [];
        foreach ($bookingSummary as $booking) {
            if ($booking->appointmentDate) {
                $date = $booking->appointmentDate->date;
                $day = date('l', strtotime($date));

                if (!isset($summary[$day])) {
                    $summary[$day] = [];
                }

                if ($booking->workshop) {
                    $slotsPerWorkshop = ($booking->workshop->id == 2) ? 14 : 20;
                    $summary[$day][] = [
                        'workshop_name' => $booking->workshop->title,
                        'time' => $booking->workshop->time,
                        'total_slots' => $slotsPerWorkshop,
                        'booked_slots' => $booking->booked_slots,
                        'balance' => $slotsPerWorkshop - $booking->booked_slots,
                    ];
                }
            }
        }

        return view('admin.booking.index', compact('appointments', 'summary'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        return redirect()
            ->route('bookings.index')
            ->with('success', 'Appointment deleted successfully.');
    }
}
