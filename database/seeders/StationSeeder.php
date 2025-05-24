<?php

namespace Database\Seeders;

use App\Models\Appointment;
use Illuminate\Database\Seeder;
use App\Models\Station;
use App\Models\Task;
use Spatie\Permission\Models\Role;
use App\Models\User;

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
        Station::create([
            'name' => '#1: The Blue Paradox',
            'description' => 'Experience the ocean’s wonder—and the urgent reality beneath the waves.',
        ]);

        Station::create([
            'name' => '#2: Big Little Things',
               'description' => 'Drop off your used plastics here and discover how they’re transformed — not just recycled, but upcycled.',
        ]);

        Station::create([
            'name' => '#3: Skin Consultation',
               'description' => 'Experience a moment of indulgence — nourishing your skin with rich almond oil for a smoother body, softer skin, and a soothed sense',
        ]);

        Station::create([
            'name' => '#4: Almond Ritual',
               'description' => '',
        ]);

        Station::create([
            'name' => '#5: Hair Diagnosis',
               'description' => '',
        ]);

        Appointment::create([
            'name' => '05-27-2025',
            'total' => '413',
        ]);

        Appointment::create([
            'name' => '05-28-2025',
            'total' => '413',
        ]);
        Appointment::create([
            'name' => '05-29-2025',
            'total' => '412',
        ]);
        Appointment::create([
            'name' => '05-30-2025',
            'total' => '412',
        ]);

        Appointment::create([
            'name' => '05-31-2025',
            'total' => '117',
        ]);

        Appointment::create([
            'name' => '06-01-2025',
            'total' => '117',
        ]);

        Appointment::create([
            'name' => '06-02-2025',
            'total' => '116',
        ]);

        Task::create([
            'name' => 'Join our Big Little Things Program',
            'description' => 'Sign up and start recycling your beauty empties with us.'
        ]);

        Task::create([
            'name' => 'Say No to Plastic Bags',
            'description' => 'Bring your own reusable bag when you shop.'
        ]);

        Task::create([
            'name' => 'Skip Single-use Straw & Bottle or Cup',
            'description' => 'Make a conscious choice to opt for reusable alternatives.'
        ]);

        Task::create([
            'name' => 'Switch to Eco-Refills',
            'description' => "Choose eco-refills for your favorite L'Occitane products and reduce waste with every purchase."
        ]);

        Task::create([
            'name' => 'Explore Sustainable Credit Card',
            'description' => 'Show your support for greener banking—express interest in Alliance Bank sustainable credit card.'
        ]);


        $role = Role::create(['name' => 'client']);

        $role = Role::create(['name' => 'admin']);

        $user = User::create([
            'fname' => 'admin',
            'lname' => 'admin',
            'dob' => 'admin',
            'number' => '0123456789',
            'email' => 'admin@gmail.com',
            'country' => 'Malaysia',
            'password' => Hash::make('WowsomeRohto'),
        ]);

        $user->assignRole('admin');
    }
}
