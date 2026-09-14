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
    public static function sendOtpEmail($email, $otp, $name = null, $otpType = 'Verification')
    {
        $provider = config('services.mail_otp_provider', 'mailtrap');

        if ($provider === 'brevo') {
            return self::sendOtpViaBrevo($email, $otp, $name,$otpType);
        }

        return self::sendOtpViaMailtrap($email, $otp, $name,$otpType);
    }

    /**
     * Send OTP using Brevo API.
     */
    private static function sendOtpViaBrevo($email, $otp, $name = null, $otpType = 'Verification')
    {
        $htmlContent = self::otpEmailContent($otp, $name,$otpType);

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

            'subject' => "Your {$otpType} OTP",
            'htmlContent' => $htmlContent,
            'textContent' => "Your {$otpType} OTP is: {$otp}. This code will expire in 10 minutes.",
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
    private static function sendOtpViaMailtrap($email, $otp, $name = null, $otpType = 'Verification')
    {
        $html = self::otpEmailContent($otp, $name,$otpType);

        Mail::html($html, function ($message) use ($email, $name,$otpType) {
            $message
                ->to($email, $name ?? $email)
                ->subject("Your {$otpType} OTP");
        });

        return true;
    }

    /**
     * OTP email HTML content.
     */
    private static function otpEmailContent($otp, $name = null, $otpType = 'Verification')
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

    /**
     * Send booking confirmation or modification email.
     */
    // public static function sendBookingConfirmationEmail($booking, bool $isModification = false)
    // {
    //     $email = $booking->customer_email;
    //     if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    //         return false;
    //     }

    //     $customerName = e($booking->customer_name ?? 'Valued Guest');
    //     $dateFormatted = $booking->bookingDate ? strtoupper(\Carbon\Carbon::parse($booking->bookingDate->date)->format('jS F')) : 'N/A';
    //     $timeFormatted = $booking->bookingSlot ? strtoupper(\Carbon\Carbon::parse($booking->bookingSlot->start_time)->format('g:i A')) : 'N/A';
    //     $venue = 'LONGCHAMP POP UP STORE THE GARDENS MALL';

    //     // Retrieve user ID if available
    //     $user = \App\Models\User::where('email', $email)->first();
    //     $userId = $user ? $user->id : ($booking->id ?? 'GUEST');

    //     $qrRawData = "USER_ID:{$userId}|REF:{$booking->reference_no}";
    //     $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=" . urlencode($qrRawData);
    //     $modifyUrl = url('/reservation-create?modify=1');

    //     $actionText = $isModification ? 'UPDATED' : 'CONFIRMED';
    //     $subject = $isModification 
    //         ? 'Booking Modification – Longchamp x Caroline Hélain'
    //         : 'Booking Confirmation – Longchamp x Caroline Hélain';

    //     $htmlContent = view('emails.booking-confirmation', [
    //         'subject' => $subject,
    //         'customerName' => $customerName,
    //         'dateFormatted' => $dateFormatted,
    //         'timeFormatted' => $timeFormatted,
    //         'qrCodeUrl' => $qrCodeUrl,
    //         'modifyUrl' => $modifyUrl,
    //         'actionText' => $actionText,
    //     ])->render();

    //     try {
    //         if (config('services.mail_otp_provider', 'mailtrap') === 'brevo') {
    //             Http::withHeaders([
    //                 'api-key' => config('services.brevo.api_key'),
    //                 'accept' => 'application/json',
    //             ])->connectTimeout(5)->timeout(15)->post('https://api.brevo.com/v3/smtp/email', [
    //                 'sender' => [
    //                     'name' => config('services.brevo.from_name'),
    //                     'email' => config('services.brevo.from_email'),
    //                 ],
    //                 'to' => [['email' => $email, 'name' => $booking->customer_name ?? 'Valued Guest']],
    //                 'subject' => $subject,
    //                 'htmlContent' => $htmlContent,
    //                 'tags' => [$isModification ? 'booking-modification' : 'booking-confirmation'],
    //             ])->throw();

    //             return true;
    //         }

    //         Mail::html($htmlContent, function ($message) use ($email, $customerName, $subject) {
    //             $message->to($email, $customerName)
    //                     ->subject($subject);
    //         });
    //         return true;
    //     } catch (\Throwable $e) {
    //         \Log::error('Failed to send booking confirmation email: ' . $e->getMessage());
    //         return false;
    //     }
    // }

    public static function sendBookingConfirmationEmail($booking, bool $isModification = false)
    {
        $email = $booking->customer_email;

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        $customerName = e($booking->customer_name ?? 'Valued Guest');

        $dateFormatted = $booking->bookingDate
            ? strtoupper(\Carbon\Carbon::parse($booking->bookingDate->date)->format('jS F'))
            : 'N/A';

        $timeFormatted = $booking->bookingSlot
            ? strtoupper(\Carbon\Carbon::parse($booking->bookingSlot->start_time)->format('g:i A'))
            : 'N/A';

        $venue = 'LONGCHAMP POP UP STORE THE GARDENS MALL';

        // Retrieve user ID if available
        $user = \App\Models\User::where('email', $email)->first();
        $userId = $user ? $user->id : ($booking->id ?? 'GUEST');

        $qrRawData = "USER_ID:{$userId}|REF:{$booking->reference_no}";

        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data='
            . urlencode($qrRawData);

        $modifyUrl = url('/reservation-create?modify=1');

        $actionText = $isModification ? 'UPDATED' : 'CONFIRMED';

        $subject = $isModification
            ? 'Booking Modification – Longchamp x Caroline Hélain'
            : 'Booking Confirmation – Longchamp x Caroline Hélain';

        $htmlContent = view('emails.booking-confirmation', [
            'subject' => $subject,
            'customerName' => $customerName,
            'dateFormatted' => $dateFormatted,
            'timeFormatted' => $timeFormatted,
            'qrCodeUrl' => $qrCodeUrl,
            'modifyUrl' => $modifyUrl,
            'actionText' => $actionText,
        ])->render();

        $textContent = $isModification
            ? "Your Longchamp x Caroline Hélain booking has been updated. "
            . "Date: {$dateFormatted}. "
            . "Time: {$timeFormatted}. "
            . "Reference: {$booking->reference_no}. "
            . "Venue: {$venue}."
            : "Your Longchamp x Caroline Hélain booking is confirmed. "
            . "Date: {$dateFormatted}. "
            . "Time: {$timeFormatted}. "
            . "Reference: {$booking->reference_no}. "
            . "Venue: {$venue}.";

        try {
            $response = Http::withHeaders([
                'accept' => 'application/json',
                'api-key' => config('services.brevo.api_key'),
                'content-type' => 'application/json',
            ])
            ->connectTimeout(5)
            ->timeout(15)
            ->post('https://api.brevo.com/v3/smtp/email', [
                'sender' => [
                    'name' => config('services.brevo.from_name'),
                    'email' => config('services.brevo.from_email'),
                ],

                'to' => [
                    [
                        'email' => $email,
                        'name' => $booking->customer_name ?? 'Valued Guest',
                    ],
                ],

                'subject' => $subject,
                'htmlContent' => $htmlContent,
                'textContent' => $textContent,

                'tags' => [
                    $isModification
                        ? 'booking-modification'
                        : 'booking-confirmation',
                ],
            ]);

            if ($response->failed()) {
                throw new \Exception(
                    'Brevo failed to send booking email: ' . $response->body()
                );
            }

            return true;

        } catch (\Throwable $e) {
            \Log::error(
                'Failed to send booking confirmation email via Brevo: '
                . $e->getMessage(),
                [
                    'email' => $email,
                    'booking_id' => $booking->id ?? null,
                    'reference_no' => $booking->reference_no ?? null,
                ]
            );

            return false;
        }
    }

}
