<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GiftsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gifts = [
            [
                'name' => 'MFL ticket x100',
                'total' => 100,
                'enabled' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Koppiku QR card x500',
                'total' => 500,
                'enabled' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Water or Fan x1000',
                'total' => 1000,
                'enabled' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('gifts')->insert($gifts);
    }
}
