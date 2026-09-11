<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure roles exist
        foreach (['superadmin', 'admin', 'staff', 'client'] as $roleName) {
            if (!Role::where('name', $roleName)->exists()) {
                Role::create(['name' => $roleName]);
            }
        }

        // Create permissions if they don't exist
        if (!Permission::where('name', 'full')->exists()) {
            Permission::create(['name' => 'full']);
        }
        
        if (!Permission::where('name', 'view')->exists()) {
            Permission::create(['name' => 'view']);
        }

        // 1. Create admin@gmail.com (Super Admin)
        $adminMain = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'fname' => 'Super',
                'lname' => 'Admin',
                'number' => '0123456788',
                'country' => 'Malaysia',
                'password' => Hash::make('LongChamp2026!'),
                'marketing' => false,
                'otp_verified' => true,
                'email_verified_at' => now(),
            ]
        );
        $adminMain->syncRoles(['superadmin', 'admin']);
        $adminMain->givePermissionTo(['full', 'view']);

        // 2. Create superadmin@gmail.com (Super Admin)
        $superAdminUser = User::updateOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'fname' => 'Super Admin',
                'number' => '0123456789',
                'country' => 'Malaysia',
                'password' => Hash::make('LongChampSuper2026!'),
                'marketing' => false,
                'otp_verified' => true,
                'email_verified_at' => now(),
            ]
        );
        $superAdminUser->syncRoles(['superadmin', 'admin']);
        $superAdminUser->givePermissionTo(['full', 'view']);

        $this->command->info('Super Admin users created successfully!');
        $this->command->info('Email: admin@gmail.com / LongChamp2026!');
        $this->command->info('Email: superadmin@gmail.com / LongChampSuper2026!');

        // Optionally create additional admin & staff users
        $this->createAdditionalAdmins();
    }




    /**
     * Create additional admin users if needed
     */
    private function createAdditionalAdmins(): void
    {
        $additionalAdmins = [
            [
                'fname' => 'Admin Manager',
                'email' => 'manager@gmail.com',
                'number' => '0198765432',
                'country' => 'Malaysia',
                'password' => Hash::make('Manager123!'),
            ],
            [
                'fname' => 'Admin Support',
                'email' => 'support@gmail.com', 
                'number' => '0187654321',
                'country' => 'Malaysia',
                'password' => Hash::make('Support123!'),
            ]
        ];

        foreach ($additionalAdmins as $adminData) {
            $user = User::updateOrCreate(
                ['email' => $adminData['email']],
                array_merge($adminData, [
                    'marketing' => false,
                    'otp_verified' => true,
                    'email_verified_at' => now(),
                ])
            );

            if (!$user->hasRole('admin')) {
                $user->assignRole('admin');
            }

            if (!$user->hasPermissionTo('full')) {
                $user->givePermissionTo('full');
            }

            if (!$user->hasPermissionTo('view')) {
                $user->givePermissionTo('view');
            }

            $plainPassword = str_replace(Hash::make(''), '', $adminData['password']);
            // Extract plain password from the array (it's already in plain text)
            $originalPassword = $adminData['email'] === 'manager@gmail.com' ? 'Manager123!' : 'Support123!';
            $this->command->info("Admin user created: {$adminData['email']} / {$originalPassword}");
        }
    }
}