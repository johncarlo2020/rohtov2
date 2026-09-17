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
                $table->boolean('email_consent')->default(false);
                $table->boolean('sms_consent')->default(false);
                $table->string('otp')->nullable();
                $table->boolean('otp_verified')->default(false);
                $table->timestamp('last_login_at')->nullable();
                $table->timestamps();
            });
            Schema::create('countries', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('phone_code');
            });
            DB::table('countries')->insert(['name' => 'Malaysia', 'phone_code' => '+60']);
            $permissions = require database_path('migrations/2024_07_16_061509_create_permission_tables.php');
            $permissions->up();
            Role::create(['name' => 'client', 'guard_name' => 'web']);

            $this->post('/pre-reg', [
                'fname' => 'Test', 'lname' => 'User', 'email' => 'test@example.com',
                'dob' => '1990-01-01', 'country' => '+60123456789',
            ])->assertSessionHasNoErrors()->assertRedirect(RouteServiceProvider::HOME);

            $this->assertAuthenticated();
            $user = User::where('email', 'test@example.com')->firstOrFail();
            $this->assertNull($user->otp);
            $this->assertTrue($user->hasRole('client'));
            Http::assertNothingSent();
            foreach (['otp', 'verify.otp', 'resend.otp', 'verifyAdmin'] as $route) {
                $this->assertFalse(Route::has($route));
            }
        } finally {
            DB::purge('registration_test');
        }
    }
}
