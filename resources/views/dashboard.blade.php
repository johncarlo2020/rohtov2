<x-app-layout>
    <style>
    .station-card {
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .station-card:hover {
        transform: translateY(-4px) scale(1.02);
    }

    .station-card:active {
        transform: scale(0.98);
    }

    /* Grid Tile Image */
    .tile-image-wrapper {
        position: relative;
        width: 100%;
        aspect-ratio: 1 / 1;
        overflow: hidden;
        background-color: rgba(0, 0, 0, 0.2);
        transition: outline 0.15s ease, box-shadow 0.15s ease;
    }

    /* Selection border */
    .station-card:hover .tile-image-wrapper,
    .station-card:focus .tile-image-wrapper,
    .station-card:active .tile-image-wrapper,
    .station-card.selected .tile-image-wrapper {
        outline: 2px solid #00FF00;
        outline-offset: -2px;
        box-shadow: 0 0 10px rgba(0, 255, 0, 0.35);
    }

    .tile-image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    /* Overlay */
    .tile-image-wrapper .overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.65);
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        pointer-events: none;
        padding: 6px;
    }

    .tile-image-wrapper .overlay span {
        color: #fff;
        font-size: clamp(9px, 2.4vw, 11px);
        font-weight: 600;
        letter-spacing: 1px;
        line-height: 1.4;
        text-transform: uppercase;
    }

    /* Text below image */
    .station-caption {
        color: #fff;
        text-align: center;
        margin-top: clamp(8px, 1.8vh, 14px);
        font-size: clamp(11px, 3.2vw, 13px);
        font-weight: 700;
        letter-spacing: 1px;
        line-height: 1.35;
        text-transform: uppercase;
    }

    /* Header & Branding consistency */
    .branding-wrapper {
        margin-bottom: clamp(49px, 8vh, 106px) !important;
        text-align: center;
    }

    .page-main-title {
        font-weight: 700 !important;
        letter-spacing: 2px;
        line-height: 1.25;
        font-size: clamp(1.15rem, 4.5vw, 1.5rem);
        margin: 0 !important;
        text-align: center;
        color: #fff;
    }

    .dashboard-page {
        min-height: 100svh;
        min-height: 100dvh;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        padding-top: max(16px, env(safe-area-inset-top, 0px)) !important;
        padding-bottom: max(20px, calc(env(safe-area-inset-bottom, 0px) + 16px)) !important;
    }

    .grid-container-wrapper {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        width: 100%;
        margin-top: clamp(42px, 5vh, 98px);
    }

    .grid-container {
        max-width: min(94vw, 460px);
        margin: 0 auto;
        width: 100%;
    }

    .station-grid-row {
        row-gap: clamp(20px, 4.5vh, 42px);
    }
</style>

<div class="map-page main-content main-background with-scroll dashboard-page">

    <!-- Header Section (Branding + Title) -->
    <div class="dashboard-header animate-entry">
        <div class="branding-wrapper">
            @include('components.branding')
        </div>

        <div class="text-center">
            <h2 class="page-main-title">THE HOLIDAY SHOW</h2>
        </div>
    </div>

    <!-- 2x2 Grid Stations matching image layout -->
    <div class="grid-container-wrapper animate-entry">
        <div class="container grid-container px-3 px-sm-4">
            <div class="row station-grid-row gx-3 gx-sm-4">
                @foreach ($stations as $station)
                    @php
                        $image = asset("images/station/ST{$station->id}.webp");
                    @endphp

                    <div class="col-6">
                        <div class="station-card"
                             data-station="{{ $station->id }}"
                             onclick="gotoStation({{ $station->id }}, this)"
                             role="button"
                             tabindex="0"
                             aria-label="Station {{ $station->id }}: {{ $station->name }}">

                            <!-- Image -->
                            <div class="tile-image-wrapper">
                                <img src="{{ $image }}" alt="Station {{ $station->id }}">

                                @if ($station->status)
                                    <div class="overlay">
                                        <span>CHECK-IN<br>SUCCESSFUL</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Text below image -->
                            <div class="station-caption">
                                <span>{{ $station->id }}.</span>
                                <span>{!! strtoupper($station->name) !!}</span>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Modal outside flex flow -->
<div class="modal fade custom-modal animate-entry" id="notAllowedModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content card modal-parent">
            <div class="modal-body">
                <div class="text-center content">
                    <img class="check mx-auto mb-4" id="badge"
                         src="{{ asset('images/error.png') }}"
                         style="filter:brightness(0);">

                    <div class="text-content mt-4 mb-4">
                        <h5 class="text-dark mb-3 fw-bold">THE HOLIDAY SHOW</h5>
                        <p class="text-dark" style="font-size: clamp(13px, 3.5vw, 15px);">
                            Kindly complete Stations 1 – 3 to proceed to<br>
                            Your Holiday Treat
                        </p>
                    </div>

                    <button type="button"
                            class="w-75 custom-btn custom-btn-primary"
                            data-bs-dismiss="modal">
                        CLOSE
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let canAccessStation4 = @json($canAccessStation4);

                window.gotoStation = function(id, el) {
                    var card = el || document.querySelector(`.station-card[data-station="${id}"]`);
                    if (card) {
                        document.querySelectorAll('.station-card').forEach(c => c.classList.remove('selected'));
                        card.classList.add('selected');
                    }

                    var url = "{{ route('station', ['station' => ':id']) }}".replace(
                        ":id",
                        id
                    );

                    if (id == 4 && !canAccessStation4) {
                        var notAllowedModal = new bootstrap.Modal(document.getElementById('notAllowedModal'));
                        notAllowedModal.show();
                        if (card) {
                            setTimeout(() => card.classList.remove('selected'), 600);
                        }
                        return;
                    }

                    // Redirect to the generated URL
                    window.location.href = url;
                };
            });
        </script>
    @endpush
</x-app-layout>
