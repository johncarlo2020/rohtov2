@extends('layouts.admin')

@section('content')
<div class="game-trigger main-content">
    <div class="card mt-5">
        <div class="card-body">
            <h4 class="card-title text-center mb-2">Game Scale Meter</h4>

            <!-- Scale Meter Container -->
            <div class="scale-meter-container mb-2">
                <div class="gauge-container">
                    <div class="gauge-background">
                        <div class="gauge-fill" id="gaugeFill"></div>
                        <!-- Gauge Tick Marks -->
                        <div class="gauge-ticks">
                            <div class="tick tick-0"></div>
                            <div class="tick tick-25"></div>
                            <div class="tick tick-50"></div>
                            <div class="tick tick-75"></div>
                            <div class="tick tick-100"></div>
                        </div>
                    </div>
                    <div class="gauge-needle" id="gaugeNeedle"></div>
                    <div class="gauge-center"></div>
                    <div class="gauge-labels" id="gaugeLabels">
                        <span class="gauge-label left">0kg<br><small>0</small></span>
                        <span class="gauge-label right" id="maxWeightLabel">{{ ($config->max_weight ?? 4) }}kg<br><small>400</small></span>
                    </div>
                </div>
            </div>

            <!-- Control Buttons -->
            <div class="control-buttons text-center">
                <input type="hidden" id="csrf-token" value="{{ csrf_token() }}">

                <!-- Kibble Count Input -->
                <div class="kibble-control-box mb-4">
                    <div class="kibble-label">Kibbles per Increase</div>
                    <div class="kibble-counter">
                        <button type="button" class="kibble-btn kibble-btn-minus" onclick="decreaseKibble()">
                            <i class="fa-solid fa-minus"></i>
                        </button>
                        <div class="kibble-display">
                            <span id="kibbleCountDisplay">4</span>
                        </div>
                        <button type="button" class="kibble-btn kibble-btn-plus" onclick="increaseKibble()">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                    <div class="kibble-description">Set how many kibbles fall on live feed per weight increase</div>
                </div>

                <button class="btn btn-success mobile-friendly-btn" id="startButton" onclick="startGame()">
                    <i class="fa-solid fa-play"></i> Start
                </button>
                <button class="btn btn-success d-none mobile-friendly-btn" id="increaseButton" onclick="increaseScale()">
                    <i class="fa-solid fa-paw"></i> Increase
                </button>

                <!-- Reset Button Area -->
                <div class="reset-button-area mt-3 d-none" id="resetButtonArea">
                    <button class="btn btn-reset rounded-pill mobile-friendly-btn" id="resetButton" onclick="resetGame()">
                        <i class="fa-solid fa-rotate-left"></i> Reset Game
                    </button>
                </div>
            </div>

            <!-- Remaining Clicks Display -->
            <div class="remaining-clicks-box text-center mb-3">
                <span id="remainingClicksLabel" style="font-weight:700;font-size:16px;color:#2c3e50;background:#f8f9fa;padding:8px 18px;border-radius:12px;border:2px solid #e9ecef;box-shadow:0 2px 8px rgba(0,0,0,0.08);display:inline-block;">
                    Remaining Clicks: <span id="remainingClicksValue"></span>
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Reset Confirmation Modal -->
<div class="modal fade" id="resetConfirmModal" tabindex="-1" aria-labelledby="resetConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content reset-modal">
            <div class="modal-header border-0">
                <h5 class="modal-title text-center w-100" id="resetConfirmModalLabel">
                    <i class="fa-solid fa-exclamation-triangle text-warning"></i>
                    Confirm Game Reset
                </h5>
            </div>
            <div class="modal-body text-center">
                <div class="reset-warning-icon mb-3">
                    <i class="fa-solid fa-rotate-left"></i>
                </div>
                <p class="mb-3"><strong>Are you sure you want to reset the game?</strong></p>
                <p class="text-muted small">This will clear the current progress and return to the start state.</p>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">
                    <i class="fa-solid fa-times"></i> Cancel
                </button>
                <button type="button" class="btn btn-danger" onclick="confirmReset()">
                    <i class="fa-solid fa-check"></i> Yes, Reset Game
                </button>
            </div>
        </div>
    </div>
</div><style>
.scale-meter-container {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 40px 20px;
    margin: 20px 0;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 25px;
    box-shadow: inset 0 2px 10px rgba(0,0,0,0.1);
}

