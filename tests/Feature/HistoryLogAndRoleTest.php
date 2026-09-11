<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\HistoryLog;
use App\Models\Booking;
use App\Models\BookingDate;
use App\Models\BookingSlot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class HistoryLogAndRoleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\AdminUserSeeder::class);
        $this->seed(\Database\Seeders\OperatingHoursSeeder::class);
        $this->seed(\Database\Seeders\EventScheduleSeeder::class);
    }

    /** @test */
    public function it_seeds_admin_at_gmail_com_as_superadmin()
    {
        $admin = User::where('email', 'admin@gmail.com')->first();
        $this->assertNotNull($admin);
        $this->assertTrue($admin->isSuperAdmin());
        $this->assertTrue($admin->hasRole('superadmin'));
    }

    /** @test */
    public function superadmin_can_access_history_logs_page()
    {
        $superAdmin = User::where('email', 'admin@gmail.com')->first();
        $response = $this->actingAs($superAdmin)->get('/admin/history-logs');
        $response->assertStatus(200);
        $response->assertSee('System History Logs');
    }

    /** @test */
    public function admin_dashboard_loads_without_errors()
    {
        $superAdmin = User::where('email', 'admin@gmail.com')->first();
        $response = $this->actingAs($superAdmin)->get('/admin');
        $response->assertStatus(200);
    }

    /** @test */
    public function staff_user_cannot_access_history_logs_page()
    {
        $staff = User::factory()->create(['email' => 'staff1@gmail.com']);
        Role::firstOrCreate(['name' => 'staff']);
        $staff->assignRole('staff');

        $response = $this->actingAs($staff)->get('/admin/history-logs');
        $response->assertStatus(403);
    }

    /** @test */
    public function superadmin_can_create_new_staff_user()
    {
        $superAdmin = User::where('email', 'admin@gmail.com')->first();

        $response = $this->from('/admin/users')->actingAs($superAdmin)->post('/admin/users', [
            'fname' => 'New',
            'lname' => 'Staff',
            'email' => 'newstaff@gmail.com',
            'number' => '0112233445',
            'role' => 'staff',
            'password' => 'password123',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('users', ['email' => 'newstaff@gmail.com']);
        
        $newStaff = User::where('email', 'newstaff@gmail.com')->first();
        $this->assertTrue($newStaff->hasRole('staff'));

        $this->assertDatabaseHas('history_logs', [
            'action' => 'CREATE_USER',
            'target_id' => $newStaff->id,
        ]);
    }

    /** @test */
    public function booking_actions_are_logged_in_history_logs()
    {
        $superAdmin = User::where('email', 'admin@gmail.com')->first();
        $date = BookingDate::where('date', '2026-09-30')->first();
        $slot = BookingSlot::where('booking_date_id', $date->id)->first();

        // 1. Walkin Booking Creation
        $this->actingAs($superAdmin)->post('/admin/walkin-booking', [
            'title' => 'Mr',
            'fname' => 'Walkin',
            'lname' => 'Guest',
            'email' => 'walkin@guest.com',
            'phone' => '0199998888',
            'booking_date_id' => $date->id,
            'booking_slot_id' => $slot->id,
            'pax' => 1,
        ]);

        $this->assertDatabaseHas('history_logs', [
            'action' => 'CREATE_WALKIN_BOOKING',
        ]);

        // 2. VIP Booking Creation
        $this->actingAs($superAdmin)->post('/admin/vip', [
            'vip_name' => 'VIP Celebrity',
            'booking_date_id' => $date->id,
            'booking_slot_id' => $slot->id,
            'pax' => 1,
        ]);

        $this->assertDatabaseHas('history_logs', [
            'action' => 'CREATE_VIP_BOOKING',
        ]);
    }
}
