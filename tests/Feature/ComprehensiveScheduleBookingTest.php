<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\BookingDate;
use App\Models\BookingSlot;
use App\Models\User;
use Database\Seeders\EventScheduleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ComprehensiveScheduleBookingTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(EventScheduleSeeder::class);

        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'client']);

        $this->admin = User::factory()->create(['fname' => 'Admin', 'email' => 'admin_comp@example.com']);
        $this->admin->assignRole('admin');
    }

    /** @test */
    public function it_validates_september_30_vip_schedule_slots_and_blocks_public()
    {
        // 1. Assert Sept 30 is CLOSED for public API
        $publicRes = $this->getJson('/api/booking/dates?start_date=2026-09-30&end_date=2026-09-30');
        $publicRes->assertStatus(200);
        $this->assertEquals('closed', $publicRes->json()[0]['status']);

        // 2. Assert Sept 30 is AVAILABLE for VIP API
        $vipRes = $this->getJson('/api/booking/dates?start_date=2026-09-30&end_date=2026-09-30&is_vip=1');
        $vipRes->assertStatus(200);
        $this->assertEquals('available', $vipRes->json()[0]['status']);

        // 3. Assert all 11 VIP slots exist with correct capacities
        $slotsRes = $this->getJson('/api/booking/dates/2026-09-30/slots?is_vip=1');
        $slots = $slotsRes->json();
        $this->assertCount(11, $slots);

        // KOL & Media (11am, 12pm, 1pm, 2pm - 20 pax each)
        $this->assertEquals('11:00', $slots[0]['start_time']);
        $this->assertEquals(20, $slots[0]['capacity']);

        $this->assertEquals('12:00', $slots[1]['start_time']);
        $this->assertEquals(20, $slots[1]['capacity']);

        $this->assertEquals('13:00', $slots[2]['start_time']);
        $this->assertEquals(20, $slots[2]['capacity']);

        $this->assertEquals('14:00', $slots[3]['start_time']);
        $this->assertEquals(20, $slots[3]['capacity']);

        // Longchamp VIC (3pm, 4pm - 10 pax each)
        $this->assertEquals('15:00', $slots[4]['start_time']);
        $this->assertEquals(10, $slots[4]['capacity']);

        $this->assertEquals('16:00', $slots[5]['start_time']);
        $this->assertEquals(10, $slots[5]['capacity']);

        // The Gardens Emerald Member (5pm, 6pm - 6 pax each)
        $this->assertEquals('17:00', $slots[6]['start_time']);
        $this->assertEquals(6, $slots[6]['capacity']);

        $this->assertEquals('18:00', $slots[7]['start_time']);
        $this->assertEquals(6, $slots[7]['capacity']);

        // Maybank Premium Customer (7pm, 8pm, 9pm - 10 pax each)
        $this->assertEquals('19:00', $slots[8]['start_time']);
        $this->assertEquals(10, $slots[8]['capacity']);

        $this->assertEquals('20:00', $slots[9]['start_time']);
        $this->assertEquals(10, $slots[9]['capacity']);

        $this->assertEquals('21:00', $slots[10]['start_time']);
        $this->assertEquals(10, $slots[10]['capacity']);
    }

    /** @test */
    public function it_validates_october_1_public_and_vip_slots()
    {
        // 1. Public API: Only 11am & 12pm slots allowed (6 pax capacity each)
        $publicSlotsRes = $this->getJson('/api/booking/dates/2026-10-01/slots');
        $publicSlots = $publicSlotsRes->json();
        $this->assertCount(2, $publicSlots);
        $this->assertEquals('11:00', $publicSlots[0]['start_time']);
        $this->assertEquals(6, $publicSlots[0]['capacity']);
        $this->assertEquals('12:00', $publicSlots[1]['start_time']);
        $this->assertEquals(6, $publicSlots[1]['capacity']);

        // 2. VIP API: All 6 slots visible (11am, 12pm public; 1pm, 2pm Pin Prestige; 3pm, 4pm Gardens Emerald)
        $vipSlotsRes = $this->getJson('/api/booking/dates/2026-10-01/slots?is_vip=1');
        $vipSlots = $vipSlotsRes->json();
        $this->assertCount(6, $vipSlots);

        // Pin Prestige (1pm, 2pm - 6 pax)
        $this->assertEquals('13:00', $vipSlots[2]['start_time']);
        $this->assertEquals(6, $vipSlots[2]['capacity']);
        $this->assertEquals('14:00', $vipSlots[3]['start_time']);
        $this->assertEquals(6, $vipSlots[3]['capacity']);

        // Gardens Emerald (3pm, 4pm - 6 pax)
        $this->assertEquals('15:00', $vipSlots[4]['start_time']);
        $this->assertEquals(6, $vipSlots[4]['capacity']);
        $this->assertEquals('16:00', $vipSlots[5]['start_time']);
        $this->assertEquals(6, $vipSlots[5]['capacity']);
    }

    /** @test */
    public function it_validates_october_2_and_october_3_public_workshop_slots()
    {
        // Oct 2: 12pm & 6pm (6 pax each)
        $oct2Slots = $this->getJson('/api/booking/dates/2026-10-02/slots')->json();
        $this->assertCount(2, $oct2Slots);
        $this->assertEquals('12:00', $oct2Slots[0]['start_time']);
        $this->assertEquals(6, $oct2Slots[0]['capacity']);
        $this->assertEquals('18:00', $oct2Slots[1]['start_time']);
        $this->assertEquals(6, $oct2Slots[1]['capacity']);

        // Oct 3: 11am & 4pm (6 pax each)
        $oct3Slots = $this->getJson('/api/booking/dates/2026-10-03/slots')->json();
        $this->assertCount(2, $oct3Slots);
        $this->assertEquals('11:00', $oct3Slots[0]['start_time']);
        $this->assertEquals(6, $oct3Slots[0]['capacity']);
        $this->assertEquals('16:00', $oct3Slots[1]['start_time']);
        $this->assertEquals(6, $oct3Slots[1]['capacity']);
    }

    /** @test */
    public function it_validates_closed_days_october_4_5_6()
    {
        $datesRes = $this->getJson('/api/booking/dates?start_date=2026-10-04&end_date=2026-10-06');
        $dates = $datesRes->json();

        $this->assertEquals('closed', $dates[0]['status']); // Oct 4 (Sun)
        $this->assertEquals('closed', $dates[1]['status']); // Oct 5 (Mon)
        $this->assertEquals('closed', $dates[2]['status']); // Oct 6 (Tue)
    }

    /** @test */
    public function it_validates_october_7_and_october_8_public_workshop_slots()
    {
        // Oct 7: 12pm & 6pm (6 pax each)
        $oct7Slots = $this->getJson('/api/booking/dates/2026-10-07/slots')->json();
        $this->assertCount(2, $oct7Slots);
        $this->assertEquals('12:00', $oct7Slots[0]['start_time']);
        $this->assertEquals('18:00', $oct7Slots[1]['start_time']);

        // Oct 8: 12pm & 6pm (6 pax each)
        $oct8Slots = $this->getJson('/api/booking/dates/2026-10-08/slots')->json();
        $this->assertCount(2, $oct8Slots);
        $this->assertEquals('12:00', $oct8Slots[0]['start_time']);
        $this->assertEquals('18:00', $oct8Slots[1]['start_time']);
    }

    /** @test */
    public function it_validates_october_9_private_shopping_session_ferhat()
    {
        // Public is CLOSED
        $publicRes = $this->getJson('/api/booking/dates?start_date=2026-10-09&end_date=2026-10-09');
        $this->assertEquals('closed', $publicRes->json()[0]['status']);

        // VIP has 5:30 PM - 8:00 PM session (30 pax)
        $vipSlots = $this->getJson('/api/booking/dates/2026-10-09/slots?is_vip=1')->json();
        $this->assertCount(1, $vipSlots);
        $this->assertEquals('17:30', $vipSlots[0]['start_time']);
        $this->assertEquals('20:00', $vipSlots[0]['end_time']);
        $this->assertEquals(30, $vipSlots[0]['capacity']);
    }

    /** @test */
    public function it_validates_october_13_glam_reader_vip_slots_and_october_14_public_workshop_slots()
    {
        // Oct 13 VIP API sees 11:00 AM (5 pax) and 12:00 PM (5 pax) slots
        $vipSlots = $this->getJson('/api/booking/dates/2026-10-13/slots?is_vip=1')->json();
        $this->assertCount(2, $vipSlots);
        $this->assertEquals('11:00', $vipSlots[0]['start_time']);
        $this->assertEquals(5, $vipSlots[0]['capacity']);
        $this->assertEquals('12:00', $vipSlots[1]['start_time']);
        $this->assertEquals(5, $vipSlots[1]['capacity']);

        // Oct 14 Public API sees 12:00 PM (12:00) and 6:00 PM (18:00) slots
        $publicSlots = $this->getJson('/api/booking/dates/2026-10-14/slots')->json();
        $this->assertCount(2, $publicSlots);
        $this->assertEquals('12:00', $publicSlots[0]['start_time']);
        $this->assertEquals('18:00', $publicSlots[1]['start_time']);
    }

    /** @test */
    public function it_tests_vip_booking_creation_and_overbooking_prevention()
    {
        $dateObj = BookingDate::where('date', '2026-10-09')->first();
        $slot = $dateObj->slots()->first(); // Ferhat session (30 pax)

        // 1. Admin books 30 pax -> SUCCEEDS
        $res = $this->actingAs($this->admin)->post('/admin/vip', [
            'vip_name' => 'PRIVATE SHOPPING SESSION: FERHAT',
            'booking_date_id' => $dateObj->id,
            'booking_slot_id' => $slot->id,
            'pax' => 30,
        ]);
        $res->assertSessionHasNoErrors();
        $this->assertEquals(30, $slot->fresh()->booked_count);

        // 2. Admin attempts another 1 pax booking when slot is at capacity (30/30) -> FAILS
        $overRes = $this->actingAs($this->admin)->post('/admin/vip', [
            'vip_name' => 'PRIVATE SHOPPING SESSION: FERHAT',
            'booking_date_id' => $dateObj->id,
            'booking_slot_id' => $slot->id,
            'pax' => 1,
        ]);
        $overRes->assertSessionHasErrors();
        $this->assertEquals(30, $slot->fresh()->booked_count);
    }

    /** @test */
    public function it_tests_public_booking_creation_and_capacity_decrement()
    {
        $client = User::factory()->create();
        $client->assignRole('client');

        $oct2Slots = $this->getJson('/api/booking/dates/2026-10-02/slots')->json();
        $slotId = $oct2Slots[0]['id']; // 12pm slot (6 pax)

        $res = $this->actingAs($client)->postJson('/reservation-create', [
            'date' => '2026-10-02',
            'slot_id' => $slotId,
        ]);

        $res->assertStatus(201);
        $res->assertJsonPath('success', true);

        $slot = BookingSlot::find($slotId);
        $this->assertEquals(1, $slot->booked_count);
        $this->assertEquals(5, $slot->capacity - $slot->booked_count);
    }
}
