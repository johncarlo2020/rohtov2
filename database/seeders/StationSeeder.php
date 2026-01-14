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
            'name' => 'Ysl the slim Discovery',
            'description' => 'Discover YSL The Slim, the new couture matte lipstick that elevates your look, your lips and your rules.',
        ]);

        Station::create([
            'name' => 'Fragrance Discovery',
            'description' => 'Discover the iconic YSL fragrances that leave a lasting, bold impression.',
        ]);

        Station::create([
            'name' => 'Be in the Spotlight',
            'description' => 'Strike a pose in the DJ photobooth/around the pop-up, post & hashtag #YSLBeautyMY',
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
