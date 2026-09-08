<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <title>Friso Gold Game</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fira+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Asap+Sharp:ital,wght@0,100..900;1,100..900&family=Fira+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">


    @vite(['resources/sass/app.scss'])

    <!-- Pusher -->
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <style>
        html,
        body {
            width: 100svw;
            height: 100svh;
            overflow: hidden;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            touch-action: manipulation;
        }

        .mobile-screen {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .mobile-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
        }

        .mobile-logo {
            position: absolute;
            top: 0vh;
            left: 6vw;
            width: 34vw;
            max-width: 160px;
            height: auto;
            object-fit: contain;
            z-index: 9999999;
        }

        /* Lobby */
        .mobile-tap-title {
            width: 70vw;
            max-width: 320px;
            height: auto;
            object-fit: contain;
            margin-bottom: 6vh;
        }

        .mobile-tap-here {
            width: 60vw;
            max-width: 320px;
            height: auto;
            object-fit: contain;
            margin: 0 auto;

            /* blinking animation for the tap here image */
            animation: blink 3s infinite;

        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }

        }

        .mobile-tap-hint {
            color: #1a3a7a;
            font-weight: 700;
            font-size: 4.5vw;
            letter-spacing: 1px;
        }

        /* Countdown */
        .mobile-countdown .countdown-ready {
            width: 60vw;
            max-width: 280px;
            height: auto;
            object-fit: contain;
            margin-bottom: 3vh;
        }

        .mobile-countdown .countdown-number-wrap {
            position: relative;
            width: 34vw;
            max-width: 160px;
            height: 34vw;
            max-height: 160px;
        }

        .mobile-countdown .countdown-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: contain;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .mobile-countdown .countdown-image.active {
            opacity: 1;
        }

        /* Tap game */
        .mobile-tap-game {
            cursor: pointer;
        }

        .mobile-tap-me {
            width: 55vw;
            max-width: 260px;
            height: auto;
            object-fit: contain;
            margin-bottom: 4vh;
        }

        .mobile-product {
            position: relative;
            z-index: 50;
            width: 55vw;
            max-width: 260px;
            height: auto;
            object-fit: contain;
        }

        .light-effect {
            position: relative;
            z-index: 49;
            width: 55vw;
            max-width: 260px;
            height: auto;
            object-fit: contain;
            margin-bottom: -2vh;
        }

        // fade in effect for the light effect image
        .light-effect {
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .light-effect.active {
            opacity: 1;
        }

        /* Finish */
        .mobile-finish {
            gap: 3vh;
        }

        .mobile-finish-product {
            width: 100vw;
            height: auto;
            object-fit: contain;
        }

        .mobile-finish-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2vh;
        }

        .mobile-finish-title {
            width: 93vw;
            max-width: 349px;
            height: auto;
            object-fit: contain;
        }

        .mobile-finish-subtitle {
            width: 70vw;
            max-width: 300px;
            height: auto;
            object-fit: contain;
        }
    </style>
</head>

<body>
    <img src="{{ asset('images/brand/logo.png') }}" alt="Friso Gold" class="mobile-logo">

    <div class="mobile-screen mobile-lobby">
        <div class="first-lobby">
            <img src="{{ asset('images/brand/background-desktop.png') }}" alt="" class="mobile-bg">
            <img src="{{ asset('images/brand/tap for more@3x 1.png') }}" alt="Tap for more good things!"
                class="mobile-tap-title">
            <img src="{{ asset('images/brand/TAP HERE TO START.png') }}" alt="Tap for more good things!"
                class="mobile-tap-here">
        </div>
    </div>

    <div class="mobile-screen mobile-countdown d-none">
        <img src="{{ asset('images/brand/background-desktop.png') }}" alt="" class="mobile-bg">
        <img src="{{ asset('images/brand/ready.png') }}" alt="Ready" class="countdown-ready">
        <div class="countdown-number-wrap">
            <img src="{{ asset('images/brand/countdown-number/3.png') }}" alt="Countdown 3" class="countdown-image"
                id="m-countdown-3">
            <img src="{{ asset('images/brand/countdown-number/2.png') }}" alt="Countdown 2" class="countdown-image"
                id="m-countdown-2">
            <img src="{{ asset('images/brand/countdown-number/1.png') }}" alt="Countdown 1" class="countdown-image"
                id="m-countdown-1">
        </div>
    </div>

    <div class="mobile-screen mobile-tap-game d-none">
        <img src="{{ asset('images/brand/background-desktop.png') }}" alt="" class="mobile-bg">
        <img src="{{ asset('images/brand/READY-05 2.png') }}" alt="Tap me" class="mobile-tap-me">
        <img src="{{ asset('images/brand/light.png') }}" alt="Friso Gold" class="light-effect">
        <img src="{{ asset('images/brand/game/product.png') }}" alt="Friso Gold" class="mobile-product"
            id="mobileProduct">
    </div>

    <div class="mobile-screen mobile-finish d-none">
        <img src="{{ asset('images/brand/background-desktop.png') }}" alt="" class="mobile-bg">

        <div class="mobile-finish-content">
            <img src="{{ asset('images/brand/congratulation/you did it@3x 1.png') }}" alt="You did it!"
                class="mobile-finish-title">
            <img src="{{ asset("images/brand/congratulation/We've unlocked 6x less tummy issues_ for more good things!-mobile.png") }}"
                alt="We've unlocked 6x less tummy issues for more good things!" class="mobile-finish-subtitle">

            <img src="{{ asset('images/brand/congratulation/Group 1000008283 1.png') }}" alt="Friso Gold"
                class="mobile-finish-product">
        </div>

        <div class="text-disclaimer">
            <p>*Referring to flatulence, diarrhoea & constipation, in just a week.
                *Sheng et al. JNME (2020)</p>
        </div>
    </div>

    <div class="footer-text">
        <p>Powered by WOWSOME®️ 2026</p>
    </div>

    <script>
        window.ASSET_BASE = "{{ asset('') }}".replace(/\/$/, '');

        // Pusher configuration for mobile game page
        window.PUSHER_CONFIG = {
            key: '{{ config('broadcasting.connections.pusher.key') }}',
            cluster: '{{ config('broadcasting.connections.pusher.cluster') }}'
        };

        const mobileTapHere = document.querySelector('.mobile-tap-here');

        function showTapGame() {
            // play tap game animation or sound if needed
            const tapSound = new Audio(`${window.ASSET_BASE}/sounds/mobile/tap.mp3`);
            tapSound.play().catch((error) => {
                console.warn("❌ Could not play tap sound:", error);
            });
            //hide lobby screens if any before showing the tap game
            const lobbyScreens = document.querySelectorAll('.mobile-screen');
            lobbyScreens.forEach(screen => screen.classList.add('d-none'));
            document.querySelector('.mobile-tap-game').classList.remove('d-none');
        }

        mobileTapHere.addEventListener('click', showTapGame);

        ['gesturestart', 'gesturechange', 'gestureend'].forEach((eventName) => {
            document.addEventListener(
                eventName,
                (e) => e.preventDefault(), {
                    passive: false
                }
            );
        });
    </script>
    @vite('resources/js/mobile-game.js')
</body>

</html>
