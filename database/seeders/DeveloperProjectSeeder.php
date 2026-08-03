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
                'name' => 'JLG',
                'projects' => [
                    ['Sanubari', "Bandar Dato' Onn"],
                    ['Onn Eight -3 Storey Shop Office N8', "Bandar Dato' Onn"],
                    ['2 Storey Terrace Tiram', 'Bandar Tiram'],
                ],
            ],
            
            3 => [
                'name' => 'WCT',
                'projects' => [
                    ['Adison @ W City Larkinton', 'Jalan Tun Abdul Razak'],
                ],
            ],

            4 => [
                'name' => 'PGB',
                'projects' => [
                    ['Calia Residences by PGB', 'Danga Bay'],
                ],
            ],

            5 => [
                'name' => 'Mah Sing',
                'projects' => [
                    ['M Minori', 'Seri Austin'],
                    ['M Grand Minori', 'Taman Pelangi'],
                    ['Meridin East', 'Pasir Gudang'],
                    ['M Tiara', 'Skudai'],
                ],
            ],

            6 => [
                'name' => 'TSLaw',
                'projects' => [
                    ['Skyline (Eastside) @ OneSentosa', 'Plaza Sentosa, Jalan Sutera, Taman Sentosa'],
                ],
            ],

            7 => [
                'name' => 'UDA',
                'projects' => [
                    ['UDA Heights', 'Bandar UDA Utama'],
                    ['UDA Sedili Residensi', 'Taman Sedili, Kota Tinggi'],
                    ['UDA Mutiara Residence', 'Bandar UDA Utama'],
                ],
            ],

            8 => [
                'name' => 'Southern Marina',
                'projects' => [
                    ['Southern Marina Residences', 'Puteri Harbour, Iskandar Puteri'],
                ],
            ],

            9 => [
                'name' => 'Maxim',
                'projects' => [
                    ['Maxim The Address JB', 'Taman Pelangi'],
                ],
            ],

            10 => [
                'name' => 'Gunung Impian',
                'projects' => [
                    ['Iconia Garden Residence 2-Storey Terrace', 'Taman Impian Emas, Skudai'],
                    ['Honeydale Residence 2-Storey Cluster', 'Taman Impian Emas, Skudai'],
                ],
            ],

            11 => [
                'name' => 'Tong Tor',
                'projects' => [
                    ['Rosewood II (Double Storey Terrace House)', 'Terra Heights @ Bukit Amber, Johor Bahru'],
                    ['Rosewood I (Double Storey Semi-D Homes)', 'Terra Heights @ Bukit Amber, Johor Bahru'],
                ],
            ],

            12 => [
                'name' => 'R&F',
                'projects' => [
                    ['R&F Princess Cove', 'R&F Tanjung Puteri'],
                ],
            ],

            13 => [
                'name' => 'Keck Seng',
                'projects' => [
                    ['Daya 1 Residences - Serviced Apartments', 'Taman Daya (TD)'],
                    ['Greenwoods Residence - 2 Storey Clusters, Semi-Dees & Link Bungalows', 'Taman Daya (TD)'],
                    ['TD@CTIVE Lifestyle Square @ TD Central', 'Taman Daya (TD)'],
                    ['TD Street - 2 Storey Shop Offices', 'Taman Daya (TD)'],
                    ['Ruby Hills III - 2 Storey Clusters', 'Bandar Baru Kangkar Pulai (BBKP)'],
                    ['Citrine Hills III - 2 Storey Terraces', 'Bandar Baru Kangkar Pulai (BBKP)'],
                    ['Alysia III - 2 Storey Terraces', 'Tanjong Puteri Resort (TPR), Pasir Gudang'],
                    ['Aster III - 1 Storey Terraces', 'Tanjong Puteri Resort (TPR), Pasir Gudang'],
                ],
            ],

            14 => [
                'name' => 'Malton',
                'projects' => [
                    ['River Park Bangsar South', 'Bangsar South, Kuala Lumpur'],
                    ['Park Green Pavilion Bukit Jalil', 'Bukit Jalil, Kuala Lumpur'],
                ],
            ],

            15 => [
                'name' => 'Sunway',
                'projects' => [
                    ['Sunway Majestic', 'Bandar Johor Bahru'],
                    ['Sunway LakeHills', 'Taman Molek'],
                    ['Sunway Citrine Residences', 'Iskandar Puteri'],
                    ['Sunway Sakura 2', 'Bandar Sunway Iskandar Puteri'],
                ],
            ],

            16 => [
                'name' => 'Horizon Hills',
                'projects' => [
                    ['Pavilion 2', 'Horizon Hills, Iskandar Puteri'],
                ],
            ],

            17 => [
                'name' => 'Tanah Sutera',
                'projects' => [
                    ['The Seed', 'Taman Sutera Utama, Skudai'],
                    ['Sutera Garden Village (SGV)', 'Taman Sutera Utama, Skudai'],
                ],
            ],

            18 => [
                'name' => 'Genting',
                'projects' => [
                    ['Bayu Idaman', 'Genting Indahputra, Kulai'],
                ],
            ],

            23 => [
                'name' => 'KPRJ',
                'projects' => [
                    ['Jauhar Bayu Damai', 'Taman Bayu Damai, Pengerang, Johor'],
                ],
            ],

            24 => [
                'name' => 'Webest',
                'projects' => [
                    ['Southbay', 'Bayu Puteri, Johor Bahru'],
                    ['29 Reserve', 'Kota Syahbandar, Melaka'],
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
