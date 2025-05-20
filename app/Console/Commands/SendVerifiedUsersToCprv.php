<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class SendVerifiedUsersToCprv extends Command
{
    protected $signature = 'cprv:send-verified-users';
    protected $description = 'Send verified users to CPRV';

    public function handle()
    {
        $users = User::whereNotNull('email_verified_at')->get();

        $apiUrl = 'https://loccitanemy.crmxs.com/?xs_app=';
        $clientId = '5vus5fnhdeeghff5de8c2nrq46fhy8nh';
        $clientSecret = 'sum388my7amm8jp7k3ru5pyb4hp8g87g';

        foreach ($users as $user) {
            $postfield_api = [
                'hpno' => $user->number,
                'firstname' => $user->fname,
                'lastname' => $user->lname,
                'email' => $user->email,
                'subscription' => ['sms', 'email'],
                'source_id' => '7122',
            ];

            $response = Http::withHeaders([
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'Content-Type' => 'text/plain',
            ])->withBody(
                json_encode($postfield_api), 'text/plain'
            )->post($apiUrl . 'endemande.createCustomerByEvent');

            if ($response->successful()) {
                $this->info("User {$user->email} sent successfully.");
            } else {
                $this->error("Failed to send user {$user->email}: " . $response->body());
            }
        }

        return Command::SUCCESS;
    }
}
