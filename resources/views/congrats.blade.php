<x-app-layout>
    @push('styles')
        <style>
            @font-face {
                font-family: 'Altone';
                src: url('{{ asset('tommy_assets/Altone-Bold.ttf') }}') format('truetype');
                font-weight: 700;
                font-display: swap;
            }

            .tommy-congrats-page {
                min-height: 100svh;
                display: grid;
                place-items: center;
                overflow: hidden;
                padding: 24px;
                background: url('{{ asset('tommy_assets/Tommy X Cadillac_background_2x.webp') }}') center / cover fixed;
                color: #000;
                font-family: 'Altone', Arial, sans-serif;
            }

            .tommy-congrats-content {
                display: grid;
                justify-items: center;
                text-align: center;
                transform: translateY(-2%);
            }

            .tommy-congrats-text {
                margin: 0;
                font-size: clamp(20px, 5vw, 27px);
                font-weight: 400;
                line-height: 1.2;
                 font-family: 'Altone', Arial, sans-serif;
            }

            .tommy-congrats-logo {
                display: block;
                width: min(34vw, 145px);
                height: auto;
                margin: 14px 0 12px;
                transition: transform .2s ease;
            }

            .tommy-congrats-logo:hover,
            .tommy-congrats-logo:focus-visible { transform: scale(1.04); }
        </style>
    @endpush

    <main class="tommy-congrats-page">
        <section class="tommy-congrats-content" aria-label="Tommy Hilfiger information">
            <p class="tommy-congrats-text">Click on the logo</p>
            <a href="https://global.tommy.com/" target="_blank" rel="noopener noreferrer" aria-label="Tommy Hilfiger website">
                <img class="tommy-congrats-logo" src="{{ asset('tommy_assets/TOMMY-HIL_logo_2x.webp') }}" alt="Tommy Hilfiger">
            </a>
            <p class="tommy-congrats-text">for more information</p>
        </section>
    </main>
</x-app-layout>
