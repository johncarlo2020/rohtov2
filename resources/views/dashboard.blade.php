<x-app-layout>
    @push('styles')
        <style>
            @font-face {
                font-family: 'Altone';
                src: url('{{ asset('tommy_assets/Altone-Bold.ttf') }}') format('truetype');
                font-weight: 700;
                font-display: swap;
            }

            @font-face {
                font-family: 'Energize';
                src: url('{{ asset('tommy_assets/Energize BoldItalic.otf') }}') format('opentype');
                font-weight: 700;
                font-style: italic;
                font-display: swap;
            }

            html, body {
                width: auto;
                height: auto;
                overflow: auto !important;
            }

            .tommy-dashboard {
                min-height: 100dvh;
                overflow-x: hidden;
                overflow-y: auto;
                -webkit-overflow-scrolling: touch;
                padding: 18px 10px 24px;
                background: url('{{ asset('tommy_assets/Tommy X Cadillac_background_2x.webp') }}') center / cover fixed;
                color: #050505;
                font-family: 'Altone', Arial, sans-serif;
            }

            .tommy-dashboard-header { display: flex; justify-content: center; position: relative; z-index: 3; }
            .tommy-dashboard-logo { width: min(48vw, 180px); height: auto; }
            .tommy-track-shell {
                position: relative;
                width: min(100%, 430px);
                margin: 60px auto 0;
            }
            .tommy-track-map { display: block; width: 100%; height: auto; }

            .tommy-station-list {
                position: absolute;
                inset: -2% 22% 9%;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                align-items: center;
                z-index: 2;
            }

            .tommy-station {
                width: 48%;
                display: grid;
                justify-items: center;
                color: #050505;
                text-decoration: none;
                text-align: center;
                transition: transform .18s ease;
            }

            .tommy-station:hover,
            .tommy-station:focus-visible { color: #050505; transform: scale(1.04); }
            .tommy-station-image {
                display: block;
                width: 100%;
                height: auto;
                aspect-ratio: 1 / 1;
                object-fit: contain;
                object-position: center;
            }
            .tommy-station-copy { display: grid; gap: 2px; margin-top: -1px; line-height: 1.05; }
            .tommy-station-number { font-size: clamp(15px, 2.4vw, 12px); font-family: 'Energize', Arial, sans-serif; font-weight: 700; font-style: italic; color:black; }
            .tommy-station-name { max-width: 120px; font-size: clamp(8px, 1.8vw, 9px); white-space: nowrap;font-family: 'Altone', Arial, sans-serif;  color: #000; }

            .tommy-counter {
                position: absolute;
                top: 0;
                z-index: 3;
                width: 18%;
                display: grid;
                justify-items: center;
                text-align: center;
                font-family: 'Altone', Arial, sans-serif;
                color:black;
            }

            .tommy-counter-redemption { left: 0; }
            .tommy-counter-store { right: 0; }
            .tommy-counter-image { display: block; width: 100%; height: auto; }
            .tommy-counter-value { margin-top: 3px; font-size: clamp(10px, 2.9vw, 14px); line-height: 1; color:black;}
            .tommy-counter-status {
                margin-top: 4px;
                font-size: clamp(7px, 1.8vw, 10px);
                line-height: 1.1;
                letter-spacing: .04em;
                color: #000;
                text-transform: uppercase;
                font-weight: 700;   
                font-family: 'Altone', Arial, sans-serif;
            }
            .tommy-dashboard-note { margin: 3px auto 0; text-align: center; font-size: clamp(9px, 2.4vw, 12px); font-family: 'Altone', Arial, sans-serif; color: #000; }
                .tommy-redemption-link { display: grid; justify-items: center; color: inherit; text-decoration: none; cursor: pointer; }
                .tommy-redemption-link:hover { color: inherit; }
            .tommy-modal { font-family: 'Altone', Arial, sans-serif; }
                .tommy-redemption-modal .modal-dialog { width: min(88vw, 330px); margin: auto; }
                .tommy-redemption-modal .modal-content {
                    min-height: 190px;
                    overflow: hidden;
                    border: 0;
                    border-radius: 9px;
                    background: rgba(248, 251, 252, .9);
                    box-shadow: 0 10px 30px rgba(0, 0, 0, .18);
                    backdrop-filter: blur(14px);
                }
                .tommy-redemption-modal .modal-body { padding: 25px 24px 28px; }
                .tommy-redemption-modal .modal-title {
                    margin: 7px 0 21px;
                    color: #050505;
                    font-size: 15px;
                    font-weight: 700;
                    letter-spacing: .01em;
                    font-family: 'Altone', Arial, sans-serif;
                }
                .tommy-redemption-modal .modal-message {
                    margin: 0 0 22px;
                    color: #111;
                    font-family: Arial, sans-serif;
                    font-size: 12px;
                    line-height: 1.45;
                    font-family: 'Altone', Arial, sans-serif;
                }
                .tommy-redemption-modal .tommy-modal-close {
                    display: grid;
                    place-items: center;
                    width: 15px;
                    height: 15px;
                    margin: 0 0 0 auto;
                    padding: 0;
                    border: 0;
                    border-radius: 50%;
                    background: #050505;
                    color: #fff;
                    font-family: Arial, sans-serif;
                    font-size: 13px;
                    line-height: 1;
                }
                .tommy-redemption-modal .tommy-modal-done {
                    width: 128px;
                    min-height: 28px;
                    padding: 0;
                    border: 0;
                    border-radius: 999px;
                    background: #000;
                    color: #fff;
                    font-family: 'Altone', Arial, sans-serif;
                    font-size: 9px;
                }

            @media (min-width: 600px) {
                .tommy-dashboard { padding-top: 24px; }
                .tommy-track-shell { width: min(100%, 500px); }
            }
        </style>
    @endpush

    <main class="tommy-dashboard">
        <header class="tommy-dashboard-header">
            <img class="tommy-dashboard-logo" src="{{ asset('tommy_assets/THXCDL_logo_horizontal_2x.webp') }}" alt="Cadillac Formula 1 Team and Tommy Hilfiger">
        </header>

        <section class="tommy-track-shell" aria-label="Tommy Hilfiger and Cadillac pit stop track">
            <img class="tommy-track-map" src="{{ asset('tommy_assets/THXC_map_2x.webp') }}" alt="Pit stop track map" loading="lazy" decoding="async">

            <div class="tommy-counter tommy-counter-redemption">
                    <a
                        class="tommy-redemption-link"
                        href="{{ $stationDone >= $totalStations ? route('bonus.stamp', ['bonus' => 'redemption']) : '#' }}"
                        @if ($stationDone < $totalStations)
                            onclick="event.preventDefault(); showRedemptionModal();"
                        @endif
                    >
                        <img class="tommy-counter-image" src="{{ asset($stationDone >= $totalStations ? 'tommy_assets/REDEMPTION_COMPLETE_2x.webp' : 'tommy_assets/REDEMPTION_2x.webp') }}" alt="Redemption" loading="lazy" decoding="async">
                    </a>
                    <span class="tommy-counter-status">Complete</span>

                <span class="tommy-counter-value">{{ $stationDone }}/{{ $totalStations }}</span>
               
             
            </div>

            <div class="tommy-counter tommy-counter-store">
                <a
                    class="tommy-redemption-link"
                    href="{{ route('bonus.stamp', ['bonus' => 'in-store']) }}"
                >
                    <img class="tommy-counter-image" src="{{ asset($inStoreStamped ? 'tommy_assets/IN-STORE-COMPLETE_2x.webp' : 'tommy_assets/IN-STORE_2x.webp') }}" alt="In-store" loading="lazy" decoding="async">
                </a>
                    <span class="tommy-counter-status">Complete</span>

                <span class="tommy-counter-value">{{ $inStoreStamped ? '1/1' : '0/1' }}</span>
            </div>

            <div class="tommy-station-list">
                @foreach ($stations as $station)
                    @php
                        $stationAsset = $station->status
                            ? 'tommy_assets/COMPLETE_2x.webp'
                            : 'tommy_assets/TMXC-Pitstop ' . $station->id . '_2x.webp';
                        $stationUrl = route('station', ['station' => $station->id]);
                    @endphp

                    <a class="tommy-station" href="{{ $stationUrl }}"
                        @if (!$station->status && $station->id === 3 && !$canAccessStation3)
                            onclick="event.preventDefault(); showTommyAccessMessage();"
                        @endif
                    >
                        <img class="tommy-station-image" src="{{ asset($stationAsset) }}" alt="Pit stop {{ $station->id }}: {{ $station->name }}" loading="lazy" decoding="async">
                        <span class="tommy-station-copy">
                            <span class="tommy-station-number">PITSTOP {{ $station->id }}</span>
                            <span class="tommy-station-name">{{ strtoupper($station->name) }}</span>
                        </span>
                    </a>
                @endforeach
            </div>
        </section>

        <p class="tommy-dashboard-note">Complete the challenge and light up the icon.</p>

        <div class="modal fade custom-modal tommy-modal" id="notAllowedModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered w-75 m-auto">
                <div class="modal-content card">
                    <div class="modal-body text-center">
                        <p class="message text-dark">Complete Pitstop 1 and Pitstop 2 to unlock Pitstop 3.</p>
                        <button type="button" class="custom-btn custom-btn-primary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade tommy-redemption-modal" id="redemptionModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered w-75 m-auto">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <button type="button" class="tommy-modal-close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
                        <h2 class="modal-title">REDEMPTION</h2>
                        <p class="modal-message">Complete all 4 pit stops<br>to claim your exclusive gift.</p>
                        <button type="button" class="tommy-modal-done" data-bs-dismiss="modal">DONE</button>
                    </div>
                </div>
            </div>
        </div>

    </main>

    @push('scripts')
        <script>
            function showTommyAccessMessage() {
                const modal = document.getElementById('notAllowedModal');
                if (modal && window.bootstrap) new bootstrap.Modal(modal).show();
            }

                function showRedemptionModal() {
                    const modal = document.getElementById('redemptionModal');
                    if (modal && window.bootstrap) new bootstrap.Modal(modal).show();
                }

        </script>
    @endpush
</x-app-layout>
