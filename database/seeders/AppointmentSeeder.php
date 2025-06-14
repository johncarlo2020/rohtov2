<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Appointment;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Appointment::create([
            'name' => '06-24-2025',
            'total' => '120',
            'status' => '1',
        ]);

        Appointment::create([
            'name' => '06-25-2025',
            'total' => '120',
            'status' => '1',
        ]);
        Appointment::create([
            'name' => '06-26-2025',
            'total' => '120',
            'status' => '1',
        ]);

        Appointment::create([
            'name' => '06-27-2025',
            'total' => '180',
            'status' => '1',
        ]);

        Appointment::create([
            'name' => '06-28-2025',
            'total' => '180',
            'status' => '1',
        ]);
        Appointment::create([
            'name' => '06-29-2025',
            'total' => '180',
            'status' => '1',
        ]);

    }
}
