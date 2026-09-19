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
                    ['Medora One', "Bandar Dato' Onn"],
                    ['Sanubari', "Bandar Dato' Onn"],
                    ['Onn Eight -3 Storey Shop Office N8', "Bandar Dato' Onn"],
                    ['2 Storey Terrace Tiram', 'Bandar Tiram'],
                ],
            ],

            2 => [
                'name' => 'Mahsing',
                'projects' => [
                    ['M Minori', 'Seri Austin'],
                    ['M Grand Minori', 'Taman Pelangi'],
                    ['Meridin East', 'Pasir Gudang'],
                    ['M Tiara', 'Skudai'],
                ],
            ],

            3 => [
                'name' => 'Premier Plus',
                'projects' => [
                    ['Senyum Residences', 'Jalan Wadi Hana'],
                    ['Bandar Cemerlang Precinct J1', 'Bandar Cemerlang'],
                    ["D' Art Nature Home", 'Iskandar Puteri'],
                ],
            ],

            4 => [
                'name' => 'WCT',
                'projects' => [
                    ['Adison @ W City Larkinton', 'Jalan Tun Abdul Razak'],
                ],
            ],

            5 => [
                'name' => 'Malton',
                'projects' => [
                    ['Mutiara Kempas', 'Kempas, Johor Bahru'],
                    ['River Park Bangsar South', 'Bangsar South, Kuala Lumpur'],
                    ['Park Green Pavilion Bukit Jalil', 'Bukit Jalil, Kuala Lumpur'],
                ],
            ],

            6 => [
                'name' => 'R&F Princess Cove',
                'projects' => [
                    ['R&F Princess Cove Phase 3, New Casa Suites', 'R&F Tanjung Puteri, Johor Bahru'],
                ],
            ],

            7 => [
                'name' => 'PGB',
                'projects' => [
                    ['Calia Residences by PGB', 'Danga Bay'],
                ],
            ],

            8 => [
                'name' => 'Tropicana',
                'projects' => [
                    ['Skypark Kepler @ Lido Waterfront Boulevard', 'Jalan Sultan Abu Bakar, Johor Bahru'],
                ],
            ],

            9 => [
                'name' => 'TSLAW Land',
                'projects' => [
                    ['Skyline (Eastside) @ OneSentosa', 'Plaza Sentosa, Jalan Sutera, Taman Sentosa'],
                ],
            ],

            10 => [
                'name' => 'Maxim',
                'projects' => [
                    ['The Address', 'Taman Pelangi'],
                ],
            ],

            11 => [
                'name' => 'SPB',
                'projects' => [
                    ['Taman Akasia', 'Kluang, Johor'],
                    ['Taman Nuri', 'Durian Tunggal, Melaka'],
                ],
            ],

            12 => [
                'name' => 'Solusi Kelana',
                'projects' => [
                    ['Residensi Sinaran @ JB City Centre', 'Johor Bahru City Centre'],
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
