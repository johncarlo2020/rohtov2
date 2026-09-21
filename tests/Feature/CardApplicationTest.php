<?php

namespace Tests\Feature;

use App\Http\Middleware\ClientMiddleware;
use App\Models\Station;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CardApplicationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config([
            'database.default' => 'card_test',
            'database.connections.card_test' => ['driver' => 'sqlite', 'database' => ':memory:', 'prefix' => ''],
            'card_application.qr_code' => 'test-booth-qr',
        ]);
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
        (require database_path('migrations/2026_09_21_000000_add_is_card_apply_to_users_table.php'))->up();
        Schema::create('stations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_mandatory');
            $table->timestamps();
        });
        $this->withoutMiddleware(ClientMiddleware::class);
    }

    protected function tearDown(): void
    {
        DB::purge('card_test');
        parent::tearDown();
    }

    public function test_booth_route_is_accepted_as_the_default_qr(): void
    {
        config(['card_application.qr_code' => null]);
        $this->withoutVite();
        $user = User::create([])->fresh();
        $url = route('card-application.booth');
        $this->get($url)->assertOk()->assertSee('Card Sales Booth');
        $this->assertFalse($user->fresh()->isCardApply);
        $this->actingAs($user)->postJson(route('card-application.scan'), ['qrCodeMessage' => $url])
            ->assertOk()->assertJson(['isCardApply' => true]);
        $this->assertTrue($user->fresh()->isCardApply);
    }

    public function test_guests_cannot_activate_card_access(): void
    {
        $this->postJson(route('card-application.scan'), ['qrCodeMessage' => 'test-booth-qr'])->assertUnauthorized();
    }

    public function test_only_the_configured_qr_activates_the_current_user(): void
    {
        $user = User::create([])->fresh();
        $other = User::create([])->fresh();
        $this->assertFalse($user->isCardApply);
        $this->actingAs($user)->postJson(route('card-application.scan'), ['qrCodeMessage' => 'invalid'])
            ->assertUnprocessable();
        $this->assertFalse($user->fresh()->isCardApply);
        foreach ([1, 2] as $attempt) {
            $this->postJson(route('card-application.scan'), ['qrCodeMessage' => 'test-booth-qr', 'user_id' => $other->id])
                ->assertOk()->assertJson(['isCardApply' => true]);
        }
        $this->assertTrue($user->fresh()->isCardApply);
        $this->assertFalse($other->fresh()->isCardApply);
    }

    public function test_unrecognized_and_empty_scans_do_not_activate(): void
    {
        $user = User::create([]);
        config(['card_application.qr_code' => null]);
        $this->actingAs($user)->postJson(route('card-application.scan'), ['qrCodeMessage' => 'test-booth-qr'])->assertUnprocessable();
        $this->postJson(route('card-application.scan'), ['qrCodeMessage' => ''])->assertUnprocessable();
        $this->assertFalse($user->fresh()->isCardApply);
    }

    public function test_optional_station_pages_and_check_ins_are_blocked_until_activation(): void
    {
        $user = User::create([])->fresh();
        $station = Station::create(['name' => 'Reward', 'is_mandatory' => false]);
        $this->actingAs($user);
        foreach (['station', 'station.extension', 'station.brand'] as $route) {
            $this->get(route($route, $station))->assertForbidden();
        }
        $this->postJson(route('process_qr_code'), ['station' => $station->id, 'qrCodeMessage' => 'station1'])->assertForbidden();
        $user->forceFill(['isCardApply' => true])->save();
        $this->withoutVite();
        $this->get(route('station.extension', $station))->assertOk();
        $user->forceFill(['isCardApply' => false])->save();
        $station->update(['is_mandatory' => true]);
        $this->get(route('station.extension', $station))->assertOk();
    }
}
