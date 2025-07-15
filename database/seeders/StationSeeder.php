<?php

namespace Database\Seeders;

use App\Models\Appointment;
use Illuminate\Database\Seeder;
use App\Models\Station;
use App\Models\Task;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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
        // Create stations
        $stations = [
            'Skin Analysis',
            'Hada Baby Aqua Lab',
            'Product Experiences',
            'Photo Op',
            'Gift Redemption',
            'Gift Redemption',
        ];
        foreach ($stations as $stationName) {
            Station::create(['name' => $stationName]);
        }

        // Create appointments in chronological order using loop
        $appointments = [
            ['07-28-2025', 200],
            ['07-29-2025', 200],
            ['07-30-2025', 200],
            ['07-31-2025', 200],
            ['08-01-2025', 200],
            ['08-02-2025', 250],
            ['08-03-2025', 250],
        ];
        foreach ($appointments as [$date, $total]) {
            Appointment::create([
                'name' => $date,
                'total' => (string) $total,
                'status' => '1',
            ]);
        }

        // Create tasks
        $tasks = [

            ['Join our Big Little Things Program', 'Sign up and start recycling your beauty empties with us.'],
            ['Say No to Plastic Bags', 'Bring your own reusable bag when you shop.'],
            ['Skip Single-use Straw & Bottle or Cup', 'Make a conscious choice to opt for reusable alternatives.'],
            ['Pledge for the ocean.','will you pledge to protect our oceans?'],
            ['Explore Sustainable Credit Card', 'Show your support for greener banking—express interest in Alliance Bank sustainable credit card.'],
        ];
        foreach ($tasks as [$taskName, $desc]) {
            Task::create(['name' => $taskName, 'description' => $desc]);
        }


        // Create roles
        foreach (['client', 'admin'] as $roleName) {
            Role::create(['name' => $roleName]);
        }

         $staffIds = [
            'YLF645', 'YLF262', 'YLF683', 'YLF637', 'YLF612',
            'YLF553', 'YLF628', 'YLF648', 'YLF531', 'YLF207',
            'YLF599', 'YLF651', 'YLF651', 'YLF620', 'YLF681',
            'YLF610', 'YLF686', 'YLF571', 'YLF516', 'YLF006',
            'YLF682', 'YLF653', 'YLF618', 'YLF500', 'YLF526',
            'YLF240', 'YLF016', 'YLF673', 'YLF533', 'YLF176',
            'YLF660', 'YLF038', 'YLF770', 'YLF684',
        ];

        foreach ($staffIds as $staffId) {
            DB::table('staff')->insert([
                'name' => $staffId,
            ]);
        }

        $productNames = [
            'Anti-Hair Loss Treatment', 'Volume & Strength Shampoo', 'Gentle & Balance Shampoo', 'Purifying Freshness Shampoo',
        ];

        foreach ($productNames as $productName) {
            DB::table('products')->insert([
                'name' => $productName,
            ]);
        }

        $user = User::create([
            'fname' => 'admin',
            'lname' => 'admin',
            'dob' => 'admin',
            'number' => '0123456789',
            'email' => 'admin@gmail.com',
            'country' => 'Malaysia',
            'password' => Hash::make('WowsomeLoccitane2025'),
        ]);

        $user->assignRole('admin');
    }
}
