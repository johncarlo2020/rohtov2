<?php

namespace Tests\Feature\Auth;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'client']);
    }

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'title' => 'Mr',
            'fname' => 'John',
            'lname' => 'Doe',
            'email' => 'registered_user@example.com',
            'preferred_contact' => 'Email',
            'privacy_policy' => '1',
            'communication_consent' => '1',
        ]);

        $this->assertAuthenticated();

        $user = \App\Models\User::where('email', 'registered_user@example.com')->first();
        $this->assertNotNull($user);

        $response->assertRedirect(route('otp', ['user' => $user->id]));
    }
}
