<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Permission;

class AdminCharmConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'fname' => 'Admin',
            'lname' => 'User',
            'email' => 'admin@loccitane.com',
            'number' => '+60123456789',
            'password' => Hash::make('password123'),
            'otp' => '123456',
            'dob' => '2000-01-01',
            'country' => 'admin',
            'otp_verified' => true,
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Assign admin role to the user
        $admin->assignRole('admin');

        // Create permissions if they don't exist
        if (!Permission::where('name', 'full')->exists()) {
            Permission::create(['name' => 'full']);
        }
        if (!Permission::where('name', 'view')->exists()) {
            Permission::create(['name' => 'view']);
        }

        // Give full permission to admin user
        $admin->givePermissionTo('full');

        // Create charm config
        DB::table('charm_configs')->insert([
            'charm_count' => 1060,
            'is_enabled' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
