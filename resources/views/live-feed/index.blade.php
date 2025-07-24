<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Live Feed</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

        .scale-labels {
            position: absolute;
            bottom: 20%;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            justify-content: space-between;
            width: 80%;
            max-width: 400px;
        }

        .scale-value {
            color: #fff;
            font-weight: bold;
            font-size: 30px;
        }

        .scale-label {
            position: absolute;
            z-index: 50;

        }

        .scale-min {
            left: 601px;
            bottom: 7px;
        }

        .scale-median {
            left: 50%;
            bottom: 118px;
            transform: translateX(-50%);
        }

        .scale-max {
            right: 601px;
            bottom: 7px;
        }

        /* Scale Pin/Needle */
        .scale-pin {
            position: absolute;
            width: 40px;
            height: 80px;
            background-image: url('{{ asset('images/brand/neddle.webp') }}');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center bottom;
            bottom: 5px;
            left: 50%;
            transform: translateX(-50%) rotate(-90deg); /* Start at -90deg (0kg) to match gameTrigger */
            transform-origin: center bottom;
            z-index: 60;
            transition: transform 0.5s ease;
            /* Debug border to see if element is there */
        }
    </style>
    </body>
</head>

<body class="live-feed">
    <div class="live-feed-lobby">
        <img src="{{ asset('images/brand/cat-background.webp') }}" alt="" class="cat-background">
        <div class="player-list rounded">
            <div class="player-list-header">
                <h2 class="heading-text">User joined</h2>
                <div class="player-count">
                    <span id="player-count">{{ $totalUsersCount }}</span>
                    <img src="{{ asset('images/brand/paw-icon.webp') }}" alt="Paw Icon" class="paw-icon">
                </div>
            </div>
            <div class="player-list-body">
                <div class="user-container">
                    @foreach ($users as $user)
                        <div class="user-item">
                            <img src="{{ asset('images/avatarCats/02_cat0' . $user->avatar_id . '.webp') }}"
                                alt="Avatar" class="avatar">
                            <p class="username-text"><span class="username">{{ $user->fname }}</span> Joined</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="qr-container">
            <img src="{{ asset('images/brand/logo.webp') }}" alt="Brand Logo" class="brand-logo">
            <img src="{{ asset('images/brand/qr-code.webp') }}" alt="QR Code" class="qr-code">
            <p>Scan for Excitement</p>
        </div>
    </div>

    <div class="count-down d-none">
        <img src="{{ asset('images/brand/logo.webp') }}" alt="Brand Logo" class="brand-logo">
    </div>

    <div class="live-game d-none">
        <img src="{{ asset('images/brand/gameBg.webp') }}" alt="" class="game-background">
        <img src="{{ asset('images/brand/scale.webp') }}" alt="" class="scale-image">

        <!-- Scale Pin/Needle -->
        <div class="scale-pin" id="scale-pin"></div>

        <!-- Scale Value Labels -->
        <div class="scale-label scale-min">
            <span class="scale-value">{{ $gameConfig->min_weight ?? 0 }}KG</span>
        </div>
        <div class="scale-label scale-median">
            <span class="scale-value">{{ intval(($gameConfig->max_weight ?? 4) / 2) }}KG</span>
        </div>
        <div class="scale-label scale-max">
            <span class="scale-value">{{ intval($gameConfig->max_weight ?? 4) }}KG</span>
        </div>

        <div id="game"></div>
    </div>
    <div class="finish d-none">
        <img src="{{ asset('images/brand/finish_led.webp') }}" alt="">
    </div>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script>
        window.ASSET_BASE = "{{ asset('') }}".replace(/\/$/, '');

        // Pusher configuration
        window.PUSHER_CONFIG = {
            key: '{{ env('PUSHER_APP_KEY') }}',
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}'
        };

        // Routes configuration
        window.ROUTES = {
            start: '{{ route("start") }}'
        };

        // Game configuration from database
        window.GAME_CONFIG = {
            maxWeight: {{ intval($gameConfig->max_weight ?? 4) }}, // Maximum weight in kg
            incrementGrams: {{ $gameConfig->increment_grams ?? 100 }}, // Increment per click in grams
            minWeight: 0.0, // Minimum weight (always 0)
            medianWeight: {{ intval(($gameConfig->max_weight ?? 4) / 2) }}, // Median weight (half of max, no decimal)
            internalMax: 400 // Internal calculation range (0-400)
        };
    </script>
    @vite('resources/js/live-feed.js')

</html>
