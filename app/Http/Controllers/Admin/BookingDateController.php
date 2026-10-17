<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookingDate;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BookingDateController extends Controller
{
    public function index()
    {
        $dates = BookingDate::withCount('bookings')
            ->orderBy('date')
            ->get();

        return view('admin.booking-dates.index', compact('dates'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'date' => ['required', 'date', 'unique:booking_dates,date'],
            'capacity' => ['required', 'integer', 'min:0'],
            'is_available' => ['nullable', 'boolean'],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);
        $data['is_available'] = $request->boolean('is_available');

        $date = BookingDate::create($data);

        return redirect()->route('booking-dates.show', $date)->with('success', 'Booking date created.');
    }

    public function show(BookingDate $bookingDate)
    {
        $bookings = $bookingDate->bookings()->orderBy('id')->get();

        return view('admin.booking-dates.show', ['date' => $bookingDate, 'bookings' => $bookings]);
    }

    public function update(Request $request, BookingDate $bookingDate)
    {
        $data = $request->validate([
            'date' => ['required', 'date', Rule::unique('booking_dates', 'date')->ignore($bookingDate->id)],
            'capacity' => ['required', 'integer', 'min:0'],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);
        $data['is_available'] = $request->boolean('is_available');

        $bookingDate->update($data);

        return back()->with('success', 'Booking date updated.');
    }

    public function destroy(BookingDate $bookingDate)
    {
        if ($bookingDate->bookings()->exists()) {
            return back()->withErrors(['delete' => 'Cannot delete a date that has bookings. Mark it closed instead.']);
        }

        $bookingDate->delete();

        return redirect()->route('booking-dates.index')->with('success', 'Booking date deleted.');
    }
}
