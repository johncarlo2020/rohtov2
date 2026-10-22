<!DOCTYPE html>
<html lang="en">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Masterclass-Workshop</title></head>
<body style="margin:0;padding:0;background:#ffffff;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="padding:20px 0;">
        <tr><td align="center">
            <table role="presentation" width="600" cellspacing="0" cellpadding="0" style="width:100%;max-width:600px;background:#f2efe9;color:#111;text-align:center;font-family:'Courier New',Courier,monospace;">
                <tr><td style="padding:55px 20px 50px;">
                    <div style="font-family:Georgia,'Times New Roman',serif;font-size:34px;font-weight:bold;">Maison Margiela</div>
                    <div style="font-family:Georgia,'Times New Roman',serif;font-size:15px;letter-spacing:6px;">PARIS</div>
                </td></tr>
                <tr><td style="padding:12px 25px 25px;border-top:1px solid #faf8f5;">
                    <h1 style="font-size:18px;margin:0 0 30px;font-weight:bold;">YOUR VISIT IS CONFIRMED</h1>
                    <p style="font-size:12px;line-height:1.5;letter-spacing:1px;margin:0 0 14px;">Hi {{ explode(' ', $booking->customer_name)[0] }}, your slot is reserved.</p>
                    <p style="font-size:12px;line-height:1.5;letter-spacing:1px;margin:0 0 28px;">We look forward to welcoming you<br>to the House of Memories.</p>
                    <p style="font-size:12px;line-height:1.6;letter-spacing:1px;margin:0 0 12px;"><strong>Date:</strong><br>{{ $booking->bookingDate->date->format('l, jS F Y') }}</p>
                    <p style="font-size:12px;line-height:1.6;letter-spacing:1px;margin:0 0 12px;"><strong>Time:</strong><br>{{ $time }}</p>
                    <p style="font-size:12px;line-height:1.6;letter-spacing:1px;margin:0 0 18px;"><strong>Venue:</strong><br>{{ $booking->venue }}</p>
                    <table role="presentation" width="85%" align="center" cellspacing="0" cellpadding="0"><tr><td style="border-top:2px solid #b5b3ae;padding:26px 0 20px;">
                        <p style="font-size:12px;letter-spacing:1px;margin:0 0 26px;">Please present this QR code upon arrival</p>
                        <img src="{{ $qrCodeUrl }}" width="130" height="130" alt="Booking QR code: {{ $booking->reference_no }}" style="display:block;margin:0 auto;border:5px solid #fff;">
                       
                    </td></tr><tr><td style="border-top:1px solid #b5b3ae;padding-top:26px;">
                        <p style="font-size:12px;line-height:1.5;letter-spacing:.5px;margin:0 0 28px;">We look forward to welcoming you to Maison Margiela Fragrances House of Memories, where scents evoke moments, emotions and memories.</p>
                        <p style="font-size:12px;letter-spacing:1px;margin:0;">See you at the House of Memories.</p>
                    </td></tr></table>
                </td></tr>
            </table>
        </td></tr>
    </table>
</body>
</html>
