<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\BookingSlot;
use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BookingViewController extends Controller
{
    protected BookingService $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    /**
     * Show the main booking flow view.
     */
    public function index()
    {
        return view('booking');
    }

    /**
     * Handle form submission for web route /reservation-create or /booking.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $sessionId = session()->getId();

        $customerName = $request->input('customer_name') 
            ?? $request->input('name') 
            ?? ($user ? trim(($user->fname ?? '') . ' ' . ($user->lname ?? '')) : null)
            ?? 'Guest Customer';

        $customerEmail = $request->input('customer_email') 
            ?? $request->input('email') 
            ?? ($user ? $user->email : null)
            ?? ('guest_' . $sessionId . '@guest.com');

        $customerPhone = $request->input('customer_phone') 
            ?? $request->input('phone') 
            ?? $request->input('phone_number')
            ?? ($user ? ($user->phone_number ?? $user->phone ?? null) : null)
            ?? ('guest_' . $sessionId);

        $date = $request->input('date');
        $slotId = $request->input('slot_id');

        // If slot_id is not directly passed, attempt to find slot by time_slot or start_time
        if (!$slotId && $date && $request->has('time_slot')) {
            $timeSlotString = $request->input('time_slot'); // e.g. "09:00 - 10:00" or "14:00 - 15:00"
            $parts = explode('-', $timeSlotString);
            $startTime = trim($parts[0]);

            $slot = BookingSlot::whereHas('bookingDate', function ($q) use ($date) {
                $q->where('date', $date);
            })->where('start_time', 'LIKE', $startTime . '%')->first();

            if ($slot) {
                $slotId = $slot->id;
            }
        }

        $validatedData = [
            'date' => $date,
            'slot_id' => $slotId,
            'customer_name' => $customerName,
            'customer_email' => $customerEmail,
            'customer_phone' => $customerPhone,
        ];

        // Validate basic parameters
        $validator = validator($validatedData, (new StoreBookingRequest())->rules());

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        try {
            $booking = $this->bookingService->createBooking($validatedData);

            // Store in session for easy reference lookup
            session(['latest_booking_ref' => $booking->reference_no]);

            if ($request->wantsJson()) {
                $slot = $booking->bookingSlot;
                $dateFormatted = Carbon::parse($booking->bookingDate->date)->format('F j, Y');
                $timeLabel = Carbon::parse($slot->start_time)->format('g:i A') . ' - ' . Carbon::parse($slot->end_time)->format('g:i A');

                return response()->json([
                    'success' => true,
                    'message' => 'BOOKING CONFIRMED',
                    'data' => [
                        'reference_no' => $booking->reference_no,
                        'date' => $dateFormatted,
                        'time' => $timeLabel,
                        'status' => strtoupper($booking->status),
                        'customer' => [
                            'name' => $booking->customer_name,
                            'email' => $booking->customer_email,
                            'phone' => $booking->customer_phone,
                        ]
                    ]
                ], 201);
            }

            return redirect()->route('booking.flow')->with('success_booking', [
                'reference_no' => $booking->reference_no,
                'date' => Carbon::parse($booking->bookingDate->date)->format('F j, Y'),
                'time' => Carbon::parse($booking->bookingSlot->start_time)->format('g:i A') . ' - ' . Carbon::parse($booking->bookingSlot->end_time)->format('g:i A'),
                'customer_name' => $booking->customer_name,
                'customer_email' => $booking->customer_email,
                'customer_phone' => $booking->customer_phone,
            ]);

        } catch (ValidationException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => $e->getMessage(),
                    'errors' => $e->errors()
                ], 422);
            }
            return back()->withErrors($e->errors())->withInput();
        }
    }

    /**
     * Handle booking modification / reschedule.
     */
    public function modify(Request $request)
    {
        $referenceNo = $request->input('reference_no');

        if (!$referenceNo) {
            if (session()->has('latest_booking_ref')) {
                $referenceNo = session('latest_booking_ref');
            } elseif (auth()->check()) {
                $userBooking = \App\Models\Booking::where('customer_email', auth()->user()->email)
                    ->where('status', 'confirmed')
                    ->latest()
                    ->first();
                if ($userBooking) {
                    $referenceNo = $userBooking->reference_no;
                }
            }
        }

        if (!$referenceNo) {
            return response()->json([
                'message' => 'No active booking reference found to modify.',
                'errors' => ['booking' => ['No active booking reference found to modify.']]
            ], 422);
        }

        $validated = $request->validate([
            'date' => 'required|date_format:Y-m-d',
            'slot_id' => 'required|integer|exists:booking_slots,id',
        ]);

        try {
            $booking = $this->bookingService->modifyBooking(
                $referenceNo,
                $validated['date'],
                $validated['slot_id']
            );

            // Update session reference
            session(['latest_booking_ref' => $booking->reference_no]);

            $slot = $booking->bookingSlot;
            $dateFormatted = Carbon::parse($booking->bookingDate->date)->format('F j, Y');
            $timeLabel = Carbon::parse($slot->start_time)->format('g:i A') . ' - ' . Carbon::parse($slot->end_time)->format('g:i A');

            return response()->json([
                'success' => true,
                'message' => 'BOOKING MODIFIED SUCCESSFULLY',
                'data' => [
                    'reference_no' => $booking->reference_no,
                    'date' => $dateFormatted,
                    'time' => $timeLabel,
                    'status' => strtoupper($booking->status),
                    'customer' => [
                        'name' => $booking->customer_name,
                        'email' => $booking->customer_email,
                        'phone' => $booking->customer_phone,
                    ]
                ]
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        }
    }
}
