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
                'name' => 'UP AT DAWN',
                'slug' => 'up-at-dawn',
                'description' => "Evoking the timeless splendor of an English rose garden, Up at Dawn captures a secret encounter amidst blooming roses on a misty morning. Hidden within the garden’s fragrant embrace, two lovers find each other at first light.\n\nUp at Dawn, a floral woody fragrance that captures the delicate memory of a romance that blooms as dawn breaks.",
                'is_redemption' => false,
            ],
            [
                'name' => 'BY THE FIREPLACE',
                'slug' => 'by-the-fireplace',
                'description' => "The enveloping sensation of a fireplace in the midst of winter. The fireplace crackles with the flames of a comforting fire.\n\nBy the Fireplace evokes cozy winter evenings indoors next to a enveloping fire. The snow gently falls on the frosty white landscape as you are lulled by the gracious dancing flames.",
                'is_redemption' => false,
            ],
            [
                'name' => 'CHASING SUNSET',
                'slug' => 'chasing-sunset',
                'description' => "Imagine Ipanema Beach at sundown, a symphony of oranges and golds melting into the endless horizon.\n\nA heaven on earth, where the wind carries the fragrance of this tropical idyll, all set to a lilting bossa nova beat. Chasing Sunsets, a woody fruity scent evoking the memory of capturing the last ray of sun.",
                'is_redemption' => false,
            ],
            [
                'name' => 'JAZZ CLUB',
                'slug' => 'jazz-club',
                'description' => "Jazz Club evokes the exhilarating atmosphere of a private club where the jazz music and scent of cocktails permeate the air late into the night.\n\nThe elegant smoky scent of cigars fills the room as you enjoy the warmth of rich whisky, cognac or rum. Listen to the jingling of cocktails preparation and lively conversations that interweave with the soft melody of jazz music played by a piano, a double bass or a saxophone...",
                'is_redemption' => false,
            ],
            [
                'name' => 'LAZY SUNDAY MORNING',
                'slug' => 'lazy-sunday-morning',
                'description' => "The soft sensation of fresh linen sheets on a sunny morning. Wake up to the summer softness and feel the immaculate sunlight gently warming your skin as you enjoy a lazy Sunday morning in freshly washed cotton sheets.\n\nLazy Sunday Morning is a delicate floral fragrance that conjures up memories of lingering Sunday mornings in bed. There's nothing you need to do, and nowhere you need to go, just take it easy.",
                'is_redemption' => false,
            ],
            [
                'name' => 'SCENTSORIUM',
                'slug' => 'scentsorium',
                'description' => "CHAPTERS OF LIFE, WRITTEN IN SCENTS.\n\nThe untold story of every human.\nThe most fundamental and contrasted emotions.\ndistilled Into six radical olfactive chapters.",
                'is_redemption' => false,
            ],

            [
                'name' => 'REDEMPTION COUNTER',
                'slug' => 'redemption-counter',
                'description' => "Proceed to\nREDEMPTION COUNTER\nto begin your journey.",
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
