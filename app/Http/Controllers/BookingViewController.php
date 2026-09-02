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
    /**
     * Show the main booking flow view.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $existingBooking = null;

        if ($user) {
            $existingBooking = \App\Models\Booking::with(['bookingDate', 'bookingSlot'])
                ->where(function ($q) use ($user) {
                    $q->where('customer_email', $user->email);
                    if (!empty($user->phone_number)) {
                        $q->orWhere('customer_phone', $user->phone_number);
                    }
                    if (!empty($user->number)) {
                        $q->orWhere('customer_phone', $user->number);
                    }
                })
                ->where('status', 'confirmed')
                ->latest()
                ->first();
        }

        if (!$existingBooking && session()->has('latest_booking_ref')) {
            $refBooking = \App\Models\Booking::with(['bookingDate', 'bookingSlot'])
                ->where('reference_no', session('latest_booking_ref'))
                ->where('status', 'confirmed')
                ->latest()
                ->first();

            if ($refBooking && (!$user || $refBooking->customer_email === $user->email || $refBooking->customer_phone === ($user->number ?? $user->phone_number ?? null))) {
                $existingBooking = $refBooking;
            }
        }

        $formattedBooking = null;
        if ($existingBooking && $existingBooking->bookingDate && $existingBooking->bookingSlot) {
            $dateObj = Carbon::parse($existingBooking->bookingDate->date);
            $day = $dateObj->day;
            $suffix = 'TH';
            if (!in_array($day, [11, 12, 13])) {
                switch ($day % 10) {
                    case 1: $suffix = 'ST'; break;
                    case 2: $suffix = 'ND'; break;
                    case 3: $suffix = 'RD'; break;
                }
            }
            $dateStr = $day . $suffix . ' ' . strtoupper($dateObj->format('F'));

            $startTime = Carbon::parse($existingBooking->bookingSlot->start_time);
            $endTime = Carbon::parse($existingBooking->bookingSlot->end_time);
            $timeStr = strtoupper($startTime->format('g:iA'));
            $timeSlotLabel = strtoupper($startTime->format('g:iA') . ' - ' . $endTime->format('g:iA'));

            $customerName = $existingBooking->customer_name ?: ($user->fname ?? 'CUSTOMER');
            $firstName = strtoupper(explode(' ', trim($customerName))[0]);

            $formattedBooking = [
                'reference_no' => $existingBooking->reference_no,
                'reschedule_count' => (int) $existingBooking->reschedule_count,
                'date_raw' => $dateObj->format('Y-m-d'),
                'date_formatted' => $dateStr,
                'time_formatted' => $timeStr,
                'time_label' => $timeSlotLabel,
                'display_text' => $dateStr . ' AT ' . $timeStr,
                'customer_name' => strtoupper($customerName),
                'first_name' => $firstName,
            ];
        }

        return view('booking', compact('existingBooking', 'formattedBooking'));
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
                        'reschedule_count' => (int) $booking->reschedule_count,
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
                'reschedule_count' => (int) $booking->reschedule_count,
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
            if (auth()->check()) {
                $user = auth()->user();
                $userBooking = \App\Models\Booking::where(function ($q) use ($user) {
                    $q->where('customer_email', $user->email);
                    if (!empty($user->phone_number)) {
                        $q->orWhere('customer_phone', $user->phone_number);
                    }
                    if (!empty($user->number)) {
                        $q->orWhere('customer_phone', $user->number);
                    }
                })
                ->where('status', 'confirmed')
                ->latest()
                ->first();

                if ($userBooking) {
                    $referenceNo = $userBooking->reference_no;
                }
            }

            if (!$referenceNo && session()->has('latest_booking_ref')) {
                $referenceNo = session('latest_booking_ref');
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
                    'reschedule_count' => (int) $booking->reschedule_count,
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
