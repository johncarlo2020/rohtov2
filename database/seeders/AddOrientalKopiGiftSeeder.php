<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddOrientalKopiGiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('gifts')->insert([
            'name' => 'Oriental Kopi RM 10 Voucher',
            'stock_level' => 10,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
