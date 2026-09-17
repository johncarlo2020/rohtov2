<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = config('admin.email');
        $password = config('admin.password');

        if (! $email || ! $password) {
            $this->command?->warn('Admin account skipped: set ADMIN_EMAIL and ADMIN_PASSWORD in .env.');
            return;
        }

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('ADMIN_EMAIL must be a valid email address.');
        }

        DB::transaction(function () use ($email, $password) {
            $admin = User::where('email', $email)->first();

            if ($admin && ! $admin->hasRole('admin')) {
                throw new \RuntimeException('ADMIN_EMAIL belongs to an existing non-admin account. Choose another email.');
            }

            $role = Role::findOrCreate('admin', 'web');
            foreach (['full', 'view'] as $permission) {
                $role->givePermissionTo(Permission::findOrCreate($permission, 'web'));
            }

            if (! $admin) {
                $admin = User::create([
                    'fname' => config('admin.name'),
                    'lname' => '',
                    'email' => $email,
                    'password' => Hash::make($password),
                    'number' => '',
                    'dob' => '',
                    'country' => '',
                    'type' => 'admin',
                    'marketing' => false,
                    'email_consent' => false,
                    'sms_consent' => false,
                ]);
            }

            $admin->assignRole($role);
        });

        $this->command?->info('Admin account is ready. Existing passwords are preserved.');
    }
}
