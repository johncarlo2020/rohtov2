<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Station;
use App\Models\Workshop;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\AppointmentDate;

use Illuminate\Support\Facades\Hash;

class StationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stations = [
            1 => [
                'name' => 'Madison Square',
                'description' => 'Madison Square',
            ],
            2 => [
                'name' => 'Arena of Stars',
                'description' => 'Arena of Stars',
            ],
            3 => [
                'name' => 'Genting SkyWorlds Theme Park – Studio Plaza',
                'description' => 'Genting SkyWorlds Theme Park<br>Studio Plaza',
            ],
            4 => [
                'name' => 'Kimhua Atrium',
                'description' => 'Kimhua Atrium',
            ],
            5 => [
                'name' => 'Pavilion Hall',
                'description' => 'Pavilion Hall<br>Near Skyropolis Indoor Theme Park',
            ],
            6 => [
                'name' => "SkyAvenue",
                'description' => "SkyAvenue<br>Near Level 1, Swensen's",
            ],
        ];

        foreach ($stations as $id => $data) {
            Station::updateOrCreate(['id' => $id], $data);
        }

        Role::firstOrCreate(['name' => 'client']);
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'Super Admin']);
        Role::firstOrCreate(['name' => 'Campaign Admin']);
        Role::firstOrCreate(['name' => 'Redemption Staff']);

        $user = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'fname' => 'admin',
                'number' => '0123456789',
                'country' => 'Malaysia',
                'password' => Hash::make('WowsomeIproperty2026'),
            ]
        );

        $user->assignRole('admin');
        $user->assignRole('Super Admin');

        // Seed Default Campaign Gift with 300 daily quota
        \App\Models\Gifts::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'Winter Wonderland Treasure Hunt Reward',
                'stock_level' => 300,
            ]
        );
    }
}
