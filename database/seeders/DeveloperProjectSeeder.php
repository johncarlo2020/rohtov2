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

            'Berjaya Property Berhad' => [
                ['Jesselton Courtyard at Jesselton Selatan', 'George Town, Penang'],
                ['OAKA Residences', 'Bukit Jalil, Kuala Lumpur'],
                ['Times Square 2', 'Bukit Bintang, Kuala Lumpur'],
            ],

            'Mah Sing Group Berhad' => [
                ['M Zenni', 'Bayan Lepas, Penang'],
            ],

            'Belleview Group' => [
                ['Gem Residences', 'Perai, Penang'],
            ],

            'GSD Group' => [
                ["G' Vinton", 'George Town, Penang'],
                ["D'Hazelton", 'Farlim, Penang'],
                ["D'Tiara", 'Teluk Kumbar, Penang'],
            ],

            'Penang Null' => [
                ["Penang", 'Null, Penang']
            ],

            'Malton Berhad' => [
                ['River Park Bangsar South', 'Bangsar South, Kuala Lumpur'],
                ['Park Green Pavilion Bukit Jalil', 'Bukit Jalil, Kuala Lumpur'],
            ],

            'S P Setia Berhad' => [
                ['Setia SV2', 'Jelutong, George Town'],
            ],

            'PDC Properties Sdn Bhd' => [
                ['Cassia Cempaka Phase 2', 'Bandar Cassia Batu Kawan'],
                ['Damai Lestari', 'Bertam Kepala Batas']
            ],

            'UDA Land (North) Sdn Bhd' => [
                ['Eight & Eight Condominium', 'Bandar Tanjung Tokong, Pulau Pinang'],
            ],
        ];

        foreach ($developers as $devName => $projects) {

            $developer = Developer::create([
                'name' => $devName
            ]);

            foreach ($projects as $project) {
                Project::create([
                    'developer_id' => $developer->id,
                    'name' => $project[0],
                    'address' => $project[1],
                ]);
            }
        }
    }
}
