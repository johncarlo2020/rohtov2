<?php

namespace Database\Seeders;

use App\Models\Developer;
use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeveloperProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $developers = [

            1 => [
                'name' => 'IJM Land Berhad',
                'projects' => [
                    ['Merione Grand', 'Gelugor, The Light Waterfront'],
                    ['Terraces Condominium', 'Bukit Jambul'],
                    ['Ayra Terraces', 'Jawi, Seberang Perai Selatan'],
                ],
            ],

            2 => [
                'name' => 'Eastern & Oriental Berhad',
                'projects' => [
                    ['The Lume', 'Andaman Island'],
                    ['Maris', 'Andaman Island'],
                    ['Avéa', 'Andaman Island'],
                ],
            ],

            3 => [
                'name' => 'Paramount Property',
                'projects' => [
                    ['Seiras Residences', 'Batu Kawan'],
                    ['Embun Hills', 'Bukit Mertajam'],
                ],
            ],

            4 => [
                'name' => 'Scientex Berhad',
                'projects' => [
                    ['Scientex Sungai Dua - Tulip', 'Tasek Gelugor'],
                ],
            ],

            5 => [
                'name' => 'Jayamas Property',
                'projects' => [
                    ['Urban Rize', 'Daerah Seberang Perai Utara'],
                    ['Tri Blighton', 'Daerah Seberang Perai Tengah'],
                ],
            ],

            6 => [
                'name' => 'Oriental Kedah Realty',
                'projects' => [
                    ['Taman Kerian Putra', 'Parit Buntar, Perak'],
                    ['Taman Sinar Putra', 'Nibong Tebal'],
                    ['Taman Seri Aman', 'Kulim, Kedah'],
                    ['Taman Ara Damai (Phase 6)', 'Ara Kuda, Tasek Gelugor'],
                    ['SP Saujana Permai (Phase 6)', 'Sungai Petani'],
                ],
            ],

            7 => [
                'name' => 'UDA Land (North) Sdn Bhd',
                'projects' => [
                    ['Eight & Eight Condominium', 'Tanjong Tokong, Daerah Timur Laut'],
                ],
            ],

            8 => [
                'name' => 'Ideal Property Group',
                'projects' => [
                    ['Queens Residences Q3', 'Persiaran Bayan Indah'],
                ],
            ],

            9 => [
                'name' => 'PDC Properties',
                'projects' => [
                    ['Cassia Cempaka Phase 2', 'Taman Cassia Cempaka, Bandar Cassia Batu Kawan'],
                    ['Damai Lestari', 'Bertam Kepala Batas'],
                ],
            ],

            10 => [
                'name' => 'SPB Property',
                'projects' => [
                    ['La Casa Ara', 'Ara Kuda, Tasek Gelugor'],
                    ['La Casa Lunas', 'Lunas, Kulim, Kedah'],
                ],
            ],

        ];

        foreach ($developers as $id => $data) {

            $developer = Developer::create([
                'id' => $id, // 👈 fixed custom ID
                'name' => $data['name'],
            ]);

            foreach ($data['projects'] as $project) {
                Project::create([
                    'developer_id' => $developer->id,
                    'name' => $project[0],
                    'address' => $project[1],
                ]);
            }
        }
    }
}
