<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\BookingDate;
use App\Models\BookingSlot;
use App\Models\OperatingHour;
use App\Models\OperatingSession;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EventScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds for Longchamp x Caroline Helain Schedule & VIP Names.
     */
    public function run(): void
    {
        // 1. Sync Operating Hours (Wed, Thu, Fri, Sat OPEN; Sun, Mon, Tue CLOSED)
        $daysConfig = [
            1 => ['is_open' => false, 'sessions' => []], // Monday
            2 => ['is_open' => false, 'sessions' => []], // Tuesday
            3 => [ // Wednesday
                'is_open' => true,
                'sessions' => [
                    ['start_time' => '12:00:00', 'end_time' => '13:00:00', 'capacity' => 6],
                    ['start_time' => '18:00:00', 'end_time' => '19:00:00', 'capacity' => 6],
                ]
            ],
            4 => [ // Thursday
                'is_open' => true,
                'sessions' => [
                    ['start_time' => '12:00:00', 'end_time' => '13:00:00', 'capacity' => 6],
                    ['start_time' => '18:00:00', 'end_time' => '19:00:00', 'capacity' => 6],
                ]
            ],
            5 => [ // Friday
                'is_open' => true,
                'sessions' => [
                    ['start_time' => '12:00:00', 'end_time' => '13:00:00', 'capacity' => 6],
                    ['start_time' => '18:00:00', 'end_time' => '19:00:00', 'capacity' => 6],
                ]
            ],
            6 => [ // Saturday
                'is_open' => true,
                'sessions' => [
                    ['start_time' => '11:00:00', 'end_time' => '12:00:00', 'capacity' => 6],
                    ['start_time' => '16:00:00', 'end_time' => '17:00:00', 'capacity' => 6],
                ]
            ],
            7 => ['is_open' => false, 'sessions' => []], // Sunday
        ];

        foreach ($daysConfig as $dayOfWeek => $config) {
            $operatingHour = OperatingHour::updateOrCreate(
                ['day_of_week' => $dayOfWeek],
                ['is_open' => $config['is_open']]
            );

            $operatingHour->sessions()->delete();

            foreach ($config['sessions'] as $sessionData) {
                OperatingSession::create([
                    'operating_hour_id' => $operatingHour->id,
                    'start_time' => $sessionData['start_time'],
                    'end_time' => $sessionData['end_time'],
                    'capacity' => $sessionData['capacity'],
                    'is_active' => true,
                ]);
            }
        }

        // 2. Specific Date & Time Slot Schedule (Sept 30 to Oct 17)
        $schedule = [
            // --- VIP: WEDNESDAY 30 SEPTEMBER ---
            '2026-09-30' => [
                'is_available' => true,
                'slots' => [
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
                ]
            ],

            // --- VIP: THURSDAY 1 OCTOBER ---
            '2026-10-01' => [
                'is_available' => true,
                'slots' => [
                    ['start_time' => '11:00:00', 'end_time' => '12:00:00', 'capacity' => 10],
                    ['start_time' => '12:00:00', 'end_time' => '13:00:00', 'capacity' => 10],
                    ['start_time' => '13:00:00', 'end_time' => '14:00:00', 'capacity' => 6],
                    ['start_time' => '14:00:00', 'end_time' => '15:00:00', 'capacity' => 6],
                    ['start_time' => '15:00:00', 'end_time' => '16:00:00', 'capacity' => 6],
                    ['start_time' => '16:00:00', 'end_time' => '17:00:00', 'capacity' => 6],
                ]
            ],

            // --- PUBLIC: 1ST WEEK ---
            // Friday 2 Oct
            '2026-10-02' => [
                'is_available' => true,
                'slots' => [
                    ['start_time' => '12:00:00', 'end_time' => '13:00:00', 'capacity' => 6],
                    ['start_time' => '18:00:00', 'end_time' => '19:00:00', 'capacity' => 6],
                ]
            ],
            // Saturday 3 Oct
            '2026-10-03' => [
                'is_available' => true,
                'slots' => [
                    ['start_time' => '11:00:00', 'end_time' => '12:00:00', 'capacity' => 6],
                    ['start_time' => '16:00:00', 'end_time' => '17:00:00', 'capacity' => 6],
                ]
            ],
            // Closed Days
            '2026-10-04' => ['is_available' => false, 'slots' => []],
            '2026-10-05' => ['is_available' => false, 'slots' => []],
            '2026-10-06' => ['is_available' => false, 'slots' => []],

            // --- PUBLIC: 2ND WEEK ---
            // Wednesday 7 Oct
            '2026-10-07' => [
                'is_available' => true,
                'slots' => [
                    ['start_time' => '12:00:00', 'end_time' => '13:00:00', 'capacity' => 6],
                    ['start_time' => '18:00:00', 'end_time' => '19:00:00', 'capacity' => 6],
                ]
            ],
            // Thursday 8 Oct
            '2026-10-08' => [
                'is_available' => true,
                'slots' => [
                    ['start_time' => '12:00:00', 'end_time' => '13:00:00', 'capacity' => 6],
                    ['start_time' => '18:00:00', 'end_time' => '19:00:00', 'capacity' => 6],
                ]
            ],
            // Friday 9 Oct
            '2026-10-09' => [
                'is_available' => true,
                'slots' => [
                    ['start_time' => '12:00:00', 'end_time' => '13:00:00', 'capacity' => 6],
                    ['start_time' => '18:00:00', 'end_time' => '19:00:00', 'capacity' => 6],
                ]
            ],
            // Saturday 10 Oct
            '2026-10-10' => [
                'is_available' => true,
                'slots' => [
                    ['start_time' => '11:00:00', 'end_time' => '12:00:00', 'capacity' => 6],
                    ['start_time' => '16:00:00', 'end_time' => '17:00:00', 'capacity' => 6],
                ]
            ],
            // Closed Days
            '2026-10-11' => ['is_available' => false, 'slots' => []],
            '2026-10-12' => ['is_available' => false, 'slots' => []],
            '2026-10-13' => ['is_available' => false, 'slots' => []],

            // --- PUBLIC: 3RD WEEK ---
            // Wednesday 14 Oct
            '2026-10-14' => [
                'is_available' => true,
                'slots' => [
                    ['start_time' => '12:00:00', 'end_time' => '13:00:00', 'capacity' => 6],
                    ['start_time' => '18:00:00', 'end_time' => '19:00:00', 'capacity' => 6],
                ]
            ],
            // Thursday 15 Oct
            '2026-10-15' => [
                'is_available' => true,
                'slots' => [
                    ['start_time' => '12:00:00', 'end_time' => '13:00:00', 'capacity' => 6],
                    ['start_time' => '18:00:00', 'end_time' => '19:00:00', 'capacity' => 6],
                ]
            ],
            // Friday 16 Oct
            '2026-10-16' => [
                'is_available' => true,
                'slots' => [
                    ['start_time' => '12:00:00', 'end_time' => '13:00:00', 'capacity' => 6],
                    ['start_time' => '18:00:00', 'end_time' => '19:00:00', 'capacity' => 6],
                ]
            ],
            // Saturday 17 Oct
            '2026-10-17' => [
                'is_available' => true,
                'slots' => [
                    ['start_time' => '11:00:00', 'end_time' => '12:00:00', 'capacity' => 6],
                    ['start_time' => '16:00:00', 'end_time' => '17:00:00', 'capacity' => 6],
                ]
            ],
        ];

        foreach ($schedule as $dateStr => $dateData) {
            $bookingDate = BookingDate::updateOrCreate(
                ['date' => $dateStr],
                ['is_available' => $dateData['is_available']]
            );

            // Clean up slots for this specific date if re-seeding
            $bookingDate->slots()->delete();

            if ($dateData['is_available']) {
                foreach ($dateData['slots'] as $slotData) {
                    BookingSlot::create([
                        'booking_date_id' => $bookingDate->id,
                        'start_time' => $slotData['start_time'],
                        'end_time' => $slotData['end_time'],
                        'capacity' => $slotData['capacity'],
                        'booked_count' => 0,
                        'is_available' => true,
                    ]);
                }
            }
        }

        // 3. VIP Reservations (No pre-created bookings; admin adds VIP bookings manually via Admin Panel)
        Booking::where('is_vip', true)->delete();
    }
}
