<style>
    #start-scanner {
    width: 75px;
    height: 36px;
    border-radius: 999px;
    background-color: #ffffff;
    border: 2px solid #005eab;
    padding: 0;

    display: flex;
    align-items: center;
    justify-content: center;

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

/* Icon image */
#start-scanner img {
    width: 18px;
    height: auto;
    display: block;
}

/* Icon font option */
#start-scanner i {
    font-size: 16px;
    color: #f7931e; /* orange accent */
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
<x-app-layout>
    <div id="stationPage" class="station-page main-content main-background with-scroll">
        <div class="modal fade custom-modal" id="scanCompleteModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content modal-parent rounded-1">
                    <div class="modal-body">
                        <div class="text-center">
                            <div class="text-content mt-0">
                                <p class="mb-2 text-white station_name_container">Stesen <span class="station_name text-white"></span></p>
                                <p class="my-4 message text-white">
                                    Daftar masuk berjaya
                                </p>
                            </div>
                            <div class="text-content mt-3">
                                <a href="{{ route('dashboard') }}" id="routeBtn"
                                    class="custom-btn px-5 fw-regular custom-btn-primary w-50">
                                    KEMBALI
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button
            class="back-btn animate-entry"
            onclick="window.location.href='{{ route('dashboard') }}'"
            aria-label="Go back"
        ></button>

        
            <div class="d-flex justify-content-center animate-entry">
                @include('components.branding')
            </div>
        @if($user)
        <div class="text-center animate-entry mt-3">
            <!-- TOP TEXT -->
            <svg viewBox="0 0 600 80" width="100%" height="60">
                <path
                id="archTop"
                d="M 60 90 Q 300 20 540 90"
                fill="transparent"
                />
                <text
                font-size="44"
                font-weight="950"
                fill="#ff7a00"
                stroke="#ffffff"
                stroke-width="7"
                paint-order="stroke"
                text-anchor="middle"
                >
                <textPath href="#archTop" startOffset="50%">
                    Daftar masuk
                </textPath>
                </text>
            </svg>

            <!-- BOTTOM TEXT -->
            <svg viewBox="0 0 600 80" width="100%" height="50" style="margin-top:-10px;">
                <path
                id="archBottom"
                d="M 60 90 Q 300 20 540 90"
                fill="transparent"
                />
                <text
                font-size="44"
                font-weight="950"
                fill="#ff7a00"
                stroke="#ffffff"
                stroke-width="7"
                paint-order="stroke"
                text-anchor="middle"
                >
                <textPath href="#archBottom" startOffset="50%">
                    Berjaya!
                </textPath>
                </text>
            </svg>
        </div>
        @endif
        <div id="mainContent"
            class="mt-1 mb-2 d-flex flex-column align-items-center justify-content-center animate-entry delay-3">
                <img class="station-image w-25 my-3" src="{{ asset('images/station/STNO' . $station->id . '.webp') }}" alt="Station Image">
            <div id="{{ $user ? '' : 'forceQr' }}" class="icon-container">
            </div>
            <img class="station-image w-50 m-auto" src="{{ asset('images/station/STNBG' . $station->id . '.webp') }}"
                alt="Station Image">
            @if ($user != true && $station->id != 7)
                <button id="start-scanner" class="mx-auto mt-5 mb-3 py-3 px-4"
                    style="font-size:24px !important;">
                    <i class="fa-solid fa-camera"  style="font-size:24px !important;"></i>
                </button>
                <strong class="px-4 mb-5 text-center text-white">Imbas kod QR untuk daftar masuk</strong>
            @endif
            @if($user)
                <a href="{{ route('dashboard') }}" class="custom-btn custom-btn-primary mt-5">
                    Selesai 
                </a>
            @endif
        </div>
        
        <div id="scannerContainer" class="scanner-container d-none mt-4">
            <!-- <button id="close" class="mx-auto mt-4 camera-btn">x</button>  -->
            <div class="d-flex justify-content-center">
                <img class="station-image w-25 mb-3" src="{{ asset('images/station/STNO' . $station->id . '.webp') }}" alt="Station Image">
            </div>
            <div id="reader"></div>
            <p class="mt-4 scanner-text text-center text-white">Imbas kod QR untuk daftar masuk</p>
            <div class="text-center">
                <a href="{{ route('dashboard') }}" class="button custom-btn custom-btn-secondary mt-3">
                    BACK
                </a>
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
                    congrats: '{{ route('congrats') }}'
                },
                assets: {
                    check_image: '{{ asset('images/check.png') }}',
                    error_image: '{{ asset('images/error.png') }}'
                },
                station_id: {{ $station->id }},
                station_name: '{{ $station->name }}'
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