<x-app-layout>
    @push('styles')
        <style>
            @font-face {
                font-family: 'Altone';
                src: url('{{ asset('tommy_assets/Altone-Regular.ttf') }}') format('truetype');
                font-weight: 400;
                font-display: swap;
            }

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
                padding: 18px 18px 28px;
                background: url('{{ asset('tommy_assets/Tommy X Cadillac_background_2x.webp') }}') center / cover fixed;
                color: #050505;
                font-family: 'Altone', Arial, sans-serif;
            }

            .bonus-stamp-page.stamping-active { touch-action: none; }
            .bonus-stamp-shell { width: min(100%, 430px); min-height: calc(100svh - 46px); display: flex; flex-direction: column; align-items: center; text-align: center; }
            .bonus-stamp-logo { width: min(52vw, 190px); height: auto; margin-bottom: clamp(22px, 6vh, 48px); }
            .bonus-stamp-state { width: 100%; flex: 1; display: flex; flex-direction: column; align-items: center; }
            .bonus-stamp-title { margin: 0 0 clamp(28px, 6vh, 52px); color: #000; font-family: 'Energize', Arial, sans-serif; font-size: clamp(30px, 9vw, 48px); font-style: italic; font-weight: 700; line-height: .95; text-transform: uppercase; }
            .bonus-stamp-hint { max-width: 300px; margin: 0 auto; font-family: 'Altone', Arial, sans-serif; font-size: clamp(17px, 4vw, 21px); font-weight: 400; line-height: 1.25; }
            .bonus-stamp-touch-status { display: block; margin-top: 8px; font-size: .8em; }
            .bonus-stamp-image { width: min(58vw, 250px); aspect-ratio: 1; margin-top: clamp(34px, 8vh, 62px); object-fit: contain; pointer-events: none; user-select: none; }
            .bonus-stamp-target { width: min(68vw, 290px); aspect-ratio: 1; margin-top: clamp(34px, 7vh, 60px); padding: 0; border: 0; background: transparent; cursor: pointer; touch-action: none; }
            .bonus-stamp-target .bonus-stamp-image { width: 100%; height: 100%; margin-top: 0; }
            .bonus-stamp-action { min-width: 150px; min-height: 40px; margin-top: auto; padding: 0 28px; border: 0; border-radius: 999px; background: #000; color: #fff; font-family: Arial, sans-serif; font-size: 11px; text-transform: uppercase; cursor: pointer; }
            .bonus-stamp-complete-image { width: min(58vw, 250px); height: auto; }
            .bonus-redemption-post-image { width: min(68vw, 290px); height: auto; }
            .bonus-redemption-complete-title { margin-bottom: clamp(26px, 6vh, 42px); }
            .bonus-redemption-complete-badge { width: min(30vw, 130px); height: auto; }
            .bonus-redemption-complete-heading { margin: clamp(24px, 5vh, 38px) 0 0; color: #000; font-family: 'Altone', Arial, sans-serif; font-size: clamp(20px, 5.8vw, 30px); font-weight: 700; line-height: 1; }
            .bonus-redemption-complete-copy { max-width: 290px; margin: clamp(30px, 6vh, 46px) 0 0; color: #000; font-family: 'Altone', Arial, sans-serif; font-size: clamp(13px, 3.5vw, 16px); font-weight: 400; line-height: 1.25; }
            .bonus-redemption-complete-action { margin-top: auto; min-width: 190px; font-size: 10px; }
            .bonus-redemption-intro-title { margin-bottom: clamp(26px, 5vh, 38px); }
            .bonus-redemption-intro-image { width: min(30vw, 130px); height: auto; }
            .bonus-redemption-intro-heading { margin: clamp(24px, 5vh, 38px) 0 0; color: #000; font-family: 'Altone', Arial, sans-serif; font-size: clamp(20px, 5.8vw, 30px); font-weight: 700; line-height: 1; }
            .bonus-redemption-intro-copy { max-width: 290px; margin: clamp(30px, 6vh, 46px) 0 0; color: #000; font-family: 'Altone', Arial, sans-serif; font-size: clamp(13px, 3.5vw, 16px); font-weight: 400; line-height: 1.25; }
            .bonus-redemption-intro-action { margin-top: auto; min-width: 190px; font-size: 10px; }
            .bonus-redemption-stamp-image { width: min(68vw, 290px); margin-top: clamp(34px, 7vh, 60px); }
            .bonus-in-store-intro-title { margin-bottom: clamp(26px, 5vh, 38px); }
            .bonus-in-store-intro-image { width: min(30vw, 130px); height: auto; }
            .bonus-in-store-intro-heading { margin: clamp(24px, 5vh, 38px) 0 0; color: #000; font-family: 'Altone', Arial, sans-serif; font-size: clamp(20px, 5.8vw, 30px); font-weight: 700; line-height: 1; }
            .bonus-in-store-intro-copy { max-width: 290px; margin: clamp(30px, 6vh, 46px) 0 0; color: #000; font-family: 'Altone', Arial, sans-serif; font-size: clamp(13px, 3.5vw, 16px); font-weight: 400; line-height: 1.25; }
            .bonus-in-store-intro-action { margin-top: auto; min-width: 150px; font-size: 10px; }
            .bonus-in-store-post-image { width: min(68vw, 290px); height: auto; }
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
                @if ($bonus === 'redemption')
                    <h1 class="bonus-stamp-title bonus-redemption-intro-title">Redemption</h1>
                    <img class="bonus-redemption-intro-image" src="{{ asset($asset) }}" alt="Redemption stamp">
                    <h2 class="bonus-redemption-intro-heading">You Made It!</h2>
                    <p class="bonus-redemption-intro-copy">Head over to our <strong>Redemption Counter</strong><br>to claim your exclusive gift.</p>
                    <button id="bonusStart" class="bonus-stamp-action bonus-redemption-intro-action" type="button">Head to Redemption Counter</button>
                @elseif ($bonus === 'in-store')
                    <h1 class="bonus-stamp-title bonus-in-store-intro-title">In-Store</h1>
                    <img class="bonus-in-store-intro-image" src="{{ asset($asset) }}" alt="In-store stamp">
                    <h2 class="bonus-in-store-intro-heading">Bonus Lap Reward!</h2>
                    <p class="bonus-in-store-intro-copy">Visit Tommy Hilfiger store #03-15 to<br>get your stamp and claim your<br>extra reward.</p>
                    <button id="bonusStart" class="bonus-stamp-action bonus-in-store-intro-action" type="button">I'm at the store</button>
                @else
                    <h1 class="bonus-stamp-title">{{ $title }}</h1>
                    <img class="bonus-stamp-image" src="{{ asset($asset) }}" alt="{{ $title }} stamp">
                    <button id="bonusStart" class="bonus-stamp-action" type="button">Start</button>
                @endif
            </div>

            <div id="bonusWaiting" class="bonus-stamp-state d-none">
                <p class="bonus-stamp-hint">Earn a digital passport<br>stamp upon completion.<span id="bonusTouchStatus" class="bonus-stamp-touch-status"></span></p>
                <button id="bonusStampTarget" class="bonus-stamp-target" type="button" aria-label="Touch the stamp to collect it">
                    <img class="bonus-stamp-image {{ $bonus === 'redemption' ? 'bonus-redemption-stamp-image' : '' }}" src="{{ asset($asset) }}" alt="">
                </button>
             </div>

            <div id="bonusComplete" class="bonus-stamp-state {{ $alreadyStamped ? '' : 'd-none' }}">
                @if ($bonus === 'redemption')
                    <h1 class="bonus-stamp-title">Stamp<br>Collected</h1>
                    <img class="bonus-stamp-complete-image bonus-redemption-post-image" src="{{ asset($completeAsset) }}" alt="Redemption stamp collected">
                    <button id="bonusDone" class="bonus-stamp-action" type="button">Done</button>
                @else
                    <h1 class="bonus-stamp-title">Stamp<br>Collected</h1>
                    <img class="bonus-stamp-complete-image bonus-in-store-post-image" src="{{ asset($completeAsset) }}" alt="{{ $title }} stamp collected">
                    <button id="bonusDone" class="bonus-stamp-action" type="button">Done</button>
                @endif
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
                const stampTarget = document.getElementById('bonusStampTarget');
                const activePointers = new Set();
                const requiredTouches = Math.max(1, Number(@json($requiredTouches)) || 1);
                const touchStatus = document.getElementById('bonusTouchStatus');
                let submitting = false;

                function updateTouchStatus() {
                    if (!touchStatus) return;
                    touchStatus.textContent = activePointers.size >= requiredTouches
                        ? 'Stamping...'
                        : `Touches: ${activePointers.size}/${requiredTouches}`;
                }

                document.getElementById('bonusStart')?.addEventListener('click', function () {
                    intro.classList.add('d-none');
                    waiting.classList.remove('d-none');
                    page.classList.add('stamping-active');
                    updateTouchStatus();
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

                stampTarget.addEventListener('pointerdown', function (event) {
                    if (event.pointerType === 'mouse' || submitting || waiting.classList.contains('d-none')) return;
                    event.preventDefault();
                    stampTarget.setPointerCapture(event.pointerId);
                    activePointers.add(event.pointerId);
                    updateTouchStatus();
                    if (activePointers.size >= requiredTouches) submitBonusStamp();
                }, { passive: false });

                ['pointerup', 'pointercancel', 'lostpointercapture'].forEach(function (eventName) {
                    document.addEventListener(eventName, function (event) {
                        activePointers.delete(event.pointerId);
                        if (!submitting) updateTouchStatus();
                    });
                });

                document.getElementById('bonusDone')?.addEventListener('click', function () {
                    window.location.href = '{{ route('dashboard') }}';
                });
            });
        </script>
    @endpush
</x-app-layout>
