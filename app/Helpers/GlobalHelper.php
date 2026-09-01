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
    public static function sendOtpEmail($email, $otp, $name = null,$otpType)
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
    private static function sendOtpViaBrevo($email, $otp, $name = null,$otpType)
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
    private static function sendOtpViaMailtrap($email, $otp, $name = null,$otpType)
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
    private static function otpEmailContent($otp, $name = null,$otpType)
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
    public static function sendBookingConfirmationEmail($booking, bool $isModification = false)
    {
        $email = $booking->customer_email;
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        $customerName = e($booking->customer_name ?? 'Valued Guest');
        $dateFormatted = $booking->bookingDate ? strtoupper(\Carbon\Carbon::parse($booking->bookingDate->date)->format('jS F Y')) : 'N/A';
        $timeFormatted = $booking->bookingSlot ? strtoupper(\Carbon\Carbon::parse($booking->bookingSlot->start_time)->format('g:i A')) : 'N/A';
        $venue = 'LONGCHAMP POP UP STORE THE GARDENS MALL';

        // Retrieve user ID if available
        $user = \App\Models\User::where('email', $email)->first();
        $userId = $user ? $user->id : ($booking->id ?? 'GUEST');

        $qrRawData = "USER_ID:{$userId}|REF:{$booking->reference_no}";
        $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=" . urlencode($qrRawData);
        $modifyUrl = url('/reservation-create?modify=1');

        $actionText = $isModification ? 'UPDATED' : 'CONFIRMED';
        $subject = $isModification 
            ? 'Booking Modification – Longchamp x Caroline Hélain'
            : 'Booking Confirmation – Longchamp x Caroline Hélain';

        $htmlContent = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset=\"utf-8\">
            <style>
                body { font-family: 'Plus Jakarta Sans', Arial, sans-serif; background-color: #f8fafc; margin: 0; padding: 20px; color: #1e293b; }
                .email-card { max-width: 580px; margin: 0 auto; background: #ffffff; border: 4px solid #e86034; border-radius: 20px; padding: 30px; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
                .brand-title { font-family: Georgia, serif; font-style: italic; font-size: 22px; color: #0f172a; margin-bottom: 2px; }
                .brand-sub { font-size: 13px; font-weight: 800; letter-spacing: 2px; text-transform: uppercase; color: #0f172a; margin-bottom: 24px; }
                .status-title { text-transform: uppercase; color: #e86034; font-size: 22px; font-weight: 900; letter-spacing: 1.5px; margin-bottom: 12px; }
                .greeting { font-size: 14px; font-weight: 800; color: #1e293b; margin-bottom: 8px; text-transform: uppercase; }
                .body-text { font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; line-height: 1.6; max-width: 440px; margin: 0 auto 20px auto; }
                .details-box { background-color: #ffffff; border: 2px dashed #ef4444; border-radius: 16px; padding: 20px; display: inline-block; width: 85%; margin-bottom: 20px; }
                .detail-item { font-size: 11px; font-weight: 800; color: #0f172a; text-transform: uppercase; margin-bottom: 6px; letter-spacing: 1px; }
                .detail-label { color: #94a3b8; }
                .qr-img { width: 180px; height: 180px; margin: 12px auto; display: block; }
                .user-badge { font-size: 10px; font-weight: 800; color: #64748b; letter-spacing: 1px; margin-top: 6px; }
                .divider { border-top: 1px solid #e2e8f0; margin: 24px 0; }
                .modify-box { font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 15px; }
                .btn-modify { display: inline-block; background-color: #e86034; color: #ffffff !important; font-weight: 900; font-size: 13px; letter-spacing: 1.5px; text-transform: uppercase; text-decoration: none; padding: 12px 32px; border-radius: 8px; box-shadow: 0 4px 12px rgba(232, 96, 52, 0.25); }
                .footer-text { font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; line-height: 1.5; margin-top: 24px; }
            </style>
        </head>
        <body>
            <div class=\"email-card\">
                <div class=\"brand-title\">Caroline Hélain</div>
                <div style=\"font-size: 12px; color: #64748b; margin: 2px 0;\">x</div>
                <div class=\"brand-sub\">LONGCHAMP</div>

                <div class=\"status-title\">YOUR BOOKING IS {$actionText}!</div>

                <div class=\"greeting\">Dear {$customerName},</div>
                <div class=\"body-text\">
                    We are delighted to confirm your booking for the Longchamp x Caroline Hélain event.<br>
                    Please find your booking details below for your reference.
                </div>

                <div class=\"details-box\">
                    <img class=\"qr-img\" src=\"{$qrCodeUrl}\" alt=\"Booking QR Code\">

                    <div style=\"font-size: 13px; font-weight: 900; color: #0f172a; text-transform: uppercase; margin-bottom: 10px;\">
                        {$customerName}
                    </div>

                    <div class=\"detail-item\"><span class=\"detail-label\">DATE:</span> {$dateFormatted}</div>
                    <div class=\"detail-item\"><span class=\"detail-label\">TIME:</span> {$timeFormatted}</div>
                    <div class=\"detail-item\" style=\"margin-top: 8px;\">
                        <span class=\"detail-label\">VENUE:</span> {$venue}
                    </div>
                    <div class=\"user-badge\">USER ID: {$userId} | REF: {$booking->reference_no}</div>
                </div>

                <div style=\"font-size: 11px; font-weight: 800; color: #475569; text-transform: uppercase; margin-bottom: 15px;\">
                    Please present the QR code above upon arrival for registration.
                </div>

                <div class=\"divider\"></div>

                <div class=\"modify-box\">
                    If you need to update your booking details, please use the button below:<br>
                    <div style=\"margin-top: 10px;\">
                        <a href=\"{$modifyUrl}\" class=\"btn-modify\">MODIFY</a>
                    </div>
                </div>

                <div class=\"divider\"></div>

                <div class=\"footer-text\">
                    Should you have any questions or require further assistance, please don't hesitate to contact us.<br>
                    We look forward to welcoming you to the Longchamp x Caroline Hélain event.
                </div>
            </div>
        </body>
        </html>
        ";

        try {
            Mail::html($htmlContent, function ($message) use ($email, $customerName, $subject) {
                $message->to($email, $customerName)
                        ->subject($subject);
            });
            return true;
        } catch (\Throwable $e) {
            \Log::error('Failed to send booking confirmation email: ' . $e->getMessage());
            return false;
        }
    }
}
