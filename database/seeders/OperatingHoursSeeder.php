<?php

namespace Database\Seeders;

use App\Models\OperatingHour;
use App\Models\OperatingSession;
use Illuminate\Database\Seeder;

class OperatingHoursSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $daysConfig = [
            1 => ['is_open' => false, 'sessions' => []], // Monday: CLOSED
            2 => [ // Tuesday: OPEN (2 sessions, 1 booking per timeslot)
                'is_open' => true,
                'sessions' => [
                    ['start_time' => '14:00:00', 'end_time' => '15:00:00', 'capacity' => 1],
                    ['start_time' => '18:00:00', 'end_time' => '19:00:00', 'capacity' => 1],
                ]
            ],
            3 => [ // Wednesday: OPEN (2 sessions, 1 booking per timeslot)
                'is_open' => true,
                'sessions' => [
                    ['start_time' => '14:00:00', 'end_time' => '15:00:00', 'capacity' => 1],
                    ['start_time' => '18:00:00', 'end_time' => '19:00:00', 'capacity' => 1],
                ]
            ],
            4 => [ // Thursday: OPEN (2 sessions, 1 booking per timeslot)
                'is_open' => true,
                'sessions' => [
                    ['start_time' => '14:00:00', 'end_time' => '15:00:00', 'capacity' => 1],
                    ['start_time' => '18:00:00', 'end_time' => '19:00:00', 'capacity' => 1],
                ]
            ],
            5 => [ // Friday: OPEN (2 sessions, 1 booking per timeslot)
                'is_open' => true,
                'sessions' => [
                    ['start_time' => '14:00:00', 'end_time' => '15:00:00', 'capacity' => 1],
                    ['start_time' => '18:00:00', 'end_time' => '19:00:00', 'capacity' => 1],
                ]
            ],
            6 => [ // Saturday: OPEN (4 sessions, 1 booking per timeslot)
                'is_open' => true,
                'sessions' => [
                    ['start_time' => '10:00:00', 'end_time' => '11:00:00', 'capacity' => 1],
                    ['start_time' => '12:00:00', 'end_time' => '13:00:00', 'capacity' => 1],
                    ['start_time' => '14:00:00', 'end_time' => '15:00:00', 'capacity' => 1],
                    ['start_time' => '18:00:00', 'end_time' => '19:00:00', 'capacity' => 1],
                ]
            ],
            7 => ['is_open' => false, 'sessions' => []], // Sunday: CLOSED
        ];

        foreach ($daysConfig as $dayOfWeek => $config) {
            $operatingHour = OperatingHour::updateOrCreate(
                ['day_of_week' => $dayOfWeek],
                ['is_open' => $config['is_open']]
            );

            // Re-sync sessions for clean seeding
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
    }
}
