<x-guest-layout>
    <style>
        .rsvp-pass-page { min-height: 100vh; background: #f2efe9; color: #111; font-family: 'Courier Prime', monospace; padding: 35px 20px 60px; text-align: center; }
        .rsvp-pass { max-width: 360px; margin: auto; padding: 0 0 20px; background: #f2efe9; }
        .rsvp-pass-logo { display: block; width: 220px; max-width: 80%; margin: 0 auto 48px; }
        .rsvp-pass h1 { font-family: inherit; font-size: 27px; line-height: 1.25; font-weight: 400; margin-bottom: 34px; }
        .rsvp-pass h2 { font-family: inherit; font-size: 18px; font-weight: 700; margin-bottom: 15px; }
        .rsvp-pass p { font-size: 12px; line-height: 1.6; letter-spacing: .5px; margin-bottom: 20px; }
        #rsvp-qr { width: 130px; margin: 35px auto 25px; padding: 5px; background: #fff; }
        #rsvp-qr img, #rsvp-qr canvas { max-width: 100%; }
        .rsvp-pass-details { text-align: left; font-size: 11px; line-height: 1.7; margin: 0 auto; max-width: 310px; }
        .rsvp-save { display: block; width: 230px; margin: 22px auto 0; padding: 12px; border: 0; border-radius: 6px; background: #000; color: #fff; font-family: inherit; font-weight: 700; }
        .rsvp-save:disabled { opacity: .5; }
    </style>
    <div class="rsvp-pass-page">
        @if (session('email_warning'))
            <p role="alert">{{ session('email_warning') }}</p>
        @endif
        <div class="rsvp-pass" id="rsvp-pass">
            <img class="rsvp-pass-logo" src="{{ asset('images/brand/logo.webp') }}" alt="Maison Margiela Paris">
            <h1>HOUSE OF MEMORIES<br>MASTERCLASS</h1>
            <h2>YOUR VISIT IS CONFIRMED</h2>
            <p>Hi {{ explode(' ', $booking->customer_name)[0] }}, your slot is reserved.</p>
            <p>We look forward to welcoming you<br>to the House of Memories.</p>
            <p>Save your pass and present this QR code<br>upon arrival.</p>
            <div id="rsvp-qr" aria-label="Booking QR code"></div>
            <h2>{{ strtoupper($booking->customer_name) }}</h2>
            <div class="rsvp-pass-details">
                <div><strong>Date:</strong> {{ $booking->bookingDate->date->format('l, jS F Y') }}</div>
                <div><strong>Time:</strong> {{ $time }}</div>
                <div><strong>Venue:</strong> {{ $booking->venue }}</div>
                <div><strong>Reference:</strong> {{ $booking->reference_no }}</div>
            </div>
        </div>
        <button class="rsvp-save" id="rsvp-save" type="button">SAVE MY PASS</button>
        <p id="rsvp-save-error" role="alert"></p>
    </div>
    @push('scripts')
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                const reference = @json($booking->reference_no);
                new QRCode(document.getElementById('rsvp-qr'), { text: reference, width: 120, height: 120 });
                document.getElementById('rsvp-save').addEventListener('click', async (event) => {
                    const button = event.currentTarget;
                    button.disabled = true;
                    try {
                        await document.fonts.ready;
                        const canvas = await html2canvas(document.getElementById('rsvp-pass'), { backgroundColor: '#f2efe9', scale: 2 });
                        const link = document.createElement('a');
                        link.download = reference + '.png';
                        link.href = canvas.toDataURL('image/png');
                        link.click();
                    } catch (error) {
                        document.getElementById('rsvp-save-error').textContent = 'Unable to save your pass. Please try again.';
                    } finally {
                        button.disabled = false;
                    }
                });
            });
        </script>
    @endpush
</x-guest-layout>
