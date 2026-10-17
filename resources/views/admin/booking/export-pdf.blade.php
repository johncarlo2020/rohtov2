<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Booking Export</title>
    <style>
        body { color: #222; font: 12px Arial, sans-serif; margin: 24px; }
        h1 { font-size: 20px; margin: 0 0 4px; }
        .meta { color: #666; margin: 0 0 18px; }
        .actions { margin-bottom: 16px; }
        button { background: #1b5e20; border: 0; color: #fff; cursor: pointer; padding: 9px 14px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #aaa; padding: 5px; text-align: left; vertical-align: top; }
        th { background: #e9eee9; }
        @media print {
            @page { size: landscape; margin: 10mm; }
            body { margin: 0; }
            .actions { display: none; }
            thead { display: table-header-group; }
            tr { break-inside: avoid; }
        }
    </style>
</head>
<body>
    <div class="actions">
        <button type="button" onclick="window.print()">Print / Save as PDF</button>
    </div>
    <h1>Booking Data</h1>
    <p class="meta">Generated {{ now()->format('Y-m-d H:i') }} | {{ $bookings->count() }} bookings</p>
    <table>
        <thead>
            <tr>
                <th>Reference</th>
                <th>Customer</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Date</th>
                <th>Time Slot</th>
                <th>Venue</th>
                <th>Type</th>
                <th>VIP Name</th>
                <th>Guests</th>
                <th>Attendance</th>
                <th>Attended At</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $booking)
                <tr>
                    <td>{{ $booking->reference_no }}</td>
                    <td>{{ $booking->customer_name }}</td>
                    <td>{{ $booking->customer_email }}</td>
                    <td>{{ $booking->customer_phone }}</td>
                    <td>{{ $booking->bookingDate?->date?->format('Y-m-d') }}</td>
                    <td>{{ $booking->bookingSlot?->display_time }}</td>
                    <td>{{ $booking->venue }}</td>
                    <td>{{ $booking->is_vip ? 'VIP' : 'Public' }}</td>
                    <td>{{ $booking->vip_name }}</td>
                    <td>{{ $booking->pax }}</td>
                    <td>{{ $booking->status === 'cancelled' ? 'Cancelled' : $booking->computed_status }}</td>
                    <td>{{ $booking->attended_at?->format('Y-m-d H:i:s') }}</td>
                    <td>{{ $booking->created_at?->format('Y-m-d H:i:s') }}</td>
                </tr>
            @empty
                <tr><td colspan="13">No bookings found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <script>
        window.addEventListener('load', () => window.print());
    </script>
</body>
</html>
