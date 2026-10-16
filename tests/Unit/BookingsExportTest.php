<?php

namespace Tests\Unit;

use App\Exports\BookingsExport;
use App\Models\Booking;
use App\Models\BookingDate;
use App\Models\BookingSlot;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Tests\TestCase;

class BookingsExportTest extends TestCase
{
    public function test_it_maps_booking_details_into_export_columns(): void
    {
        $booking = new Booking();
        $booking->forceFill([
            'reference_no' => 'REF-123',
            'customer_name' => 'A Customer',
            'customer_email' => 'customer@example.com',
            'customer_phone' => '0123456789',
            'venue' => 'Main Venue',
            'is_vip' => true,
            'vip_name' => 'VIP Guest',
            'pax' => 2,
            'status' => 'confirmed',
            'attended_at' => null,
            'created_at' => '2026-01-01 10:00:00',
        ]);
        $booking->setRelation('bookingDate', (new BookingDate())->forceFill(['date' => '2099-10-16']));
        $booking->setRelation('bookingSlot', (new BookingSlot())->forceFill([
            'start_time' => '11:00:00',
            'end_time' => '12:00:00',
        ]));

        $row = (new BookingsExport())->map($booking);

        $this->assertSame([
            'REF-123',
            'A Customer',
            'customer@example.com',
            '0123456789',
            '2099-10-16',
            '11:00 AM - 12:00 PM',
            'Main Venue',
            'VIP',
            'VIP Guest',
            2,
            'Not Yet Attended',
            null,
            '2026-01-01 10:00:00',
        ], $row);
    }

    public function test_it_binds_untrusted_strings_as_text_in_spreadsheets(): void
    {
        $cell = (new Spreadsheet())->getActiveSheet()->getCell('A1');

        (new BookingsExport())->bindValue($cell, '=1+1');

        $this->assertSame(DataType::TYPE_STRING, $cell->getDataType());
        $this->assertSame('=1+1', $cell->getValue());
    }
}
