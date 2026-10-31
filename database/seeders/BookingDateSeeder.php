<?php

namespace Database\Seeders;

use App\Models\BookingDate;
use Illuminate\Database\Seeder;

class BookingDateSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['2026-10-24', '2026-10-31', '2026-11-07', '2026-11-14','2026-11-21','2026-11-28'] as $date) {
            BookingDate::updateOrCreate(
                ['date' => $date],
                ['capacity' => 18, 'is_available' => true, 'reason' => null]
            );
        }
    }
}
