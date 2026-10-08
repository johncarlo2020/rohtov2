<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GiftsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('gifts')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $gifts = [
            [
                'name' => 'Phone Lanyard',
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
                'name' => 'Notebook',
                'stock_level' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'AEON Voucher RM10',
                'stock_level' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Baskin Robbins RM5',
                'stock_level' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Texas / SF Voucher RM5',
                'stock_level' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Watsons RM10',
                'stock_level' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Oriental Kopi RM10',
                'stock_level' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'BonusLink Giftcard',
                'stock_level' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('gifts')->insert($gifts);
    }
}
