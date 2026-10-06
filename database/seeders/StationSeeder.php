<?php

namespace Database\Seeders;

use App\Models\Station;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stations = [
            [
                'name' => 'REPLICA CAFE',
                'slug' => 'replica-cafe',
                'description' => 'A cozy cafe experience filled with coffee and pastry fragrance notes.',
                'is_redemption' => false,
            ],
            [
                'name' => '37 AT DAWN',
                'slug' => '37-at-dawn',
                'description' => 'Fresh early morning dew and soft light captured in scent.',
                'is_redemption' => false,
            ],
            [
                'name' => 'BY THE FIREPLACE',
                'slug' => 'by-the-fireplace',
                'description' => 'Warm chestnuts and smoky wood notes for a comforting ambience.',
                'is_redemption' => false,
            ],
            [
                'name' => 'CHASING SUNSETS',
                'slug' => 'chasing-sunsets',
                'description' => 'Golden hour memories infused with warm amber and floral notes.',
                'is_redemption' => false,
            ],
            [
                'name' => 'JAZZ CLUB',
                'slug' => 'jazz-club',
                'description' => 'Smooth cocktails and rich tobacco leaves in an intimate setting.',
                'is_redemption' => false,
            ],
            [
                'name' => 'LAST SUNDAY MORNING',
                'slug' => 'last-sunday-morning',
                'description' => 'Clean linen, white musk, and delicate lily of the valley.',
                'is_redemption' => false,
            ],
            [
                'name' => 'SCENTSORIUM',
                'slug' => 'scentsorium',
                'description' => 'An immersive olfactory journey exploring memory notes.',
                'is_redemption' => false,
            ],
            [
                'name' => 'PHOTOBOOTH',
                'slug' => 'photobooth',
                'description' => 'Capture your Maison Margiela memory photo moment.',
                'is_redemption' => false,
            ],
            [
                'name' => 'REDEMPTION COUNTER',
                'slug' => 'redemption-counter',
                'description' => 'Redeem your exclusive Maison Margiela gift upon completing 5 stations.',
                'is_redemption' => true,
            ],
        ];

        foreach ($stations as $data) {
            Station::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'is_redemption' => $data['is_redemption'],
                ]
            );
        }
    }
}
