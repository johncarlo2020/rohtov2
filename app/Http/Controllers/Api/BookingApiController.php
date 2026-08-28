<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Services\AvailabilityService;
use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingApiController extends Controller
{
    protected AvailabilityService $availabilityService;
    protected BookingService $bookingService;

    public function __construct(AvailabilityService $availabilityService, BookingService $bookingService)
    {
        $this->availabilityService = $availabilityService;
        $this->bookingService = $bookingService;
    }

    /**
     * GET /api/booking/dates
     * Load calendar date availability.
     */
    public function getDates(Request $request): JsonResponse
    {
        $startDate = $request->query('start_date', Carbon::today()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->query('end_date', Carbon::today()->addMonths(2)->endOfMonth()->format('Y-m-d'));

        $availabilities = $this->availabilityService->getDateAvailabilities($startDate, $endDate);

        return response()->json($availabilities);
    }

    /**
     * GET /api/booking/dates/{date}/slots
     * Load available sessions for a given date.
     */
    public function getSlots(string $date): JsonResponse
    {
        if (!strtotime($date)) {
            return response()->json(['message' => 'Invalid date format.'], 422);
        }

        $slots = $this->availabilityService->getSlotsForDate($date);

        return response()->json($slots);
    }

    /**
     * POST /api/bookings
     * Create an appointment with double-booking prevention.
     */
    public function store(StoreBookingRequest $request): JsonResponse
    {
        $booking = $this->bookingService->createBooking($request->validated());

        $slot = $booking->bookingSlot;
        $dateFormatted = Carbon::parse($booking->bookingDate->date)->format('F j, Y');
        $startTime = Carbon::parse($slot->start_time)->format('g:i A');
        $endTime = Carbon::parse($slot->end_time)->format('g:i A');
        $timeLabel = "{$startTime} - {$endTime}";

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
                ],
            ]
        ], 201);
    }

    /**
     * POST /api/bookings/{id}/cancel
     * Cancel an existing booking.
     */
    public function cancel(int $id): JsonResponse
    {
        $booking = $this->bookingService->cancelBooking($id);

        return response()->json([
            'success' => true,
            'message' => 'Booking cancelled successfully.',
            'data' => [
                'reference_no' => $booking->reference_no,
                'status' => strtoupper($booking->status),
            ]
        ]);
    }

    /**
     * POST /api/bookings/{id}/modify
     * Reschedule an existing booking.
     */
    public function modify(int $id, Request $request): JsonResponse
    {
        $validated = $request->validate([
            'date' => 'required|date_format:Y-m-d',
            'slot_id' => 'required|integer|exists:booking_slots,id',
        ]);

        $booking = $this->bookingService->modifyBooking($id, $validated['date'], $validated['slot_id']);

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
    }
}
