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
                'name' => 'AEON RM 10 Gift Voucher',
                'stock_level' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Baskin Robbins Voucher',
                'stock_level' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Neck Fan',
                'stock_level' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'iProperty Notebook',
                'stock_level' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Towel',
                'stock_level' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Texas Chicken RM 5 Cash Voucher',
                'stock_level' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Watsons RM 10 Gift Voucher',
                'stock_level' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'iProperty Phone Lanyard',
                'stock_level' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mini Duffel Bag',
                'stock_level' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('gifts')->insert($gifts);
    }
}
