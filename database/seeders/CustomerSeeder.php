<?php

namespace Database\Seeders;

use App\Models\User; // Replace with your actual model, e.g. Customer if different
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 2000; $i++) {
           $user = User::create([
                'email_consent'     => $faker->boolean,
                'sms_consent'       => $faker->boolean,
                'utm_medium'        => $faker->word,
                'utm_source'        => $faker->domainWord,
                'type'              => 'pre-reg',
                'guess'             => $faker->word,
                'is_appointment'    => $faker->boolean,
                'lname'             => $faker->lastName,
                'email'             => $faker->unique()->safeEmail,
                'fname'             => $faker->firstName,
                'number'            => $faker->phoneNumber,
                'password'          => Hash::make('password'), // default password
                'last_login_at'     => $faker->dateTimeBetween('-1 year', 'now'),
                'dob'               => $faker->date('Y-m-d', '-18 years'),
                'country'           => $faker->country,
                'otp'               => rand(100000, 999999),
                'otp_verified'      => 1,
                'task_2_image'      => null,
                'task_3_image'      => null,
                'email_verified_at' => Carbon::now(),
            ]);
        $user->assignRole('client');

        }
    }
}
