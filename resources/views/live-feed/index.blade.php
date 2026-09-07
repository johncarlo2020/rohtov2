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
            transform: translateY(-50%);
            width: 500px;
            height: 500px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .qr-container.left {
            left: 15%;
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
    <div class="live-feed-lobby">
        <div class="qr-container left">
            <img class="qr-text" src="{{ asset('images/brand/text-exitement.png') }}" alt="">
            <div class="qr-img"></div>
        </div>

        <div class="qr-container right">
            <img class="qr-text" src="{{ asset('images/brand/text-exitement.png') }}" alt="">
            <div class="qr-img"></div>
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
