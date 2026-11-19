<?php

namespace Tests\Feature;

use App\Models\PorscheQuizAttempt;
use App\Models\PorscheRegistration;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PorscheAdminTest extends TestCase
{
    use DatabaseTransactions;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::firstOrCreate(['name' => 'admin']);

        $this->adminUser = User::firstOrCreate(
            ['email' => 'test_admin@porsche.test'],
            [
                'fname' => 'Porsche',
                'lname' => 'Admin',
                'number' => '0123456789',
                'password' => bcrypt('Password123!'),
            ]
        );

        if (!$this->adminUser->hasRole('admin')) {
            $this->adminUser->assignRole($role);
        }
    }

    public function test_guest_is_redirected_from_admin_overview(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/admin/login');
    }

    public function test_admin_can_access_overview_and_sees_metrics(): void
    {
        $reg = PorscheRegistration::create([
            'full_name' => 'Ferdinand Porsche',
            'contact_number' => '0123334444',
            'email' => 'ferdinand@porsche.com',
            'consent_given' => true,
            'registered_at' => Carbon::now(),
        ]);

        PorscheQuizAttempt::create([
            'porsche_registration_id' => $reg->id,
            'attempt_number' => 1,
            'question_1_answer' => 'A',
            'question_2_answer' => 'A',
            'question_3_answer' => 'B',
            'question_4_answer' => 'C',
            'score_a' => 2,
            'score_b' => 1,
            'score_c' => 1,
            'result_type' => 'A',
            'completed_at' => Carbon::now(),
        ]);

        $response = $this->actingAs($this->adminUser)->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('Total Participants');
        $response->assertSee('ENDLESS POSSIBILITIES');
        $response->assertSee('Ferdinand Porsche');
    }

    public function test_admin_can_load_participants_view_and_datatable_ajax(): void
    {
        $reg = PorscheRegistration::create([
            'full_name' => 'Sally Carrera',
            'contact_number' => '0198887777',
            'email' => 'sally@carrera.com',
            'consent_given' => true,
            'registered_at' => Carbon::now(),
        ]);

        // HTML view
        $htmlResponse = $this->actingAs($this->adminUser)->get('/admin/participants');
        $htmlResponse->assertStatus(200);
        $htmlResponse->assertSee('Participants');

        // AJAX DataTables response with default empty search array
        $emptySearchResponse = $this->actingAs($this->adminUser)
            ->getJson('/admin/participants?draw=1&start=0&length=10&search[value]=&search[regex]=false');

        $emptySearchResponse->assertStatus(200);
        $emptySearchResponse->assertJsonStructure(['draw', 'recordsTotal', 'recordsFiltered', 'data']);

        // AJAX DataTables response with search query
        $ajaxResponse = $this->actingAs($this->adminUser)
            ->getJson('/admin/participants?draw=1&start=0&length=10&search[value]=Sally');

        $ajaxResponse->assertStatus(200);
        $ajaxResponse->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data',
        ]);
        $ajaxResponse->assertJsonFragment([
            'email' => 'sally@carrera.com',
        ]);
    }

    public function test_admin_can_view_participant_detail(): void
    {
        $reg = PorscheRegistration::create([
            'full_name' => 'Walter Rohrl',
            'contact_number' => '0177776666',
            'email' => 'walter@rohrl.com',
            'consent_given' => true,
            'registered_at' => Carbon::now(),
        ]);

        $attempt = PorscheQuizAttempt::create([
            'porsche_registration_id' => $reg->id,
            'attempt_number' => 1,
            'question_1_answer' => 'B',
            'question_2_answer' => 'B',
            'question_3_answer' => 'B',
            'question_4_answer' => 'A',
            'score_a' => 1,
            'score_b' => 3,
            'score_c' => 0,
            'result_type' => 'B',
            'completed_at' => Carbon::now(),
        ]);

        $response = $this->actingAs($this->adminUser)->get("/admin/participants/{$reg->id}");

        $response->assertStatus(200);
        $response->assertSee('Walter Rohrl');
        $response->assertSee('walter@rohrl.com');
        $response->assertSee('Attempt #1');
        $response->assertSee('Result B');
    }

    public function test_admin_can_load_entries_view_and_datatable_ajax(): void
    {
        $reg = PorscheRegistration::create([
            'full_name' => 'Jacky Ickx',
            'contact_number' => '0112233445',
            'email' => 'jacky@ickx.com',
            'consent_given' => true,
            'registered_at' => Carbon::now(),
        ]);

        PorscheQuizAttempt::create([
            'porsche_registration_id' => $reg->id,
            'attempt_number' => 1,
            'question_1_answer' => 'C',
            'question_2_answer' => 'C',
            'question_3_answer' => 'C',
            'question_4_answer' => 'A',
            'score_a' => 1,
            'score_b' => 0,
            'score_c' => 3,
            'result_type' => 'C',
            'completed_at' => Carbon::now(),
        ]);

        // HTML view
        $htmlResponse = $this->actingAs($this->adminUser)->get('/admin/entries');
        $htmlResponse->assertStatus(200);
        $htmlResponse->assertSee('Quiz Entries');

        // AJAX DataTables response with default empty search array
        $emptySearchResponse = $this->actingAs($this->adminUser)
            ->getJson('/admin/entries?draw=1&start=0&length=10&search[value]=&search[regex]=false');

        $emptySearchResponse->assertStatus(200);
        $emptySearchResponse->assertJsonStructure(['draw', 'recordsTotal', 'recordsFiltered', 'data']);

        // AJAX DataTables response
        $ajaxResponse = $this->actingAs($this->adminUser)
            ->getJson('/admin/entries?draw=1&start=0&length=10&result_type=C');

        $ajaxResponse->assertStatus(200);
        $ajaxResponse->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data',
        ]);
        $this->assertStringContainsString('Jacky Ickx', json_encode($ajaxResponse->json('data')));
    }

    public function test_admin_can_fetch_entry_details_json(): void
    {
        $reg = PorscheRegistration::create([
            'full_name' => 'Hurley Haywood',
            'contact_number' => '0191234567',
            'email' => 'hurley@haywood.com',
            'consent_given' => true,
            'registered_at' => Carbon::now(),
        ]);

        $attempt = PorscheQuizAttempt::create([
            'porsche_registration_id' => $reg->id,
            'attempt_number' => 1,
            'question_1_answer' => 'A',
            'question_2_answer' => 'B',
            'question_3_answer' => 'C',
            'question_4_answer' => 'A',
            'score_a' => 2,
            'score_b' => 1,
            'score_c' => 1,
            'result_type' => 'A',
            'completed_at' => Carbon::now(),
        ]);

        $response = $this->actingAs($this->adminUser)->getJson("/admin/entries/{$attempt->id}/detail");

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $attempt->id,
            'participant_name' => 'Hurley Haywood',
            'result_type' => 'A',
            'score_a' => 2,
        ]);
        $response->assertJsonCount(4, 'breakdown');
    }

    public function test_admin_can_export_participants_and_entries_csv(): void
    {
        $reg = PorscheRegistration::create([
            'full_name' => 'Hans Stuck',
            'contact_number' => '0189988776',
            'email' => 'hans@stuck.com',
            'consent_given' => true,
            'registered_at' => Carbon::now(),
        ]);

        PorscheQuizAttempt::create([
            'porsche_registration_id' => $reg->id,
            'attempt_number' => 1,
            'question_1_answer' => 'A',
            'question_2_answer' => 'B',
            'question_3_answer' => 'A',
            'question_4_answer' => 'A',
            'score_a' => 3,
            'score_b' => 1,
            'score_c' => 0,
            'result_type' => 'A',
            'completed_at' => Carbon::now(),
        ]);

        // Export Participants
        $partResponse = $this->actingAs($this->adminUser)->get('/admin/reports/export-participants');
        $partResponse->assertStatus(200);
        $this->assertStringContainsString('text/csv', $partResponse->headers->get('Content-Type'));

        // Export Entries
        $entryResponse = $this->actingAs($this->adminUser)->get('/admin/reports/export-entries');
        $entryResponse->assertStatus(200);
        $this->assertStringContainsString('text/csv', $entryResponse->headers->get('Content-Type'));
    }

    public function test_admin_can_view_and_update_campaign_settings(): void
    {
        $viewResponse = $this->actingAs($this->adminUser)->get('/admin/settings');
        $viewResponse->assertStatus(200);
        $viewResponse->assertSee('Campaign Settings');

        $postResponse = $this->actingAs($this->adminUser)->post('/admin/settings', [
            'campaign_name' => 'Porsche Cayenne Journey Quiz 2026',
            'campaign_status' => 'active',
            'allow_multiple_entries' => 1,
            'max_entries_per_participant' => 5,
            'privacy_policy_url' => 'https://www.porsche.com/malaysia/en/privacy-policy/',
        ]);

        $postResponse->assertRedirect('/admin/settings');
        $postResponse->assertSessionHas('success');
    }
}
