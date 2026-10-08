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
                'name' => 'L&G',
                'projects' => [
                    ['The WYN Residences', 'Puchong Jaya'],
                ],
            ],

            2 => [
                'name' => 'Paramount',
                'projects' => [
                    ['Uptown Residences 2 @ Berkeley Uptown', 'Bandar Klang'],
                    ['Sejati Residences', 'Cyberjaya'],
                    ['The Atera', 'Petaling Jaya'],
                ],
            ],

            3 => [
                'name' => 'Gamuda Land',
                'projects' => [
                    ['The Clove', 'Gamuda Cove'],
                    ['Mori Pines', 'Gamuda Cove'],
                    ['Mio Spring', 'Gamuda Cove'],
                    ['Quayside Plazas', 'Gamuda Cove'],
                    ['Luxura Designer Courtyard', 'twentyfive7'],
                    ['Link Villas', 'twentyfive7'],
                    ['Levane Residences', 'twentyfive7'],
                    ['The Clove Signature', 'twentyfive7'],
                ],
            ],

            4 => [
                'name' => 'Tropicana Alam',
                'projects' => [
                    ['Avisa Residences', 'Puncak Alam, Selangor'],
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
