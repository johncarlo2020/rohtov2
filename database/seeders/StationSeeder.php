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
            'name' => 'Let Your Skin Dream Too',
            'description' => 'Snap,Share,tag @kosemy',
        ]);

        Station::create([
            'name' => 'UV Detector Room',
            'description' => 'Apply & Test',
        ]);

        Station::create([
            'name' => 'Save the Blue Gallery',
            'description' => 'Shine for hidden message',
        ]);

        Station::create([
            'name' => 'Save the Blue Pledge',
            'description' => 'Sign & Pledge',
        ]);

        Station::create([
            'name' => 'Holistic Clean Beauty',
            'description' => 'Match & Correct yout tone',
        ]);

        Station::create([
            'name' => 'Redemption',
            'description' => 'Redemption Sample',
        ]);



        $role = Role::create(['name' => 'client']);

        $role = Role::create(['name' => 'admin']);

        $user = User::create([
            'fname' => 'admin',
            'lname' => 'admin',
            'find' => 'Facebook',
            'dob' => 'admin',
            'number' => '0123456789',
            'email' => 'admin@gmail.com',
            'country' => 'Malaysia',
            'password' => Hash::make('WowsomeDutch'),
        ]);

        $user->assignRole('admin');
    }
}
