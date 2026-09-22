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

            .bonus-stamp-page {
                min-height: 100svh;
                display: flex;
                justify-content: center;
                overflow: hidden;
                padding: 18px;
                background: url('{{ asset('tommy_assets/Tommy X Cadillac_background_2x.webp') }}') center / cover fixed;
                color: #000;
                font-family: 'Altone', Arial, sans-serif;
            }

            .bonus-stamp-page.stamping-active { touch-action: none; }
            .bonus-stamp-shell { width: min(100%, 430px); min-height: calc(100svh - 36px); display: flex; flex-direction: column; align-items: center; text-align: center; }
            .bonus-stamp-logo { width: min(52vw, 190px); height: auto; margin-bottom: clamp(24px, 7vh, 52px); }
            .bonus-stamp-state { width: 100%; flex: 1; display: flex; flex-direction: column; align-items: center; }
            .bonus-stamp-title { margin: 0 0 clamp(30px, 7vh, 52px); font-family: 'Energize', Arial, sans-serif; font-size: clamp(31px, 9vw, 48px); font-style: italic; line-height: .95; text-transform: uppercase; }
            .bonus-stamp-image { width: min(58vw, 250px); aspect-ratio: 1; margin-top: clamp(34px, 8vh, 62px); object-fit: contain; pointer-events: none; user-select: none; }
            .bonus-stamp-action { min-width: 150px; min-height: 40px; margin-top: auto; padding: 0 28px; border: 0; border-radius: 999px; background: #000; color: #fff; font-family: Arial, sans-serif; font-size: 11px; text-transform: uppercase; cursor: pointer; }
            .bonus-stamp-complete-image { width: min(58vw, 250px); height: auto; }
            .d-none { display: none !important; }
        </style>
    @endpush

    @php
        $asset = $bonus === 'redemption' ? 'tommy_assets/REDEMPTION_2x.webp' : 'tommy_assets/IN-STORE_2x.webp';
        $completeAsset = $bonus === 'redemption' ? 'tommy_assets/REDEMPTION_COMPLETE_2x.webp' : 'tommy_assets/IN-STORE-COMPLETE_2x.webp';
        $title = $bonus === 'redemption' ? 'Redemption' : 'In-Store';
    @endphp

    <main class="bonus-stamp-page" aria-live="polite">
        <section class="bonus-stamp-shell">
            <img class="bonus-stamp-logo" src="{{ asset('tommy_assets/THXCDL_logo_horizontal_2x.webp') }}" alt="Cadillac Formula 1 Team and Tommy Hilfiger">

            <div id="bonusIntro" class="bonus-stamp-state {{ $alreadyStamped ? 'd-none' : '' }}">
                <h1 class="bonus-stamp-title">{{ $title }}</h1>
                <img class="bonus-stamp-image" src="{{ asset($asset) }}" alt="{{ $title }} stamp">
                <button id="bonusStart" class="bonus-stamp-action" type="button">Start</button>
            </div>

            <div id="bonusWaiting" class="bonus-stamp-state d-none">
                <img class="bonus-stamp-image" src="{{ asset($asset) }}" alt="Touch anywhere to stamp">
             </div>

            <div id="bonusComplete" class="bonus-stamp-state {{ $alreadyStamped ? '' : 'd-none' }}">
                <h1 class="bonus-stamp-title">Stamp<br>Collected</h1>
                <img class="bonus-stamp-complete-image" src="{{ asset($completeAsset) }}" alt="{{ $title }} stamp collected">
                <button id="bonusDone" class="bonus-stamp-action" type="button">Done</button>
            </div>
        </section>
    </main>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const page = document.querySelector('.bonus-stamp-page');
                const intro = document.getElementById('bonusIntro');
                const waiting = document.getElementById('bonusWaiting');
                const complete = document.getElementById('bonusComplete');
                const activePointers = new Set();
                const requiredTouches = @json($requiredTouches);
                let submitting = false;

                document.getElementById('bonusStart')?.addEventListener('click', function () {
                    intro.classList.add('d-none');
                    waiting.classList.remove('d-none');
                    page.classList.add('stamping-active');
                });

                function submitBonusStamp() {
                    if (submitting) return;
                    submitting = true;

                    fetch('{{ route('process_bonus_stamp') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ bonus: @json($bonus) })
                    }).then(function (response) {
                        if (!response.ok) throw new Error('Bonus stamp failed');
                        return response.json();
                    }).then(function () {
                        page.classList.remove('stamping-active');
                        waiting.classList.add('d-none');
                        complete.classList.remove('d-none');
                    }).catch(function () {
                        submitting = false;
                    });
                }

                document.addEventListener('pointerdown', function (event) {
                    if (event.pointerType === 'mouse' || submitting || waiting.classList.contains('d-none')) return;
                    event.preventDefault();
                    activePointers.add(event.pointerId);
                    if (activePointers.size >= requiredTouches) submitBonusStamp();
                }, { passive: false });

                ['pointerup', 'pointercancel'].forEach(function (eventName) {
                    document.addEventListener(eventName, function (event) {
                        activePointers.delete(event.pointerId);
                    });
                });

                document.getElementById('bonusDone')?.addEventListener('click', function () {
                    window.location.href = '{{ route('dashboard') }}';
                });
            });
        </script>
    @endpush
</x-app-layout>
