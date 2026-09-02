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
        // Fetch slots for Tuesday Oct 6 and Wednesday Oct 7
        $tueSlots = $this->getJson('/api/booking/dates/2026-10-06/slots')->json();
        $wedSlots = $this->getJson('/api/booking/dates/2026-10-07/slots')->json();

        $slot1 = $tueSlots[0]['id'];
        $slot2 = $wedSlots[0]['id'];

        // Create initial booking
        $createRes = $this->postJson('/api/bookings', [
            'date' => '2026-10-06',
            'slot_id' => $slot1,
            'customer_name' => 'Modify User',
            'customer_email' => 'modify@example.com',
            'customer_phone' => '09444444444',
        ]);
        $createRes->assertStatus(201);
        $booking = Booking::where('customer_email', 'modify@example.com')->first();

        // 1st Modification: Move to Wednesday Oct 7 -> SUCCEEDS
        $modifyRes1 = $this->postJson("/api/bookings/{$booking->id}/modify", [
            'date' => '2026-10-07',
            'slot_id' => $slot2,
        ]);
        $modifyRes1->assertStatus(200);
        $modifyRes1->assertJsonPath('success', true);

        // Verify slot counts
        $this->assertEquals(0, BookingSlot::find($slot1)->booked_count);
        $this->assertEquals(1, BookingSlot::find($slot2)->booked_count);

        // Verify booking reschedule_count is 1
        $booking->refresh();
        $this->assertEquals(1, $booking->reschedule_count);

        // 2nd Modification attempt -> FAILS (1 time limit)
        $modifyRes2 = $this->postJson("/api/bookings/{$booking->id}/modify", [
            'date' => '2026-10-06',
            'slot_id' => $slot1,
        ]);
        $modifyRes2->assertStatus(422);
        $modifyRes2->assertJsonValidationErrors(['slot']);
    }

    /** @test */
    public function it_allows_different_users_to_book_same_date_but_blocks_same_user_from_booking_same_date_twice()
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

        // User A tries to book slot 2 (6:00 PM) on Oct 6 as well -> Should FAIL!
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
}
