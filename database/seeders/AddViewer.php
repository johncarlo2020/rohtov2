<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Station;
use App\Models\Regime;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class AddViewer extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'fname' => 'viewer',
            'lname' => 'viewer',
            'dob' => 'admin',

            'number' => '0123426789',
            'email' => 'pt@gmail.com',
            'country' => 'Malaysia',
            'password' => Hash::make('loccitane2025'),
        ]);

        // $permission = Permission::create(['name' => 'view']);
        // $permission = Permission::create(['name' => 'full']);

        $user->assignRole('admin');

        $user->givePermissionTo('view');

        // $user2 = User::find(1);
        // $user2->givePermissionTo('full');
    }
}
