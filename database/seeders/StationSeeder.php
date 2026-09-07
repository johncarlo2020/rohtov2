<?php

namespace Database\Seeders;

use App\Models\Station;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class StationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Station::firstOrCreate(
            ['name' => 'Let Your Skin Dream Too'],
            ['description' => 'Snap, Share, Tag @kosemy <br> #Sekkisei #SavetheBlue #SustainableBeauty <br> #BlueBottlePower #BeautywithPurpose']
        );

        Station::firstOrCreate(
            ['name' => 'UV Detector Room'],
            ['description' => 'Apply & Test']
        );

        Station::firstOrCreate(
            ['name' => 'Save the Blue Gallery'],
            ['description' => 'Shine for Hidden Message']
        );

        Station::firstOrCreate(
            ['name' => 'Save the Blue Pledge'],
            ['description' => 'Sign & Pledge']
        );

        Station::firstOrCreate(
            ['name' => 'Holistic Clean Beauty'],
            ['description' => 'Match & Correct Your Tone']
        );

        Station::firstOrCreate(
            ['name' => 'Redemption'],
            ['description' => 'Redemption Sample']
        );

        Role::firstOrCreate(['name' => 'client']);

        Role::firstOrCreate(['name' => 'admin']);

        $user = User::updateOrCreate(
            ['email' => 'frisoGold@gmail.com'],
            [
                'fname' => 'admin',
                'find' => 'facebook',
                'number' => '0123456789',
                'dob' => '10/20/2001',
                'country' => 'Malaysia',
                'password' => Hash::make('FrisoGold!!2026'),
            ]
        );

        $user->assignRole('admin');
    }
}
