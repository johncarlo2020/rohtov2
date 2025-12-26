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
            'name' => 'Omega Gallery',
            'description' => 'Weekday Exclusive Gifts',
        ]);

        Station::create([
            'name' => 'Curious Kids Corner',
            'description' => 'Weekend Exclusive Gifts',
        ]);

        Station::create([
            'name' => 'Power Path Maze',
            'description' => 'Referral Exclusive Gifts',
        ]);

        Station::create([
            'name' => 'Omega Power Up',
            'description' => 'Referral Exclusive Gifts',
        ]);

        Station::create([
            'name' => 'Smile & Shine',
            'description' => 'Referral Exclusive Gifts',
        ]);

        Station::create([
            'name' => 'Penebusan Hadiah',
            'description' => 'Referral Exclusive Gifts',
        ]);

        



        $role = Role::create(['name' => 'client']);

        $role = Role::create(['name' => 'admin']);

        $user = User::create([
            'fname' => 'admin',
            'number' => '0123456789',
            'email' => 'admin@gmail.com',
            'country' => 'Malaysia',
            'password' => Hash::make('WowsomeOmega2025'),
        ]);

        $user->assignRole('admin');
    }
}
