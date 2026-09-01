<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingSlot;
use App\Models\OperatingHour;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class BookingService
{
    /**
     * Create appointment with pessimistic transaction lock to prevent double booking.
     */
    public function createBooking(array $data): Booking
    {
        return DB::transaction(function () use ($data) {
            $slotId = $data['slot_id'];
            $requestedDate = $data['date'];

            /** @var BookingSlot $slot */
            $slot = BookingSlot::with('bookingDate')
                ->where('id', $slotId)
                ->lockForUpdate()
                ->first();

            if (!$slot) {
                throw ValidationException::withMessages([
                    'slot' => ['The selected time slot does not exist.']
                ]);
            }

            // Verify slot belongs to selected date
            if ($slot->bookingDate->date->format('Y-m-d') !== $requestedDate) {
                throw ValidationException::withMessages([
                    'slot' => ['Selected slot does not match the chosen date.']
                ]);
            }

            // Verify date override is available
            if (!$slot->bookingDate->is_available) {
                throw ValidationException::withMessages([
                    'date' => ['The selected date is closed for bookings.']
                ]);
            }

            // Verify day of week is operating day
            $dayOfWeek = Carbon::parse($requestedDate)->dayOfWeekIso;
            $operatingHour = OperatingHour::where('day_of_week', $dayOfWeek)->first();
            if (!$operatingHour || !$operatingHour->is_open) {
                throw ValidationException::withMessages([
                    'date' => ['The selected date is an off-day and not open for bookings.']
                ]);
            }

            // Verify slot availability and remaining capacity
            if (!$slot->is_available || $slot->booked_count >= $slot->capacity) {
                throw ValidationException::withMessages([
                    'slot' => ['This session is no longer available. Please select another time.']
                ]);
            }

            // Prevent user from double booking on the same date
            $existingBooking = Booking::where('booking_date_id', $slot->booking_date_id)
                ->where('status', '!=', 'cancelled')
                ->where(function ($q) use ($data) {
                    $q->where('customer_email', $data['customer_email'])
                      ->orWhere('customer_phone', $data['customer_phone']);
                })
                ->first();

            if ($existingBooking) {
                throw ValidationException::withMessages([
                    'slot' => ['You have already booked a session for this date.']
                ]);
            }

            // Generate unique reference number (e.g. BK-20261007-0001)
            $dateCompact = Carbon::parse($requestedDate)->format('Ymd');
            $countToday = Booking::where('booking_date_id', $slot->booking_date_id)->count() + 1;
            $refNumber = 'BK-' . $dateCompact . '-' . str_pad((string)$countToday, 4, '0', STR_PAD_LEFT);

            // Handle potential collision
            while (Booking::where('reference_no', $refNumber)->exists()) {
                $refNumber = 'BK-' . $dateCompact . '-' . strtoupper(Str::random(4));
            }

            // Create booking record
            $booking = Booking::create([
                'booking_date_id' => $slot->booking_date_id,
                'booking_slot_id' => $slot->id,
                'reference_no' => $refNumber,
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'customer_phone' => $data['customer_phone'],
                'status' => 'confirmed',
            ]);

            // Increment slot booked count
            $slot->increment('booked_count');

            $booking->load(['bookingDate', 'bookingSlot']);

            // Send confirmation email to customer
            \App\Helpers\GlobalHelper::sendBookingConfirmationEmail($booking, false);

            return $booking;
        });
    }

    /**
     * Cancel an existing booking and restore capacity.
     */
    public function cancelBooking(int $bookingId): Booking
    {
        return DB::transaction(function () use ($bookingId) {
            /** @var Booking $booking */
            $booking = Booking::where('id', $bookingId)->lockForUpdate()->firstOrFail();

            if ($booking->status === 'cancelled') {
                return $booking;
            }

            $booking->status = 'cancelled';
            $booking->save();

            // Lock and decrement slot count
            $slot = BookingSlot::where('id', $booking->booking_slot_id)->lockForUpdate()->first();
            if ($slot && $slot->booked_count > 0) {
                $slot->decrement('booked_count');
            }

            return $booking;
        });
    }

    /**
     * Reschedule / Modify an existing booking.
     */
    public function modifyBooking(string|int $bookingIdentifier, string $newDate, int $newSlotId): Booking
    {
        return DB::transaction(function () use ($bookingIdentifier, $newDate, $newSlotId) {
            /** @var Booking $booking */
            $booking = Booking::where('id', $bookingIdentifier)
                ->orWhere('reference_no', $bookingIdentifier)
                ->lockForUpdate()
                ->first();

            if (!$booking) {
                throw ValidationException::withMessages([
                    'booking' => ['Booking record not found.']
                ]);
            }

            if ($booking->status === 'cancelled') {
                throw ValidationException::withMessages([
                    'booking' => ['Cannot modify a cancelled booking.']
                ]);
            }

            // Rule: You can only reschedule once
            if ($booking->reschedule_count >= 1) {
                throw ValidationException::withMessages([
                    'slot' => ['You can only reschedule once. Further modifications are not permitted.']
                ]);
            }

            // Lock new slot
            $newSlot = BookingSlot::with('bookingDate')
                ->where('id', $newSlotId)
                ->lockForUpdate()
                ->first();

            if (!$newSlot) {
                throw ValidationException::withMessages([
                    'slot' => ['The selected new time slot does not exist.']
                ]);
            }

            // Verify new slot matches requested date
            $slotDate = Carbon::parse($newSlot->bookingDate->date)->format('Y-m-d');
            if ($slotDate !== $newDate) {
                throw ValidationException::withMessages([
                    'slot' => ['New time slot does not match the requested date.']
                ]);
            }

            // Verify new slot is available and has capacity
            if (!$newSlot->is_available || $newSlot->booked_count >= $newSlot->capacity) {
                throw ValidationException::withMessages([
                    'slot' => ['The selected time slot is no longer available. Please select another time.']
                ]);
            }

            // Lock old slot and release capacity
            $oldSlot = BookingSlot::where('id', $booking->booking_slot_id)->lockForUpdate()->first();
            if ($oldSlot && $oldSlot->booked_count > 0) {
                $oldSlot->decrement('booked_count');
            }

            // Increment new slot capacity
            $newSlot->increment('booked_count');

            // Update booking details
            $booking->booking_date_id = $newSlot->booking_date_id;
            $booking->booking_slot_id = $newSlot->id;
            $booking->reschedule_count += 1;
            $booking->save();

            $booking->load(['bookingDate', 'bookingSlot']);

            // Send modification email to customer
            \App\Helpers\GlobalHelper::sendBookingConfirmationEmail($booking, true);

            return $booking;
        });
    }
}
