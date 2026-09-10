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
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        $existingBooking = null;

        if ($user) {
            $existingBooking = \App\Models\Booking::with(['bookingDate', 'bookingSlot'])
                ->where(function ($q) use ($user) {
                    $q->where('customer_email', strtolower($user->email));
                    $phone1 = trim($user->phone_number ?? '');
                    $phone2 = trim($user->number ?? '');
                    if (!empty($phone1) && !in_array($phone1, ['-', 'N/A', 'null'])) {
                        $q->orWhere('customer_phone', $phone1);
                    }
                    if (!empty($phone2) && !in_array($phone2, ['-', 'N/A', 'null'])) {
                        $q->orWhere('customer_phone', $phone2);
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

            if ($refBooking && ($refBooking->customer_email === $user->email || $refBooking->customer_phone === ($user->number ?? $user->phone_number ?? null))) {
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
            $dayOfWeek = strtoupper($dateObj->format('l'));
            $monthName = strtoupper($dateObj->format('F'));
            $dateStr = $dayOfWeek . ', ' . $day . $suffix . ' ' . $monthName;

            $startTime = Carbon::parse($existingBooking->bookingSlot->start_time);
            $endTime = Carbon::parse($existingBooking->bookingSlot->end_time);
            $timeStr = strtoupper($startTime->format('g:iA'));
            $timeSlotLabel = strtoupper($startTime->format('g:iA') . ' - ' . $endTime->format('g:iA'));

            $customerName = $existingBooking->customer_name ?: (trim(($user->fname ?? '') . ' ' . ($user->lname ?? '')) ?: ($user->name ?? 'CUSTOMER'));
            $firstName = strtoupper(explode(' ', trim($customerName))[0]);

            $slotDateStr = $dateObj->format('Y-m-d');
            $slotTimeStr = $existingBooking->bookingSlot->start_time ?? '00:00:00';
            $slotDateTime = Carbon::parse($slotDateStr . ' ' . $slotTimeStr);

            $rescheduleCount = (int) $existingBooking->reschedule_count;
            $canModify = ($rescheduleCount < 1) && now()->lessThan($slotDateTime->copy()->subDays(7));

            $formattedBooking = [
                'reference_no' => $existingBooking->reference_no,
                'booking_date_id' => $existingBooking->booking_date_id,
                'booking_slot_id' => $existingBooking->booking_slot_id,
                'reschedule_count' => $rescheduleCount,
                'can_modify' => $canModify,
                'date_raw' => $dateObj->format('Y-m-d'),
                'date_formatted' => $dateStr,
                'time_formatted' => $timeSlotLabel,
                'time_label' => $timeSlotLabel,
                'display_text' => $dateStr . ' AT ' . $timeSlotLabel,
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
        if (!auth()->check()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Unauthenticated. Please log in to complete your booking.',
                    'errors' => ['auth' => ['Please log in to complete your booking.']]
                ], 401);
            }
            return redirect()->route('login');
        }

        $user = auth()->user();

        $fullName = trim(($user->fname ?? '') . ' ' . ($user->lname ?? ''));
        if (empty($fullName)) {
            $fullName = $user->name ?? 'Customer';
        }

        $customerName = $request->input('customer_name') ?? $request->input('name') ?? $fullName;
        $customerEmail = $user->email;
        $customerPhone = $user->phone_number ?? $user->number ?? '-';

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
                $dateObj = Carbon::parse($booking->bookingDate->date);
                $day = $dateObj->day;
                $suffix = 'TH';
                if (!in_array($day, [11, 12, 13])) {
                    switch ($day % 10) {
                        case 1: $suffix = 'ST'; break;
                        case 2: $suffix = 'ND'; break;
                        case 3: $suffix = 'RD'; break;
                    }
                }
                $dayOfWeek = strtoupper($dateObj->format('l'));
                $monthName = strtoupper($dateObj->format('F'));
                $dateFormatted = $dayOfWeek . ', ' . $day . $suffix . ' ' . $monthName;

                $startTime = Carbon::parse($slot->start_time)->format('g:iA');
                $endTime = Carbon::parse($slot->end_time)->format('g:iA');
                $timeLabel = strtoupper("{$startTime} - {$endTime}");

                $slotDateTime = Carbon::parse($dateObj->format('Y-m-d') . ' ' . ($slot->start_time ?? '00:00:00'));
                $rescheduleCount = (int) $booking->reschedule_count;
                $canModify = ($rescheduleCount < 1) && now()->lessThan($slotDateTime->copy()->subDays(7));

                return response()->json([
                    'success' => true,
                    'message' => 'BOOKING CONFIRMED',
                    'data' => [
                        'reference_no' => $booking->reference_no,
                        'reschedule_count' => $rescheduleCount,
                        'can_modify' => $canModify,
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

            $slot = $booking->bookingSlot;
            $dateObj = Carbon::parse($booking->bookingDate->date);
            $day = $dateObj->day;
            $suffix = 'TH';
            if (!in_array($day, [11, 12, 13])) {
                switch ($day % 10) {
                    case 1: $suffix = 'ST'; break;
                    case 2: $suffix = 'ND'; break;
                    case 3: $suffix = 'RD'; break;
                }
            }
            $dayOfWeek = strtoupper($dateObj->format('l'));
            $monthName = strtoupper($dateObj->format('F'));
            $dateFormatted = $dayOfWeek . ', ' . $day . $suffix . ' ' . $monthName;

            $startTime = Carbon::parse($slot->start_time)->format('g:iA');
            $endTime = Carbon::parse($slot->end_time)->format('g:iA');
            $timeLabel = strtoupper("{$startTime} - {$endTime}");

            return redirect()->route('booking.flow')->with('success_booking', [
                'reference_no' => $booking->reference_no,
                'reschedule_count' => (int) $booking->reschedule_count,
                'date' => $dateFormatted,
                'time' => $timeLabel,
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
        if (!auth()->check()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Unauthenticated. Please log in to modify your booking.',
                    'errors' => ['auth' => ['Please log in to modify your booking.']]
                ], 401);
            }
            return redirect()->route('login');
        }

        $referenceNo = $request->input('reference_no');

        if (auth()->check()) {
            $user = auth()->user();
            $userBooking = \App\Models\Booking::where(function ($q) use ($user) {
                $q->where('customer_email', strtolower($user->email));
                $phone1 = trim($user->phone_number ?? '');
                $phone2 = trim($user->number ?? '');
                if (!empty($phone1) && !in_array($phone1, ['-', 'N/A', 'null'])) {
                    $q->orWhere('customer_phone', $phone1);
                }
                if (!empty($phone2) && !in_array($phone2, ['-', 'N/A', 'null'])) {
                    $q->orWhere('customer_phone', $phone2);
                }
            })
            ->where('status', 'confirmed')
            ->latest()
            ->first();

            if (!$userBooking && session()->has('latest_booking_ref')) {
                $refBooking = \App\Models\Booking::where('reference_no', session('latest_booking_ref'))
                    ->where('status', 'confirmed')
                    ->first();
                if ($refBooking && ($refBooking->customer_email === $user->email || $refBooking->customer_phone === ($user->number ?? $user->phone_number ?? null))) {
                    $userBooking = $refBooking;
                }
            }

            if (!$userBooking) {
                return response()->json([
                    'message' => 'No active booking reference found to modify.',
                    'errors' => ['booking' => ['No active booking reference found to modify.']]
                ], 422);
            }

            if ($referenceNo && $userBooking->reference_no !== $referenceNo) {
                return response()->json([
                    'message' => 'You are not authorized to modify this booking.',
                    'errors' => ['booking' => ['You are not authorized to modify this booking.']]
                ], 403);
            }

            $referenceNo = $userBooking->reference_no;
        }

        if (!$referenceNo && session()->has('latest_booking_ref')) {
            $referenceNo = session('latest_booking_ref');
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
            $dateObj = Carbon::parse($booking->bookingDate->date);
            $day = $dateObj->day;
            $suffix = 'TH';
            if (!in_array($day, [11, 12, 13])) {
                switch ($day % 10) {
                    case 1: $suffix = 'ST'; break;
                    case 2: $suffix = 'ND'; break;
                    case 3: $suffix = 'RD'; break;
                }
            }
            $dayOfWeek = strtoupper($dateObj->format('l'));
            $monthName = strtoupper($dateObj->format('F'));
            $dateFormatted = $dayOfWeek . ', ' . $day . $suffix . ' ' . $monthName;

            $startTime = Carbon::parse($slot->start_time)->format('g:iA');
            $endTime = Carbon::parse($slot->end_time)->format('g:iA');
            $timeLabel = strtoupper("{$startTime} - {$endTime}");

            $slotDateTime = Carbon::parse($dateObj->format('Y-m-d') . ' ' . ($slot->start_time ?? '00:00:00'));
            $rescheduleCount = (int) $booking->reschedule_count;
            $canModify = ($rescheduleCount < 1) && now()->lessThan($slotDateTime->copy()->subDays(7));

            return response()->json([
                'success' => true,
                'message' => 'BOOKING MODIFIED SUCCESSFULLY',
                'data' => [
                    'reference_no' => $booking->reference_no,
                    'reschedule_count' => $rescheduleCount,
                    'can_modify' => $canModify,
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
