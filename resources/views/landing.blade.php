<x-app-layout>
    @push('styles')
        <style>
            @font-face {
                font-family: 'Altone';
                src: url('{{ asset('tommy_assets/Altone-Bold.ttf') }}') format('truetype');
                font-style: normal;
                font-weight: 700;
                font-display: swap;
            }

            .landing-page {
                min-height: 100svh;
                display: flex;
                align-items: center;
                justify-content: center;
                overflow: hidden;
                padding: 16px;
                background: url('{{ asset('tommy_assets/Tommy X Cadillac_background_2x.webp') }}') center / cover no-repeat;
                color: #111;
                font-family: 'Altone', Arial, sans-serif;
            }

            .landing-panel {
                width: min(100%, 430px);
                min-height: calc(100svh - 32px);
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: space-between;
                padding: clamp(42px, 11vh, 88px) 28px 42px;
                text-align: center;
            }

            .landing-brands {
                display: grid;
                justify-items: center;
                gap: 0;
            }

            .landing-brand-lockup {
                display: block;
                width: min(72vw, 218px);
                height: auto;
            }

            .cadillac-mark {
                display: grid;
                justify-items: center;
                gap: 5px;
                font-family: 'GothamBold', Arial, sans-serif;
                letter-spacing: .32em;
                line-height: 1;
            }

            .cadillac-crest {
                width: 58px;
                height: 28px;
                position: relative;
                border: 3px solid #151515;
                clip-path: polygon(8% 0, 92% 0, 82% 100%, 18% 100%);
            }

            .cadillac-crest::before,
            .cadillac-crest::after {
                content: '';
                position: absolute;
                left: 8px;
                right: 8px;
                height: 3px;
                background: #151515;
            }

            .cadillac-crest::before { top: 8px; }
            .cadillac-crest::after { bottom: 7px; }

            .cadillac-name {
                font-size: 17px;
            }

            .cadillac-subtitle {
                font-size: 8px;
                letter-spacing: .08em;
            }

            .official-partner {
                display: flex;
                align-items: center;
                gap: 10px;
                font-size: 7px;
                letter-spacing: .08em;
            }

            .official-partner::before,
            .official-partner::after {
                content: '';
                width: 28px;
                height: 1px;
                background: #111;
            }

            .tommy-mark {
                display: flex;
                width: 91px;
                height: 45px;
                border: 2px solid #20283e;
                font-family: Arial, sans-serif;
                font-size: 8px;
                font-weight: 700;
                letter-spacing: .22em;
                color: #fff;
            }

            .tommy-mark span {
                flex: 1;
                display: grid;
                place-items: center;
                padding-left: .22em;
            }

            .tommy-mark span:first-child { background: #1f2a52; }
            .tommy-mark span:nth-child(2) { background: #fff; color: #1f2a52; }
            .tommy-mark span:last-child { background: #bb1238; }

            .landing-copy {
                display: grid;
                gap: 16px;
                max-width: 300px;
                margin-top: auto;
                margin-bottom: auto;
            }

            .landing-copy h1 {
                margin: 0;
                color: #000;
                font-family: 'Altone', Arial, sans-serif;
                font-size: clamp(22px, 6vw, 27px);
                font-weight: 700;
                letter-spacing: 0;
            }

            .landing-copy p {
                margin: 0;
                font-size: 13px;
                line-height: 1.38;
                font-weight: 600;
                color: #000;
                font-family: 'Altone', Arial, sans-serif;
            }

            .landing-action {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-width: 142px;
                min-height: 40px;
                padding: 0 22px;
                border-radius: 999px;
                background: #050505;
                color: #fff;
                font-family: 'GothamBold', Arial, sans-serif;
                font-size: 9px;
                letter-spacing: .04em;
                text-decoration: none;
                transition: transform .2s ease, background-color .2s ease;
            }

            .landing-action:hover,
            .landing-action:focus-visible {
                background: #222;
                color: #fff;
                transform: translateY(-2px);
            }

            @media (min-width: 600px) {
                .landing-page { padding: 24px; }
                .landing-panel { min-height: calc(100svh - 48px); }
            }
        </style>
    @endpush

    <main class="landing-page">
        <section class="landing-panel" aria-labelledby="landing-title">
            <div class="landing-brands">
                <img
                    class="landing-brand-lockup"
                    src="{{ asset('tommy_assets/THXCDL_logo_vertical_2x.webp') }}"
                    alt="Cadillac Formula 1 Team and Tommy Hilfiger"
                >
            </div>

            <div class="landing-copy">
                <h1 id="landing-title">Welcome to Singapore</h1>
                <p>Step into the world of the<br>Tommy Hilfiger x Cadillac F1® Team.</p>
                <p>Hit the track and complete all 4 pit stops<br>to unlock your exclusive gift.</p>
            </div>

            <a class="landing-action" href="{{ route('dashboard') }}">EXPLORE THE TRACK</a>
        </section>
    </main>
</x-app-layout>
