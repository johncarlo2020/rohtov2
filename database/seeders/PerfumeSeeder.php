<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Perfume;


class PerfumeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $perfumes = [
            ['title' => 'Libre EDP'],
            ['title' => 'Libre Berry Crush'],
            ['title' => 'Libre F&F'],
            ['title' => "Libre L'eau Nu"],
            ['title' => 'Libre EDP Intense'],
        ];

        foreach ($perfumes as $perfume) {
            Perfume::create($perfume);
        }
    }
}
