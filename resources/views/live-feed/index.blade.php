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
    </body>

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
    </style>
</head>

<body>
    <div class="live-feed-lobby">
        <div class="player-list rounded">
            <div class="player-list-header">
                <h2>User joined</h2>

            </div>
            <div class="player-list-body">
            </div>
        </div>
    </div>
    <script>
        window.ASSET_BASE = "{{ asset('') }}".replace(/\/$/, '');
    </script>
    @vite('resources/js/live-feed.js')
</html>
