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

            .tommy-station-page {
                min-height: 100svh;
                display: flex;
                justify-content: center;
                overflow: hidden;
                padding: 18px 18px 28px;
                background: url('{{ asset('tommy_assets/Tommy X Cadillac_background_2x.webp') }}') center / cover fixed;
                color: #050505;
                font-family: 'Altone', Arial, sans-serif;
            }

            .tommy-station-page.stamping-active {
                touch-action: none;
            }

            .tommy-station-shell {
                width: min(100%, 430px);
                min-height: calc(100svh - 46px);
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .tommy-station-logo {
                width: min(52vw, 190px);
                height: auto;
                margin-bottom: clamp(22px, 6vh, 48px);
            }

            .tommy-station-state {
                width: 100%;
                flex: 1;
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            .tommy-station-title {
                margin: 0 0 clamp(28px, 6vh, 52px);
                color: #000;
                font-family: 'Energize', Arial, sans-serif;
                font-size: clamp(30px, 9vw, 48px);
                font-style: italic;
                font-weight: 700;
                line-height: .95;
                text-transform: uppercase;
            }

            .tommy-station-image-button {
                width: min(48vw, 210px);
                aspect-ratio: 1;
                padding: 0;
                border: 0;
                background: transparent;
                cursor: pointer;
                touch-action: none;
            }

            .tommy-station-image-button img {
                display: block;
                width: 100%;
                height: 100%;
                object-fit: contain;
                pointer-events: none;
                user-select: none;
            }

            .tommy-station-name {
                margin: clamp(28px, 5vh, 46px) 0 0;
                color: #000;
                font-family: 'Altone', Arial, sans-serif;
                font-size: clamp(20px, 5.8vw, 30px);
                font-weight: 700;
                line-height: 1.05;
                text-transform: uppercase;
            }

            .tommy-station-description {
                max-width: 300px;
                margin: 16px 0 0;
                font-family: Arial, sans-serif;
                font-size: clamp(14px, 3.8vw, 18px);
                line-height: 1.3;
            }

            .tommy-station-hint {
                max-width: 300px;
                margin: 0 auto;
                font-family: Arial, sans-serif;
                font-size: clamp(17px, 4vw, 21px);
                line-height: 1.25;
            }

            .tommy-station-action {
                min-width: 150px;
                min-height: 40px;
                margin-top: auto;
                padding: 0 28px;
                border: 0;
                border-radius: 999px;
                background: #000;
                color: #fff;
                font-family: Arial, sans-serif;
                font-size: 11px;
                text-transform: uppercase;
                cursor: pointer;
            }

            .tommy-station-action:disabled {
                opacity: .45;
                cursor: default;
            }

            .tommy-station-complete-title {
                max-width: 300px;
                margin-bottom: clamp(34px, 7vh, 62px);
            }

            .tommy-station-complete-image {
                width: min(58vw, 250px);
                height: auto;
            }

            .tommy-station-complete-action { margin-top: auto; }

            .d-none { display: none !important; }
        </style>
    @endpush

    @php
        $stationAsset = 'tommy_assets/TMXC-Pitstop ' . $station->id . '_2x.webp';
    @endphp

    <main class="tommy-station-page">
        <section class="tommy-station-shell" aria-live="polite">
            <img class="tommy-station-logo" src="{{ asset('tommy_assets/THXCDL_logo_horizontal_2x.webp') }}" alt="Cadillac Formula 1 Team and Tommy Hilfiger">

            <div id="stationIntro" class="tommy-station-state {{ $user ? 'd-none' : '' }}">
                <h1 class="tommy-station-title">Pitstop {{ $station->id }}</h1>
                <button id="startStamping" class="tommy-station-image-button" type="button" aria-label="Start Pitstop {{ $station->id }}">
                    <img src="{{ asset($stationAsset) }}" alt="Pitstop {{ $station->id }}">
                </button>
                <h2 class="tommy-station-name">{{ $station->name }}</h2>
                <p class="tommy-station-description">
                    @switch($station->id)
                        @case(1) Pick Team Pérez or Team Bottas.<br>Hit the button as close to 30 seconds as possible. @break
                        @case(2) Get ready to put your skills to the test. @break
                        @case(3) Take the wheel and show your racing instinct. @break
                        @default Capture the moment and complete the pitstop. @break
                    @endswitch
                </p>
                <button id="startButton" class="tommy-station-action" type="button">Start</button>
            </div>

            <div id="stationWaiting" class="tommy-station-state d-none">
                <p class="tommy-station-hint">Earn a digital passport<br>stamp upon completion.</p>
                <button id="stampTarget" class="tommy-station-image-button" type="button" aria-label="Touch the station image to stamp">
                    <img src="{{ asset($stationAsset) }}" alt="Touch the station image to stamp">
                </button>

            </div>

            <div id="stationComplete" class="tommy-station-state {{ $user ? '' : 'd-none' }}">
                <h1 class="tommy-station-title tommy-station-complete-title">Stamp<br>Collected</h1>
                <img class="tommy-station-complete-image" src="{{ asset('tommy_assets/COMPLETE_2x.webp') }}" alt="Stamp collected">
                <button id="doneButton" class="tommy-station-action tommy-station-complete-action" type="button">Done</button>
            </div>
        </section>
    </main>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const intro = document.getElementById('stationIntro');
                const waiting = document.getElementById('stationWaiting');
                const complete = document.getElementById('stationComplete');
                const stationPage = document.querySelector('.tommy-station-page');
                const startButton = document.getElementById('startButton');
                const fingerStatus = document.getElementById('fingerStatus');
                const doneButton = document.getElementById('doneButton');
                const activePointers = new Set();
                const requiredTouches = @json($requiredTouches ?? 1);
                let submitting = false;

                startButton.addEventListener('click', function () {
                    intro.classList.add('d-none');
                    waiting.classList.remove('d-none');
                    stationPage.classList.add('stamping-active');
                });

                function updateFingerStatus() {
                    const count = activePointers.size;
                    if (fingerStatus) {
                        fingerStatus.textContent = count >= requiredTouches
                            ? 'Stamping...'
                            : `Place ${requiredTouches} finger${requiredTouches === 1 ? '' : 's'} on the image at the same time. (${count}/${requiredTouches})`;
                    }
                }

                function submitStamp() {
                    if (submitting) return;
                    submitting = true;
                    if (fingerStatus) fingerStatus.textContent = 'Stamping...';

                    fetch('{{ route('process_stamp') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ station: {{ $station->id }} })
                    })
                        .then(function (response) {
                            if (!response.ok) throw new Error('Stamp failed');
                            return response.json();
                        })
                        .then(function () {
                            stationPage.classList.remove('stamping-active');
                            waiting.classList.add('d-none');
                            complete.classList.remove('d-none');
                        })
                        .catch(function () {
                            submitting = false;
                            if (fingerStatus) fingerStatus.textContent = 'Stamp failed. Please try again.';
                        });
                }

                document.addEventListener('pointerdown', function (event) {
                    if (event.pointerType === 'mouse' || submitting || waiting.classList.contains('d-none')) return;
                    event.preventDefault();
                    activePointers.add(event.pointerId);
                    updateFingerStatus();
                    if (activePointers.size >= requiredTouches) submitStamp();
                }, { passive: false });

                ['pointerup', 'pointercancel', 'lostpointercapture'].forEach(function (eventName) {
                    document.addEventListener(eventName, function (event) {
                        activePointers.delete(event.pointerId);
                        if (!submitting) updateFingerStatus();
                    });
                });

                doneButton.addEventListener('click', function () {
                    window.location.href = '{{ route('dashboard') }}';
                });
            });
        </script>
    @endpush
</x-app-layout>
