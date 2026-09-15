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
        $response->assertSee('Title:');
        $response->assertSee('FIRST Name:');
        $response->assertSee('LAST Name:');
        $response->assertSee('E-mail address:');
        $response->assertSee('Phone number:');
        $response->assertSee('Consent');
        $response->assertSee('SUBSCRIBE TO THE LONGCHAMP E-NEWSLETTER*');
        $response->assertSee('COMMUNICATION CONSENT*');
    }

    public function test_new_users_can_register_with_legacy_payload(): void
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

    public function test_new_users_can_register_with_new_mockup_fields(): void
    {
        $response = $this->post('/register', [
            'title' => 'Mrs.',
            'fname' => 'Jane',
            'lname' => 'Smith',
            'email' => 'jane.smith@example.com',
            'number' => '0123456789',
            'consent_channels' => ['whatsapp', 'email'],
            'newsletter_consent' => '1',
            'communication_consent' => '1',
        ]);

        $this->assertAuthenticated();

        $user = \App\Models\User::where('email', 'jane.smith@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('Mrs.', $user->title);
        $this->assertEquals('Jane', $user->fname);
        $this->assertEquals('Smith', $user->lname);
        $this->assertEquals('+60123456789', $user->number);
        $this->assertTrue((bool)$user->newsletter_consent);
        $this->assertTrue((bool)$user->communication_consent);
        $this->assertTrue((bool)$user->marketing);
        $this->assertContains('whatsapp', $user->consent_channels);
        $this->assertContains('email', $user->consent_channels);

        $response->assertRedirect(route('otp', ['user' => $user->id]));
    }

    public function test_new_users_prefer_not_to_be_contacted(): void
    {
        $response = $this->post('/register', [
            'title' => 'Ms.',
            'fname' => 'Emily',
            'lname' => 'Brown',
            'email' => 'emily.brown@example.com',
            'number' => '+60198765432',
            'consent_channels' => ['none'],
            'newsletter_consent' => '0',
            'communication_consent' => '0',
        ]);

        $this->assertAuthenticated();

        $user = \App\Models\User::where('email', 'emily.brown@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('None', $user->preferred_contact);
        $this->assertFalse((bool)$user->newsletter_consent);
        $this->assertFalse((bool)$user->communication_consent);
        $this->assertFalse((bool)$user->marketing);
    }
}
