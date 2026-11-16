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
        Voucher::where('name', 'CHAGEE')->where('session', '>', 1)->delete();

        Voucher::updateOrCreate(
            [
                'name' => 'CHAGEE',
                'session' => 1,
            ],
            [
                'quota' => 50,
                'starts_at' => Carbon::create(2026, 11, 22, 11, 0, 0),
                'ends_at'   => Carbon::create(2026, 11, 22, 23, 59, 59),
            ]
        );
    }
}