.gauge-container {
    position: relative;
    width: 350px;
    height: 200px;
    margin: 0 auto;
}

.gauge-background {
    width: 350px;
    height: 175px;
    background: conic-gradient(from 180deg,
        #ff4757 0deg,
        #ff6b35 45deg,
        #ffa502 90deg,
        #f39c12 120deg,
        #2ed573 180deg);
    border-radius: 175px 175px 0 0;
    position: relative;
    overflow: hidden;
    box-shadow:
        0 8px 32px rgba(0,0,0,0.15),
        inset 0 2px 0 rgba(255,255,255,0.3),
        inset 0 -2px 0 rgba(0,0,0,0.1);
    border: 3px solid #ffffff;
}

.gauge-background::after {
    content: '';
    position: absolute;
    top: 25px;
    left: 25px;
    width: 300px;
    height: 150px;
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-radius: 150px 150px 0 0;
    box-shadow:
        0 4px 20px rgba(0,0,0,0.1),
        inset 0 2px 0 rgba(255,255,255,0.8);
}

.gauge-background::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, transparent 0%, rgba(255,255,255,0.2) 50%, transparent 100%);
    border-radius: 175px 175px 0 0;
    z-index: 1;
}

.gauge-needle {
    position: absolute;
    top: 173px;
    left: 175px;
    width: 6px;
    height: 125px;
    background: linear-gradient(135deg, #c0392b 0%, #e74c3c 50%, #c0392b 100%);
    transform-origin: bottom center;
    transform: translate(-50%, -100%);
    transition: transform 0.8s cubic-bezier(0.4, 0.0, 0.2, 1);
    z-index: 20;
    clip-path: polygon(50% 0%, 20% 90%, 50% 100%, 80% 90%);
    filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3));
}

