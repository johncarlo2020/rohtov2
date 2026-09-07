<?php

namespace App\Services;

use App\Models\BookingDate;
use App\Models\BookingSlot;
use App\Models\OperatingHour;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AvailabilityService
{
    /**
     * Get availability statuses for a date range.
     */
    public function getDateAvailabilities(string $startDate, string $endDate): array
    {
        // Enforce event date boundary: September 30, 2026 to October 17, 2026
        $eventMin = '2026-09-30';
        $eventMax = '2026-10-17';

        $startStr = max($startDate, $eventMin);
        $endStr = min($endDate, $eventMax);

        $start = Carbon::parse($startStr)->startOfDay();
        $end = Carbon::parse($endStr)->endOfDay();
        $result = [];

        if ($start->gt($end)) {
            return [];
        }

        $current = $start->copy();
        while ($current->lte($end)) {
            $dateStr = $current->format('Y-m-d');
            $status = $this->getDateStatus($dateStr);
            $result[] = [
                'date' => $dateStr,
                'status' => $status, // 'available', 'full', or 'closed'
            ];
            $current->addDay();
        }

        return $result;
    }

    /**
     * Get the dynamic status of a specific date.
     * Returns: 'available', 'full', or 'closed'
     */
    public function getDateStatus(string $date): string
    {
        $carbonDate = Carbon::parse($date);
        $dateStr = $carbonDate->format('Y-m-d');

        // Enforce event date boundary: September 30, 2026 to October 17, 2026
        if ($dateStr < '2026-09-30' || $dateStr > '2026-10-17') {
            return 'closed';
        }

        $dayOfWeek = $carbonDate->dayOfWeekIso; // 1 (Mon) to 7 (Sun)

        // 1. Check special date override in booking_dates
        $bookingDate = BookingDate::where('date', $dateStr)->first();
        if ($bookingDate && !$bookingDate->is_available) {
            return 'closed';
        }

        // 2. Check weekly operating hours schedule
        $operatingHour = OperatingHour::where('day_of_week', $dayOfWeek)->first();
        if (!$operatingHour || !$operatingHour->is_open) {
            return 'closed';
        }

        // 3. Provision / ensure slots exist for this date if open
        $slots = $this->getOrProvisionSlotsForDate($dateStr, $operatingHour, $bookingDate);

        if ($slots->isEmpty()) {
            return 'closed';
        }

        // 4. Check if at least ONE slot has remaining capacity
        $hasAvailableSlot = $slots->contains(function (BookingSlot $slot) {
            return $slot->is_available && ($slot->booked_count < $slot->capacity);
        });

        return $hasAvailableSlot ? 'available' : 'full';
    }

    /**
     * Get time slots formatted for the frontend for a given date.
     */
    public function getSlotsForDate(string $date): array
    {
        $carbonDate = Carbon::parse($date);
        $dateStr = $carbonDate->format('Y-m-d');

        // Enforce event date boundary: September 30, 2026 to October 17, 2026
        if ($dateStr < '2026-09-30' || $dateStr > '2026-10-17') {
            return [];
        }

        $dayOfWeek = $carbonDate->dayOfWeekIso;

        // Check date availability override & operating hours
        $bookingDate = BookingDate::where('date', $dateStr)->first();
        if ($bookingDate && !$bookingDate->is_available) {
            return [];
        }

        $operatingHour = OperatingHour::where('day_of_week', $dayOfWeek)->first();
        if (!$operatingHour || !$operatingHour->is_open) {
            return [];
        }

        $slots = $this->getOrProvisionSlotsForDate($date, $operatingHour, $bookingDate);

        return $slots->map(function (BookingSlot $slot) {
            $isAvailable = $slot->is_available && ($slot->booked_count < $slot->capacity);

            $startTimeFormatted = Carbon::parse($slot->start_time)->format('g:i A');
            $endTimeFormatted = Carbon::parse($slot->end_time)->format('g:i A');
            $label = "{$startTimeFormatted} - {$endTimeFormatted}";

            return [
                'id' => $slot->id,
                'start_time' => Carbon::parse($slot->start_time)->format('H:i'),
                'end_time' => Carbon::parse($slot->end_time)->format('H:i'),
                'label' => $label,
                'status' => $isAvailable ? 'available' : 'full',
                'available' => $isAvailable,
                'capacity' => $slot->capacity,
                'booked_count' => $slot->booked_count,
            ];
        })->values()->toArray();
    }

    /**
     * Get or create BookingDate and BookingSlot records for an open date based on operating sessions.
     */
    public function getOrProvisionSlotsForDate(string $date, OperatingHour $operatingHour, ?BookingDate $bookingDate = null): Collection
    {
        if (!$bookingDate) {
            $bookingDate = BookingDate::firstOrCreate(
                ['date' => $date],
                ['is_available' => true]
            );
        }

        $existingSlots = $bookingDate->slots()->orderBy('start_time')->get();

        if ($existingSlots->isNotEmpty()) {
            return $existingSlots;
        }

        // Generate slots from active operating sessions
        $activeSessions = $operatingHour->sessions()->where('is_active', true)->orderBy('start_time')->get();

        foreach ($activeSessions as $session) {
            BookingSlot::create([
                'booking_date_id' => $bookingDate->id,
                'start_time' => $session->start_time,
                'end_time' => $session->end_time,
                'capacity' => $session->capacity,
                'booked_count' => 0,
                'is_available' => true,
            ]);
        }

        return $bookingDate->slots()->orderBy('start_time')->get();
    }
}
