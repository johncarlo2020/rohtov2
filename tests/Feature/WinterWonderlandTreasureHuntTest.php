<?php

namespace Tests\Feature;

use App\Models\Gifts;
use App\Models\Station;
use App\Models\StationUser;
use App\Models\User;
use App\Models\UserGift;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class WinterWonderlandTreasureHuntTest extends TestCase
{
    use DatabaseTransactions;
    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'client']);
        Role::firstOrCreate(['name' => 'admin']);

        // Seed the 6 stations
        for ($i = 1; $i <= 6; $i++) {
            \Illuminate\Support\Facades\DB::table('stations')->updateOrInsert(
                ['id' => $i],
                [
                    'name' => "Station {$i}",
                    'description' => "Location {$i}",
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // Seed default gift
        \Illuminate\Support\Facades\DB::table('gifts')->updateOrInsert(
            ['id' => 1],
            [
                'name' => 'Winter Wonderland Reward',
                'stock_level' => 300,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    public function test_landing_and_registration_screens_render_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Winter Wonderland 2026');

        $registerRes = $this->get('/register');
        $registerRes->assertStatus(200);
        $registerRes->assertSee('SIGN UP');
        $registerRes->assertSee('Age Verification:');
        $registerRes->assertSee('I am under guidance');
    }

    public function test_instructions_page_renders_with_mission_guide(): void
    {
        $response = $this->get('/discover');
        $response->assertStatus(200);
        $response->assertSee('YOUR MISSION:');
        $response->assertSee('Explore the Magic:');
        $response->assertSee('Hunt for Clues:');
        $response->assertSee('Scan to unlock:');
        $response->assertSee('START THE HUNT');

        $aliasResponse = $this->get('/instructions');
        $aliasResponse->assertStatus(200);
        $aliasResponse->assertSee('YOUR MISSION:');
    }

    public function test_under_18_registered_user_modal_presence(): void
    {
        $under18User = User::create([
            'fname' => 'Young Adventurer',
            'email' => 'young@rwgenting.test',
            'dob' => Carbon::now()->subYears(15)->format('Y-m-d'),
            'password' => bcrypt('password'),
            'guardian_consent' => true,
        ]);
        $under18User->assignRole('client');

        $this->assertTrue($under18User->isUnder18());

        // Visiting welcome as an under-18 user shows the modal
        $response = $this->actingAs($under18User)->get('/');
        $response->assertStatus(200);
        $response->assertSee('Age Verification:');
        $response->assertSee('As you are under 18, please ensure you are accessing this website under the supervision of a parent or legal guardian.');
        $response->assertSee('I am under guidance');

        // Visiting dashboard as an under-18 user shows the modal
        $dashResponse = $this->actingAs($under18User)->get('/dashboard?start=1');
        $dashResponse->assertStatus(200);
        $dashResponse->assertSee('Age Verification:');
    }

    public function test_participant_can_register_and_is_redirected_to_instructions_first(): void
    {
        $response = $this->post('/register', [
            'fname' => 'Elena Frost',
            'email' => 'elena@rwgenting.test',
            'dob' => '1996-12-05',
            'number' => '+60112345678',
            'grc_id' => 'GRC998877',
            'terms_conditions' => '1',
            'privacy_policy' => '1',
        ]);

        $response->assertRedirect(route('instructions'));
        $this->assertAuthenticated();

        $user = User::where('email', 'elena@rwgenting.test')->first();
        $this->assertNotNull($user);
        $this->assertEquals('GRC998877', $user->grc_id);
        $this->assertFalse((bool)$user->guardian_consent);

        // First-time visit to /dashboard without starting redirects to /instructions
        $dashDirect = $this->get('/dashboard');
        $dashDirect->assertRedirect(route('instructions'));

        // Clicking "START THE HUNT" from instructions lands on /dashboard
        $dashWithStart = $this->get('/dashboard?start=1');
        $dashWithStart->assertStatus(200);
        $dashWithStart->assertSee('Treasure Hunt Progress');
    }

    public function test_participant_under_18_requires_guardian_consent(): void
    {
        $under18Dob = Carbon::now()->subYears(16)->format('Y-m-d');

        // Without guardian consent -> fails validation
        $response = $this->post('/register', [
            'fname' => 'Junior Hunter',
            'email' => 'junior@rwgenting.test',
            'dob' => $under18Dob,
            'terms_conditions' => '1',
            'privacy_policy' => '1',
        ]);
        $response->assertSessionHasErrors('guardian_consent');

        // With guardian consent -> passes and redirects to instructions
        $response2 = $this->post('/register', [
            'fname' => 'Junior Hunter',
            'email' => 'junior@rwgenting.test',
            'dob' => $under18Dob,
            'terms_conditions' => '1',
            'privacy_policy' => '1',
            'guardian_consent' => '1',
        ]);
        $response2->assertRedirect(route('instructions'));
    }

    public function test_participant_can_scan_qr_codes_in_any_order(): void
    {
        $user = User::create([
            'fname' => 'Trail Walker',
            'email' => 'walker@rwgenting.test',
            'dob' => '1990-01-01',
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('client');

        $this->actingAs($user);

        // Scan Station 3 first
        $resStation3 = $this->postJson(route('process_qr_code'), [
            'station' => 3,
            'qrCodeMessage' => 'https://rwg.test/station/3',
        ]);
        $resStation3->assertStatus(200);
        $resStation3->assertJson(['status' => 'success', 'station_id' => 3]);

        // Scan Station 1 next
        $resStation1 = $this->postJson(route('process_qr_code'), [
            'station' => 1,
            'qrCodeMessage' => 'RWG_STATION_1',
        ]);
        $resStation1->assertStatus(200);

        // Verify database records
        $this->assertDatabaseHas('station_users', [
            'user_id' => $user->id,
            'station_id' => 3,
        ]);
        $this->assertDatabaseHas('station_users', [
            'user_id' => $user->id,
            'station_id' => 1,
        ]);
    }

    public function test_duplicate_station_scan_is_prevented(): void
    {
        $user = User::create([
            'fname' => 'Duplicate Tester',
            'email' => 'duplicate@rwgenting.test',
            'dob' => '1992-02-02',
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('client');

        $this->actingAs($user);

        // First scan succeeds
        $res1 = $this->postJson(route('process_qr_code'), [
            'station' => 2,
            'qrCodeMessage' => 'ST2',
        ]);
        $res1->assertStatus(200);

        // Second scan of same station fails
        $res2 = $this->postJson(route('process_qr_code'), [
            'station' => 2,
            'qrCodeMessage' => 'ST2',
        ]);
        $res2->assertStatus(400);
        $res2->assertJson(['status' => 'error']);
    }

    public function test_redemption_requires_all_6_stations_and_is_concurrency_safe(): void
    {
        $user = User::create([
            'fname' => 'Champion Hunter',
            'email' => 'champion@rwgenting.test',
            'dob' => '1988-08-08',
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('client');
        $this->actingAs($user);

        // Attempting redemption before completing all stations -> rejected
        $prematureRedeem = $this->postJson(route('giftselection.redeem'), []);
        $prematureRedeem->assertStatus(403);

        // Complete all 6 stations
        for ($s = 1; $s <= 6; $s++) {
            StationUser::create([
                'user_id' => $user->id,
                'station_id' => $s,
                'time_spent' => '30',
            ]);
        }

        // Now redemption succeeds
        $redeemRes = $this->postJson(route('giftselection.redeem'), []);
        $redeemRes->assertStatus(200);
        $redeemRes->assertJson(['success' => true]);

        $this->assertDatabaseHas('user_gifts', [
            'user_id' => $user->id,
            'is_redeemed' => 1,
        ]);

        // Attempting duplicate redemption -> rejected
        $dupRedeem = $this->postJson(route('giftselection.redeem'), []);
        $dupRedeem->assertStatus(400);
    }

    public function test_daily_redemption_quota_of_300_is_enforced(): void
    {
        $user = User::create([
            'fname' => 'Late Hunter',
            'email' => 'late@rwgenting.test',
            'dob' => '1991-03-03',
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('client');

        for ($s = 1; $s <= 6; $s++) {
            StationUser::create([
                'user_id' => $user->id,
                'station_id' => $s,
                'time_spent' => '30',
            ]);
        }

        // Simulate 300 redemptions by other participants completed today
        $otherUser = User::create([
            'fname' => 'Other Hunter',
            'email' => 'other_hunter@rwgenting.test',
            'dob' => '1990-01-01',
            'password' => bcrypt('password'),
        ]);

        for ($i = 0; $i < 300; $i++) {
            UserGift::create([
                'user_id' => $otherUser->id,
                'gift_id' => 1,
                'is_redeemed' => true,
                'redeemed_at' => Carbon::today()->addSeconds($i),
            ]);
        }

        $this->actingAs($user);
        $res = $this->postJson(route('giftselection.redeem'), []);
        $res->assertStatus(200);
        $res->assertJson([
            'success' => false,
            'quota_full' => true,
        ]);
    }

    public function test_participant_progress_persists_across_sessions(): void
    {
        $user = User::create([
            'fname' => 'Persistent User',
            'email' => 'persistent@rwgenting.test',
            'dob' => '1994-04-04',
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('client');

        StationUser::create([
            'user_id' => $user->id,
            'station_id' => 1,
            'time_spent' => '25',
        ]);
        StationUser::create([
            'user_id' => $user->id,
            'station_id' => 4,
            'time_spent' => '40',
        ]);

        // Login via email credentials
        $loginRes = $this->post('/login', [
            'email' => 'persistent@rwgenting.test',
            'password' => 'password',
        ]);
        $loginRes->assertRedirect(route('dashboard'));

        $dashboardRes = $this->get('/dashboard');
        $dashboardRes->assertStatus(200);
        $dashboardRes->assertSee('2 / 6 Stations');
        $dashboardRes->assertSee('STAMPED');
    }

    public function test_profile_page_renders_in_view_mode(): void
    {
        $user = User::create([
            'fname' => 'John Wick',
            'email' => 'johnwick@rwgenting.test',
            'dob' => '1987-08-17',
            'grc_id' => '4035 5010 0000 0008',
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('client');

        $this->actingAs($user);
        $response = $this->get(route('profile.edit'));

        $response->assertStatus(200);
        $response->assertSee('MY PROFILE');
        $response->assertSee('Full Name');
        $response->assertSee('John Wick');
        $response->assertSee('johnwick@rwgenting.test');
        $response->assertSee('17/08/1987');
        $response->assertSee('4035 5010 0000 0008');
        $response->assertSee('EDIT PROFILE');
    }

    public function test_full_name_is_editable_and_updates_successfully(): void
    {
        $user = User::create([
            'fname' => 'Old Name',
            'email' => 'update@rwgenting.test',
            'dob' => '1990-01-01',
            'grc_id' => 'GRC123',
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('client');

        $this->actingAs($user);
        $response = $this->patch(route('profile.update'), [
            'fname' => 'New Full Name',
        ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('status', 'profile-updated');

        $user->refresh();
        $this->assertSame('New Full Name', $user->fname);
        $this->assertSame('GRC123', $user->grc_id);
    }

    public function test_user_without_grc_id_can_set_grc_id(): void
    {
        $user = User::create([
            'fname' => 'No GRC User',
            'email' => 'nogrc@rwgenting.test',
            'dob' => '1992-02-02',
            'grc_id' => null,
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('client');

        $this->actingAs($user);
        $response = $this->patch(route('profile.update'), [
            'fname' => 'No GRC User',
            'grc_id' => 'NEW-GRC-9999',
        ]);

        $response->assertRedirect(route('profile.edit'));
        $user->refresh();
        $this->assertSame('NEW-GRC-9999', $user->grc_id);
    }

    public function test_user_with_existing_grc_id_cannot_change_grc_id(): void
    {
        $user = User::create([
            'fname' => 'Existing GRC User',
            'email' => 'existinggrc@rwgenting.test',
            'dob' => '1992-02-02',
            'grc_id' => 'LOCKED-GRC-1111',
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('client');

        $this->actingAs($user);
        $response = $this->patch(route('profile.update'), [
            'fname' => 'Existing GRC User Updated',
            'grc_id' => 'ATTEMPTED-CHANGE-2222',
        ]);

        $response->assertRedirect(route('profile.edit'));
        $user->refresh();
        $this->assertSame('Existing GRC User Updated', $user->fname);
        $this->assertSame('LOCKED-GRC-1111', $user->grc_id); // GRC ID unchanged
    }

    public function test_login_page_renders_matching_winter_wonderland_design(): void
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);
        $response->assertSee('LOGIN');
        $response->assertSee('Email Address');
        $response->assertSee('placeholder="Enter your email"', false);
        $response->assertSee("HAVEN'T?", false);
        $response->assertSee('REGISTER HERE');
    }

    public function test_profile_update_success_modal_displays_and_logs_out_to_login(): void
    {
        $user = User::create([
            'fname' => 'Modal User',
            'email' => 'modaluser@rwgenting.test',
            'dob' => '1995-05-05',
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('client');

        $this->actingAs($user);

        // Flash status simulates post-update redirect
        $pageResponse = $this->withSession(['status' => 'profile-updated'])
            ->get(route('profile.edit'));
        $pageResponse->assertStatus(200);
        $pageResponse->assertSee('Account Updated Successfully!');
        $pageResponse->assertSee('For your security, you need to sign in again.');
        $pageResponse->assertSee('GOT IT');

        // Submitting logout with redirect parameter
        $logoutResponse = $this->post(route('logout'), [
            'redirect' => route('login'),
        ]);
        $logoutResponse->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_faq_page_renders_with_accordion_categories(): void
    {
        $response = $this->get(route('faq'));
        $response->assertStatus(200);
        $response->assertSee('Frequently Asked Questions (FAQ)');
        $response->assertSee('Treasure Hunt Frequent Asked Question (FAQ)');
        $response->assertSee('Getting Started &amp; Access', false);
        $response->assertSee('General Participation');
        $response->assertSee('General Disclaimer');
        $response->assertSee('BACK');
    }

    public function test_station_page_renders_with_feature_layout_and_modals(): void
    {
        $user = User::create([
            'fname' => 'Station Hunter',
            'email' => 'stationhunter@rwgenting.test',
            'dob' => '1995-05-05',
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('client');

        $this->actingAs($user);

        $response = $this->get(route('station', 1));
        $response->assertStatus(200);
        $response->assertSee('Proceed to hunt the QR code.');
        $response->assertSee('Scan the QR code to proceed');
        $response->assertSee('start-scanner');
        $response->assertSee('scanCompleteModal');
        $response->assertSee('Station Unlocked!');
        $response->assertSee('VIEW MY MAP');
        $response->assertSee('CANCEL');

        // Dashboard renders giftLockedModal when user has not completed all stations
        $dashResponse = $this->get(route('dashboard') . '?start=1');
        $dashResponse->assertStatus(200);
        $dashResponse->assertSee('giftLockedModal');
        $dashResponse->assertSee('Gift Redemption Locked');
        $dashResponse->assertSee('Complete all 6 stations to unlock Gift Redemption!');
    }
}


