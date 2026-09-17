<?php

namespace Tests\Feature;

use App\Models\Station;
use Database\Seeders\StationSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class StationSeederTest extends TestCase
{
    public function test_station_migrations_and_seeding_can_be_repeated(): void
    {
        config([
            'database.default' => 'station_test',
            'database.connections.station_test' => [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
            ],
        ]);

        try {
            require_once database_path('migrations/2024_05_09_152825_create_stations_table.php');
            $create = new \CreateStationsTable;
            $create->up();
            $create->up();

            // Simulate an existing station with linked history that must retain its ID.
            $id = DB::table('stations')->insertGetId(['name' => 'Shop for More']);
            $migration = require database_path('migrations/2026_09_17_000000_add_is_mandatory_to_stations_table.php');
            $migration->up();
            $migration->up();

            $seeder = new StationSeeder;
            $seeder->run();
            Station::where('name', 'Shop for More')->update(['is_mandatory' => false]);
            $seeder->run();

            $this->assertSame(8, Station::count());
            $this->assertSame([
                'Shop for More', 'More to Enjoy', 'More to Unwind',
                'More to Stream', 'Enjoy More Cafe',
            ], Station::where('is_mandatory', true)->orderBy('id')->pluck('name')->all());
            $this->assertSame([
                'Flight Simulator', 'Gashapon Lucky Draw', 'Merchandise',
            ], Station::where('is_mandatory', false)->orderBy('id')->pluck('name')->all());
            $this->assertSame($id, Station::where('name', 'Shop for More')->firstOrFail()->id);
            $this->assertTrue(Station::findOrFail($id)->is_mandatory);
            $this->assertFalse(Station::where('name', 'Merchandise')->firstOrFail()->is_mandatory);

            $migration->down();
            $this->assertFalse(Schema::hasColumn('stations', 'is_mandatory'));
        } finally {
            DB::purge('station_test');
        }
    }
}
