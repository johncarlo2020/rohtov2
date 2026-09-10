<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'client']);
        $user = User::factory()->create([
            'email' => 'registered@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);
        $user->assignRole($role);

        // 1. Submit email/password via login page form
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('otp', ['user' => $user->id]));

        $user->refresh();
        $this->assertNotNull($user->otp);

        // 2. Submit OTP verification code
        $otpResponse = $this->actingAs($user)->post('/verify-otp', [
            'otp' => str_split((string)$user->otp),
        ]);

        $user->refresh();
        $this->assertEquals(1, $user->otp_verified);

        // 3. User with active booking accesses dashboard
        $bookingDate = \App\Models\BookingDate::create(['date' => '2026-10-06', 'is_available' => true]);
        $bookingSlot = \App\Models\BookingSlot::create([
            'booking_date_id' => $bookingDate->id,
            'start_time' => '14:00:00',
            'end_time' => '15:00:00',
            'capacity' => 1,
            'booked_count' => 1,
            'is_available' => true,
        ]);
        \App\Models\Booking::create([
            'booking_date_id' => $bookingDate->id,
            'booking_slot_id' => $bookingSlot->id,
            'reference_no' => 'BK-20261006-0001',
            'customer_name' => $user->name ?? 'Registered User',
            'customer_email' => $user->email,
            'customer_phone' => '09111111111',
            'status' => 'confirmed',
        ]);

        $dashboardResponse = $this->actingAs($user)->get('/dashboard');
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('BOOKING CONFIRMED!');
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
