<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $productNames = [
            'Anti-Hair Loss Treatment', 'Volume & Strength Shampoo', 'Gentle & Balance Shampoo', 'Purifying Freshness Shampoo',
        ];

        foreach ($productNames as $productName) {
            DB::table('products')->insert([
                'name' => $productName,
            ]);
        }
    }
}
