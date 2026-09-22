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
    public function getDateAvailabilities(string $startDate, string $endDate, bool $isVip = false): array
    {
        // Enforce event date boundary dynamically: September 30, 2026 to latest configured event date
        $eventMin = '2026-09-30';
        $eventMax = $this->getEventMaxDate();

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
            $status = $this->getDateStatus($dateStr, $isVip);
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
    public function getDateStatus(string $date, bool $isVip = false): string
    {
        $carbonDate = Carbon::parse($date);
        $dateStr = $carbonDate->format('Y-m-d');

        // Enforce event date boundary dynamically: September 30, 2026 to latest event date
        if ($dateStr < '2026-09-30' || $dateStr > $this->getEventMaxDate()) {
            return 'closed';
        }

        // Private / VIP-only dates hidden from public self-booking
        if (!$isVip) {
            $privateOnlyDates = ['2026-09-30', '2026-10-09', '2026-10-13'];
            if (in_array($dateStr, $privateOnlyDates)) {
                return 'closed';
            }
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
            if (!($isVip && $bookingDate && $bookingDate->is_available)) {
                return 'closed';
            }
        }

        // 3. Provision / ensure slots exist for this date if open
        if ($operatingHour && $operatingHour->is_open) {
            $slots = $this->getOrProvisionSlotsForDate($dateStr, $operatingHour, $bookingDate);
        } elseif ($bookingDate && $bookingDate->is_available) {
            $slots = $bookingDate->slots()->orderBy('start_time')->get();
        } else {
            return 'closed';
        }

        // Filter public-allowed time slots for specific dates if not VIP
        if (!$isVip) {
            if ($dateStr === '2026-10-01') {
                $slots = $slots->filter(function (BookingSlot $slot) {
                    return in_array(substr($slot->start_time, 0, 5), ['11:00', '12:00']);
                });
            }
        }

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
    public function getSlotsForDate(string $date, bool $isVip = false): array
    {
        $carbonDate = Carbon::parse($date);
        $dateStr = $carbonDate->format('Y-m-d');

        // Enforce event date boundary dynamically: September 30, 2026 to latest event date
        if ($dateStr < '2026-09-30' || $dateStr > $this->getEventMaxDate()) {
            return [];
        }

        // Private / VIP-only dates hidden from public self-booking
        if (!$isVip) {
            $privateOnlyDates = ['2026-09-30', '2026-10-09', '2026-10-13'];
            if (in_array($dateStr, $privateOnlyDates)) {
                return [];
            }
        }

        $dayOfWeek = $carbonDate->dayOfWeekIso;

        // Check date availability override & operating hours
        $bookingDate = BookingDate::where('date', $dateStr)->first();
        if ($bookingDate && !$bookingDate->is_available) {
            return [];
        }

        $operatingHour = OperatingHour::where('day_of_week', $dayOfWeek)->first();
        if (!$operatingHour || !$operatingHour->is_open) {
            if (!($isVip && $bookingDate && $bookingDate->is_available)) {
                return [];
            }
        }

        if ($operatingHour && $operatingHour->is_open) {
            $slots = $this->getOrProvisionSlotsForDate($date, $operatingHour, $bookingDate);
        } elseif ($bookingDate && $bookingDate->is_available) {
            $slots = $bookingDate->slots()->orderBy('start_time')->get();
        } else {
            return [];
        }

        // Filter public-allowed time slots for specific dates if not VIP
        if (!$isVip) {
            if ($dateStr === '2026-10-01') {
                $slots = $slots->filter(function (BookingSlot $slot) {
                    return in_array(substr($slot->start_time, 0, 5), ['11:00', '12:00']);
                });
            }
        }

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

        // 1. Check for specific event schedule override
        $eventSchedule = $this->getEventScheduleForDate($date);
        if ($eventSchedule !== null) {
            foreach ($eventSchedule as $slotData) {
                BookingSlot::create([
                    'booking_date_id' => $bookingDate->id,
                    'start_time' => $slotData['start_time'],
                    'end_time' => $slotData['end_time'],
                    'capacity' => $slotData['capacity'],
                    'booked_count' => 0,
                    'is_available' => true,
                ]);
            }
            return $bookingDate->slots()->orderBy('start_time')->get();
        }

        // 2. Generate slots from active operating sessions
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

    /**
     * Single source event schedule definitions for fallback provisioning.
     */
    public function getEventScheduleForDate(string $date): ?array
    {
        if (app()->environment('testing')) {
            return null;
        }

        $schedules = [
            '2026-09-30' => [
                ['start_time' => '11:00:00', 'end_time' => '12:00:00', 'capacity' => 20],
                ['start_time' => '12:00:00', 'end_time' => '13:00:00', 'capacity' => 20],
                ['start_time' => '13:00:00', 'end_time' => '14:00:00', 'capacity' => 20],
                ['start_time' => '14:00:00', 'end_time' => '15:00:00', 'capacity' => 20],
                ['start_time' => '15:00:00', 'end_time' => '16:00:00', 'capacity' => 10],
                ['start_time' => '16:00:00', 'end_time' => '17:00:00', 'capacity' => 10],
                ['start_time' => '17:00:00', 'end_time' => '18:00:00', 'capacity' => 6],
                ['start_time' => '18:00:00', 'end_time' => '19:00:00', 'capacity' => 6],
                ['start_time' => '19:00:00', 'end_time' => '20:00:00', 'capacity' => 10],
                ['start_time' => '20:00:00', 'end_time' => '21:00:00', 'capacity' => 10],
                ['start_time' => '21:00:00', 'end_time' => '22:00:00', 'capacity' => 10],
            ],
            '2026-10-01' => [
                ['start_time' => '11:00:00', 'end_time' => '12:00:00', 'capacity' => 6],
                ['start_time' => '12:00:00', 'end_time' => '13:00:00', 'capacity' => 6],
                ['start_time' => '13:00:00', 'end_time' => '14:00:00', 'capacity' => 6],
                ['start_time' => '14:00:00', 'end_time' => '15:00:00', 'capacity' => 6],
                ['start_time' => '15:00:00', 'end_time' => '16:00:00', 'capacity' => 6],
                ['start_time' => '16:00:00', 'end_time' => '17:00:00', 'capacity' => 6],
            ],
            '2026-10-02' => [
                ['start_time' => '12:00:00', 'end_time' => '13:00:00', 'capacity' => 6],
                ['start_time' => '18:00:00', 'end_time' => '19:00:00', 'capacity' => 6],
            ],
            '2026-10-03' => [
                ['start_time' => '11:00:00', 'end_time' => '12:00:00', 'capacity' => 6],
                ['start_time' => '16:00:00', 'end_time' => '17:00:00', 'capacity' => 6],
            ],
            '2026-10-07' => [
                ['start_time' => '12:00:00', 'end_time' => '13:00:00', 'capacity' => 6],
                ['start_time' => '18:00:00', 'end_time' => '19:00:00', 'capacity' => 6],
            ],
            '2026-10-08' => [
                ['start_time' => '12:00:00', 'end_time' => '13:00:00', 'capacity' => 6],
                ['start_time' => '18:00:00', 'end_time' => '19:00:00', 'capacity' => 6],
            ],
            '2026-10-09' => [
                ['start_time' => '17:30:00', 'end_time' => '20:00:00', 'capacity' => 30],
            ],
            '2026-10-10' => [
                ['start_time' => '11:00:00', 'end_time' => '12:00:00', 'capacity' => 6],
                ['start_time' => '16:00:00', 'end_time' => '17:00:00', 'capacity' => 6],
            ],
            '2026-10-13' => [
                ['start_time' => '11:00:00', 'end_time' => '12:00:00', 'capacity' => 5],
                ['start_time' => '12:00:00', 'end_time' => '13:00:00', 'capacity' => 5],
            ],
            '2026-10-14' => [
                ['start_time' => '12:00:00', 'end_time' => '13:00:00', 'capacity' => 6],
                ['start_time' => '18:00:00', 'end_time' => '19:00:00', 'capacity' => 6],
            ],
            '2026-10-15' => [
                ['start_time' => '12:00:00', 'end_time' => '13:00:00', 'capacity' => 6],
                ['start_time' => '18:00:00', 'end_time' => '19:00:00', 'capacity' => 6],
            ],
            '2026-10-16' => [
                ['start_time' => '12:00:00', 'end_time' => '13:00:00', 'capacity' => 6],
                ['start_time' => '18:00:00', 'end_time' => '19:00:00', 'capacity' => 6],
            ],
            '2026-10-17' => [
                ['start_time' => '11:00:00', 'end_time' => '12:00:00', 'capacity' => 6],
                ['start_time' => '16:00:00', 'end_time' => '17:00:00', 'capacity' => 6],
            ],
        ];

        return $schedules[$date] ?? null;
    }

    /**
     * Get the dynamic maximum event end date (at least 2026-10-17, or max date in DB).
     */
    public function getEventMaxDate(): string
    {
        $maxDbDate = BookingDate::max('date');
        if ($maxDbDate instanceof \Carbon\Carbon || $maxDbDate instanceof \DateTimeInterface) {
            $maxDbDate = $maxDbDate->format('Y-m-d');
        }
        return ($maxDbDate && $maxDbDate > '2026-10-17') ? (string)$maxDbDate : '2026-10-17';
    }
}
