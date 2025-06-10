<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Station;
use App\Models\User;

class ResetStationsAndAdminSeeder extends Seeder
{
    public function run()
    {
        // Update admin password
        User::where('email', 'admin@gmail.com')->update([
            'password' => Hash::make('WowsomeHL'), // New password
        ]);
    }
}