.gauge-needle::before {
    content: '';
    position: absolute;
    bottom: -8px;
    left: 50%;
    transform: translateX(-50%);
    width: 16px;
    height: 16px;
    background: linear-gradient(135deg, #c0392b 0%, #e74c3c 100%);
    border-radius: 50%;
    border: 3px solid #ffffff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.3);
}

.gauge-center {
    position: absolute;
    top: 173px;
    left: 175px;
    width: 24px;
    height: 24px;
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    z-index: 25;
    border: 4px solid #ffffff;
    box-shadow:
        0 4px 12px rgba(0,0,0,0.3),
        inset 0 2px 0 rgba(255,255,255,0.3);
}

.gauge-labels {
    position: absolute;
    width: 100%;
    height: 100%;
    z-index: 10;
}

.gauge-label {
    position: absolute;
    font-weight: 700;
    font-size: 12px;
    color: #2c3e50;
    text-align: center;
    line-height: 1.1;
    width: 50px;
    padding: 6px;
    background: rgba(255,255,255,0.9);
    border-radius: 8px;
    border: 2px solid #e9ecef;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    backdrop-filter: blur(5px);
}

.gauge-label small {
    font-size: 9px;
    color: #7f8c8d;
    font-weight: 500;
    display: block;
    margin-top: 2px;
}

.gauge-label.left {
    top: 5px;
    left: 5px;
    background: linear-gradient(135deg, #ff7675 0%, #fd79a8 100%);
    color: white;
    border-color: #ff7675;
}

.gauge-label.left small {
    color: rgba(255,255,255,0.8);
}

.gauge-label.right {
    top: 5px;
    right: 5px;
    background: linear-gradient(135deg, #00b894 0%, #00cec9 100%);
    color: white;
    border-color: #00b894;
}

.gauge-label.right small {
    color: rgba(255,255,255,0.8);
}

.gauge-label.center {
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%);
    color: white;
    border-color: #6c5ce7;
    font-size: 14px;
    padding: 8px 12px;
    box-shadow: 0 4px 15px rgba(108, 92, 231, 0.3);
}

.gauge-ticks {
    position: absolute;
    width: 100%;
    height: 100%;
    z-index: 5;
}

.tick {
    position: absolute;
    width: 3px;
    height: 20px;
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
    transform-origin: bottom center;
    border-radius: 2px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.tick-0 {
    bottom: 0;
    left: 25px;
    transform: rotate(-90deg);
}

.tick-25 {
    bottom: 35px;
    left: 65px;
    transform: rotate(-45deg);
}

.tick-50 {
    bottom: 75px;
    left: 50%;
    transform: translateX(-50%);
}

.tick-75 {
    bottom: 35px;
    right: 65px;
    transform: rotate(45deg);
}

.tick-100 {
    bottom: 0;
    right: 25px;
    transform: rotate(90deg);
}

.control-buttons .btn {
    min-width: 120px;
    font-weight: 600;
    border-radius: 25px;
    padding: 10px 20px;
    transition: all 0.3s ease;
}

.control-buttons .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

/* Mobile-friendly button styles to prevent zoom */
.mobile-friendly-btn {
    min-width: 150px !important;
    min-height: 50px !important;
    font-size: 16px !important;
    padding: 15px 25px !important;
    touch-action: manipulation !important;
    -webkit-tap-highlight-color: transparent !important;
    -webkit-touch-callout: none !important;
    -webkit-user-select: none !important;
    -moz-user-select: none !important;
    -ms-user-select: none !important;
    user-select: none !important;
}

/* Prevent zoom on mobile devices */
@media (max-width: 768px) {
    .mobile-friendly-btn {
        min-width: 200px !important;
        min-height: 60px !important;
        font-size: 18px !important;
        padding: 20px 30px !important;
        margin: 10px 5px !important;
    }

    .kibble-btn {
        min-width: 55px !important;
        min-height: 55px !important;
        font-size: 18px !important;
        touch-action: manipulation !important;
        -webkit-tap-highlight-color: transparent !important;
    }

    .control-buttons {
        padding: 20px 10px !important;
    }

    /* Prevent text selection and callouts */
    * {
        -webkit-tap-highlight-color: transparent !important;
        -webkit-touch-callout: none !important;
        -webkit-user-select: none !important;
        -moz-user-select: none !important;
        -ms-user-select: none !important;
        user-select: none !important;
    }
}

.kibble-control-box {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border: 2px solid #e9ecef;
    border-radius: 20px;
    padding: 25px 20px;
    margin: 0 auto 30px auto;
    max-width: 300px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.kibble-label {
    font-weight: 700;
    color: #2c3e50;
    font-size: 16px;
    text-align: center;
    margin-bottom: 15px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.kibble-counter {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
    margin-bottom: 15px;
    position: relative;
    z-index: 50;
}

.kibble-btn {
    width: 45px;
    height: 45px;
    border: 2px solid #2ed573;
    background: linear-gradient(135deg, #2ed573 0%, #26d063 100%);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 16px;
    font-weight: bold;
    box-shadow: 0 4px 15px rgba(46, 213, 115, 0.3);
    position: relative;
    z-index: 100;
    outline: none;
}

.kibble-btn:hover {
    transform: translateY(-2px) scale(1.05);
    box-shadow: 0 6px 20px rgba(46, 213, 115, 0.4);
    background: linear-gradient(135deg, #26d063 0%, #2ed573 100%);
}

.kibble-btn:active {
    transform: translateY(0) scale(0.95);
}

.kibble-btn:focus {
    outline: 2px solid #2ed573;
    outline-offset: 2px;
}

.kibble-btn-minus:hover {
    background: linear-gradient(135deg, #ff6b6b 0%, #ff5252 100%);
    border-color: #ff6b6b;
    box-shadow: 0 6px 20px rgba(255, 107, 107, 0.4);
}

.kibble-display {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    width: 70px;
    height: 70px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    font-weight: 900;
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
    border: 3px solid white;
}

.kibble-description {
    font-size: 12px;
    color: #6c757d;
    text-align: center;
    line-height: 1.4;
    font-style: italic;
}

.kibble-control {
    margin-bottom: 20px;
}

.kibble-control label {
    font-weight: 600;
    color: #333;
    margin-bottom: 5px;
    font-size: 14px;
}

.kibble-control input {
    border: 2px solid #2ed573;
    border-radius: 8px;
    text-align: center;
    font-weight: bold;
}

.kibble-control input:focus {
    outline: none;
    border-color: #ff6b35;
    box-shadow: 0 0 0 0.2rem rgba(46, 213, 115, 0.25);
}

.game-trigger {
    max-width: 600px;
    margin: 0 auto;
}

#currentValue {
    font-size: 18px;
    padding: 8px 16px;
}

/* Reset Confirmation Modal Styles */
.reset-modal {
    border-radius: 20px;
    box-shadow: 0 15px 50px rgba(0,0,0,0.3);
    border: none;
    overflow: hidden;
}

.reset-modal .modal-header {
    background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
    color: white;
    padding: 20px;
}

.reset-modal .modal-title {
    font-weight: 700;
    font-size: 18px;
    letter-spacing: 0.5px;
}

.reset-modal .modal-body {
    padding: 30px 20px;
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
}

.reset-warning-icon {
    font-size: 48px;
    color: #ff6b35;
    opacity: 0.8;
}

.reset-modal .modal-footer {
    background: #f8f9fa;
    padding: 20px;
}

.reset-modal .btn {
    min-width: 120px;
    font-weight: 600;
    border-radius: 25px;
    padding: 10px 20px;
    transition: all 0.3s ease;
}

.reset-modal .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

/* Reset Button Styling */
.reset-button-area {
    margin-top: 20px;
}

.btn-reset {
    background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
    border: 2px solid #ff6b35;
    color: white;
    font-weight: 600;
    padding: 12px 24px;
    min-width: 150px;
    border-radius: 50px !important;
    box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
    transition: all 0.3s ease;
    font-size: 14px;
    letter-spacing: 0.5px;
}

.btn-reset:hover {
    background: linear-gradient(135deg, #f7931e 0%, #ff6b35 100%);
    border-color: #f7931e;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(255, 107, 53, 0.4);
}

.btn-reset:active {
    transform: translateY(0);
    box-shadow: 0 3px 10px rgba(255, 107, 53, 0.3);
}

.btn-reset:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.2);
}

.btn-reset:disabled {
    background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
    border-color: #95a5a6;
    color: #ffffff;
    cursor: not-allowed !important;
    transform: none !important;
    box-shadow: 0 2px 8px rgba(149, 165, 166, 0.3);
    opacity: 0.6;
}

.btn-reset:disabled:hover {
    background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
    border-color: #95a5a6;
    transform: none !important;
    box-shadow: 0 2px 8px rgba(149, 165, 166, 0.3);
}
</style>

<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script>
let currentWeight = 0.0; // Starting weight in kg - Always start with 0
let maxWeight = {{ $config->max_weight ?? 4.0 }}; // Maximum weight in kg from database
let incrementGrams = {{ $config->increment_grams ?? 100 }}; // Increment per click in grams from database
const INTERNAL_MAX = 400; // Internal calculation range (0-400)
let totalKibbles = 0; // Track total kibbles dropped
let kibbleCount = 4; // Current kibble count per increase

// Pusher setup for broadcasting events
const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
    cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
    encrypted: true,
});

const channel = pusher.subscribe("live-feed-channel");

channel.bind("live-feed-event", (data) => {
    console.log("Received live feed event:", data);

    // Listen for enable-increase event to enable increase button
    if (data.action === 'enable-increase') {
        enableIncreaseButton();
    }

    // Listen for game finish acknowledgment to reset admin interface
    if (data.action === 'finish-ack') {
        console.log('Game finish acknowledged by live feed');
        setTimeout(() => {
            resetToStartState();
        }, 8000); // Reset after 8 seconds to match live feed timing
    }
});

// Function to enable the increase button
function enableIncreaseButton() {
    const startButton = document.getElementById('startButton');
    const increaseButton = document.getElementById('increaseButton');

    // Clear starting state
    isStarting = false;
    if (startTimeout) {
        clearTimeout(startTimeout);
        startTimeout = null;
    }

    // Hide start button and show increase button
    startButton.classList.add('d-none');
    increaseButton.classList.remove('d-none');

    // Reset start button for next game
    startButton.innerHTML = '<i class="fa-solid fa-play"></i> Start';
    startButton.disabled = false;
    startButton.style.backgroundColor = '';
    startButton.onclick = startGame;

    console.log('Increase button enabled - Game is ready!');
}

// Retry game start function
function retryGameStart() {
    console.log('Retrying game start...');

    const startButton = document.getElementById('startButton');

    // Reset state
    isStarting = false;
    if (startTimeout) {
        clearTimeout(startTimeout);
        startTimeout = null;
    }

    // Reset button appearance
    startButton.style.backgroundColor = '';
    startButton.onclick = startGame;

    // Try starting again
    startGame();
}

// Enhanced event triggering with retry mechanism
function triggerLiveFeedEventWithRetry(action, data = null, retryCount = 0) {
    const maxRetries = 3;

    console.log(`Triggering live feed event: ${action} (attempt ${retryCount + 1})`, data);

    $.ajax({
        url: '{{ route('trigger.live.feed') }}',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        data: {
            action: action,
            data: data
        },
        timeout: 5000, // 5 second timeout
        success: function(response) {
            console.log(`LiveFeedEvent triggered successfully: ${action}`);

            // For start events, wait for acknowledgment
            if (action === 'start') {
                console.log('Game start event sent, waiting for live feed acknowledgment...');

                // If no acknowledgment after 10 seconds, show manual recovery
                setTimeout(() => {
                    if (isStarting) {
                        console.warn('No acknowledgment from live feed after 10 seconds');
                        showManualRecovery();
                    }
                }, 10000);
            }
        },
        error: function(xhr, status, error) {
            console.error(`Error triggering live feed event (attempt ${retryCount + 1}):`, error);

            if (retryCount < maxRetries) {
                console.log(`Retrying in 2 seconds... (${retryCount + 1}/${maxRetries})`);
                setTimeout(() => {
                    triggerLiveFeedEventWithRetry(action, data, retryCount + 1);
                }, 2000);
            } else {
                console.error('Max retries reached for live feed event');
                showConnectionError();
            }
        }
    });
}

// Show manual recovery options
function showManualRecovery() {
    const startButton = document.getElementById('startButton');

    startButton.innerHTML = `
        <div style="font-size: 12px;">
            <i class="fa-solid fa-exclamation-triangle"></i> Live Feed Not Responding
            <br><button onclick="forceGameStart()" style="margin-top: 5px; padding: 2px 8px; font-size: 11px; background: #ff6b35; color: white; border: none; border-radius: 4px;">Force Start</button>
            <button onclick="resetGameAdmin()" style="margin-top: 5px; margin-left: 5px; padding: 2px 8px; font-size: 11px; background: #dc3545; color: white; border: none; border-radius: 4px;">Reset</button>
        </div>
    `;
}

// Force start bypass
function forceGameStart() {
    console.log('Force starting game...');

    // Directly enable the increase button
    enableIncreaseButton();

    // Also try to trigger enable-increase event for live feed
    triggerLiveFeedEventWithRetry('enable-increase', {
        forced: true,
        maxWeight: maxWeight,
        incrementGrams: incrementGrams
    });
}

// Reset admin game state
function resetGameAdmin() {
    console.log('Resetting admin game state...');

    const startButton = document.getElementById('startButton');
    const increaseButton = document.getElementById('increaseButton');
    const resetButtonArea = document.getElementById('resetButtonArea');

    // Reset all states
    isStarting = false;
    if (startTimeout) {
        clearTimeout(startTimeout);
        startTimeout = null;
    }

    // Reset UI
    startButton.classList.remove('d-none');
    increaseButton.classList.add('d-none');
    resetButtonArea.classList.add('d-none');

    startButton.innerHTML = '<i class="fa-solid fa-play"></i> Start';
    startButton.disabled = false;
    startButton.style.backgroundColor = '';
    startButton.style.cursor = 'pointer';
    startButton.onclick = startGame;

    // Reset scale
    currentWeight = 0.0;
    totalKibbles = 0;
    updateMeter();

    // Send reset event to live feed
    triggerLiveFeedEventWithRetry('reset', {
        status: 'admin_reset'
    });
}

// Show connection error
function showConnectionError() {
    const startButton = document.getElementById('startButton');

    startButton.innerHTML = `
        <div style="font-size: 12px;">
            <i class="fa-solid fa-wifi"></i> Connection Error
            <br><button onclick="retryGameStart()" style="margin-top: 5px; padding: 2px 8px; font-size: 11px; background: #28a745; color: white; border: none; border-radius: 4px;">Retry</button>
        </div>
    `;
    startButton.disabled = false;
}

// Reset admin interface to start state after game completion
function resetToStartState() {
    console.log('Reset requested - showing confirmation modal...');

    // Show confirmation modal using Bootstrap 5
    const modal = new bootstrap.Modal(document.getElementById('resetConfirmModal'));
    modal.show();
}

// Actual reset function called after confirmation
function confirmReset() {
    console.log('Reset confirmed - resetting admin interface to start state');

    const startButton = document.getElementById('startButton');
    const increaseButton = document.getElementById('increaseButton');

    // Reset all states
    isStarting = false;
    if (startTimeout) {
        clearTimeout(startTimeout);
        startTimeout = null;
    }

    // Reset UI to start state
    startButton.classList.remove('d-none');
    increaseButton.classList.add('d-none');

    // Reset start button
    startButton.innerHTML = '<i class="fa-solid fa-play"></i> Start';
    startButton.disabled = false;
    startButton.style.backgroundColor = '';
    startButton.style.cursor = 'pointer';
    startButton.onclick = startGame;

    // Reset increase button
    increaseButton.innerHTML = '<i class="fa-solid fa-paw"></i> Increase';
    increaseButton.disabled = false;
    increaseButton.style.backgroundColor = '';
    increaseButton.style.cursor = 'pointer';

    // Reset scale
    currentWeight = 0.0;
    totalKibbles = 0;
    updateMeter();

    // Close the modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('resetConfirmModal'));
    modal.hide();

    console.log('Admin interface reset - ready for new game!');
}

// Get CSRF token from hidden input
const csrfToken = document.getElementById('csrf-token').value;

// Function to trigger LiveFeedEvent (legacy - now uses retry version)
function triggerLiveFeedEvent(action, data = null) {
    return triggerLiveFeedEventWithRetry(action, data);
}

// Load configuration from database (fallback API call if needed)
async function loadConfiguration() {
    try {
        const response = await fetch('{{ route('game.config.active') }}');
        if (response.ok) {
            const config = await response.json();

            // Check if configuration has changed
            const oldMaxWeight = maxWeight;
            const oldIncrement = incrementGrams;

            // Ensure values are properly converted to numbers
            maxWeight = parseFloat(config.max_weight) || 4.0;
            incrementGrams = parseInt(config.increment_grams) || 100;

            // Update labels after loading config
            updateLabels();

            // Show notification if config changed (not on initial load)
            if (oldMaxWeight !== maxWeight || oldIncrement !== incrementGrams) {
                console.log('Configuration updated - Max Weight:', maxWeight, 'kg, Increment:', incrementGrams, 'g');
            }

            console.log('Configuration loaded from database:', config);
        }
    } catch (error) {
        console.log('Using default configuration, database not available:', error);
        // Keep the server-side values if API fails
    }
}

function updateMeter() {
    const gaugeNeedle = document.getElementById('gaugeNeedle');

    // Convert current weight to percentage based on max weight
    const percentage = (currentWeight / maxWeight) * 100;

    // Calculate internal value (0-400 scale)
    const internalValue = (currentWeight / maxWeight) * INTERNAL_MAX;

    // Calculate rotation angle (-90deg to 90deg for 180deg range)
    const angle = (percentage / 100) * 180 - 90;

    // Update needle rotation
    gaugeNeedle.style.transform = `translate(-50%, -100%) rotate(${angle}deg)`;
    updateRemainingClicks();
}

// Update gauge labels dynamically
function updateLabels() {
    const maxWeightLabel = document.getElementById('maxWeightLabel');

    if (maxWeightLabel) {
        // Ensure maxWeight is a number before calling toFixed
        const maxWeightValue = parseFloat(maxWeight) || 4.0;
        maxWeightLabel.innerHTML = `${maxWeightValue.toFixed(1)}kg<br><small>400</small>`;
    }
}

// Calculate and update remaining clicks display
function updateRemainingClicks() {
    // Calculate remaining clicks based on maxWeight, currentWeight, and incrementGrams
    const remaining = Math.max(0, Math.floor((maxWeight - currentWeight) * 1000 / incrementGrams));
    document.getElementById('remainingClicksValue').textContent = remaining;
}

// Increase kibble count
function increaseKibble() {
    console.log('Increase kibble clicked, current count:', kibbleCount);
    if (kibbleCount < 20) {
        kibbleCount++;
        document.getElementById('kibbleCountDisplay').textContent = kibbleCount;
        console.log('Kibble count increased to:', kibbleCount);
    } else {
        console.log('Maximum kibble count reached (20)');
    }
}

// Decrease kibble count
function decreaseKibble() {
    console.log('Decrease kibble clicked, current count:', kibbleCount);
    if (kibbleCount > 1) {
        kibbleCount--;
        document.getElementById('kibbleCountDisplay').textContent = kibbleCount;
        console.log('Kibble count decreased to:', kibbleCount);
    } else {
        console.log('Minimum kibble count reached (1)');
    }
}

function increaseScale() {
    const incrementKg = incrementGrams / 1000; // Convert grams to kg
    const newWeight = currentWeight + incrementKg;

    if (newWeight <= maxWeight) {
        currentWeight = newWeight;
        updateMeter();

        // Use the current kibble count from the + / - buttons
        const kibblesThisIncrease = kibbleCount;

        totalKibbles += kibblesThisIncrease;

        // Check if maximum weight has been reached
        const hasReachedMax = currentWeight >= maxWeight;

        if (hasReachedMax) {
            console.log('🎉 Maximum weight reached! Sending finish event...');

            // Send finish event when max weight is reached
            triggerLiveFeedEvent('finish', {
                currentWeight: currentWeight,
                maxWeight: maxWeight,
                incrementGrams: incrementGrams,
                kibbleCount: kibblesThisIncrease,
                totalKibbles: totalKibbles,
                status: 'game_finished',
                message: 'Maximum weight achieved! Game completed successfully!'
            });

            // Disable increase button to prevent further increases
            const increaseButton = document.getElementById('increaseButton');
            increaseButton.disabled = true;
            increaseButton.innerHTML = '<i class="fa-solid fa-trophy"></i> Max Reached!';
            increaseButton.style.backgroundColor = '#28a745';

            // Show reset button
            showResetButton();

            console.log('🏆 Game completed! Maximum weight of', maxWeight, 'kg achieved!');
        } else {
            // Regular weight update event
            triggerLiveFeedEvent('update', {
                currentWeight: currentWeight,
                maxWeight: maxWeight,
                incrementGrams: incrementGrams,
                kibbleCount: kibblesThisIncrease,
                totalKibbles: totalKibbles,
                status: 'weight_updated'
            });
        }

        console.log('Weight updated to:', currentWeight, 'kg', '| Kibbles dropped:', kibblesThisIncrease, '| Total:', totalKibbles);
    } else {
        console.log('Cannot increase weight further - maximum weight already reached');

        // Send finish event to live feed
        triggerLiveFeedEvent('finish', {
            currentWeight: currentWeight,
            maxWeight: maxWeight,
            incrementGrams: incrementGrams,
            kibbleCount: 0,
            totalKibbles: totalKibbles,
            status: 'game_finished',
            message: 'Maximum weight limit exceeded!'
        });

        // Show reset button instead of auto-resetting
        showResetButton();
    }
}

// Show reset button when max weight is reached
function showResetButton() {
    const resetButtonArea = document.getElementById('resetButtonArea');
    const resetButton = document.getElementById('resetButton');

    // Show the dedicated reset button area
    resetButtonArea.classList.remove('d-none');

    // Initially disable the reset button to prevent spam clicking
    resetButton.disabled = true;
    resetButton.innerHTML = '<i class="fa-solid fa-clock"></i> Please wait...';
    resetButton.style.opacity = '0.6';
    resetButton.style.cursor = 'not-allowed';

    // Enable the reset button after 3 seconds
    setTimeout(() => {
        resetButton.disabled = false;
        resetButton.innerHTML = '<i class="fa-solid fa-rotate-left"></i> Reset Game';
        resetButton.style.opacity = '1';
        resetButton.style.cursor = 'pointer';
        console.log('Reset button enabled');
    }, 3000);

    console.log('Reset button shown (will be enabled in 3 seconds)');
}// Simple reset function that reloads the page
function resetGame() {
    console.log('Resetting game by reloading page...');
        triggerLiveFeedEventWithRetry('reset', null);
    location.reload();
}

// Game start state tracking
let isStarting = false;
let startTimeout = null;

function startGame() {
    const startButton = document.getElementById('startButton');
    const increaseButton = document.getElementById('increaseButton');

    // Prevent multiple start attempts
    if (isStarting) {
        console.warn('Game start already in progress');
        return;
    }

    isStarting = true;
    console.log('Starting game...');

    // Show loading state on start button
    startButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Starting...';
    startButton.disabled = true;

    // Reset the scale and kibble count when starting
    currentWeight = 0.0;
    totalKibbles = 0;
    updateMeter();

    // Trigger live feed event for game start with enhanced error handling
    triggerLiveFeedEventWithRetry('start', {
        maxWeight: maxWeight,
        incrementGrams: incrementGrams,
        status: 'game_started'
    });

    // Safety timeout - if no response after 8 seconds, allow manual intervention
    startTimeout = setTimeout(() => {
        if (isStarting) {
            console.warn('Game start taking longer than expected, adding recovery options');

            // Add recovery button
            startButton.innerHTML = `
                <div>
                    <i class="fa-solid fa-exclamation-triangle"></i> Stuck?
                    <br><small>Click to retry</small>
                </div>
            `;
            startButton.onclick = retryGameStart;
            startButton.disabled = false;
            startButton.style.backgroundColor = '#ff6b35';
        }
    }, 8000);

    console.log('Game start initiated!');
}

// Initialize meter on page load
document.addEventListener('DOMContentLoaded', function() {
    loadConfiguration(); // Load saved configuration first
    updateMeter();
    updateLabels();

    // Initialize connection monitoring
    initializeConnectionMonitoring();

    // Prevent zoom on mobile devices
    preventMobileZoom();
});

// Prevent mobile zoom and improve touch interaction
function preventMobileZoom() {
    // Prevent double-tap zoom
    let lastTouchEnd = 0;
    document.addEventListener('touchend', function (event) {
        const now = (new Date()).getTime();
        if (now - lastTouchEnd <= 300) {
            event.preventDefault();
        }
        lastTouchEnd = now;
    }, false);

    // Prevent pinch zoom
    document.addEventListener('gesturestart', function (e) {
        e.preventDefault();
    });

    document.addEventListener('gesturechange', function (e) {
        e.preventDefault();
    });

    document.addEventListener('gestureend', function (e) {
        e.preventDefault();
    });

    // Improve button touch handling
    const buttons = document.querySelectorAll('.mobile-friendly-btn, .kibble-btn');
    buttons.forEach(button => {
        // Prevent default touch behaviors that can cause zoom
        button.addEventListener('touchstart', function(e) {
            e.stopPropagation();
        }, { passive: true });

        button.addEventListener('touchend', function(e) {
            e.stopPropagation();
        }, { passive: true });

        // Add visual feedback for touch
        button.addEventListener('touchstart', function() {
            this.style.transform = 'scale(0.95)';
        });

        button.addEventListener('touchend', function() {
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    });

    console.log('Mobile zoom prevention and touch optimization initialized');
}

// Connection monitoring
let lastHeartbeat = Date.now();
let connectionHealthy = true;

function initializeConnectionMonitoring() {
    // Send periodic heartbeat to check live feed connection
    setInterval(() => {
        if (!isStarting) { // Don't interfere during game start
            pingLiveFeed();
        }
    }, 10000); // Every 10 seconds

    // Monitor connection health
    setInterval(() => {
        const timeSinceHeartbeat = Date.now() - lastHeartbeat;
        const wasHealthy = connectionHealthy;
        connectionHealthy = timeSinceHeartbeat < 30000; // 30 second timeout

        if (wasHealthy && !connectionHealthy) {
            console.warn('Live feed connection appears unhealthy');
            showConnectionStatus(false);
        } else if (!wasHealthy && connectionHealthy) {
            console.log('Live feed connection restored');
            showConnectionStatus(true);
        }
    }, 5000);
}

function pingLiveFeed() {
    $.ajax({
        url: '{{ route('trigger.live.feed') }}',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        data: {
            action: 'ping',
            data: { timestamp: Date.now() }
        },
        timeout: 3000,
        success: function(response) {
            lastHeartbeat = Date.now();
        },
        error: function() {
            console.warn('Heartbeat failed - live feed may be disconnected');
        }
    });
}

function showConnectionStatus(isHealthy) {
    const existingStatus = document.getElementById('connection-status');
    if (existingStatus) {
        existingStatus.remove();
    }

    if (!isHealthy) {
        const statusDiv = document.createElement('div');
        statusDiv.id = 'connection-status';
        statusDiv.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #dc3545;
            color: white;
            padding: 10px 15px;
            border-radius: 8px;
            font-size: 12px;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        `;
        statusDiv.innerHTML = '<i class="fa-solid fa-exclamation-triangle"></i> Live Feed Connection Issues';
        document.body.appendChild(statusDiv);
    }
}

// Refresh configuration when page becomes visible (user navigates back from config page)
document.addEventListener('visibilitychange', function() {
    if (!document.hidden) {
        loadConfiguration();
    }
});

// Also refresh when window gains focus
window.addEventListener('focus', function() {
    loadConfiguration();
});
</script>
@endsection
