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
                'name' => 'Malton Berhad',
                'projects' => [
                    ['River Park Bangsar South', 'Kuala Lumpur'],
                ],
            ],

            2 => [
                'name' => 'Paramount Property',
                'projects' => [
                    ['Seiras Residences', 'Bandar Cassia, Batu Kawan'],
                    ['Embun Hills', 'Bukit Mertajam'],
                ],
            ],

            3 => [
                'name' => 'Kerjaya Prospek',
                'projects' => [
                    ['AndaBreeze Residences', 'Batu Kawan'],
                ],
            ],

            4 => [
                'name' => 'PDC Properties',
                'projects' => [
                    ['Taman Cassia Cempaka Fasa 2', 'Bandar Cassia, Batu Kawan'],
                    ['Taman Cassia Cempaka Fasa 3', 'Bandar Cassia, Batu Kawan'],
                    ['Taman Damai Lestari', 'Bertam, Kepala Batas'],
                    ['(RMM) Dua Residensi', 'Teluk Kumbar, Bayan Lepas'],
                    ['(RMM) Jiran Residensi', 'Kg Jawa, Butterworth'],
                ],
            ],

            5 => [
                'name' => 'Mah Sing Group',
                'projects' => [
                    ['M Amaya', 'Bayan Lepas'],
                ],
            ],

            6 => [
                'name' => 'Jayamas Property Group',
                'projects' => [
                    ['Ventus & Tradesmen', 'Batu Maung'],
                ],
            ],

            7 => [
                'name' => 'Airmas Group of Companies',
                'projects' => [
                    ['Alton Skyvillas', 'Jelutong'],
                    ['Damai Springs', 'Jalan Residensi Harmoni, Bukit Mertajam'],
                    ['Taman Tan Sai Gin', 'Jalan Tan Sai Gin, Bukit Mertajam'],
                    ['Silkspring Residence', 'Jalan Telaga Air, Butterworth'],
                    ['Taman Prima Emas', 'Permatang Pauh'],
                ],
            ],

            8 => [
                'name' => 'SkyWorld',
                'projects' => [
                    ['SkyWorld Cassia', 'Batu Kawan'],
                    ['SkyWorld Pearlmont', 'Seberang Jaya'],
                ],
            ],

            9 => [
                'name' => 'GSD Group',
                'projects' => [
                    ["G'Vinton", 'Georgetown'],
                    ["D'Harringttons", 'Jelutong'],
                    ["D'Tiara", 'Bayan Lepas'],
                ],
            ],

        ];

        $newIds = array_keys($developers);
        Project::whereNotIn('developer_id', $newIds)->delete();
        Developer::whereNotIn('id', $newIds)->delete();

        foreach ($developers as $id => $data) {

            $developer = Developer::updateOrCreate(
                ['id' => $id],
                ['name' => $data['name']]
            );

            $projectNames = collect($data['projects'])->pluck(0)->all();
            Project::where('developer_id', $developer->id)
                ->whereNotIn('name', $projectNames)
                ->delete();

            foreach ($data['projects'] as $project) {
                Project::updateOrCreate([
                    'developer_id' => $developer->id,
                    'name' => $project[0],
                ], [
                    'address' => $project[1],
                ]);
            }
        }
    }
}
