<?php

namespace Database\Seeders;

use App\Models\Station;
use Illuminate\Database\Seeder;

class StationSeeder extends Seeder
{
    public function run(): void
    {
        $stations = [
            1 => ['name' => 'Shop for More', 'is_mandatory' => true],
            2 => ['name' => 'More to Enjoy', 'is_mandatory' => true],
            3 => ['name' => 'More to Unwind', 'is_mandatory' => true],
            4 => ['name' => 'More to Stream', 'is_mandatory' => true],
            5 => ['name' => 'Cafe', 'is_mandatory' => true],
            6 => ['name' => 'Flight Simulator', 'is_mandatory' => false],
            7 => ['name' => 'Gashapon Lucky Draw', 'is_mandatory' => false],
            8 => ['name' => 'Merchandise', 'is_mandatory' => false],
        ];

        foreach ($stations as $id => $data) {
            $station = Station::find($id);

            if ($station) {
                $station->update($data);
            } else {
                Station::create(array_merge(['id' => $id], $data));
            }
        }
    }
}
