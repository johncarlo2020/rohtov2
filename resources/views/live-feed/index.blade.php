<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Live Feed</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flipclock@0.7.8/compiled/flipclock.css" />
    @vite(['resources/sass/app.scss'])

    <style>
        .live-game {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* User Container Overflow Prevention */
        .user-container {
            overflow: hidden;
            /* Hide users that don't fit */
            display: flex;
            flex-direction: column;
        }

        /* Smooth animation for new users */
        .user-item {
            flex-shrink: 0;
            /* Prevent items from shrinking */
        }

        .cart-tail,
        .cat-left,
        .cat-right {
            position: absolute !important;
            z-index: 0;
        }

        .cat-tail {
            top: 0;
            right: 0;
            width: 426px;
            height: auto;
            position: absolute
        }

        .cat-left {
            left: 452px;
            top: 57%;
            transform: translateY(-50%);
            width: 257px;
            height: auto;
        }

        .cat-right {
            right: -112px;
            top: 56%;
            transform: translateY(-50%);
            width: 498px;
            height: auto;
        }

        .qr-container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 500px;
            height: 500px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }


        .qr-container.right {
            right: 15%;
        }

        .qr-container .qr-img {
            width: 261px;
            height: 261px;
            background: #fff;
            border: 10px solid #243B81;
            border-radius: 10px;
            padding: 10px;
        }

        .qr-container .qr-text {
            margin-bottom: 20px;
            object-fit: contain;
            width: 100%;
            height: auto;
        }

        .live-feed-lobby {
            width: 100%;
            height: 100%;
            background-image: url('{{ asset('images/brand/background-desktop.png') }}');
            background-size: cover;
            background-position: center;
        }

        .count-down {
            background-image: url('{{ asset('images/brand/background-desktop.png') }}');
        }

        .progress-tube-fill {
            background-image: url('{{ asset('images/brand/game/bar-fill.png') }}');
        }
    </style>
</head>

<body class="live-feed">
    <img src="{{ asset('images/brand/logo.png') }}" alt="Friso Gold" class="live-feed-logo">
    <div class="live-feed-lobby">
        <div class="qr-container left">
            <img class="qr-text" src="{{ asset('images/brand/text-exitement.png') }}" alt="">
            <div class="qr-img" id="qrCodeLeft"></div>
        </div>
    </div>

    <div class="count-down d-none">
        <img src="{{ asset('images/brand/ready.png') }}" alt="Ready" class="countdown-ready">
        <div class="countdown-number-wrap">
            <img src="{{ asset('images/brand/countdown-number/3.png') }}" alt="Countdown 3" class="countdown-image"
                id="countdown-3">
            <img src="{{ asset('images/brand/countdown-number/2.png') }}" alt="Countdown 2" class="countdown-image"
                id="countdown-2">
            <img src="{{ asset('images/brand/countdown-number/1.png') }}" alt="Countdown 1" class="countdown-image"
                id="countdown-1">
        </div>
    </div>

    <div class="live-game d-none">
        <img src="{{ asset('images/brand/background-desktop.png') }}" alt="" class="game-background">

        <img src="{{ asset('images/brand/game/product.png') }}" alt="Friso Gold" class="product-can">

        <div class="side-panel side-panel-left">
            <img src="{{ asset('images/brand/game/side-message.png') }}" alt="Tap for more good things!"
                class="side-message">
            <div class="progress-tube">
                <div id="game-progress-bar-left" class="progress-tube-fill"></div>
            </div>
        </div>

        <div class="side-panel side-panel-right">
            <img src="{{ asset('images/brand/game/side-message.png') }}" alt="Tap for more good things!"
                class="side-message">
            <div class="progress-tube">
                <div id="game-progress-bar-right" class="progress-tube-fill"></div>
            </div>
        </div>

        <div id="game"></div>
    </div>
    <div class="finish d-none">
        <img src="{{ asset('images/brand/background-desktop.png') }}" alt="" class="finish-background">
        <img src="{{ asset('images/brand/congratulation/product with lines.png') }}" alt="Friso Gold"
            class="finish-product">
        <div class="finish-content">
            <img src="{{ asset('images/brand/congratulation/you did it@3x 1.png') }}" alt="You did it!"
                class="finish-title">
            <img src="{{ asset("images/brand/congratulation/We've unlocked 6x less tummy issues for more good things!.png") }}"
                alt="We've unlocked 6x less tummy issues for more good things!" class="finish-subtitle">
        </div>
    </div>

    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
    <script>
        window.ASSET_BASE = "{{ asset('') }}".replace(/\/$/, '');

        // Pusher configuration
        window.PUSHER_CONFIG = {
            key: '{{ config('broadcasting.connections.pusher.key') }}',
            cluster: '{{ config('broadcasting.connections.pusher.cluster') }}'
        };

        // Routes configuration
        window.ROUTES = {
            start: '{{ route('start') }}'
        };

        // Game configuration from database
        window.GAME_CONFIG = {
            maxWeight: {{ intval($gameConfig->max_weight ?? 4) }}, // Maximum weight in kg
            incrementGrams: {{ $gameConfig->increment_grams ?? 100 }}, // Increment per click in grams
            minWeight: 0.0, // Minimum weight (always 0)
            medianWeight: {{ intval(($gameConfig->max_weight ?? 4) / 2) }}, // Median weight (half of max, no decimal)
            internalMax: 400 // Internal calculation range (0-400)
        };

        // QR codes linking to the mobile game page
        const gameUrl = "{{ route('game.index') }}";
        [
            'qrCodeLeft',
            'qrCodeRight'
        ].forEach(function(id) {
            new QRCode(document.getElementById(id), {
                text: gameUrl,
                width: 241,
                height: 241,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });
        });

        // Browsers block audio autoplay until a user gesture - unlock it on first interaction
        document.addEventListener('click', function unlockAudio() {
            window.dispatchEvent(new CustomEvent('startExperience'));
            document.removeEventListener('click', unlockAudio);
        }, {
            once: true
        });
    </script>
    @vite('resources/js/live-feed.js')

</html>
