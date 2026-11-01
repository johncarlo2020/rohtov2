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
        Station::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'The Burgundy Obsession',
                'subtitle' => 'BURGUNDY SETS THE STAGE.',
                'description' => 'Discover the new YSL HOLIDAY COLLECTORS,<br>a coveted curation of festive treasures, made to elevate your holiday ritual.',
            ]
        );

        Station::updateOrCreate(
            ['id' => 2],
            [
                'name' => 'Scents of the Season',
                'subtitle' => 'DRESS IN YOUR SCENT.',
                'description' => 'Discover MYSLF\'s modern intensity and LIBRE\'s bold signature—two iconic scents,<br>made for every festive moment.',
            ]
        );

        Station::updateOrCreate(
            ['id' => 3],
            [
                'name' => 'Own the Stage',
                'subtitle' => 'BEAUTY TAKES THE SPOTLIGHT.',
                'description' => 'Strike a post, share your HOLIDAY look on social media<br>& hashtag #YSLBeautyMY',
            ]
        );

        Station::updateOrCreate(
            ['id' => 4],
            [
                'name' => 'Your Holiday Treat',
                'subtitle' => null,
                'description' => 'Redeem your YSL Discovery Gift<br>at the gift redemption counter.',
            ]
        );

        // Ensure only 4 stations exist
        Station::where('id', '>', 4)->delete();

        $clientRole = Role::firstOrCreate(['name' => 'client']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        $user = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'fname' => 'admin',
                'number' => '0123456789',
                'country' => 'Malaysia',
                'password' => Hash::make('WowsomeYsl2026'),
            ]
        );

        if (!$user->hasRole('admin')) {
            $user->assignRole('admin');
        }
    }
}
