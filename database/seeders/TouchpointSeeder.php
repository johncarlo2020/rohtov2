<?php

namespace Database\Seeders;

use App\Models\Touchpoint;
use Illuminate\Database\Seeder;

class TouchpointSeeder extends Seeder
{
    public function run(): void
    {
        $touchpoints = [
            ['key' => 'station_1', 'label' => 'Station 1', 'required_touches' => 4],
            ['key' => 'station_2', 'label' => 'Station 2', 'required_touches' => 4],
            ['key' => 'station_3', 'label' => 'Station 3', 'required_touches' => 5],
            ['key' => 'station_4', 'label' => 'Station 4', 'required_touches' => 5],
            ['key' => 'redemption', 'label' => 'Redemption', 'required_touches' => 6],
            ['key' => 'in-store', 'label' => 'In-store', 'required_touches' => 6],
        ];

        foreach ($touchpoints as $touchpoint) {
            Touchpoint::updateOrCreate(
                ['key' => $touchpoint['key']],
                $touchpoint
            );
        }
    }
}
