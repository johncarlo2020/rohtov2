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
            'name' => 'Weekday',
            'description' => 'Weekday Exclusive Gifts',
        ]);

        Station::create([
            'name' => 'Weekend',
            'description' => 'Weekend Exclusive Gifts',
        ]);

        Station::create([
            'name' => 'Referral',
            'description' => 'Referral Exclusive Gifts',
        ]);

        

       


        $role = Role::create(['name' => 'client']);

        $role = Role::create(['name' => 'admin']);

        $user = User::create([
            'name' => 'admin',
            'number' => '0123456789',
            'email' => 'admin@gmail.com',
            'country' => 'Malaysia',
            'password' => Hash::make('Wowsome4s2025'),
        ]);

        $user->assignRole('admin');
    }
}
