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
            'name' => 'Iconic Beginnings',
            'description' => 'Product Discovery',
        ]);

        Station::create([
            'name' => 'Follow your Instinct',
            'description' => 'Makeup Stations',
        ]);

        Station::create([
            'name' => 'Seeing Multiple',
            'description' => 'Promotion Shoutout',
        ]);

        Station::create([
            'name' => 'Shape your Way',
            'description' => 'Get Free gelatos',
        ]);

        // Station::create([
        //     'name' => 'Gift Redemption',
        //     'description' => 'Get Free Gelatos',
        // ]);



        $role = Role::create(['name' => 'client']);
        $role = Role::create(['name' => 'admin']);
        $user = User::create([
            'fname' => 'super',
            'lname' => 'admin',
            'number' => '0123456789',
            'dob' => '10/20/2001',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('WowsomeKose2025'),
        ]);

        $user->assignRole('admin');
    }
}
