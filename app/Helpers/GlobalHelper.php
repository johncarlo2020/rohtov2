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
        // Retrieve the authenticated user's details
        $user = Auth::user();
        // return 'asdasd';

        // Prepare the postfield data
        $postfield = [
            'first_name' => $user->fname,
            'last_name' => $user->lname,
            // 'number' => $user->number,
            'number' => '0123456789',
            'email' => 'test@email.com',
            'source_id' => '4871', // Static value as in your example
        ];
        // return $postfield;

        // Determine subscription status
        $subscriptions = ['sms', 'email', 'call', 'whatsapp'];


        // Set up the mobile number based on the country code

        // $postfield['number'] = ltrim($postfield['number'], '+6');


        // Prepare API post data
        $postfield_api = [
            'hpno' => $postfield['number'],
            'firstname' => $postfield['first_name'],
            'lastname' => $postfield['last_name'],
            'email' => $postfield['email'],
            'subscription' => $subscriptions,
            'source_id' => $postfield['source_id'],
        ];
        //   dd($postfield_api);

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

        // Make the API request using Laravel's HTTP client
        $response = Http::withHeaders([
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'Content-Type' => 'text/plain', // match API requirement
        ])->withBody(
            json_encode($postfield_api), 'text/plain'
        )->post($apiUrl . 'endemande.createCustomerByEvent');
        // dd($response);

        // Handle the response
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

}
