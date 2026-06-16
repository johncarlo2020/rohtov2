<?php

namespace Database\Seeders;

use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VoucherSeeder extends Seeder
{
    public function run(): void
    {
        Voucher::updateOrCreate(
            [
                'name' => 'CHAGEE',
                'session' => 1,
            ],
            [
                'quota' => 50,
                'starts_at' => Carbon::create(2026, 6, 20, 12, 0, 0),
                'ends_at'   => Carbon::create(2026, 6, 20, 17, 59, 59),
            ]
        );

        Voucher::updateOrCreate(
            [
                'name' => 'CHAGEE',
                'session' => 2,
            ],
            [
                'quota' => 50,
                'starts_at' => Carbon::create(2026, 6, 20, 18, 0, 0),
                'ends_at'   => Carbon::create(2026, 6, 20, 23, 59, 59),
            ]
        );
    }
}
