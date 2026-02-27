<style>
    #start-scanner {
        border-radius: 50%;
        width: 50px !important;
        height: 50px;
        background-color: transparent;
        color: rgb(255, 255, 255);
        border-color: rgb(255, 255, 255);
    }

    #scanCompleteModal .modal-content.failed-qr {
        background: #e9e9e9;
    }

    #scanCompleteModal .modal-content {
        background: #e9e9e9;
    }

    #scanCompleteModal .modal-body {
        padding: 18px 16px;
    }

    #scanCompleteModal .station-name-wrap {
        margin-bottom: 12px !important;
        color: #111 !important;
        font-size: 14px;
        letter-spacing: 0;
        line-height: 1.2;
    }

    #scanCompleteModal .message {
        margin-top: 0 !important;
        margin-bottom: 16px !important;
        color: #6f6f6f !important;
        font-size: 13px;
        line-height: 1.2;
        text-transform: uppercase;
    }

    #scanCompleteModal #routeBtn {
        width: 78% !important;
        margin: 0 auto;
        display: block;
        background: #000 !important;
        border-color: #000 !important;
        color: #fff !important;
        text-transform: uppercase;
    }

    #scanCompleteModal .modal-content.failed-qr .station-name-wrap {
        display: none;
    }

    #scanCompleteModal .modal-content.failed-qr .message {
        margin-top: 0 !important;
        margin-bottom: 16px !important;
        color: #111 !important;
        text-transform: none;
    }

    #scanCompleteModal .modal-content.failed-qr #routeBtn {
        background: #000;
        border-color: #000;
        color: #fff !important;
    }
</style>
<x-app-layout>
    <div id="stationPage" class="station-page main-content main-background with-scroll">
        <div class="modal fade custom-modal" id="scanCompleteModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content card rounded-1" id="scanCompleteModalContent">
                    <div class="modal-body">
                        <div class="text-center">
                            <img class="check mx-auto mb-4" id="badge" src="">
                            <div class="text-content mt-0">
                                <p class="mb-2 text-uppercase station-name-wrap"><span class="station_name"></span></p>
                                <p class="my-4 message">
                                    Check-in Successful
                                </p>
                            </div>
                            <div class="text-content mt-3">
                                <a href="{{ route('dashboard') }}" id="routeBtn"
                                    class="custom-btn px-5 fw-regular custom-btn-secondary w-100 text-white">
                                    BACK
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="back-btn animate-entry">
            <a href="{{ route('dashboard') }}" class="">
                <i class="fas fa-chevron-left"></i>
            </a>
        </div>

            <div class="d-flex justify-content-center animate-entry" @if($station->id == 5) style="margin-top:10vh;" @endif>
                @if($station->id != 5)
                    @include('components.branding')
                @endif
            </div>

        <div id="mainContent"
            class="mt-1 mb-2 d-flex flex-column align-items-center justify-content-center animate-entry delay-3">
                <h1 class="welcome-title station-face-title mb-3">
                    <span>FACE</span>
                    <span>EVERYTHING</span>
                </h1>
                <span class="mb-4 fw-thin text-center text-uppercase text-white">
                    {{ isset($station->name) ? $station->name : '' }}
                </span>
            <div id="{{ $user ? '' : 'forceQr' }}" class="icon-container">
            </div>
            <img class="station-image w-90" src="{{ asset('images/brand/station-' . $station->id . '.webp') }}"
                alt="Station Image">
            @if ($user != true )
            <button id="start-scanner" class="mx-auto my-3 w-auto px-4 custom-btn custom-btn-transparent" style="font-size:20px;">
                <i class="fa-solid fa-camera"></i>
            </button>
            <p class="px-4 bottom-text scanner-text text-center white">Scan the QR Code at the station to proceed</p>

            @endif
            @if($user)
            <p class="mt-4 px-4 bottom-text scanner-text text-center text-white">Checked In</p>
                <a href="{{ route('dashboard') }}" class="custom-btn custom-btn-secondary mt-3">
                    Back
                </a>
            @endif
        </div>
        <div id="scannerContainer" class="scanner-container d-none mt-4">
            <!-- <button id="close" class="mx-auto mt-4 camera-btn">x</button>  -->
            <div class="text-center mb-5">
                <h1 class="welcome-title station-face-title mb-0">
                    <span>FACE</span>
                    <span>EVERYTHING</span>
                </h1>
            </div>
            <div id="reader"></div>
            <p class="mt-4 scanner-text text-center text-white">Find the QR code & Scan to check into the station</p>

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
