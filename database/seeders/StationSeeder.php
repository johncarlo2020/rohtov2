<?php

namespace Database\Seeders;

use App\Models\Appointment;
use Illuminate\Database\Seeder;
use App\Models\Station;
use App\Models\Regime;
use Spatie\Permission\Models\Role;
use App\Models\User;

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
            'name' => 'Skin Analysis',
        ]);

        Station::create([
            'name' => 'Hada Baby Aqua Lab',
        ]);

        Station::create([
            'name' => 'Product Experiences',
        ]);

        Station::create([
            'name' => 'Photo Op',
        ]);

        Station::create([
            'name' => 'Gift Redemption',
        ]);

        Appointment::create([
            'name' => '05-27-2025',
            'total' => '413',
        ]);

        Appointment::create([
            'name' => '05-28-2025',
            'total' => '413',
        ]);
        Appointment::create([
            'name' => '05-29-2025',
            'total' => '412',
        ]);
        Appointment::create([
            'name' => '05-30-2025',
            'total' => '412',
        ]);

        Appointment::create([
            'name' => '05-31-2025',
            'total' => '117',
        ]);

        Appointment::create([
            'name' => '06-01-2025',
            'total' => '117',
        ]);

        Appointment::create([
            'name' => '06-02-2025',
            'total' => '116',
        ]);


        $role = Role::create(['name' => 'client']);

        $role = Role::create(['name' => 'admin']);

        $user = User::create([
            'fname' => 'admin',
            'lname' => 'admin',
            'dob' => 'admin',
            'number' => '0123456789',
            'email' => 'admin@gmail.com',
            'country' => 'Malaysia',
            'password' => Hash::make('WowsomeRohto'),
        ]);

        $user->assignRole('admin');
    }
}
