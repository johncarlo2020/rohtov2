@extends('layouts.admin')

@section('content')
    <div class="station-qr-heading">
        <div><h2>Ready for check-in</h2><p>Display each QR code at its station or the Card Sales Booth for participants to scan.</p></div>
        <button type="button" class="btn btn-primary" onclick="window.print()">Print QR codes</button>
    </div>
    <p id="station-qr-error" class="alert alert-danger" role="alert" hidden>QR codes could not load. Refresh this page before printing or downloading.</p>
    <div class="station-qr-grid">
        <article class="card station-qr-card">
            <div class="station-qr-card-header">
                <span class="admin-station-marker"><i class="fa-solid fa-credit-card" aria-hidden="true"></i></span>
                <span class="station-qr-type">Card application</span>
            </div>
            <img class="station-qr-logo" src="{{ asset('files/main/logo.webp') }}" alt="Maybank" />
            <h3>Card Sales Booth</h3>
            <div class="station-qr-image" data-qr-url="{{ config('card_application.qr_code') ?: route('card-application.booth') }}" role="img" aria-label="Card application activation QR"></div>
            <button type="button" class="btn btn-primary station-qr-download d-print-none" data-qr-filename="card-sales-booth-qr.png" disabled>Download PNG</button>
            <p class="station-qr-instruction">Scan to activate card access</p>
            <p class="station-qr-url">Use “Click here to apply!” on the map to unlock more rewards.</p>
        </article>
        @forelse ($stations as $station)
            <article class="card station-qr-card">
                <div class="station-qr-card-header">
                    <span class="admin-station-marker">{{ $station->id }}</span>
                    <span class="station-qr-type">{{ $station->is_mandatory ? 'Mandatory' : 'Card applicants' }}</span>
                </div>
                <img class="station-qr-logo" src="{{ asset('files/main/logo.webp') }}" alt="Maybank" />
                <h3>{{ $station->name }}</h3>
                <div class="station-qr-image" data-qr-url="{{ route('station', $station) }}" role="img" aria-label="Check-in QR for {{ $station->name }}"></div>
                <button type="button" class="btn btn-primary station-qr-download d-print-none" data-qr-filename="station-{{ $station->id }}-{{ \Illuminate\Support\Str::slug($station->name) }}-qr.png" disabled>Download PNG</button>
                <p class="station-qr-instruction">Scan to check in</p>
                <a class="station-qr-url" href="{{ route('station', $station) }}">{{ route('station', $station) }}</a>
            </article>
        @empty
            <p>No stations available. Seed the stations to generate their QR codes.</p>
        @endforelse
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/gh/davidshimjs/qrcodejs/qrcode.min.js"></script>
    <script>
        try {
            document.querySelectorAll('[data-qr-url]').forEach(element => {
                new QRCode(element, {
                    text: element.dataset.qrUrl,
                    width: 220,
                    height: 220,
                    colorDark: '#000000',
                    colorLight: '#ffffff',
                    correctLevel: QRCode.CorrectLevel.H,
                });
                const button = element.closest('.station-qr-card').querySelector('.station-qr-download');
                const source = element.querySelector('canvas');
                if (!source) return;
                button.disabled = false;
                button.addEventListener('click', () => {
                    try {
                        const canvas = document.createElement('canvas');
                        canvas.width = canvas.height = 1100;
                        const context = canvas.getContext('2d');
                        context.fillStyle = '#ffffff';
                        context.fillRect(0, 0, canvas.width, canvas.height);
                        context.imageSmoothingEnabled = false;
                        context.drawImage(source, 110, 110, 880, 880);
                        const link = document.createElement('a');
                        link.download = button.dataset.qrFilename;
                        link.href = canvas.toDataURL('image/png');
                        document.body.appendChild(link);
                        link.click();
                        link.remove();
                    } catch (error) {
                        const message = document.getElementById('station-qr-error');
                        message.textContent = 'QR download failed. Please try again or use Print QR codes.';
                        message.hidden = false;
                    }
                });
            });
        } catch (error) {
            document.getElementById('station-qr-error').hidden = false;
        }
    </script>
@endpush
