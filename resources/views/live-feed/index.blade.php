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
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
</head>

<body class="live-feed">
    <div class="live-feed-lobby d-none">
        <img src="{{ asset('images/brand/cat-background.webp') }}" alt="" class="cat-background">
        <div class="player-list rounded">
            <div class="player-list-header">
                <h2 class="heading-text">User joined</h2>
                <div class="player-count">
                    <span id="player-count">300</span>
                    <img src="{{ asset('images/brand/paw-icon.webp') }}" alt="Paw Icon" class="paw-icon">
                </div>
            </div>
            <div class="player-list-body">
            </div>
        </div>

        <div class="qr-container">
            <img src="{{ asset('images/brand/logo.webp') }}" alt="Brand Logo" class="brand-logo">
            <img src="{{ asset('images/brand/qr-code.webp') }}" alt="QR Code" class="qr-code">
            <p>Scan for Excitement</p>
        </div>
    </div>
    <div class="live-game">
        <div id="game"></div>
    </div>
    <script>
        window.ASSET_BASE = "{{ asset('') }}".replace(/\/$/, '');
    </script>
    @vite('resources/js/live-feed.js')
</html>
