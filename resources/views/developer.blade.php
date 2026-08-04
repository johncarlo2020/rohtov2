<x-app-layout>
    <style>
        .option-btn {
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

        #start-scanner,
        #start-quiz,
        #perfume-next-btn {
            width: 50%;
            margin: auto;
            border-color: transparent;
            border-radius: 30px;
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

        .main-content {
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

        .tile-title,
        .station_name {
            text-transform: uppercase;
        }

        .scan-btn {
            background: transparent;
            border: none;
            outline: none;
            cursor: pointer;

            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        /* pill icon */
        .scan-icon {
            width: 80px;
            height: 40px;

            background: rgba(255, 255, 255, 0.6);
            border-radius: 30px;

            display: flex;
            align-items: center;
            justify-content: center;

            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);

            transition: 0.25s ease;
        }

        /* icon */
        .scan-icon i {
            font-size: 18px;
            color: #2f5ea8;
        }

        /* text */
        .scan-label {
            color: #2f5ea8;
            font-size: 14px;
            font-weight: 500;
        }

        /* hover effect */
        .scan-btn:hover .scan-icon {
            transform: translateY(-3px);
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.15);
        }

        /* Idle animation */
        @keyframes scannerIdle {
            0% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-2px);
            }

            100% {
                transform: translateY(0);
            }
        }

        /* Accessibility */
        @media (prefers-reduced-motion: reduce) {
            #start-scanner {
                animation: none;
                transition: none;
            }
        }

        .station-image {
            width: 70vw;
            height: 25vh;
            object-fit: contain;
        }
    </style>
    <div id="stationPage" class="px-0 station-page main-content main-background">
        <div class="modal fade custom-modal" id="scanCompleteModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="rounded-1 modal-content modal-parent">
                    <div class="modal-body">
                        <div class="text-center">
                            <img class="mx-auto mb-4 check" id="badge" src="">
                            <div class="mt-0 text-content">
                                <p class="mb-2 text-dark message station_name"></p>
                                <p class="my-4 text-dark status-text">
                                </p>
                            </div>
                            <div class="mt-3 text-content">
                                <a href="{{ route('dashboard') }}" id="routeBtn"
                                    class="px-5 w-50 custom-btn fw-regular custom-btn-primary">
                                    BACK
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <button class="animate-entry back-btn" onclick="window.location.href='{{ route('dashboard') }}'"
            aria-label="Go back"></button>

        <div id="mainContainer">

            <!-- Branding -->
            <div class="px-4 animate-entry branding-container">
                @include('components.branding')
            </div>

            <!-- Main content -->
            <div id="mainContent"
                class="d-flex flex-column align-items-center justify-content-between animate-entry delay-3">

                <div class="text-center img-container">
                    <!-- station image -->
                    <img class="mx-auto my-4 station-image"
                        src="{{ asset('images/developer/DEV' . $developer->id . '.webp') }}" alt="Station Image">

                    <!-- description -->
                    <h2 class="px-3 text-center">
                        Proceed to {{ strtoupper($developer->name) }} booth
                        to scan QR code
                        & begin the journey.
                    </h2>
                </div>

                <!-- actions -->
                @if ($developer->pivot->isCompleted)
                    <!-- ✅ Already checked in -->
                    <div class="mx-auto w-50 checkedInContainer">
                        <p class="mb-2 text-center">Checked In</p>
                        <a href="{{ route('dashboard') }}" class="w-100 custom-btn custom-btn-secondary">
                            BACK
                        </a>
                    </div>
                @else
                    <!-- ✅ Other stations → Scanner -->


                    <div class="mb-5 scan-container">
                        <button id="start-scanner" class="mb-4 scan-btn">
                            <span class="scan-icon">
                                <i class="fa-solid fa-camera"></i>
                            </span>
                        </button>
                        <span class="scan-label">
                            Scan the QR code to proceed
                        </span>
                    </div>


                    {{-- <div class="mt-3 text-content">
                                <a href="{{ route('station.stamping', $station->id);}}" id="routeBtn"
                                    class="px-5 w-auto text-white custom-btn fw-regular custom-btn-primary">
                                    I'M THERE
                                </a>
                            </div> --}}
                @endif
            </div>

            <div id="quizContainer" class="d-none">
                <!-- Quiz content will be injected here by JavaScript -->
                <div id="quiz-container" style="display:none; width:100%; max-width:360px;">
                    <h3 id="question-text" class="mb-3 text-center"></h3>
                    <div id="options"></div>
                </div>
            </div>

            <!-- Scanner -->
            <!-- scanner-container -->
            <div id="{{ $user ? '' : 'forceQr' }}" class="icon-container"></div>
            <div id="scannerContainer" class="scanner-container d-none">
                <!-- <button id="close" class="mx-auto mt-4 camera-btn">x</button> -->
                <h2 class="mb-4 text-center fw-bold">{{ strtoupper($developer->name) }} booth</h2>
                <div id="reader"></div>
                <p class="mt-4 text-center scanner-text">Find the QR code & scan to proceed</p>
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
                    process_qr_code: '{{ route('process_qr_code.developer') }}',
                    submit_quiz: '{{ route('submit.answer') }}',
                    congrats: '{{ route('congrats.redeemed') }}'
                },
                assets: {
                    check_image: '{{ asset('images/check.png') }}',
                    error_image: '{{ asset('images/error.png') }}'
                },
                developer_id: {{ $developer->id }},
                station_name: `{!! strtoupper($developer->name) !!}`,
                asset_base: "{{ asset('') }}"
            };

            window.gotoStation = function(id, ) {
                var url = "{{ route('developer', ['developer' => ':id']) }}".replace(
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
