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
        Station::create([
            'name' => 'Head it Ryt',
            'description' => 'Snap, Share, Tag @kosemy <br> #Sekkisei #SavetheBlue #SustainableBeauty <br> #BlueBottlePower #BeautywithPurpose',
        ]);

        Station::create([
            'name' => 'Discover and Activate',
            'description' => 'Apply & Test',
        ]);

        Station::create([
            'name' => 'Collect your Gift',
            'description' => 'Shine for Hidden Message',
        ]);


        $role = Role::create(['name' => 'client']);

        $role = Role::create(['name' => 'admin']);

        $user = User::create([
            'name' => 'admin',
            'number' => '0123456789',
            'email' => 'admin@gmail.com',
            'country' => 'Malaysia',
            'password' => Hash::make('WowsomeKose2025'),
        ]);

        $user->assignRole('admin');
    }
}
