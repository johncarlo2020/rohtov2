@extends('layouts.admin')

@section('content')
<div class="game-trigger main-content">
    <div class="card mt-5">
        <div class="card-body">
            <h4 class="card-title text-center mb-4">Game Scale Meter</h4>

            <!-- Scale Meter Container -->
            <div class="scale-meter-container mb-4">
                <div class="gauge-container">
                    <div class="gauge-background">
                        <div class="gauge-fill" id="gaugeFill"></div>
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
                <button class="btn btn-success" id="startButton" onclick="startGame()">
                    <i class="fa-solid fa-play"></i> Start
                </button>
                <button class="btn btn-success d-none" id="increaseButton" onclick="increaseScale()">
                    <i class="fa-solid fa-paw"></i> Increase
                </button>
            </div>
        </div>
    </div>
</div><style>
.scale-meter-container {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 30px 20px;
    margin: 20px 0;
}

.gauge-container {
    position: relative;
    width: 320px;
    height: 180px;
    margin: 0 auto;
}

.gauge-background {
    width: 320px;
    height: 160px;
    background: conic-gradient(from 180deg, #ff4757 0deg, #ffa502 90deg, #2ed573 180deg);
    border-radius: 160px 160px 0 0;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.gauge-background::after {
    content: '';
    position: absolute;
    top: 20px;
    left: 20px;
    width: 280px;
    height: 140px;
    background: #f8f9fa;
    border-radius: 140px 140px 0 0;
}

.gauge-needle {
    position: absolute;
    top: 158px;
    left: 160px;
    width: 4px;
    height: 110px;
    background: #d32f2f;
    transform-origin: bottom center;
    transform: translate(-50%, -100%);
    transition: transform 0.5s ease;
    z-index: 10;
    clip-path: polygon(50% 0%, 0% 100%, 100% 100%);
}

.gauge-needle::before {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 50%;
    transform: translateX(-50%);
    width: 12px;
    height: 12px;
    background: #d32f2f;
    border-radius: 50%;
    border: 2px solid #f8f9fa;
}

.gauge-center {
    position: absolute;
    top: 158px;
    left: 160px;
    width: 18px;
    height: 18px;
    background: #d32f2f;
    border-radius: 50%;
    transform: translate(-50%, -50%);
    z-index: 15;
    border: 3px solid #f8f9fa;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.gauge-labels {
    position: absolute;
    width: 100%;
    height: 100%;
}

.gauge-label {
    position: absolute;
    font-weight: bold;
    font-size: 11px;
    color: #333;
    text-align: center;
    line-height: 1.1;
    width: 40px;
    padding: 2px;
}

.gauge-label small {
    font-size: 8px;
    color: #666;
    font-weight: normal;
    display: block;
}

.gauge-label.left {
    top: 0px;
    left: 0px;
}

.gauge-label.center {
    top: 15px;
    left: 50%;
    transform: translateX(-50%);
    color: white;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.8);
    background: rgba(0,0,0,0.3);
    border-radius: 4px;
    padding: 4px 6px;
}

.gauge-label.right {
    top:0px;
    right:0px;
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

.game-trigger {
    max-width: 600px;
    margin: 0 auto;
}

#currentValue {
    font-size: 18px;
    padding: 8px 16px;
}
</style>

<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script>
let currentWeight = 0.0; // Starting weight in kg - Always start with 0
let maxWeight = {{ $config->max_weight ?? 4.0 }}; // Maximum weight in kg from database
let incrementGrams = {{ $config->increment_grams ?? 100 }}; // Increment per click in grams from database
const INTERNAL_MAX = 400; // Internal calculation range (0-400)

// Pusher setup for broadcasting events
const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
    cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
    encrypted: true,
});

const channel = pusher.subscribe("live-feed-channel");

channel.bind("live-feed-event", (data) => {
    console.log("Received live feed event:", data);
});

// Get CSRF token from hidden input
const csrfToken = document.getElementById('csrf-token').value;

// Function to trigger LiveFeedEvent
function triggerLiveFeedEvent(action, data = null) {
    console.log(`Triggering live feed event: ${action}`, data);
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
        success: function(response) {
            console.log(`LiveFeedEvent triggered: ${action}`);
        },
        error: function(xhr, status, error) {
            console.error('Error triggering live feed event:', error);
        }
    });
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

            maxWeight = config.max_weight || 4.0;
            incrementGrams = config.increment_grams || 100;

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
}

// Update gauge labels dynamically
function updateLabels() {
    const maxWeightLabel = document.getElementById('maxWeightLabel');

    if (maxWeightLabel) {
        maxWeightLabel.innerHTML = `${maxWeight.toFixed(1)}kg<br><small>400</small>`;
    }
}

function increaseScale() {
    const incrementKg = incrementGrams / 1000; // Convert grams to kg
    const newWeight = currentWeight + incrementKg;

    if (newWeight <= maxWeight) {
        currentWeight = newWeight;
        updateMeter();
    }
}

function startGame() {
    // Hide start button and show increase button
    document.getElementById('startButton').classList.add('d-none');
    document.getElementById('increaseButton').classList.remove('d-none');

    // Reset the scale to 0 when starting
    currentWeight = 0.0;
    updateMeter();

    // Trigger live feed event for game start
    triggerLiveFeedEvent('start', {
        maxWeight: maxWeight,
        incrementGrams: incrementGrams,
        status: 'game_started'
    });

    console.log('Game started!');
}

// Initialize meter on page load
document.addEventListener('DOMContentLoaded', function() {
    loadConfiguration(); // Load saved configuration first
    updateMeter();
    updateLabels();
});

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
