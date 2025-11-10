<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Station;
use App\Models\Workshop;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\AppointmentDate;

use Illuminate\Support\Facades\Hash;

class StationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Station::create([
            'name' => 'Treasure Spot 1',
            'question' => 'Which fertilizer practice helps support ESG goals in oil palm farming?',
        ]);

        Station::create([
            'name' => 'Treasure Spot 2',
            'question' => 'Can Yield Booster Prophycient help control Ganoderma deiseasein oil palm?',
        ]);

        Station::create([
            'name' => 'Treasure Spot 3',
            'question' => 'Which of the following services are offered by Agri Analyrics & Services (AAS)',
        ]);

        

       


        $role = Role::create(['name' => 'client']);

        $role = Role::create(['name' => 'admin']);

        $user = User::create([
            'name' => 'admin',
            'number' => '0123456789',
            'email' => 'admin@gmail.com',
            'country' => 'Malaysia',
            'password' => Hash::make('WowsomeRyt2025'),
        ]);

        $user->assignRole('admin');
    }
}
