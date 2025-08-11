<?php

namespace Database\Seeders;

use App\Models\Appointment;
use Illuminate\Database\Seeder;

class UpdateAppointmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Set all existing appointments status to 0
        Appointment::query()->update(['status' => '0']);

        // Create new appointments with the slots from the image
        $newAppointments = [
            ['08-18-2025', 130], // Monday
            ['08-19-2025', 130], // Tuesday
            ['08-20-2025', 130], // Wednesday
            ['08-21-2025', 130], // Thursday
            ['08-22-2025', 180], // Friday
            ['08-23-2025', 180], // Saturday
            ['08-24-2025', 180], // Sunday
        ];

        foreach ($newAppointments as [$date, $total]) {
            Appointment::create([
                'name' => $date,
                'total' => (string) $total,
                'status' => '1',
            ]);
        }
    }
}
