<x-guest-layout>
    <style>
        .rsvp-pass-page { min-height: 100dvh; background: #f2efe9; color: #111; font-family: 'Courier Prime', monospace; padding: 35px 31px 60px; text-align: center; }
        .rsvp-pass { max-width: 340px; margin: auto; padding: 0 0 20px; background: #f2efe9; }
        .rsvp-pass-logo { display: block; width: 200px; max-width: 85%; margin: 0 auto 46px; }
        .rsvp-pass h1 { font-family: inherit; font-size: 23px; line-height: 1.3; letter-spacing: .3px; font-weight: 400; margin: 0 0 24px; }
        .rsvp-pass h2 { font-family: inherit; font-size: 12px; line-height: 1.4; letter-spacing: .4px; font-weight: 700; margin: 0 0 14px; }
        .rsvp-pass p { font-size: 10px; line-height: 1.6; letter-spacing: .2px; margin-bottom: 14px; }
        #rsvp-qr img, #rsvp-qr canvas { max-width: 100%; }
        #rsvp-qr { width: 130px; margin: 25px auto 20px; padding: 5px; background: #fff; }
        .rsvp-pass-details { text-align: left; font-size: 10px; line-height: 1.6; letter-spacing: .1px; margin: 0 auto; max-width: 310px; }
        .rsvp-save { display: block; width: 185px; margin: 20px auto 0; padding: 8px; min-height: 36px; border: 0; border-radius: 6px; background: #000; color: #fff; font-family: inherit; font-size: 12px; font-weight: 700; }
        .rsvp-save:disabled { opacity: .5; }
        @media (min-width: 768px) {
            .rsvp-pass-page { min-height: 100dvh; padding: 35px 40px 50px; }
            .rsvp-pass { max-width: 420px; padding-bottom: 10px; }
            .rsvp-pass-logo { width: 200px; margin-bottom: 46px; }
            .rsvp-pass h1 { font-size: 28px; margin-bottom: 22px; }
            .rsvp-pass h2 { font-size: 13px; margin-bottom: 14px; }
            .rsvp-pass p { font-size: 11px; line-height: 1.6; margin-bottom: 14px; }
            #rsvp-qr { margin-top: 24px; margin-bottom: 20px; }
            .rsvp-pass-details { max-width: 380px; font-size: 11px; line-height: 1.7; overflow-wrap: anywhere; }
            .rsvp-save { width: 100%; max-width: 420px; min-height: 40px; margin-top: 22px; font-size: 13px; }
        }
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
            <p>Please check your email for your confirmation<br>details and present this QR code upon arrival</p>
            <div id="rsvp-qr" aria-label="Booking QR code"></div>
            <h2>{{ strtoupper($booking->customer_name) }}</h2>
            <div class="rsvp-pass-details">
                <div><strong>Date:</strong> {{ $booking->bookingDate->date->format('l, jS F Y') }}</div>
                <div><strong>Time:</strong> {{ $time }}</div>
                <div><strong>Venue:</strong> {{ $booking->venue }}</div>

            </div>
        </div>
        <button class="rsvp-save" id="rsvp-save" type="button">SAVE MY PASS</button>
        <p id="rsvp-save-error" role="alert"></p>
    </div>
    @push('scripts')
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                const reference = @json($booking->reference_no);
                const qrContainer = document.getElementById('rsvp-qr');
                new QRCode(qrContainer, { text: reference, width: 120, height: 120 });
                document.getElementById('rsvp-save').addEventListener('click', async (event) => {
                    const button = event.currentTarget;
                    button.disabled = true;
                    try {
                        const qrCanvas = qrContainer.querySelector('canvas');
                        const qrImage = qrContainer.querySelector('img');
                        if (!qrCanvas && !qrImage) throw new Error('QR image is unavailable.');
                        if (qrImage && !qrCanvas && !qrImage.complete) {
                            await new Promise((resolve, reject) => {
                                qrImage.addEventListener('load', resolve, { once: true });
                                qrImage.addEventListener('error', reject, { once: true });
                            });
                        }
                        const qrSource = qrCanvas || qrImage;
                        const logo = new Image();
                        logo.src = @json(asset('images/brand/logo.webp'));
                        await new Promise((resolve) => { logo.onload = resolve; logo.onerror = resolve; });

                        const details = [
                            ['Date: ', @json($booking->bookingDate->date->format('l, jS F Y'))],
                            ['Time: ', @json($time)],
                            ['Venue: ', @json($booking->venue)],
                        ];
                        if (document.fonts) await Promise.all([document.fonts.load('bold 11px "Courier Prime"'), document.fonts.load('11px "Courier Prime"')]).catch(() => {});
                        const scale = 2, width = 340, height = 470, font = "'Courier Prime', 'Courier New', monospace";
                        const card = document.createElement('canvas');
                        card.width = width * scale;
                        card.height = height * scale;
                        const ctx = card.getContext('2d');
                        ctx.scale(scale, scale);
                        ctx.fillStyle = '#f2efe9';
                        ctx.fillRect(0, 0, width, height);
                        ctx.fillStyle = '#111';
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'alphabetic';

                        let y = 30;
                        if (logo.naturalWidth) {
                            const lw = 200, lh = lw * logo.naturalHeight / logo.naturalWidth;
                            ctx.drawImage(logo, (width - lw) / 2, y, lw, lh);
                            y += lh + 24;
                        } else {
                            ctx.font = "24px 'Times New Roman', serif";
                            ctx.fillText('Maison Margiela', width / 2, y + 24);
                            y += 50;
                        }

                        const qrSize = 150;
                        ctx.fillStyle = '#fff';
                        ctx.fillRect((width - qrSize - 12) / 2, y, qrSize + 12, qrSize + 12);
                        ctx.imageSmoothingEnabled = false;
                        ctx.drawImage(qrSource, (width - qrSize) / 2, y + 6, qrSize, qrSize);
                        y += qrSize + 12 + 30;

                        ctx.fillStyle = '#111';
                        ctx.font = 'bold 16px ' + font;
                        ctx.fillText(@json(strtoupper($booking->customer_name)), width / 2, y);
                        y += 28;

                        ctx.textAlign = 'left';
                        const maxWidth = width - 60;
                        details.forEach(([label, value]) => {
                            ctx.font = 'bold 11px ' + font;
                            const labelWidth = ctx.measureText(label).width;
                            ctx.font = '11px ' + font;
                            const words = String(value).split(' ');
                            let line = '', first = true;
                            const flush = () => {
                                if (first) { ctx.font = 'bold 11px ' + font; ctx.fillText(label, 30, y); ctx.font = '11px ' + font; }
                                ctx.fillText(line, 30 + labelWidth, y);
                                y += 17; first = false;
                            };
                            words.forEach((word) => {
                                const test = line ? line + ' ' + word : word;
                                if (line && ctx.measureText(test).width > maxWidth - labelWidth) { flush(); line = word; } else { line = test; }
                            });
                            flush();
                        });

                        const link = document.createElement('a');
                        link.download = reference + '.png';
                        link.href = card.toDataURL('image/png');
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
