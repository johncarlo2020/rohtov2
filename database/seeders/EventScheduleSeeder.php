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
        // 1. Sync Operating Hours (Wed, Thu, Fri, Sat OPEN; Sun, Mon CLOSED; Tue Special for Oct 13)
        $daysConfig = [
            1 => ['is_open' => false, 'sessions' => []], // Monday: CLOSED
            2 => [ // Tuesday: OPEN for Oct 13 VIP session
                'is_open' => true,
                'sessions' => [
                    ['start_time' => '11:00:00', 'end_time' => '13:00:00', 'capacity' => 10],
                ]
            ],
            3 => [ // Wednesday: Public Workshop Sessions
                'is_open' => true,
                'sessions' => [
                    ['start_time' => '12:00:00', 'end_time' => '13:00:00', 'capacity' => 6],
                    ['start_time' => '18:00:00', 'end_time' => '19:00:00', 'capacity' => 6],
                ]
            ],
            4 => [ // Thursday: Public Workshop Sessions
                'is_open' => true,
                'sessions' => [
                    ['start_time' => '12:00:00', 'end_time' => '13:00:00', 'capacity' => 6],
                    ['start_time' => '18:00:00', 'end_time' => '19:00:00', 'capacity' => 6],
                ]
            ],
            5 => [ // Friday: Public Workshop Sessions
                'is_open' => true,
                'sessions' => [
                    ['start_time' => '12:00:00', 'end_time' => '13:00:00', 'capacity' => 6],
                    ['start_time' => '18:00:00', 'end_time' => '19:00:00', 'capacity' => 6],
                ]
            ],
            6 => [ // Saturday: Public Workshop Sessions
                'is_open' => true,
                'sessions' => [
                    ['start_time' => '11:00:00', 'end_time' => '12:00:00', 'capacity' => 6],
                    ['start_time' => '16:00:00', 'end_time' => '17:00:00', 'capacity' => 6],
                ]
            ],
            7 => ['is_open' => false, 'sessions' => []], // Sunday: CLOSED
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

        // 2. Specific Date & Time Slot Schedule (Sept 30 to Oct 18)
        $schedule = [
            // --- WEDNESDAY 30 SEPTEMBER (VIP Only) ---
            '2026-09-30' => [
                'is_available' => true,
                'slots' => [
                    ['start_time' => '11:00:00', 'end_time' => '12:00:00', 'capacity' => 20], // Media & Influencer x20
                    ['start_time' => '12:00:00', 'end_time' => '13:00:00', 'capacity' => 20], // Media & Influencer x20
                    ['start_time' => '13:00:00', 'end_time' => '14:00:00', 'capacity' => 20], // Media & Influencer x20
                    ['start_time' => '14:00:00', 'end_time' => '15:00:00', 'capacity' => 20], // Media & Influencer x20
                    ['start_time' => '15:00:00', 'end_time' => '16:00:00', 'capacity' => 10], // LC VIC x10
                    ['start_time' => '16:00:00', 'end_time' => '17:00:00', 'capacity' => 10], // LC VIC x10
                    ['start_time' => '17:00:00', 'end_time' => '18:00:00', 'capacity' => 6],  // The Gardens Emerald Members x6
                    ['start_time' => '18:00:00', 'end_time' => '19:00:00', 'capacity' => 6],  // The Gardens Emerald Members x6
                    ['start_time' => '19:00:00', 'end_time' => '20:00:00', 'capacity' => 10], // Maybank Premium Customer x10
                    ['start_time' => '20:00:00', 'end_time' => '21:00:00', 'capacity' => 10], // Maybank Premium Customer x10
                    ['start_time' => '21:00:00', 'end_time' => '22:00:00', 'capacity' => 10], // Maybank Premium Customer x10
                ]
            ],

            // --- THURSDAY 1 OCTOBER ---
            '2026-10-01' => [
                'is_available' => true,
                'slots' => [
                    ['start_time' => '11:00:00', 'end_time' => '12:00:00', 'capacity' => 6],  // Workshop for Public x6
                    ['start_time' => '12:00:00', 'end_time' => '13:00:00', 'capacity' => 6],  // Workshop for Public x6
                    ['start_time' => '13:00:00', 'end_time' => '14:00:00', 'capacity' => 6],  // Pin Prestige x6
                    ['start_time' => '14:00:00', 'end_time' => '15:00:00', 'capacity' => 6],  // Pin Prestige x6
                    ['start_time' => '15:00:00', 'end_time' => '16:00:00', 'capacity' => 6],  // The Gardens Emerald Members x6
                    ['start_time' => '16:00:00', 'end_time' => '17:00:00', 'capacity' => 6],  // The Gardens Emerald Members x6
                ]
            ],

            // --- FRIDAY 2 OCTOBER ---
            '2026-10-02' => [
                'is_available' => true,
                'slots' => [
                    ['start_time' => '12:00:00', 'end_time' => '13:00:00', 'capacity' => 6],  // Workshop for Public x6
                    ['start_time' => '18:00:00', 'end_time' => '19:00:00', 'capacity' => 6],  // Workshop for Public x6
                ]
            ],

            // --- SATURDAY 3 OCTOBER ---
            '2026-10-03' => [
                'is_available' => true,
                'slots' => [
                    ['start_time' => '11:00:00', 'end_time' => '12:00:00', 'capacity' => 6],  // Workshop for Public x6
                    ['start_time' => '16:00:00', 'end_time' => '17:00:00', 'capacity' => 6],  // Workshop for Public x6
                ]
            ],

            // --- CLOSED DAYS ---
            '2026-10-04' => ['is_available' => false, 'slots' => []], // Sun
            '2026-10-05' => ['is_available' => false, 'slots' => []], // Mon
            '2026-10-06' => ['is_available' => false, 'slots' => []], // Tue

            // --- WEDNESDAY 7 OCTOBER ---
            '2026-10-07' => [
                'is_available' => true,
                'slots' => [
                    ['start_time' => '12:00:00', 'end_time' => '13:00:00', 'capacity' => 6],  // Workshop for Public x6
                    ['start_time' => '18:00:00', 'end_time' => '19:00:00', 'capacity' => 6],  // Workshop for Public x6
                ]
            ],

            // --- THURSDAY 8 OCTOBER ---
            '2026-10-08' => [
                'is_available' => true,
                'slots' => [
                    ['start_time' => '12:00:00', 'end_time' => '13:00:00', 'capacity' => 6],  // Workshop for Public x6
                    ['start_time' => '18:00:00', 'end_time' => '19:00:00', 'capacity' => 6],  // Workshop for Public x6
                ]
            ],

            // --- FRIDAY 9 OCTOBER ---
            '2026-10-09' => [
                'is_available' => true,
                'slots' => [
                    ['start_time' => '17:30:00', 'end_time' => '20:00:00', 'capacity' => 30], // Private Shopping Session: Ferhat (30 pax)
                ]
            ],

            // --- SATURDAY 10 OCTOBER ---
            '2026-10-10' => [
                'is_available' => true,
                'slots' => [
                    ['start_time' => '11:00:00', 'end_time' => '12:00:00', 'capacity' => 6],  // Workshop for Public x6
                    ['start_time' => '16:00:00', 'end_time' => '17:00:00', 'capacity' => 6],  // Workshop for Public x6
                ]
            ],

            // --- CLOSED DAYS ---
            '2026-10-11' => ['is_available' => false, 'slots' => []], // Sun
            '2026-10-12' => ['is_available' => false, 'slots' => []], // Mon

            // --- TUESDAY 13 OCTOBER ---
            '2026-10-13' => [
                'is_available' => true,
                'slots' => [
                    ['start_time' => '11:00:00', 'end_time' => '13:00:00', 'capacity' => 10], // GLAM reader (10 pax)
                ]
            ],

            // --- WEDNESDAY 14 OCTOBER ---
            '2026-10-14' => [
                'is_available' => true,
                'slots' => [
                    ['start_time' => '11:00:00', 'end_time' => '12:00:00', 'capacity' => 5],  // GLAM reader x5 (VIP)
                    ['start_time' => '12:00:00', 'end_time' => '13:00:00', 'capacity' => 5],  // GLAM reader x5 (VIP)
                    ['start_time' => '18:00:00', 'end_time' => '19:00:00', 'capacity' => 6],  // Workshop for Public x6
                ]
            ],

            // --- THURSDAY 15 OCTOBER ---
            '2026-10-15' => [
                'is_available' => true,
                'slots' => [
                    ['start_time' => '12:00:00', 'end_time' => '13:00:00', 'capacity' => 6],  // Workshop for Public x6
                    ['start_time' => '18:00:00', 'end_time' => '19:00:00', 'capacity' => 6],  // Workshop for Public x6
                ]
            ],

            // --- FRIDAY 16 OCTOBER ---
            '2026-10-16' => [
                'is_available' => true,
                'slots' => [
                    ['start_time' => '12:00:00', 'end_time' => '13:00:00', 'capacity' => 6],  // Workshop for Public x6
                    ['start_time' => '18:00:00', 'end_time' => '19:00:00', 'capacity' => 6],  // Workshop for Public x6
                ]
            ],

            // --- SATURDAY 17 OCTOBER ---
            '2026-10-17' => [
                'is_available' => true,
                'slots' => [
                    ['start_time' => '11:00:00', 'end_time' => '12:00:00', 'capacity' => 6],  // Workshop for Public x6
                    ['start_time' => '16:00:00', 'end_time' => '17:00:00', 'capacity' => 6],  // Workshop for Public x6
                ]
            ],

            // --- CLOSED DAY ---
            '2026-10-18' => ['is_available' => false, 'slots' => []], // Sun
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
