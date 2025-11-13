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
        $user = User::firstOrCreate(
            ['email' => 'viewer@gmail.com'],
            [
                'name' => 'viewer',
                'number' => '0123456789',
                'country' => 'Malaysia',
                'password' => Hash::make('WowsomeKoseViewer'),
                // 'find' => 'facebook',
                // 'dob' => '10/20/2001',
            ]
        );

        $permission = Permission::firstOrCreate(['name' => 'view']);
        $permission = Permission::firstOrCreate(['name' => 'full']);

        $user->assignRole('admin');

        $user->givePermissionTo('view');

        $user2 = User::find(1);
        if ($user2) {
            $user2->givePermissionTo('full');
        }
    }
}
