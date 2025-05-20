<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Station;
use App\Models\User;

class ResetStationsAndAdminSeeder extends Seeder
{
    public function run()
    {
        // Truncate stations table to delete all and reset auto-increment
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate stations table
        DB::table('stations')->truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Recreate stations
        $stations = [
            'The Blue Paradox',
            'Big Little Things',
            'Skin Consultation',
            'Almond Ritual',
            'Hair Diagnosis',
            'Redemption Counter',
            'Upcycled Marine Charm Corner',

        ];

        foreach ($stations as $stationName) {
            Station::create(['name' => $stationName]);
        }

        // Update admin password
        User::where('email', 'admin@gmail.com')->update([
            'password' => Hash::make('loccitane2025'), // New password
        ]);
    }
}
