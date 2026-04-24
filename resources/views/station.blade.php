<x-app-layout>
<style>
    .option-btn
    {   
        width: 100%;
        border-color: #ffffff;
        border-radius: 5px;
        background-color: #ffffff;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        cursor: pointer;

        /* Subtle elevation */
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);

        /* Animations */
        animation: scannerIdle 2.8s ease-in-out infinite;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    #start-scanner,#start-quiz,#perfume-next-btn {
        width: 50%;
        border-color: #ffffff;
        border-radius: 5px;
        background-color: #ffffff;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        cursor: pointer;

        /* Subtle elevation */
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);

        /* Animations */
        animation: scannerIdle 2.8s ease-in-out infinite;
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

    .main-content
    {
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }

   /* Whole screen */
#mainContainer {
  min-height: 100vh;
  min-height: 100svh;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: clamp(12px, 3vh, 24px);
  padding-block: clamp(12px, 3vh, 24px);
}

/* Main content */
#mainContent {
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: space-between;
  gap: clamp(12px, 3vh, 24px);
}

/* Title */
/* #mainContent h2 {
  font-size: clamp(1rem, 4.5vw, 1.4rem);
  letter-spacing: clamp(1px, 0.4vw, 2px);
} */
/* Text */
/* #mainContent p {
  font-size: 16px;
  max-width: 38ch;
  line-height: 1.5;
} */

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

    /* Idle animation */
    @keyframes scannerIdle {
        0%   { transform: translateY(0); }
        50%  { transform: translateY(-2px); }
        100% { transform: translateY(0); }
    }

    /* Accessibility */
    @media (prefers-reduced-motion: reduce) {
        #start-scanner {
            animation: none;
            transition: none;
        }
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
        <!-- <button
            class="back-btn animate-entry"
            onclick="window.location.href='{{ route('dashboard') }}'"
            aria-label="Go back"
        ></button> -->
        
        <div id="mainContainer">

            <!-- Branding -->
            <div class="branding-container animate-entry px-4">
                @include('components.branding')
            </div>

            <!-- Main content -->
            <div id="mainContent"
                class="d-flex flex-column align-items-center justify-content-between animate-entry delay-3">

                <div class="img-container text-center">
                    <!-- station image -->
                    <img class="station-image w-50 mx-auto my-5"
                        src="{{ asset('images/station/ST' . $station->id . '.webp') }}"
                        alt="Station Image">

                    <!-- description -->
                    <h2 class="text-center px-3 fw-bold">
                        {!! $station->description !!}
                    </h2>
                </div>
                
                <!-- actions -->
                @if ($user)

                    <!-- ✅ Already checked in -->
                    <div class="checkedInContainer w-50 mx-auto">
                        <p class="text-center mb-2">Checked In</p>
                        <a href="{{ route('dashboard') }}"
                        class="custom-btn custom-btn-secondary w-100">
                            BACK
                        </a>
                    </div>

                  

                @elseif ($station->id == 10)

                    <!-- ✅ Station 1 → Quiz -->
                    <button id="goto-stamping"
                            class="text-dark custom-btn-secondary px-3 py-2">
                        I'M THERE
                    </button>

                @else

                    <!-- ✅ Other stations → Scanner -->
                    {{-- <button id="start-scanner"
                            class="text-dark custom-btn-secondary px-3 py-2">
                        SCAN QR CODE TO PROCEED
                    </button> --}}

                      <div class="text-content mt-3">
                                <a href="{{ route('station.stamping', $station->id);}}" id="routeBtn"
                                    class="custom-btn w-auto px-5 fw-regular custom-btn-primary text-white">
                                    I'M THERE
                                </a>
                            </div>

                @endif
            </div>

            <div id="quizContainer" class="d-none">
                <!-- Quiz content will be injected here by JavaScript -->
                <div id="quiz-container" style="display:none; width:100%; max-width:360px;">
                    <h3 id="question-text" class="text-center mb-3"></h3>
                    <div id="options"></div>
                </div>
            </div>

            <!-- Scanner -->
            <!-- scanner-container --> 
            <div id="{{ $user ? '' : 'forceQr' }}" class="icon-container"></div> 
                <div id="scannerContainer" class="scanner-container d-none"> 
                    <!-- <button id="close" class="mx-auto mt-4 camera-btn">x</button> --> 
                    <h2 class="text-center fw-bold mb-4">FREEDOM HAS TASTE</h2> 
                    <div id="reader"></div> 
                    <p class="mt-4 scanner-text text-center text-white">Find the QR code &<br> scan to continue your journey</p> 
                </div> 
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
                    submit_quiz: '{{ route('submit.answer') }}',
                    congrats: '{{ route('congrats') }}'
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