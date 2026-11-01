<x-app-layout>
<style>
    #start-scanner {
        width: min(90%, 360px);
        border-color: #ffffff;
        border-radius: 7px;
        background-color: #ffffff;
        color: #000000 !important;
        padding: clamp(12px, 2.5vh, 16px) clamp(16px, 5vw, 24px);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        cursor: pointer;
        letter-spacing: 1.5px;
        font-size: clamp(12px, 3.5vw, 14px);

        /* Subtle elevation */
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    /* Hover / Active */
    #start-scanner:hover {
        animation-play-state: paused;
        transform: translateY(-2px) scale(1.03);
        box-shadow: 0 8px 18px rgba(0, 0, 0, 0.2);
    }

    #start-scanner:active {
        transform: translateY(0) scale(0.98);
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
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
        font-size: clamp(1.15rem, 4.5vw, 1.45rem);
        margin: 0 0 clamp(42px,5vh,98px) 0 !important;
        text-align: center;
        color: #fff;
    }

    .station-page {
        min-height: 100svh;
        min-height: 100dvh;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        padding-top: max(16px, env(safe-area-inset-top, 0px)) !important;
        padding-bottom: max(24px, calc(env(safe-area-inset-bottom, 0px) + 20px)) !important;
    }

    .grid-container-wrapper {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        width: 100%;
    }

    #mainContainer {
        min-height: 100%;
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        width: 100%;
        padding: 0;
        margin: 0;
    }

    #mainContent {
        flex: 1;
        width: 100%;
        max-width: min(92vw, 420px);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        margin: 0 auto;
    }

    .station-visual-group {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
    }

    .station-image {
        width: 100%;
        max-width: min(88vw, 360px);
        max-height: clamp(240px, 38vh, 360px);
        aspect-ratio: 1 / 1;
        object-fit: cover;
        margin: 0 auto clamp(12px, 2vh, 18px) auto;
        display: block;
    }

    .station-subtitle {
        font-weight: 400 !important;
        font-size: clamp(13px, 3.8vw, 16px);
        letter-spacing: 1.5px;
        text-transform: uppercase;
        margin-bottom: 8px;
        color: #fff;
        line-height: 1.35;
    }

    .station-description {
        font-size: clamp(13px, 3.8vw, 16px);
        line-height: 1.45;
        max-width: 36ch;
        margin: 0 auto;
        color: #fff;
        font-weight: 400;
    }

    .station-actions-wrapper {
        width: 100%;
        margin-top: clamp(16px, 3vh, 26px);
    }

/* Buttons */
.custom-btn-secondary {
  font-size: 16px;
  padding: clamp(12px, 3vh, 16px) clamp(16px, 6vw, 24px);
  width: min(90%, 360px);
}

/* Scanner */
.scanner-wrapper {
  width: 100%;
}

.scanner-container {
  padding: clamp(16px, 4vh, 32px);
}

#reader {
  width: min(90vw, 360px);
  aspect-ratio: 1 / 1;
}

.tile-title , .station_name  {
    text-transform: uppercase;
}
</style>
    <div id="stationPage" class="station-page main-content main-background with-scroll px-0">
        <div class="modal fade custom-modal" id="scanCompleteModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content modal-parent rounded-1">
                    <div class="modal-body">
                        <div class="text-center">
                            <img class="check mx-auto mb-4" id="badge" src="">
                            <div class="text-content mt-0">
                                <p class="mb-2 message station_name text-dark"></p>
                                <p class="status-text my-4 text-dark">
                                </p>
                            </div>
                            <div class="text-content mt-3">
                                <a href="{{ route('dashboard') }}" id="routeBtn"
                                    class="custom-btn px-5 fw-regular custom-btn-primary w-50">
                                    BACK
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div id="mainContainer">

            <!-- Branding -->
            <div class="branding-wrapper animate-entry px-4 text-center">
                @include('components.branding')
            </div>

            <!-- Main content -->
            <div id="mainContent" class="animate-entry delay-3">

                <!-- station name -->
                <h2 class="page-main-title">{!! strtoupper($station->name) !!}</h2>

                <!-- Visual Group (Image + Text) -->
                <div class="station-visual-group text-center">
                    <!-- station image -->
                    <img class="station-image"
                        src="{{ asset('images/station/ST' . $station->id . '.webp') }}"
                        alt="Station Image">

                    <div class="station-content-wrapper text-center px-3 my-3">
                        @if (!empty($station->subtitle))
                            <p class="station-subtitle text-uppercase mb-2">
                                {{ $station->subtitle }}
                            </p>
                        @endif

                        <!-- description -->
                        <p class="station-description text-center">
                            {!! $station->description !!}
                        </p>
                    </div>
                </div>

                <!-- actions -->
                <div class="station-actions-wrapper text-center">
                    @if ($user)
                        <!-- ✅ Already checked in -->
                        <div class="checkedInContainer w-75 mx-auto text-center">
                            <p class="text-center mb-2" style="font-size: 13px;">Checked In</p>
                            <a href="{{ route('dashboard') }}"
                            class="custom-btn custom-btn-secondary w-100">
                                BACK
                            </a>
                        </div>
                    @else
                        <!-- ✅ All stations → QR Scanner -->
                        <button id="start-scanner"
                                class="custom-btn custom-btn-secondary mx-auto">
                            STEP INTO THE NEXT CHAPTER
                        </button>
                    @endif
                </div>
            </div>

            <!-- Scanner -->
            <div id="{{ $user ? '' : 'forceQr' }}" class="icon-container"></div> 
            <div id="scannerContainer" class="scanner-container d-none"> 
                <h2 class="text-center fw-bold mb-5">FREEDOM HAS TASTE</h2> 
                <div id="reader"></div> 
                <p class="mt-4 scanner-text text-center text-white">Find the QR code &<br> scan to continue your journey</p> 
            </div> 
        </div>
    </div>
    
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
        <script>
            // Pass data from Blade to JavaScript
            window.stationConfig = {
                urls: {
                    process_qr_code: '{{ route('process_qr_code') }}',
                    congrats: '{{ route('congrats') }}',
                    dashboard: '{{ route('dashboard') }}'
                },
                assets: {
                    check_image: '{{ asset('images/check.png') }}',
                    error_image: '{{ asset('images/error.png') }}'
                },
                station_id: {{ $station->id }},
                station_name: `{!! strtoupper($station->name) !!}`,
                asset_base: "{{ asset('') }}"
            };

            window.gotoStation = function(id,) {
                    var url = "{{ route('station', ['station' => ':id']) }}".replace(
                        ":id",
                        id
                    );

                    // Redirect to the generated URL
                    window.location.href = url;
                }
        </script>
        @vite(['resources/js/station.js'])
    @endpush
</x-app-layout>