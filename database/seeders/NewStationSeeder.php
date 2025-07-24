<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Station;
use Illuminate\Support\Facades\DB;

class NewStationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Truncate the stations table to remove all existing data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Station::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Add your new stations here
        $stations = [
            'The Blue Paradox',
            'Big Little Things',
            'Almond Ritual',
            'Immortelle Discovery',
            'Hair Care Lounge',
            'Redemption Counter',
        ];

        foreach ($stations as $stationName) {
            Station::create(['name' => $stationName]);
        }

        $this->command->info('Stations table truncated and new stations seeded successfully!');
    }
}
