<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Live Feed</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Pusher -->
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <style>
        @font-face {
            font-family: 'Stella Demo';
            src: url('{{ asset('images/font/Stella Demo.otf') }}') format('opentype');
            font-weight: normal;
            font-style: normal;
        }


    html, body {
        width: 100vw;
        height: 100vh;
        overflow: hidden;
        margin: 0;
        padding: 0;
    }

    #aquarium-container {
        position: relative;
        width: 100vw;
        height: 100vh;
        max-width: 100vw;
        max-height: 100vh;
        margin: 0;
        background: none !important;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #aquarium-container canvas,
    #aquarium-container video {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: contain;
        background: transparent !important;
        pointer-events: none; /* optional: lets clicks pass through */
    }

    #aquarium-container video {
        z-index: 1;
        pointer-events: none;
    }

    #aquarium-container canvas {
        z-index: 2;
    }
    </style>
</head>

<body>
    <div id="aquarium-container">
        <video id="aquarium-bg-video" src="{{ asset('video/840 x 1008.mp4') }}" width="840" height="1008" autoplay loop muted playsinline></video>
    </div>
    <script>
        window.ASSET_BASE = "{{ asset('') }}".replace(/\/$/,'');
      </script>
    @vite('resources/js/aquarium.js')
</body>

</html>
