<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Station;
use App\Models\Regime;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Brand;

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
            'required' => true,
            'nurse' => 'Rohto',
        ]);

        Station::create([
            'name' => 'First Known Brand',
            'required' => true,
            'nurse' => 'Rohto',
        ]);

        Station::create([
            'name' => 'Haircare (Scalp Check)',
            'required' => true,
            'nurse' => 'Selsun',
        ]);

        Station::create([
            'name' => 'Skin Care (Skin Check)',
            'required' => true,
            'nurse' => 'Hada Labo',
        ]);

        Station::create([
            'name' => 'Photobooth',
            'required' => false,
            'nurse' => 'Rohto',
        ]);

        Station::create([
            'name' => 'Sun Protection (UV Camera)',
            'required' => false,
            'nurse' => 'Sunplay',
        ]);

        Station::create([
            'name' => 'Lip Care (Puzzle)',
            'required' => false,
            'nurse' => 'Mentholatum',
        ]);

        Station::create([
            'name' => 'Acne & Eye Care (Eye Check)',
            'required' => false,
            'nurse' => 'OXY',
        ]);

        Brand::create([
            'name' => 'Hada Labo',
        ]);

        Brand::create([
            'name' => 'Selson Blue',
        ]);

        Brand::create([
            'name' => 'Rohto',
        ]);

        Brand::create([
            'name' => 'Mentholatum',
        ]);

        Brand::create([
            'name' => 'OXY',
        ]);

        Brand::create([
            'name' => '50 Megumi',
        ]);

        Brand::create([
            'name' => 'Sunplay',
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
            'country' => 'Malaysia',
            'password' => Hash::make('WowsomeRohto'),
        ]);

        $user->assignRole('admin');
    }
}
