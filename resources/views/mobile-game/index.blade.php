<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Live Feed</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flipclock@0.7.8/compiled/flipclock.css" />
    @vite(['resources/sass/app.scss'])


    <!-- Pusher -->
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <style>
        @font-face {
            font-family: 'Stella Demo';
            src: url('{{ asset('images/font/Stella Demo.otf') }}') format('opentype');
            font-weight: normal;
            font-style: normal;
        }


        html,
        body {
            width: 100vw;
            height: 100vh;
            overflow: hidden;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .waiting-img {
            animation: blinkWaiting 1.2s infinite;
        }

        @keyframes blinkWaiting {
            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.3;
            }
        }
    </style>
</head>

<body>
    <div class="w-100 d-flex flex-column justify-content-center align-items-center animate-entry p-4 mt-5" style="z-index: 99;">
        @include('components.branding')
        <img id="game-status-image" class="waiting-img my-4" src="{{ asset('images/brand/waiting-page.webp') }}" alt="Waiting for Game" />
    </div>
    <div id="mobile-game-container"></div>
    <script>
        window.ASSET_BASE = "{{ asset('') }}".replace(/\/$/, '');

        // Pusher configuration for mobile game page
          window.PUSHER_CONFIG = {
            key: '{{ env('PUSHER_APP_KEY') }}',
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}'
        };

        // Handle image switching based on game state
        window.switchGameImage = function(action) {

            const gameImage = document.getElementById('game-status-image');
            switch(action) {
                case 'start':
                    // Switch to start game text and show game container
                    gameImage.src = "{{ asset('images/brand/start-game-text.webp') }}";

                    break;
                case 'finish':
                    //goto congrats page
                    window.location.href = "{{ route('congrats') }}";
                    break;
            }
        };
    </script>
    @vite('resources/js/mobile-game.js')</html>
    </body>
</html>
