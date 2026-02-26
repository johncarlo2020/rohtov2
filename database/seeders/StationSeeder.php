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
            'name' => 'Your Libre, Your Freedom',
            'description' => 'Strike a pose, post & hashtag #YSLBeautySG',
        ]);

        Station::create([
            'name' => 'New Icons, Now in Berry Crush',
            'description' => 'From the floral fruity of LIBRE Berry Crush to juicy shine of YSL LOVESHINE Berry Crush, indulge in a double hit of freedom and 
desire.',
        ]);

        Station::create([
            'name' => 'Refill your icons',
            'description' => 'Refill you iconic LIBRE fragrance with the power of forever freedom.',
        ]);

        Station::create([
            'name' => 'Gift Redemption',
            'description' => 'Redeem your YSL Discovery Gift at the gift redemption counter.',
        ]);




        $role = Role::create(['name' => 'client']);

        $role = Role::create(['name' => 'admin']);

        $user = User::create([
            'fname' => 'admin',
            'number' => '0123456789',
            'email' => 'admin@gmail.com',
            'country' => 'Malaysia',
            'password' => Hash::make('WowsomeYsl2025'),
        ]);

        $user->assignRole('admin');
    }
}
