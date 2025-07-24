<?php

namespace Database\Seeders;

use App\Models\GameConfig;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GameConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default game configuration
        GameConfig::create([
            'max_weight' => 4.0,
            'increment_grams' => 100,
            'is_active' => true,
        ]);
    }
}
