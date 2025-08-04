@extends('layouts.admin')

@section('content')
<div class="game-config">
    <div class="card mt-5">
        <div class="card-body">
            <h4 class="card-title text-center mb-4">Game Configuration</h4>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="maxValueInput" class="form-label">Max Gauge Value (kg):</label>
                        <input type="number" class="form-control" id="maxValueInput" step="0.1" value="{{ $config->max_weight ?? 4 }}" oninput="updateMaxValue()">
                        <div class="form-text">Set the maximum weight the gauge can display</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="incrementInput" class="form-label">Increment per Click (grams):</label>
                        <input type="number" class="form-control" id="incrementInput" step="10" value="{{ $config->increment_grams ?? 100 }}" oninput="updateIncrement()">
                        <div class="form-text">How much weight each +/- button adds/removes</div>
                    </div>
                </div>
            </div>

            <!-- Configuration Display -->
            <div class="mt-4 p-3 bg-light rounded">
                <small class="text-muted">
                    <strong>Current Config:</strong>
                    Max: <span id="configMax">{{ ($config->max_weight ?? 4) }}kg</span> |
                    Increment: <span id="configIncrement">{{ ($config->increment_grams ?? 100) }}g</span> per click |
                    Clicks to Complete: <span id="configClicks">0</span>
                </small>
            </div>

            <!-- Save Configuration Button -->
            <div class="mt-4 text-end">
                <button class="btn btn-success btn-lg" onclick="saveConfiguration()" id="saveBtn">
                    <i class="fas fa-save"></i> Save Configuration
                </button>
            </div>

            <!-- Success/Error Messages -->
            <div id="messageContainer" class="mt-3"></div>
        </div>
    </div>
</div>

<style>
.game-config {
    max-width: 800px;
    margin: 0 auto;
}

.btn-lg {
    padding: 12px 30px;
    font-size: 16px;
    border-radius: 8px;
}

.alert {
    padding: 10px 15px;
    border-radius: 5px;
    margin-top: 10px;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}
</style>

<script>
let maxWeight = {{ $config->max_weight ?? 4.0 }}; // Maximum weight in kg
let incrementGrams = {{ $config->increment_grams ?? 100 }}; // Increment per click in grams
const INTERNAL_MAX = 400; // Internal calculation range (0-400)

// CSRF token for Laravel
const csrfToken = '{{ csrf_token() }}';

// Update max value configuration
function updateMaxValue() {
    const input = document.getElementById('maxValueInput');
    const newMax = parseFloat(input.value);

    if (!isNaN(newMax) && newMax > 0) {
        maxWeight = newMax;
        updateConfigDisplay();
    }
}

// Update increment configuration
function updateIncrement() {
    const input = document.getElementById('incrementInput');
    const newIncrement = parseInt(input.value);

    if (!isNaN(newIncrement) && newIncrement > 0) {
        incrementGrams = newIncrement;
        updateConfigDisplay();
    }
}

// Update configuration display
function updateConfigDisplay() {
    const maxWeightNum = parseFloat(maxWeight);
    const incrementGramsNum = parseInt(incrementGrams);

    document.getElementById('configMax').textContent = maxWeightNum.toFixed(1) + 'kg';
    document.getElementById('configIncrement').textContent = incrementGramsNum + 'g';

    // Calculate how many clicks to complete (from 0 to maxWeight, in increments of incrementGrams)
    // Convert maxWeight (kg) to grams
    const maxWeightGrams = maxWeightNum * 1000;
    let clicks = 0;
    if (incrementGramsNum > 0) {
        clicks = Math.ceil(maxWeightGrams / incrementGramsNum);
    }
    document.getElementById('configClicks').textContent = clicks;
}

// Show message to user
function showMessage(message, type = 'success') {
    const container = document.getElementById('messageContainer');
    container.innerHTML = `<div class="alert alert-${type}">${message}</div>`;

    // Auto-hide after 3 seconds
    setTimeout(() => {
        container.innerHTML = '';
    }, 3000);
}

// Save configuration to database
async function saveConfiguration() {
    const saveBtn = document.getElementById('saveBtn');
    const originalText = saveBtn.innerHTML;

    // Show loading state
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
    saveBtn.disabled = true;

    try {
        const response = await fetch('{{ route('game.config.store') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                max_weight: maxWeight,
                increment_grams: incrementGrams
            })
        });

        const data = await response.json();

        if (response.ok && data.success) {
            showMessage(data.message, 'success');

            // Update the displayed config values with the saved data (ensure they're numbers)
            maxWeight = parseFloat(data.config.max_weight);
            incrementGrams = parseInt(data.config.increment_grams);
            updateConfigDisplay();

            // Update form inputs to reflect saved values
            document.getElementById('maxValueInput').value = maxWeight;
            document.getElementById('incrementInput').value = incrementGrams;

            console.log('Configuration saved:', data.config);
        } else {
            throw new Error(data.message || 'Failed to save configuration');
        }
    } catch (error) {
        console.error('Error saving configuration:', error);
        showMessage('Error saving configuration: ' + error.message, 'danger');
    } finally {
        // Restore button state
        saveBtn.innerHTML = originalText;
        saveBtn.disabled = false;
    }
}

// Initialize configuration on page load
document.addEventListener('DOMContentLoaded', function() {
    updateConfigDisplay();
});
</script>
@endsection
