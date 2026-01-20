<style>
    #start-scanner {
    width: 100%;
    height: 100%;
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

.scanner-wrapper {
    position: relative;
    width: clamp(220px, 70vw, 320px);
    aspect-ratio: 1 / 1;
    margin-inline: auto;
}

/* Dark overlay */
.scanner-wrapper.redeemed::before {
    content: "";
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.45);
    border-radius: 999px;
    z-index: 1;
}

/* Text overlay */
.scanner-overlay {
    position: absolute;
    inset: 0;
    z-index: 2;

    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;

    color: #fff;
    font-size: 14px;
    font-weight: 600;
    letter-spacing: 2px;
    line-height: 1.4;
    text-transform: uppercase;

    pointer-events: none;
}

/* Stop animation & interaction when redeemed */
.scanner-wrapper.redeemed #start-scanner {
    animation: none;
    cursor: default;
}

.scanner-wrapper.redeemed {
    pointer-events: none;
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

    <div id="stationPage" class="main-content main-background with-scroll">
        <div class="modal fade custom-modal" id="scanCompleteModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content modal-parent rounded-1">
                    <div class="modal-body">
                        <div class="text-center">
                            <div class="text-content mt-0">
                                <p class="mb-2 text-white station_name_container"></p>
                                <p class="my-4 mt-4 message text-white">
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
        
            <div class="d-flex justify-content-center animate-entry">
                @include('components.branding')
            </div>
          <h2 class="mx-4 mt-4 text-center sub-heading-text animate-entry">REDEMPTION</h2>
        <div id="mainContent"
            class="pt-5 mb-2 d-flex flex-column align-items-center justify-content-center animate-entry delay-3">
            <div id="{{ isset($user) ? '' : 'forceQr' }}" class="icon-container">
            </div>

            @foreach($stations as $station)
            <div class="scanner-wrapper {{ $station->status ? 'redeemed' : '' }}">
                <img
                    id="start-scanner"
                    src="{{ asset('images/station/redemption.webp') }}"
                    alt="Station Image"
                >

                @if($station->status)
                    <div class="scanner-overlay">
                        <span>REDEEM<br>SUCCESSFUL</span>
                    </div>
                @endif
            </div>
            @endforeach
            

            @if(isset($user))
                <a href="{{ route('redemption') }}" class="custom-btn custom-btn-primary mt-5">
                    BACK 
                </a>
            @endif
        </div>
        
        <div id="scannerContainer" class="scanner-container d-none mt-4">
            <!-- <button id="close" class="mx-auto mt-4 camera-btn">x</button>  -->
            <div id="reader"></div>
            <p class="mt-4 scanner-text text-center text-primary">Find the QR code & <br>
scan to continue your journey.</p>
            <div class="text-center">
                <a href="{{ route('redemption') }}" class="button custom-btn custom-btn-secondary mt-3">
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
                    congrats: '{{ route('congrats') }}',
                    thankyou: '{{ route('thankyou') }}'
                },
                assets: {
                    check_image: '{{ asset('images/check.png') }}',
                    error_image: '{{ asset('images/error.png') }}'
                },
                station_id: 1,
            };

        </script>
        @vite(['resources/js/station.js'])
    @endpush
</x-app-layout>