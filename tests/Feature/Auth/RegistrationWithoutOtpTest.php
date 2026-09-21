<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RegistrationWithoutOtpTest extends TestCase
{
    public function test_registration_logs_in_without_generating_or_sending_an_otp(): void
    {
        config([
            'database.default' => 'registration_test',
            'database.connections.registration_test' => [
                'driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '',
            ],
        ]);
        Http::fake();

        try {
            $migration = require database_path('migrations/2014_10_12_000000_create_users_table.php');
            $migration->up();
            $this->assertFalse(Schema::hasColumn('users', 'otp'));
            $this->assertFalse(Schema::hasColumn('users', 'otp_verified'));
            $permissions = require database_path('migrations/2024_07_16_061509_create_permission_tables.php');
            $permissions->up();
            $this->assertSame(0, Role::count());

            $this->post('/register', [
                'full_name' => 'Test User', 'email' => 'test@example.com',
                'number' => '012-345 6789', 'terms' => '1', 'age_confirmed' => '1',
            ])->assertSessionHasNoErrors()->assertRedirect(route('map'));

            $this->assertSame(1, Role::where('name', 'client')->where('guard_name', 'web')->count());
            $this->assertAuthenticated();
            $user = User::where('email', 'test@example.com')->firstOrFail();
            $this->assertTrue($user->terms);
            $this->assertTrue($user->age_confirmed);
            $this->assertFalse($user->marketing);
            $this->assertTrue($user->hasRole('client'));
            $this->assertSame('Test', $user->fname);
            $this->assertSame('User', $user->lname);
            $this->assertSame('+60123456789', $user->number);
            $this->assertFalse($user->email_consent);
            $this->assertFalse($user->sms_consent);

            $this->post('/register', [
                'full_name' => 'Another User', 'email' => 'another@example.com',
                'number' => '+60198765432',
            ])->assertSessionHasErrors(['terms', 'age_confirmed']);
            $this->assertSame(1, User::count());

            $this->post('/register', [
                'full_name' => 'SingleName', 'email' => 'optin@example.com',
                'number' => '+60 19-876 5432', 'terms' => '1', 'age_confirmed' => '1', 'marketing' => '1',
            ])->assertRedirect(route('map'));
            $optedIn = User::where('email', 'optin@example.com')->firstOrFail();
            $this->assertSame('', $optedIn->lname);
            $this->assertTrue($optedIn->email_consent);
            $this->assertTrue($optedIn->sms_consent);
            $this->assertTrue($optedIn->marketing);
            $this->assertSame(1, Role::where('name', 'client')->where('guard_name', 'web')->count());
            Http::assertNothingSent();
            foreach (['otp', 'verify.otp', 'resend.otp', 'verifyAdmin'] as $route) {
                $this->assertFalse(Route::has($route));
            }
            $migration->down();
            $this->assertFalse(Schema::hasTable('users'));
            $this->assertFalse(Schema::hasColumn('users', 'terms'));
        } finally {
            DB::purge('registration_test');
        }
    }
}
