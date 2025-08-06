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
            font-weight: normal;
            font-size: 21px;
        }

        .scale-label {
            position: absolute;
            z-index: 50;

        }

        .scale-min {
            left: 726px;
            bottom: 7px;
        }

        .scale-median {
            left: 50%;
            bottom: 84px;
            transform: translateX(-50%);
        }

        .scale-max {
            right: 731px;
            bottom: 7px;
        }

        /* Scale Pin/Needle */
        .scale-pin {
            position: absolute;
            width: 40px;
            height: 80px;
            background-image: url('{{ asset('images/brand/niddle.webp') }}');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center bottom;
            bottom: 5px;
            left: 50%;
            transform: translateX(-50%) rotate(-90deg);
            /* Start at -90deg (0kg) to match gameTrigger */
            transform-origin: center bottom;
            z-index: 60;
            transition: transform 0.5s ease;
            /* Debug border to see if element is there */
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

        /* Connection Modal Styles */
        .modal-content {
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px 15px 0 0;
            border-bottom: none;
        }

        .connection-details {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin: 15px 0;
        }

        .status-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .status-label {
            font-weight: 600;
            color: #495057;
        }

        .status-value {
            font-weight: 500;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
        }

        .status-value.text-success {
            background-color: #d4edda;
            color: #155724;
        }

        .status-value.text-warning {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-value.text-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        #start-experience-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        #start-experience-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .cart-tail,
        .cat-left,
        .cat-right {
            position: absolute !important;
            z-index: 10;
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
            right: 25px;
            top: 56%;
            transform: translateY(-50%);
            width: 566px;
            height: auto;
        }

        .progress-container {
            position: absolute;
            left: 160px;
            top: 136px;
            width: 10vw;
            height: 72vh;
            /* background: red; */
            z-index: 90;
        }
    </style>
    </body>
</head>

<body class="live-feed">
    <div class="live-feed-lobby">
        <img src="{{ asset('images/brand/cat-right.webp') }}" alt="" class="cat-right">
        <div class="player-list rounded">
            <div class="player-list-header">
                <h2 class="heading-text">User joined</h2>
                {{-- <div class="player-count">
                    <span id="player-count">{{ $totalUsersCount }}</span>
                    <img src="{{ asset('images/brand/paw-icon.webp') }}" alt="Paw Icon" class="paw-icon">
                </div> --}}
            </div>
            <div class="player-list-body">
                <div class="user-container">
                    @foreach ($users as $user)
                        <div class="user-item">
                            <img src="{{ asset('images/avatarCats/02_cat0' . $user->avatar_id . '.webp') }}"
                                alt="Avatar" class="avatar">
                            <p class="username-text"><span class="username">{{ $user->fname }}</span> <span
                                    class="joined-text">Joined</span> </p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="qr-container">
            <img src="{{ asset('images/brand/logo.webp') }}" alt="Brand Logo" class="brand-logo">
            <img src="{{ asset('images/brand/qr.png') }}" alt="QR Code" class="qr-code">
            <img src="{{ asset('images/brand/scan_for_excitement.webp') }}" alt="Scan for Excitement" class="scan-image">
        </div>
    </div>

    <div class="count-down d-none">
        {{-- <img src="{{ asset('images/brand/logo.webp') }}" alt="Brand Logo" class="pattern-left">
        <img src="{{ asset('images/brand/logo.webp') }}" alt="Brand Logo" class="pattern-right"> --}}
        <img src="{{ asset('images/brand/countdown_images/3.webp') }}" alt="Countdown 3" class="countdown-image"
            id="countdown-3">
        <img src="{{ asset('images/brand/countdown_images/2.webp') }}" alt="Countdown 2" class="countdown-image"
            id="countdown-2">
        <img src="{{ asset('images/brand/countdown_images/1.webp') }}" alt="Countdown 1" class="countdown-image"
            id="countdown-1">
    </div>

    <div class="live-game d-none">
        <img src="{{ asset('images/brand/gameBg.webp') }}" alt="" class="game-background">
        <img src="{{ asset('images/brand/scale.webp') }}" alt="" class="scale-image">

        <div class="progress-container" style="position: absolute; left: 160px; top: 136px; width: 32px; height: 60vh; min-height: 300px; max-height: 700px; z-index: 90;">
            <div style="position: absolute; left: 40px; top: 0; height: 100%; display: flex; flex-direction: column; justify-content: space-between; align-items: flex-start; z-index: 91; pointer-events: none;">
                <span style="color: #000; font-weight: bold; font-size: 1.1em; text-shadow: 0 1px 2px #fff;">MAX</span>
                <span style="color: #000; font-weight: bold; font-size: 1.1em; text-shadow: 0 1px 2px #fff;">0kg</span>
            </div>
            <div class="progress-bar-bg" style="position: relative; width: 100%; height: 100%; background: linear-gradient(180deg, #FF0101 0%, #FFA600 36%, #F7FF00 91%); border: 2px solid #e6c97a; border-radius: 20px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                <div id="game-progress-bar" class="progress-bar-fill" style="
                    position: absolute;
                    left: 0;
                    bottom: 0;
                    width: 100%;
                    height: 0%;
                    background: lime;
                    transition: height 0.5s cubic-bezier(.4,2,.6,1);
                    opacity: 0.95;
                    border-radius: 20px;
                "></div>
                <!-- Rounded circle cap, positioned with JS -->
                <div id="progress-cap" class="progress-cap" style="
                    position: absolute;
                    left: 50%;
                    transform: translateX(-50%);
                    width: 30%;
                    aspect-ratio: 1/1;
                    background-color: lime;
                    border-radius: 50%;
                    box-shadow: 0 0 10px rgba(0,255,0,0.4);
                    bottom: 0%;
                    z-index: 2;
                    transition: bottom 0.5s cubic-bezier(.4,2,.6,1);
                "></div>
            </div>
        </div>

<script>
// Example: update the progress bar based on current value (0-100%)
// You should call this function whenever the game value changes
function setGameProgressBar(percent) {
    // percent: 0 to 100
    const bar = document.getElementById('game-progress-bar');
    const cap = document.getElementById('progress-cap');
    const fillPercent = Math.max(0, Math.min(100, percent));
    if (bar) {
        bar.style.height = fillPercent + '%';
    }
    if (cap) {
        // Cap's bottom should match the top of the fill
        cap.style.bottom = `calc(${fillPercent}% - 15px)`; // 15px is half the cap's height (adjust as needed)
        // Hide cap if fill is 0
        cap.style.opacity = fillPercent > 0 ? 1 : 0;
    }
}

// Example usage: set to 50% on load (replace with your actual logic)
// document.addEventListener('DOMContentLoaded', function() {
//     setGameProgressBar(50);
// });
</script>

        <!-- Scale Pin/Needle -->
        <div class="scale-pin" id="scale-pin"></div>

        <!-- Scale Value Labels -->
        <div class="scale-label scale-min">
            <span class="scale-value">{{ intval($gameConfig->min_weight ?? 0) }}KG</span>
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
        <img src="{{ asset('images/brand/logo.webp') }}" alt="Brand Logo" class="brand-logo">
        <img src="{{ asset('images/brand/donate gif.gif') }}" alt="" class="donate-gif">
        <img src="{{ asset('images/brand/thank-end.webp') }}" alt="" class="thank-end">
    </div>

    <!-- Connection Status Modal -->
    <div class="modal fade" id="connectionModal" tabindex="-1" aria-labelledby="connectionModalLabel"
        aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="connectionModalLabel">🎮 Game Connection Status</h5>
                </div>
                <div class="modal-body text-center">
                    <div id="connection-status">
                        <div class="spinner-border text-primary mb-3" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p>Checking game connection...</p>
                        <div class="connection-details mt-3">
                            <div class="status-item">
                                <span class="status-label">Pusher Status:</span>
                                <span id="pusher-status" class="status-value text-warning">Connecting...</span>
                            </div>
                            <div class="status-item mt-2">
                                <span class="status-label">Channel Status:</span>
                                <span id="channel-status" class="status-value text-warning">Subscribing...</span>
                            </div>
                        </div>
                    </div>
                    <div id="connection-success" class="d-none">
                        <div class="text-success mb-3">
                            <i class="fas fa-check-circle" style="font-size: 3rem;"></i>
                        </div>
                        <h4 class="text-success">🎉 Connected Successfully!</h4>
                        <p>Ready to start your gaming experience with background music.</p>
                    </div>
                    <div id="connection-error" class="d-none">
                        <div class="text-danger mb-3">
                            <i class="fas fa-exclamation-triangle" style="font-size: 3rem;"></i>
                        </div>
                        <h4 class="text-danger">⚠️ Connection Issues</h4>
                        <p>There may be connectivity issues, but you can still proceed.</p>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" id="start-experience-btn" class="btn btn-primary btn-lg d-none"
                        data-bs-dismiss="modal">
                        🎵 Start Experience
                    </button>
                    <button type="button" id="retry-connection-btn" class="btn btn-outline-primary d-none">
                        🔄 Retry Connection
                    </button>
                </div>
            </div>
        </div>
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

        // Connection Modal Management
        let connectionModal;
        let connectionCheckInterval;
        let modalShown = false;

        // Show connection modal on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Bootstrap modal
            connectionModal = new bootstrap.Modal(document.getElementById('connectionModal'));

            // Show modal immediately
            connectionModal.show();
            modalShown = true;

            // Start checking connection status
            startConnectionCheck();

            // Handle start experience button
            document.getElementById('start-experience-btn').addEventListener('click', function() {
                // This will be handled by live-feed.js when modal closes
                console.log('User clicked start experience - music will begin');

                // Dispatch custom event that live-feed.js can listen to
                window.dispatchEvent(new CustomEvent('startExperience'));
            });

            // Handle retry connection button
            document.getElementById('retry-connection-btn').addEventListener('click', function() {
                resetConnectionCheck();
                // Trigger reconnection in live-feed.js
                if (window.retryConnection) {
                   location.reload();
                }
            });
        });

        function startConnectionCheck() {
            // Reset UI
            document.getElementById('connection-status').classList.remove('d-none');
            document.getElementById('connection-success').classList.add('d-none');
            document.getElementById('connection-error').classList.add('d-none');
            document.getElementById('start-experience-btn').classList.add('d-none');
            document.getElementById('retry-connection-btn').classList.add('d-none');

            // Check connection status every 500ms
            connectionCheckInterval = setInterval(checkConnectionStatus, 500);

            // Timeout after 10 seconds
            setTimeout(function() {
                if (connectionCheckInterval) {
                    clearInterval(connectionCheckInterval);

                    // Check final status
                    const finalStatus = getCurrentConnectionStatus();
                    if (finalStatus.pusherConnected) {
                        showConnectionSuccess();
                    } else {
                        showConnectionError();
                    }
                }
            }, 10000);
        }

        function checkConnectionStatus() {
            const status = getCurrentConnectionStatus();

            // Update status displays
            updateStatusDisplay('pusher-status', status.pusherStatus, status.pusherConnected);
            updateStatusDisplay('channel-status', status.channelStatus, status.channelConnected);

            // Check if fully connected
            if (status.pusherConnected && status.channelConnected) {
                clearInterval(connectionCheckInterval);
                connectionCheckInterval = null;
                showConnectionSuccess();
            }
        }

        function getCurrentConnectionStatus() {
            // Check if pusher exists in window scope (set by live-feed.js)
            let pusherConnected = false;
            let channelConnected = false;
            let pusherStatus = 'Connecting...';
            let channelStatus = 'Waiting...';

            if (window.pusher) {
                const state = window.pusher.connection.state;
                pusherStatus = state.charAt(0).toUpperCase() + state.slice(1);
                pusherConnected = state === 'connected';

                if (window.channel && pusherConnected) {
                    channelStatus = 'Subscribed';
                    channelConnected = true;
                } else if (pusherConnected) {
                    channelStatus = 'Subscribing...';
                }
            }

            return {
                pusherConnected,
                channelConnected,
                pusherStatus,
                channelStatus
            };
        }

        function updateStatusDisplay(elementId, text, isConnected) {
            const element = document.getElementById(elementId);
            element.textContent = text;

            // Remove all status classes
            element.classList.remove('text-success', 'text-warning', 'text-danger');

            // Add appropriate class
            if (isConnected) {
                element.classList.add('text-success');
            } else if (text.includes('Failed') || text.includes('Error')) {
                element.classList.add('text-danger');
            } else {
                element.classList.add('text-warning');
            }
        }

        function showConnectionSuccess() {
            document.getElementById('connection-status').classList.add('d-none');
            document.getElementById('connection-error').classList.add('d-none');
            document.getElementById('connection-success').classList.remove('d-none');
            document.getElementById('start-experience-btn').classList.remove('d-none');
        }

        function showConnectionError() {
            document.getElementById('connection-status').classList.add('d-none');
            document.getElementById('connection-success').classList.add('d-none');
            document.getElementById('connection-error').classList.remove('d-none');
            document.getElementById('start-experience-btn').classList.remove('d-none');
            document.getElementById('retry-connection-btn').classList.remove('d-none');
        }

        function resetConnectionCheck() {
            if (connectionCheckInterval) {
                clearInterval(connectionCheckInterval);
                connectionCheckInterval = null;
            }
            startConnectionCheck();
        }

        // Make functions available globally for debugging
        window.modalDebug = {
            checkStatus: getCurrentConnectionStatus,
            showModal: () => connectionModal.show(),
            hideModal: () => connectionModal.hide()
        };
    </script>
    @vite('resources/js/live-feed.js')

</html>
