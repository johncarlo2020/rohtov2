<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class GlobalHelper
{
    public static function sendOtpSms($phoneNumber, $otp)
    {

        // Compose message
        $content = "L'OCCITANE: OTP code: $otp. NEVER share this code with others.";

        // Build query parameters
        $query = [
            'user'   => 'davino',
            'pass'   => 'Wowsome@820####!',
            'type'   => 0,
            'to'     => $phoneNumber,
            'from'   => 'Loccitane',
            'text'   => $content,
            'servid' => 'MES01',
            'title'  => 'EnDemande_MY_OceanOrPlastic2025',
        ];

        // Send GET request using Laravel HTTP Client
        $response = Http::acceptJson()->get('https://www.etracker.cc/bulksms/mesapi.aspx', $query);

        return $response->body(); // Or ->json() if needed
    }

    public static function createSampleProfile()
    {

        $user = Auth::user();
        $postfield = [
            'first_name' => $user->fname,
            'last_name' => $user->lname,
            'number' => $user->number,
            // 'number' => '0123456789',
            'email' => $user->email,
            'source_id' => '4878', // Static value as in your example
        ];
        $subscriptions = ['sms', 'email', 'call', 'whatsapp'];

        $postfield_api = [
            'hpno' => $postfield['number'],
            'firstname' => $postfield['first_name'],
            'lastname' => $postfield['last_name'],
            'email' => $postfield['email'],
            'subscription' => $subscriptions,
            'source_id' => $postfield['source_id'],
        ];

        // Determine API URL and client credentials based on mode
        // if ($this->mode == 'test') {
            $apiUrl = 'https://loccitanemy-uat.crmxs.com/?xs_app=';
            $clientId = '5vus5fnhdeeghff5de8c2nrq46fhy8nh';
            $clientSecret = 'sum388my7amm8jp7k3ru5pyb4hp8g87g';
        // } elseif ($this->mode == 'prod') {
            // $apiUrl = 'https://loccitanemy.crmxs.com/?xs_app=';
        //     $clientId = '5vus5fnhdeeghff5de8c2nrq46fhy8nh';
        //     $clientSecret = 'sum388my7amm8jp7k3ru5pyb4hp8g87g';
        // }

        $response = Http::withHeaders([
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'Content-Type' => 'text/plain', // match API requirement
        ])->withBody(
            json_encode($postfield_api), 'text/plain'
        )->post($apiUrl . 'endemande.createCustomerByEvent');

        if ($response->successful()) {
            return response()->json([
                'message' => 'User profile sent successfully to CPRV.',
                'response' => $response->json()
            ]);
        }

        return response()->json([
            'message' => 'Failed to send user profile to CPRV.',
            'error' => $response->body()
        ], $response->status());
    }

    public static function checkOrRegisterUser(array $userData)
    {
        // Step 1: Clean the mobile number (Remove the '+' for checking)
        $fullMobile = ltrim($userData['mobile'], '+'); // Remove the '+' from the mobile number

        // Step 2: Check if the user exists
        $checkResponse = Http::withHeaders([
            'X-Api-Secret-Id' => '2p3wmae30p5blyl9z1btw',
            'X-Api-Secret-Key' => 'vijld48mnyex0w5tocpz8',
        ])->get('https://blt.experienceloccitane.com/api/user/check.php', [
            'mobile' => $fullMobile, // Check with cleaned mobile number (without '+')
        ]);

        if ($checkResponse->failed()) {
            return ['status' => 'error', 'message' => 'Check request failed.'];
        }

        $exists = $checkResponse->json('data.exists');

        if ($exists === true) {
            return ['status' => 'exists', 'message' => 'User already exists.'];
        }

        // Step 3: Clean mobile for registration (remove '60' prefix and add '0' at the beginning)
        $mobileForRegistration = ltrim($fullMobile, '60'); // Remove the '60' country code prefix
        $mobileForRegistration = '0' . $mobileForRegistration; // Add leading '0' for registration

        // Step 4: Register the user
        $registerResponse = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-Api-Secret-Id' => '2p3wmae30p5blyl9z1btw',
            'X-Api-Secret-Key' => 'vijld48mnyex0w5tocpz8',
        ])->post('https://blt.experienceloccitane.com/api/user/create.php', [
            'mobile' => $mobileForRegistration,  // Pass number as 0123451719
            'country_code' => '60',              // Provide the country code separately
            'first_name' => $userData['first_name'],
            'last_name' => $userData['last_name'],
            'email' => $userData['email'],
            'subscription' => $userData['subscription'],
            'password' => $userData['password'],
        ]);

        if ($registerResponse->successful()) {
            return [
                'status' => 'registered',
                'message' => 'User created successfully.',
                'data' => $registerResponse->json('data')
            ];
        }

        return ['status' => 'error', 'message' => 'User creation failed.'];
    }

}
