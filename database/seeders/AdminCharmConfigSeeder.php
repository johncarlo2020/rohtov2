<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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

        // Create charm config
        DB::table('charm_configs')->insert([
            'charm_count' => 1060,
            'is_enabled' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
