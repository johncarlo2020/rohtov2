<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
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
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                foreach (['fname', 'lname', 'email', 'number', 'country', 'dob', 'password'] as $column) {
                    $table->string($column);
                }
                $table->boolean('marketing')->default(false);
                $table->boolean('email_consent')->default(false);
                $table->boolean('sms_consent')->default(false);
                $table->string('otp')->nullable();
                $table->boolean('otp_verified')->default(false);
                $table->timestamp('last_login_at')->nullable();
                $table->timestamps();
            });
            $migration = require database_path('migrations/2026_09_17_000001_update_registration_fields_on_users_table.php');
            $migration->up();
            $migration->up();
            $this->assertFalse(Schema::hasColumn('users', 'otp'));
            $this->assertFalse(Schema::hasColumn('users', 'otp_verified'));
            $permissions = require database_path('migrations/2024_07_16_061509_create_permission_tables.php');
            $permissions->up();
            Role::create(['name' => 'client', 'guard_name' => 'web']);

            $this->post('/pre-reg', [
                'full_name' => 'Test User', 'email' => 'test@example.com',
                'number' => '012-345 6789', 'terms' => '1', 'age_confirmed' => '1',
            ])->assertSessionHasNoErrors()->assertRedirect(RouteServiceProvider::HOME);

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

            $this->post('/pre-reg', [
                'full_name' => 'Another User', 'email' => 'another@example.com',
                'number' => '+60198765432',
            ])->assertSessionHasErrors(['terms', 'age_confirmed']);
            $this->assertSame(1, User::count());

            $this->post('/pre-reg', [
                'full_name' => 'SingleName', 'email' => 'optin@example.com',
                'number' => '+60 19-876 5432', 'terms' => '1', 'age_confirmed' => '1', 'marketing' => '1',
            ])->assertRedirect(RouteServiceProvider::HOME);
            $optedIn = User::where('email', 'optin@example.com')->firstOrFail();
            $this->assertSame('', $optedIn->lname);
            $this->assertTrue($optedIn->email_consent);
            $this->assertTrue($optedIn->sms_consent);
            $this->assertTrue($optedIn->marketing);
            Http::assertNothingSent();
            foreach (['otp', 'verify.otp', 'resend.otp', 'verifyAdmin'] as $route) {
                $this->assertFalse(Route::has($route));
            }
            $migration->down();
            $this->assertTrue(Schema::hasColumn('users', 'otp'));
            $this->assertFalse(Schema::hasColumn('users', 'terms'));
        } finally {
            DB::purge('registration_test');
        }
    }
}
