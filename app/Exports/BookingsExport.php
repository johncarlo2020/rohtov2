<?php

namespace App\Exports;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\DefaultValueBinder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class BookingsExport extends DefaultValueBinder implements FromQuery, ShouldAutoSize, WithCustomValueBinder, WithHeadings, WithMapping
{
    public function bindValue(Cell $cell, $value): bool
    {
        if (is_string($value)) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }

        return parent::bindValue($cell, $value);
    }

    public function query(): Builder
    {
        return Booking::query()
            ->with(['bookingDate', 'bookingSlot'])
            ->orderByDesc('id');
    }

    public function headings(): array
    {
        return [
            'Reference No',
            'Customer Name',
            'Email',
            'Phone',
            'Booking Date',
            'Time Slot',
            'Venue',
            'Booking Type',
            'VIP Name',
            'Guests',
            'Attendance',
            'Attended At',
            'Created At',
        ];
    }

    public function map($booking): array
    {
        $attendance = $booking->status === 'cancelled'
            ? 'Cancelled'
            : $booking->computed_status;

        return [
            $booking->reference_no,
            $booking->customer_name,
            $booking->customer_email,
            $booking->customer_phone,
            $booking->bookingDate?->date?->format('Y-m-d'),
            $booking->bookingSlot?->display_time,
            $booking->venue,
            $booking->is_vip ? 'VIP' : 'Public',
            $booking->vip_name,
            $booking->pax,
            $attendance,
            $booking->attended_at?->format('Y-m-d H:i:s'),
            $booking->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
