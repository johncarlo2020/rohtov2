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
            'name' => 'Voting',
            'description' => 'Visit our voting station to<br>get stamp',
        ]);

        Station::create([
            'name' => 'Lucky Draw',
            'description' => 'Visit our lucky draw  <br> to claim your prizes',
        ]);

        Station::create([
            'name' => 'Early Bird',
            'description' => 'Redeem your <br> pre-registration gift!',
        ]);

        Station::create([
            'name' => 'Game',
            'description' => 'Visit our game booth <br> to join the fun',
        ]);

        

        $role = Role::create(['name' => 'client']);

        $role = Role::create(['name' => 'admin']);

        $user = User::create([
            'fname' => 'admin',
            'number' => '0123456789',
            'email' => 'admin@gmail.com',
            'country' => 'Malaysia',
            'password' => Hash::make('WowsomeIproperty2026'),
        ]);

        $user->assignRole('admin');
    }
}
