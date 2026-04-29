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
                'name' => 'JRK Group',
                'projects' => [
                    ['JRK CELESTIA', 'Kinrara Puchong'],
                ],
            ],

            2 => [
                'name' => 'Mah Sing Group Berhad M Terra',
                'projects' => [
                    ['M Terra', 'Puchong'],
                    ['M Aspira', 'Taman Desa, Kuala Lumpur'],
                ],
            ],

            3 => [
                'name' => 'Land & General Berhad',
                'projects' => [
                    ['The WYN Residences', 'Puchong Jaya'],
                ],
            ],

            4 => [
                'name' => 'Mah Sing Group Berhad M Aspira',
                'projects' => [
                    ['M Aspira', 'Taman Desa, Kuala Lumpur'],
                ],
            ],

            6 => [
                'name' => 'Malton Berhad',
                'projects' => [
                    ['River Park Bangsar South', 'Bangsar South, Kuala Lumpur'],
                    ['Park Green Pavilion Bukit Jalil', 'Bukit Jalil, Kuala Lumpur'],
                    ['Mutiara Lake', 'Puchong, Selangor'],
                ],
            ],

            11 => [
                'name' => 'Windsor Land',
                'projects' => [
                    ['Windsor Villa @ Cyberjaya', 'Sepang, Cyberjaya'],
                    ['ALAIA Titiwangsa', 'Titiwangsa'],
                ],
            ],

            12 => [
                'name' => 'Selangor Dredging Berhad',
                'projects' => [
                    ['DaMai', 'Ampang Jaya, Selangor'],
                    ['Elina Senai', 'Taman Putra Perdana, Puchong, Selangor'],
                ],
            ],

            13 => [
                'name' => 'LBS Bina Group Berhad',
                'projects' => [
                    ['KITA Sejati', 'KITA Cybersouth'],
                    ['AULICA', "D'Island Residence Puchong"],
                ],
            ],

            14 => [
                'name' => 'Matrix Concepts Holdings Berhad',
                'projects' => [
                    ['Levia Residence Puchong', 'Persiaran Wawasan Puchong'],
                ],
            ],

            15 => [
                'name' => 'Saujana Development Sdn Bhd',
                'projects' => [
                    ['RESIDENSI RIMBUN SAUJANA', 'Shah Alam, Selangor'],
                ],
            ],

            16 => [
                'name' => 'Eastern & Oriental Berhad',
                'projects' => [
                    ['The Lume', 'Andaman Island, Penang'],
                    ['Maris', 'Andaman Island, Penang'],
                    ['Avéa', 'Andaman Island, Penang'],
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
