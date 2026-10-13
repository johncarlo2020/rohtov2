<?php

namespace App\Services;

use App\Models\Booking;
use App\Http\Controllers\RsvpDashboardController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class RsvpConfirmationEmailService
{
    public function send(Booking $booking): void
    {
        $booking->loadMissing('bookingDate');
        $html = view('emails.rsvp-confirmation', [
            'booking' => $booking,
            'time' => RsvpDashboardController::TIME,
            'qrCodeUrl' => 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=' . rawurlencode($booking->reference_no),
        ])->render();

        if (config('services.mail_otp_provider', 'mailtrap') === 'brevo') {
            Http::withHeaders(['api-key' => config('services.brevo.api_key')])
                ->acceptJson()->connectTimeout(5)->timeout(15)
                ->post('https://api.brevo.com/v3/smtp/email', [
                    'sender' => ['name' => config('services.brevo.from_name'), 'email' => config('services.brevo.from_email')],
                    'to' => [['email' => $booking->customer_email, 'name' => $booking->customer_name]],
                    'subject' => 'Masterclass-Workshop',
                    'htmlContent' => $html,
                    'tags' => ['rsvp-confirmation'],
                ])->throw();
        } else {
            Mail::html($html, function ($message) use ($booking) {
                $message->to($booking->customer_email, $booking->customer_name)->subject('Masterclass-Workshop');
            });
        }
    }
}
