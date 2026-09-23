<x-app-layout>
    <main class="station-journey {{ $station->is_mandatory ? '' : 'is-reward' }}" id="station-journey"
        data-station-id="{{ $station->id }}" data-scan-url="{{ route('process_qr_code') }}"
        data-completed="{{ $stationDone ? 'true' : 'false' }}">
        <header class="station-journey-header">
            <a href="{{ route('map') }}" class="station-back" aria-label="Back to map"><i class="fa-chevron-left fa-solid"
                    aria-hidden="true"></i></a>
            <img src="{{ asset('files/main/logo-space.svg') }}" alt="" aria-hidden="true" />
        </header>
        <section class="station-journey-details" aria-labelledby="station-title">
            <span class="station-journey-number">{{ $station->id }}</span>
            <h1 id="station-title">{{ $station->name }}</h1>
            <img class="station-journey-art" src="{{ asset('files/station/' . $station->id . '.webp') }}"
                alt="{{ $station->name }} event booth" width="145" height="145" />
            <div class="station-journey-instructions" @if ($stationDone) hidden @endif>
                <p>Proceed to</p>
                <p><strong>{{ $station->name }}</strong></p>
                <p>to begin your journey.</p>
                @if ((int) $station->id === 5)
                    <p class="station-journey-note">Redemption of Zus Coffee &amp; Krispy Kreme<br>( First 500 per day )
                    </p>
                @endif
            </div>
        </section>
        <section class="station-camera" hidden aria-label="Station QR camera">
            <div id="station-reader"></div>
        </section>
        <footer class="station-journey-footer">
            <div id="station-start" @if ($stationDone) hidden @endif>
                <button type="button" class="station-scan-button" aria-label="Scan station QR code"><i
                        class="fa-solid fa-camera" aria-hidden="true"></i></button>
                <p>Scan the QR code to proceed</p>
            </div>
            <p id="station-camera-hint" hidden>Find the QR code &amp; scan to proceed</p>
            <div id="station-completed" @unless ($stationDone) hidden @endunless>
                <p>Checked-In Successful</p>
                <a class="station-action" href="{{ route('map') }}">BACK</a>
            </div>
        </footer>
        <dialog id="dialog" class="station-result" aria-labelledby="station-result-message">
            <span class="station-result-icon" aria-hidden="true">i</span>
            <p id="station-result-message" role="status"></p>
            <button type="button" class="station-action" id="station-result-done">DONE</button>
        </dialog>
    </main>

    <script>
        window.mapRouteUrl = "{{ route('map') }}";
    </script>

    @vite('resources/js/station.js')
</x-app-layout>
