<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\VoucherSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(StationSeeder::class);
        $this->call(CountriesTableSeeder::class);
        $this->call(AdminUserSeeder::class);
        $this->call(PerfumeSeeder::class);
        $this->call(DeveloperProjectSeeder::class);
        $this->call(QuestionSeeder::class);
        $this->call(GiftsSeeder::class);
        $this->call(VoucherSeeder::class);
        $this->call(OperatingHoursSeeder::class);
    }
}
