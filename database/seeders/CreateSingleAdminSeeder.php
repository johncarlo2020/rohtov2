<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class CreateSingleAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * This seeder creates a single admin user for quick setup.
     * Run with: php artisan db:seed --class=CreateSingleAdminSeeder
     */
    public function run(): void
    {
        // Prompt for admin details or use defaults
        $name = $this->command->ask('Enter admin name', 'New Admin');
        $email = $this->command->ask('Enter admin email', 'newadmin@gmail.com');
        $password = $this->command->secret('Enter admin password (leave empty for default)') ?: 'NewAdmin123!';
        $number = $this->command->ask('Enter phone number', '0123456789');
        $country = $this->command->ask('Enter country', 'Malaysia');

        // Check if user already exists
        if (User::where('email', $email)->exists()) {
            $this->command->error("User with email '{$email}' already exists!");
            
            if ($this->command->confirm('Do you want to update the existing user?')) {
                $user = User::where('email', $email)->first();
                $user->update([
                    'fname' => $name,
                    'number' => $number,
                    'country' => $country,
                    'password' => Hash::make($password),
                ]);
                $this->command->info('Existing user updated successfully!');
            } else {
                return;
            }
        } else {
            // Create new admin user
            $user = User::create([
                'fname' => $name,
                'email' => $email,
                'number' => $number,
                'country' => $country,
                'password' => Hash::make($password),
                'marketing' => false,
                'otp_verified' => true,
                'email_verified_at' => now(),
            ]);
            $this->command->info('New admin user created successfully!');
        }

        // Ensure admin role exists
        if (!Role::where('name', 'admin')->exists()) {
            Role::create(['name' => 'admin']);
            $this->command->info('Admin role created.');
        }

        // Ensure permissions exist
        $permissions = ['full', 'view'];
        foreach ($permissions as $permission) {
            if (!Permission::where('name', $permission)->exists()) {
                Permission::create(['name' => $permission]);
                $this->command->info("Permission '{$permission}' created.");
            }
        }

        // Assign role and permissions
        if (!$user->hasRole('admin')) {
            $user->assignRole('admin');
            $this->command->info('Admin role assigned to user.');
        }

        foreach ($permissions as $permission) {
            if (!$user->hasPermissionTo($permission)) {
                $user->givePermissionTo($permission);
                $this->command->info("Permission '{$permission}' granted to user.");
            }
        }

        // Display credentials
        $this->command->info('');
        $this->command->info('========================');
        $this->command->info('Admin User Details:');
        $this->command->info('========================');
        $this->command->info("Name: {$name}");
        $this->command->info("Email: {$email}");
        $this->command->info("Password: {$password}");
        $this->command->info("Phone: {$number}");
        $this->command->info("Country: {$country}");
        $this->command->info('========================');
        $this->command->info('You can now login at /admin/login');
    }
}