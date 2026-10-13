<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\BookingDate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RsvpBookingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['services.mail_otp_provider' => 'brevo']);
        \Illuminate\Support\Facades\Http::fake(['api.brevo.com/*' => \Illuminate\Support\Facades\Http::response(['messageId' => 'test'], 201)]);
    }

    public function test_confirmation_saves_booking_and_repeat_submission_reuses_it(): void
    {
        $this->withoutMiddleware();
        $user = User::factory()->create();
        $date = BookingDate::create(['date' => now()->addWeek()->toDateString(), 'capacity' => 18, 'is_available' => true]);
        $response = $this->actingAs($user)->post(route('rsvp.store'), ['date' => $date->date->toDateString()]);
        $booking = Booking::sole();
        $this->assertSame($user->email, $booking->customer_email);
        $this->assertSame('confirmed', $booking->status);
        $this->assertSame('1278 Songwat Road, Bangkok', $booking->venue);
        $response->assertRedirect(route('rsvp.confirmed', $booking->reference_no));
        $this->post(route('rsvp.store'), ['date' => $date->date->toDateString()])->assertRedirect($response->headers->get('Location'));
        $this->assertSame(1, Booking::count());
        \Illuminate\Support\Facades\Http::assertSentCount(1);
        \Illuminate\Support\Facades\Http::assertSent(fn ($request) =>
            $request['to'][0]['email'] === $user->email
            && str_contains($request['htmlContent'], 'YOUR VISIT IS CONFIRMED')
            && str_contains($request['htmlContent'], $booking->reference_no)
            && str_contains($request['htmlContent'], '1278 Songwat Road, Bangkok')
        );
        $this->get(route('dashboard'))->assertRedirect(route('rsvp.confirmed', $booking->reference_no));
        $this->get(route('rsvp.confirmed', $booking->reference_no))->assertOk()->assertSee('YOUR VISIT IS CONFIRMED');
        $this->actingAs(User::factory()->create())->get(route('rsvp.confirmed', $booking->reference_no))->assertNotFound();
    }

    public function test_full_date_cannot_be_booked(): void
    {
        $this->withoutMiddleware();
        $date = BookingDate::create(['date' => now()->addWeek()->toDateString(), 'capacity' => 0, 'is_available' => true]);
        $this->actingAs(User::factory()->create())->post(route('rsvp.store'), ['date' => $date->date->toDateString()])->assertSessionHasErrors('date');
        $this->assertSame(0, Booking::count());
    }

    public function test_cancelled_booking_does_not_redirect_to_confirmation(): void
    {
        $this->withoutMiddleware();
        // ShareErrorsFromSession is disabled with the other middleware in this test.
        view()->share('errors', new \Illuminate\Support\ViewErrorBag());
        $user = User::factory()->create();
        $date = BookingDate::create(['date' => now()->addWeek()->toDateString(), 'capacity' => 18, 'is_available' => true]);
        $this->actingAs($user)->post(route('rsvp.store'), ['date' => $date->date->toDateString()]);
        Booking::sole()->update(['status' => 'cancelled']);

        $this->get(route('dashboard'))->assertOk()->assertViewIs('rsvpDashboard');
    }
}
