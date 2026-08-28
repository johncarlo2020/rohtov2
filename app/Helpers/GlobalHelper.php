<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class GlobalHelper
{
    public static function sendOtpSms($phoneNumber, $otp)
    {

        // Compose message
        $content = "Save the blue: OTP code: $otp. NEVER share this code with others.";

        // Build query parameters
        $query = [
            'user'   => 'davino',
            'pass'   => 'Wowsome@820####!',
            'type'   => 0,
            'to'     => $phoneNumber,
            'from'   => 'Sekkiseibykose',
            'text'   => $content,
            'servid' => 'MES01',
            'title'  => 'EnDemande_MY_OceanOrPlastic2025',
        ];

        // Send GET request using Laravel HTTP Client
        $response = Http::acceptJson()->get('https://www.etracker.cc/bulksms/mesapi.aspx', $query);

        return $response->body(); // Or ->json() if needed
    }

    // public static function sendOtpEmail($email, $otp, $name = null)
    // {
    //     $content = "
    //         <html>
    //             <body>
    //                 <h2>Email Verification</h2>

    //                 <p>Hello " . e($name ?? 'there') . ",</p>

    //                 <p>Your verification code is:</p>

    //                 <h1 style=\"letter-spacing: 5px;\">{$otp}</h1>

    //                 <p>This OTP will expire in 10 minutes.</p>

    //                 <p>
    //                     If you did not request this code,
    //                     please ignore this email.
    //                 </p>
    //             </body>
    //         </html>
    //     ";

    //     $response = Http::withHeaders([
    //         'accept' => 'application/json',
    //         'api-key' => config('services.brevo.api_key'),
    //         'content-type' => 'application/json',
    //     ])->post('https://api.brevo.com/v3/smtp/email', [
    //         'sender' => [
    //             'name' => config('services.brevo.from_name'),
    //             'email' => config('services.brevo.from_email'),
    //         ],

    //         'to' => [
    //             [
    //                 'email' => $email,
    //                 'name' => $name ?? $email,
    //             ],
    //         ],

    //         'subject' => 'Your Registration OTP',

    //         'htmlContent' => $content,

    //         'textContent' => "Your registration OTP is: {$otp}. This code will expire in 10 minutes.",

    //         'tags' => [
    //             'registration-otp',
    //         ],
    //     ]);

    //     if ($response->failed()) {
    //         throw new \Exception(
    //             'Failed to send OTP email: ' . $response->body()
    //         );
    //     }

    //     return $response->json();
    // }


    /**
     * Send OTP via configured email provider.
     *
     * Providers:
     * - brevo
     * - mailtrap
     */
    public static function sendOtpEmail($email, $otp, $name = null)
    {
        $provider = config('services.mail_otp_provider', 'mailtrap');

        if ($provider === 'brevo') {
            return self::sendOtpViaBrevo($email, $otp, $name);
        }

        return self::sendOtpViaMailtrap($email, $otp, $name);
    }

    /**
     * Send OTP using Brevo API.
     */
    private static function sendOtpViaBrevo($email, $otp, $name = null)
    {
        $htmlContent = self::otpEmailContent($otp, $name);

        $response = Http::withHeaders([
            'accept' => 'application/json',
            'api-key' => config('services.brevo.api_key'),
            'content-type' => 'application/json',
        ])->post('https://api.brevo.com/v3/smtp/email', [
            'sender' => [
                'name' => config('services.brevo.from_name'),
                'email' => config('services.brevo.from_email'),
            ],

            'to' => [
                [
                    'email' => $email,
                    'name' => $name ?? $email,
                ],
            ],

            'subject' => 'Your Registration OTP',
            'htmlContent' => $htmlContent,
            'textContent' => "Your registration OTP is: {$otp}. This code will expire in 10 minutes.",
            'tags' => [
                'registration-otp',
            ],
        ]);

        if ($response->failed()) {
            throw new \Exception(
                'Brevo failed to send OTP email: ' . $response->body()
            );
        }

        return $response->json();
    }

    /**
     * Send OTP using Mailtrap SMTP.
     */
    private static function sendOtpViaMailtrap($email, $otp, $name = null)
    {
        $html = self::otpEmailContent($otp, $name);

        Mail::html($html, function ($message) use ($email, $name) {
            $message
                ->to($email, $name ?? $email)
                ->subject('Your Registration OTP');
        });

        return true;
    }

    /**
     * OTP email HTML content.
     */
    private static function otpEmailContent($otp, $name = null)
    {
        $name = e($name ?? 'there');

        return "
            <html>
                <body>
                    <h2>Email Verification</h2>
                    <p>Hello {$name},</p>
                    <p>Your verification code is:</p>
                    <h1 style=\"letter-spacing: 5px;\">{$otp}</h1>
                    <p>This OTP will expire in 10 minutes.</p>
                    <p>
                        If you did not request this code,
                        please ignore this email.
                    </p>
                </body>
            </html>
        ";
    }


}
