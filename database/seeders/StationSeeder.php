<?php

namespace Database\Seeders;

use App\Models\Station;
use Illuminate\Database\Seeder;

class StationSeeder extends Seeder
{
    public function run(): void
    {
        $stations = [
            'Shop for More' => true,
            'More to Enjoy' => true,
            'More to Unwind' => true,
            'More to Stream' => true,
            'Maybank Cafe' => true,
            'Flight Simulator' => false,
            'Gashapon Lucky Draw' => false,
            'Merchandise' => false,
        ];

        foreach ($stations as $name => $isMandatory) {
            Station::updateOrCreate(
                ['name' => $name],
                ['is_mandatory' => $isMandatory],
            );
        }
    }
}
