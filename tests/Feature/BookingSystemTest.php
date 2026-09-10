<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\BookingDate;
use App\Models\BookingSlot;
use Database\Seeders\OperatingHoursSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingSystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(OperatingHoursSeeder::class);
    }

    /** @test */
    public function it_seeds_operating_hours_correctly()
    {
        $this->assertDatabaseHas('operating_hours', ['day_of_week' => 1, 'is_open' => false]); // Monday
        $this->assertDatabaseHas('operating_hours', ['day_of_week' => 2, 'is_open' => true]);  // Tuesday
        $this->assertDatabaseHas('operating_hours', ['day_of_week' => 6, 'is_open' => true]);  // Saturday
        $this->assertDatabaseHas('operating_hours', ['day_of_week' => 7, 'is_open' => false]); // Sunday

        // Check Tuesday has 2 sessions
        $tuesday = \App\Models\OperatingHour::where('day_of_week', 2)->first();
        $this->assertCount(2, $tuesday->sessions);

        // Check Saturday has 4 sessions
        $saturday = \App\Models\OperatingHour::where('day_of_week', 6)->first();
        $this->assertCount(4, $saturday->sessions);
    }

    /** @test */
    public function it_returns_date_availabilities_via_api()
    {
        $response = $this->getJson('/api/booking/dates?start_date=2026-10-01&end_date=2026-10-07');

        $response->assertStatus(200);
        $data = $response->json();

        $this->assertIsArray($data);
        $this->assertNotEmpty($data);

        // October 4, 2026 is a Sunday (day 7) => should be closed
        $oct4 = collect($data)->firstWhere('date', '2026-10-04');
        $this->assertEquals('closed', $oct4['status']);

        // October 6, 2026 is a Tuesday (day 2) => should be available
        $oct6 = collect($data)->firstWhere('date', '2026-10-06');
        $this->assertEquals('available', $oct6['status']);
    }

    /** @test */
    public function it_returns_slots_for_an_open_date()
    {
        // 2026-10-06 is Tuesday
        $response = $this->getJson('/api/booking/dates/2026-10-06/slots');

        $response->assertStatus(200);
        $slots = $response->json();

        $this->assertCount(2, $slots);
        $this->assertEquals('2:00 PM - 3:00 PM', $slots[0]['label']);
        $this->assertTrue($slots[0]['available']);
    }

    /** @test */
    public function it_creates_a_booking_successfully()
    {
        // 2026-10-06 is Tuesday
        $slotsResponse = $this->getJson('/api/booking/dates/2026-10-06/slots');
        $slotId = $slotsResponse->json()[0]['id'];

        $bookingData = [
            'date' => '2026-10-06',
            'slot_id' => $slotId,
            'customer_name' => 'Jane Doe',
            'customer_email' => 'jane@example.com',
            'customer_phone' => '09876543210',
        ];

        $response = $this->postJson('/api/bookings', $bookingData);

        $response->assertStatus(201);
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('message', 'BOOKING CONFIRMED');
        $response->assertJsonPath('data.customer.name', 'Jane Doe');

        $this->assertDatabaseHas('bookings', [
            'customer_email' => 'jane@example.com',
            'status' => 'confirmed',
        ]);

        $slot = BookingSlot::find($slotId);
        $this->assertEquals(1, $slot->booked_count);
    }

    /** @test */
    public function it_prevents_double_booking_when_capacity_is_reached()
    {
        // 2026-10-06 is Tuesday
        $slotsResponse = $this->getJson('/api/booking/dates/2026-10-06/slots');
        $slotId = $slotsResponse->json()[0]['id'];

        // Set capacity to 1
        $slot = BookingSlot::find($slotId);
        $slot->update(['capacity' => 1]);

        // First booking succeeds
        $firstBooking = $this->postJson('/api/bookings', [
            'date' => '2026-10-06',
            'slot_id' => $slotId,
            'customer_name' => 'First Customer',
            'customer_email' => 'first@example.com',
            'customer_phone' => '09111111111',
        ]);
        $firstBooking->assertStatus(201);

        // Second booking fails with 422
        $secondBooking = $this->postJson('/api/bookings', [
            'date' => '2026-10-06',
            'slot_id' => $slotId,
            'customer_name' => 'Second Customer',
            'customer_email' => 'second@example.com',
            'customer_phone' => '09222222222',
        ]);

        $secondBooking->assertStatus(422);
        $secondBooking->assertJsonValidationErrors(['slot']);
    }

    /** @test */
    public function it_cancels_booking_and_decrements_booked_count()
    {
        $slotsResponse = $this->getJson('/api/booking/dates/2026-10-06/slots');
        $slotId = $slotsResponse->json()[0]['id'];

        $booking = $this->postJson('/api/bookings', [
            'date' => '2026-10-06',
            'slot_id' => $slotId,
            'customer_name' => 'Cancel Test',
            'customer_email' => 'cancel@example.com',
            'customer_phone' => '09333333333',
        ])->json();

        $slot = BookingSlot::find($slotId);
        $this->assertEquals(1, $slot->booked_count);

        $bookingId = Booking::where('customer_email', 'cancel@example.com')->first()->id;

        $cancelResponse = $this->postJson("/api/bookings/{$bookingId}/cancel");
        $cancelResponse->assertStatus(200);

        $slot->refresh();
        $this->assertEquals(0, $slot->booked_count);
        $this->assertDatabaseHas('bookings', [
            'id' => $bookingId,
            'status' => 'cancelled',
        ]);
    }

    /** @test */
    public function it_allows_guest_to_modify_booking_once_only()
    {
        // Fetch slots for Wednesday Oct 14 and Wednesday Oct 7 (7 days before Oct 14)
        $oct14Slots = $this->getJson('/api/booking/dates/2026-10-14/slots')->json();
        $oct7Slots = $this->getJson('/api/booking/dates/2026-10-07/slots')->json();

        $slotLate = $oct14Slots[0]['id'];
        $slotEarly = $oct7Slots[0]['id'];

        // Create initial booking on Oct 14
        $createRes = $this->postJson('/api/bookings', [
            'date' => '2026-10-14',
            'slot_id' => $slotLate,
            'customer_name' => 'Modify User',
            'customer_email' => 'modify@example.com',
            'customer_phone' => '09444444444',
        ]);
        $createRes->assertStatus(201);
        $booking = Booking::where('customer_email', 'modify@example.com')->first();

        // 1st Modification: Move to Oct 7 (7 days before Oct 14) -> SUCCEEDS
        $modifyRes1 = $this->postJson("/api/bookings/{$booking->id}/modify", [
            'date' => '2026-10-07',
            'slot_id' => $slotEarly,
        ]);
        $modifyRes1->assertStatus(200);
        $modifyRes1->assertJsonPath('success', true);

        // Verify slot counts
        $this->assertEquals(0, BookingSlot::find($slotLate)->booked_count);
        $this->assertEquals(1, BookingSlot::find($slotEarly)->booked_count);

        // Verify booking reschedule_count is 1
        $booking->refresh();
        $this->assertEquals(1, $booking->reschedule_count);

        // 2nd Modification attempt -> FAILS (1 time limit)
        $modifyRes2 = $this->postJson("/api/bookings/{$booking->id}/modify", [
            'date' => '2026-10-14',
            'slot_id' => $slotLate,
        ]);
        $modifyRes2->assertStatus(422);
        $modifyRes2->assertJsonValidationErrors(['slot']);
    }

    /** @test */
    public function it_prevents_rescheduling_less_than_one_week_before_slot()
    {
        $tueSlots = $this->getJson('/api/booking/dates/2026-10-06/slots')->json();
        $wedSlots = $this->getJson('/api/booking/dates/2026-10-07/slots')->json();

        $slot1 = $tueSlots[0]['id'];
        $slot2 = $wedSlots[0]['id'];

        $booking = $this->postJson('/api/bookings', [
            'date' => '2026-10-06',
            'slot_id' => $slot1,
            'customer_name' => 'Late Reschedule User',
            'customer_email' => 'late@example.com',
            'customer_phone' => '09555555555',
        ]);
        $bookingObj = Booking::where('customer_email', 'late@example.com')->first();

        // Travel to 4 days before slot (October 2, 2026) -> Less than 1 week (7 days) away
        \Carbon\Carbon::setTestNow('2026-10-02 10:00:00');

        $modifyRes = $this->postJson("/api/bookings/{$bookingObj->id}/modify", [
            'date' => '2026-10-07',
            'slot_id' => $slot2,
        ]);

        $modifyRes->assertStatus(422);
        $modifyRes->assertJsonValidationErrors(['slot']);
        $this->assertStringContainsString('at least one week before', $modifyRes->json('errors.slot.0'));

        \Carbon\Carbon::setTestNow(); // Reset test time
    }

    /** @test */
    public function it_allows_different_users_to_book_different_slots_but_blocks_user_from_booking_multiple_slots()
    {
        // 2026-10-06 is Tuesday (2 sessions: slot 0 and slot 1)
        $slots = $this->getJson('/api/booking/dates/2026-10-06/slots')->json();
        $slot1 = $slots[0]['id']; // 2:00 PM
        $slot2 = $slots[1]['id']; // 6:00 PM

        // User A books slot 1 (2:00 PM) on Oct 6
        $userABooking = $this->postJson('/api/bookings', [
            'date' => '2026-10-06',
            'slot_id' => $slot1,
            'customer_name' => 'User A',
            'customer_email' => 'usera@example.com',
            'customer_phone' => '09111111111',
        ]);
        $userABooking->assertStatus(201);

        // User B books slot 2 (6:00 PM) on Oct 6 -> Should SUCCEED!
        $userBBooking = $this->postJson('/api/bookings', [
            'date' => '2026-10-06',
            'slot_id' => $slot2,
            'customer_name' => 'User B',
            'customer_email' => 'userb@example.com',
            'customer_phone' => '09222222222',
        ]);
        $userBBooking->assertStatus(201);

        // User A tries to book slot 2 (6:00 PM) on Oct 6 as well -> Should FAIL! (1 time slot per user rule)
        $userADuplicate = $this->postJson('/api/bookings', [
            'date' => '2026-10-06',
            'slot_id' => $slot2,
            'customer_name' => 'User A',
            'customer_email' => 'usera@example.com',
            'customer_phone' => '09111111111',
        ]);
        $userADuplicate->assertStatus(422);
        $userADuplicate->assertJsonValidationErrors(['slot']);
    }

    /** @test */
    public function it_assigns_distinct_bookings_per_user_and_no_booking_for_unbooked_users()
    {
        $admin = \App\Models\User::factory()->create();
        $admin->assignRole(\Spatie\Permission\Models\Role::create(['name' => 'admin']));

        $userWithBooking = \App\Models\User::factory()->create([
            'email' => 'user1@example.com',
            'fname' => 'UserOne',
        ]);

        $userWithoutBooking = \App\Models\User::factory()->create([
            'email' => 'user2@example.com',
            'fname' => 'UserTwo',
        ]);

        // Create booking for user1
        $slots = $this->getJson('/api/booking/dates/2026-10-06/slots')->json();
        $this->postJson('/api/bookings', [
            'date' => '2026-10-06',
            'slot_id' => $slots[0]['id'],
            'customer_name' => 'UserOne',
            'customer_email' => 'user1@example.com',
            'customer_phone' => '09111111111',
        ]);

        $response = $this->actingAs($admin)->get(route('users'));
        $response->assertStatus(200);

        $users = $response->viewData('data')['users'];
        $u1 = $users->firstWhere('email', 'user1@example.com');
        $u2 = $users->firstWhere('email', 'user2@example.com');

        $this->assertNotNull($u1->booking_ref);
        $this->assertNotEquals('No Booking', $u1->booking_date_text);

        $this->assertNull($u2->booking_ref);
        $this->assertEquals('No Booking', $u2->booking_date_text);
    }

    /** @test */
    public function it_redirects_guests_attempting_to_access_booking_flow_to_login()
    {
        $response = $this->get('/booking');
        $response->assertRedirect(route('login'));

        $modifyResponse = $this->get('/booking?modify=1');
        $modifyResponse->assertRedirect(route('login'));
    }

    /** @test */
    public function it_allows_authenticated_client_to_access_booking_flow()
    {
        $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'client']);
        $user = \App\Models\User::factory()->create();
        $user->assignRole($role);

        $response = $this->actingAs($user)->get('/booking');
        $response->assertStatus(200);
    }

    /** @test */
    public function it_runs_full_booking_and_modification_flow_across_slots_without_loopholes()
    {
        $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'client']);

        // Create 3 Users
        $user1 = \App\Models\User::factory()->create(['email' => 'scenario1@example.com', 'fname' => 'ScenarioOne']);
        $user1->assignRole($role);

        $user2 = \App\Models\User::factory()->create(['email' => 'scenario2@example.com', 'fname' => 'ScenarioTwo']);
        $user2->assignRole($role);

        $user3 = \App\Models\User::factory()->create(['email' => 'scenario3@example.com', 'fname' => 'ScenarioThree']);
        $user3->assignRole($role);

        // Fetch slots for Oct 14 (Wednesday), Oct 7 (Wednesday - 7 days prior), and Oct 10 (Saturday - 4 days prior)
        $oct14Slots = $this->getJson('/api/booking/dates/2026-10-14/slots')->json();
        $oct7Slots = $this->getJson('/api/booking/dates/2026-10-07/slots')->json();
        $oct10Slots = $this->getJson('/api/booking/dates/2026-10-10/slots')->json();

        $slotOct14 = $oct14Slots[0]['id'];
        $slotOct7 = $oct7Slots[0]['id'];
        $slotOct10 = $oct10Slots[0]['id'];

        // 1. User 1 books Oct 14 slot
        $res1 = $this->actingAs($user1)->postJson('/reservation-create', [
            'date' => '2026-10-14',
            'slot_id' => $slotOct14,
        ]);
        $res1->assertStatus(201);
        $res1->assertJsonPath('success', true);
        $booking1Ref = $res1->json('data.reference_no');

        // 2. User 1 tries to book a second slot on Oct 7 -> FAILS (1 slot per user rule)
        $res1Duplicate = $this->actingAs($user1)->postJson('/reservation-create', [
            'date' => '2026-10-07',
            'slot_id' => $slotOct7,
        ]);
        $res1Duplicate->assertStatus(422);

        // 3. User 2 tries to book the same Oct 14 slot -> FAILS (capacity limit = 1)
        $res2Overbook = $this->actingAs($user2)->postJson('/reservation-create', [
            'date' => '2026-10-14',
            'slot_id' => $slotOct14,
        ]);
        $res2Overbook->assertStatus(422);

        // 4. User 2 books Oct 10 slot instead -> SUCCEEDS
        $res2 = $this->actingAs($user2)->postJson('/reservation-create', [
            'date' => '2026-10-10',
            'slot_id' => $slotOct10,
        ]);
        $res2->assertStatus(201);

        // 5. User 1 tries to modify to Oct 10 (only 4 days prior to Oct 14) -> FAILS (< 7 days prior rule)
        $modInvalidDate = $this->actingAs($user1)->postJson('/reservation-create/modify', [
            'reference_no' => $booking1Ref,
            'date' => '2026-10-10',
            'slot_id' => $slotOct10,
        ]);
        $modInvalidDate->assertStatus(422);

        // 6. User 2 tries to modify User 1's booking -> FAILS (403 unauthorized)
        $modUnauthorized = $this->actingAs($user2)->postJson('/reservation-create/modify', [
            'reference_no' => $booking1Ref,
            'date' => '2026-10-07',
            'slot_id' => $slotOct7,
        ]);
        $modUnauthorized->assertStatus(403);

        // 7. User 1 modifies booking to Oct 7 (exactly 7 days prior) -> SUCCEEDS
        $modSuccess = $this->actingAs($user1)->postJson('/reservation-create/modify', [
            'reference_no' => $booking1Ref,
            'date' => '2026-10-07',
            'slot_id' => $slotOct7,
        ]);
        $modSuccess->assertStatus(200);
        $modSuccess->assertJsonPath('success', true);

        // Verify slot counts: Oct 14 slot is now 0 (freed), Oct 7 slot is now 1
        $this->assertEquals(0, BookingSlot::find($slotOct14)->booked_count);
        $this->assertEquals(1, BookingSlot::find($slotOct7)->booked_count);

        // 8. User 1 attempts a second modification -> FAILS (1-time modification limit)
        $modSecond = $this->actingAs($user1)->postJson('/reservation-create/modify', [
            'reference_no' => $booking1Ref,
            'date' => '2026-10-02',
            'slot_id' => $this->getJson('/api/booking/dates/2026-10-02/slots')->json()[0]['id'],
        ]);
        $modSecond->assertStatus(422);

        // 9. Now Oct 14 slot is freed, User 3 can book Oct 14 slot -> SUCCEEDS
        $res3 = $this->actingAs($user3)->postJson('/reservation-create', [
            'date' => '2026-10-14',
            'slot_id' => $slotOct14,
        ]);
        $res3->assertStatus(201);
        $this->assertEquals(1, BookingSlot::find($slotOct14)->booked_count);
    }

    /** @test */
    public function it_validates_vip_pax_capacity_and_prevents_overbooking()
    {
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']);
        $admin = \App\Models\User::factory()->create(['fname' => 'Admin', 'lname' => 'User']);
        $admin->assignRole('admin');

        $this->getJson('/api/booking/dates/2026-09-30/slots');
        $dateObj = \App\Models\BookingDate::where('date', '2026-09-30')->first();
        $slot = $dateObj->slots()->first();
        $slot->capacity = 20;
        $slot->booked_count = 0;
        $slot->save();

        // Attempting to book 25 pax on a slot of capacity 20 -> fails validation
        $response = $this->actingAs($admin)->post('/admin/vip', [
            'vip_name' => 'Test VIP Group',
            'booking_date_id' => $dateObj->id,
            'booking_slot_id' => $slot->id,
            'pax' => 25,
        ]);

        $response->assertSessionHasErrors(['pax']);
        $this->assertEquals(0, $slot->fresh()->booked_count);

        // Booking 15 pax -> succeeds
        $validRes = $this->actingAs($admin)->post('/admin/vip', [
            'vip_name' => 'Valid VIP Group',
            'booking_date_id' => $dateObj->id,
            'booking_slot_id' => $slot->id,
            'pax' => 15,
        ]);

        $validRes->assertSessionHasNoErrors();
        $validRes->assertSessionHas('success');
        $this->assertEquals(15, $slot->fresh()->booked_count);

        // Attempting to book 10 pax when only 5 remaining -> fails validation
        $overRes = $this->actingAs($admin)->post('/admin/vip', [
            'vip_name' => 'Excess VIP Group',
            'booking_date_id' => $dateObj->id,
            'booking_slot_id' => $slot->id,
            'pax' => 10,
        ]);

        $overRes->assertSessionHasErrors(['pax']);
        $this->assertEquals(15, $slot->fresh()->booked_count);
    }

    /** @test */
    public function it_requires_unique_email_for_walkin_customer_registration()
    {
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']);
        $admin = \App\Models\User::factory()->create(['fname' => 'Admin', 'lname' => 'User']);
        $admin->assignRole('admin');

        $this->getJson('/api/booking/dates/2026-10-02/slots');
        $dateObj = \App\Models\BookingDate::where('date', '2026-10-02')->first();
        $slot = $dateObj->slots()->first();
        $slot->capacity = 5;
        $slot->booked_count = 0;
        $slot->save();

        // 1. Create initial walk-in registration
        $res1 = $this->actingAs($admin)->from('/admin/booking')->post('/admin/walkin-booking', [
            'title' => 'MR.',
            'fname' => 'Unique',
            'lname' => 'Walker',
            'email' => 'unique.walkin@example.com',
            'phone' => '+60123456789',
            'booking_date_id' => $dateObj->id,
            'booking_slot_id' => $slot->id,
            'pax' => 1,
        ]);

        $res1->assertSessionHasNoErrors();
        $res1->assertSessionHas('success');

        // 2. Attempting to register another walk-in customer with the same email -> FAILS
        $res2 = $this->actingAs($admin)->from('/admin/booking')->post('/admin/walkin-booking', [
            'title' => 'MS.',
            'fname' => 'Duplicate',
            'lname' => 'Walker',
            'email' => 'unique.walkin@example.com',
            'phone' => '+60123456799',
            'booking_date_id' => $dateObj->id,
            'booking_slot_id' => $slot->id,
            'pax' => 1,
        ]);

        $res2->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function it_creates_user_account_for_walkin_registration_and_shows_on_admin_users_table()
    {
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']);
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'client']);
        $admin = \App\Models\User::factory()->create(['fname' => 'Admin', 'lname' => 'User']);
        $admin->assignRole('admin');

        $this->getJson('/api/booking/dates/2026-10-02/slots');
        $dateObj = \App\Models\BookingDate::where('date', '2026-10-02')->first();
        $slot = $dateObj->slots()->first();

        $res = $this->actingAs($admin)->from('/admin/booking')->post('/admin/walkin-booking', [
            'title' => 'MS.',
            'fname' => 'Sarah',
            'lname' => 'Walkin',
            'email' => 'sarah.walkin@example.com',
            'phone' => '+601122334455',
            'booking_date_id' => $dateObj->id,
            'booking_slot_id' => $slot->id,
            'pax' => 1,
            'mark_attended' => 1,
        ]);

        $res->assertSessionHasNoErrors();
        $res->assertRedirect();

        // 1. Assert user record was created
        $user = \App\Models\User::where('email', 'sarah.walkin@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('Sarah', $user->fname);
        $this->assertEquals('Walkin', $user->lname);
        $this->assertEquals(1, $user->otp_verified);
        $this->assertTrue($user->hasRole('client'));

        // 2. Assert walkin user appears in the admin users list response
        $usersRes = $this->actingAs($admin)->get('/admin/users');
        $usersRes->assertStatus(200);
        $usersRes->assertSee('sarah.walkin@example.com');
        $usersRes->assertSee('Sarah');
        $usersRes->assertSee('Walkin');
    }
}
