<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Station;
use App\Models\Regime;
use Spatie\Permission\Models\Role;
use App\Models\User;
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
            'name' => 'History Wall',
            'required'=>true
        ]);

        Station::create([
            'name' => 'Best Seller',
            'required'=>true

        ]);

        Station::create([
            'name' => 'Haircare (Scalp Check)',
            'required'=>true

        ]);

        Station::create([
            'name' => 'Skin Care (Skin Check)',
            'required'=>true

        ]);

        Station::create([
            'name' => 'Photobooth',
            'required'=>false

        ]);

        Station::create([
            'name' => 'Sun Protection (UV Camera)',
            'required'=>false
        ]);

        Station::create([
            'name' => 'Lip Care (Puzzle)',
            'required'=>false
        ]);

        Station::create([
            'name' => 'Acne & Eye Care (Eye Check)',
            'required'=>false
        ]);

        $role = Role::create(['name' => 'client']);

        $role = Role::create(['name' => 'admin']);

        $user = User::create([
            'fname' => 'admin',
            'lname' => 'admin',
            'where' => 'admin',
            'dob' => 'admin',
            'number' => '0123456789',
            'email' => 'admin@gmail.com',
            'country'=> 'Malaysia',
            'password' => Hash::make('WowsomeWardah'),
        ]);

        $user->assignRole('admin');



    }
}
