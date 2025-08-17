<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Permission;

class UpdateAdminPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find the admin user by email
        $admin = User::where('email', 'admin@loccitane.com')->first();

        if (!$admin) {
            $this->command->error('Admin user with email admin@loccitane.com not found!');
            return;
        }

        // Create permissions if they don't exist
        if (!Permission::where('name', 'full')->exists()) {
            Permission::create(['name' => 'full']);
            $this->command->info('Created "full" permission');
        }

        if (!Permission::where('name', 'view')->exists()) {
            Permission::create(['name' => 'view']);
            $this->command->info('Created "view" permission');
        }

        // Assign admin role if not already assigned
        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
            $this->command->info('Assigned "admin" role to user');
        }

        // Give full permission to admin user
        if (!$admin->hasPermissionTo('full')) {
            $admin->givePermissionTo('full');
            $this->command->info('Gave "full" permission to admin user');
        }

        // Also give view permission
        if (!$admin->hasPermissionTo('view')) {
            $admin->givePermissionTo('view');
            $this->command->info('Gave "view" permission to admin user');
        }

        $this->command->info('Admin user permissions updated successfully!');
        $this->command->info('User roles: ' . $admin->getRoleNames()->implode(', '));
        $this->command->info('User permissions: ' . $admin->getPermissionNames()->implode(', '));
    }
}
