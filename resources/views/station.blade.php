<x-app-layout>
    <div id="stationPage" class="station-page main-content main-background with-scroll">
        <div class="modal fade custom-modal" id="scanCompleteModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered w-75 m-auto">
                <div class="modal-content card">
                    <div class="modal-body">
                        <div class="text-center">
                            <!-- <img class="check mx-auto mb-4" id="badge" src=""> -->
                            <div class="text-content mt-0">
                                <p class="sub-heading fw-bold mb-2">Station #<span class="station_id"></span></p>
                                <p class="mb-4">
                                    Check-in Successful
                                </p>
                            </div>
                            <div class="text-content mt-3">
                                <a href="{{ route('dashboard') }}" id="routeBtn"
                                    class="custom-btn w-auto px-5 fw-regular custom-btn-primary text-white">
                                    Close
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
        <div class="d-flex justify-content-center animate-entry">
            @include('components.branding')
        </div>
        <div id="mainContent"
            class="mt-1 mb-2 d-flex flex-column align-items-center justify-content-center animate-entry delay-3">
            @if ($user)
                <p class="my-0 mt-3 curve heading-dutch small">Checked-in</p>
                <p class="my-0 curve heading-dutch ">Succesful</p>
            @else
                <h1 class="heading mb-3 mt-3">STATION {{ $station->id }}</h1>
                <p class="sub-heading mb-1 fw-thin">
                    {{ isset($station->name) ? $station->name : '' }}
                </p>
                <span class="mb-4 fw-thin">
                    {{ isset($station->description) ? $station->description : '' }}
                </span>
            @endif
            <div id="{{ $user ? '' : 'forceQr' }}" class="icon-container">
            </div>
            <img class="mt-2 station-image w-75" src="{{ asset('images/station/station_' . $station->id . '.webp') }}"
                alt="Station Image">
            @if ($user != true && $station->id != 4)
                <button id="start-scanner" class="mx-auto mt-5 w-auto px-4 mb-3 custom-btn custom-btn-secondary"
                    style="font-size:20px;">
                    <i class="fa-solid fa-camera"></i>
                </button>
                <p class="px-4 mt-3 bottom-text scanner-text text-center">Scan the QR code to check in</p>
            @elseif ($station->id == 4)
                <a href="{{ route('pledgeDj') }}" class="custom-btn custom-btn-secondary mt-5">
                    Start
                </a>
            @else
                <div class="scanner-button">
                    <p class="my-0 mt-3 text-center mb-3">Checked-in Successful</p>
                    <a href="{{ route('dashboard') }}" class="custom-btn custom-btn-secondary">
                        Back
                    </a>
                </div>
            @endif
        </div>
        <div id="scannerContainer" class="scanner-container d-none mt-4">
            <!-- <button id="close" class="mx-auto mt-4 camera-btn">x</button> -->
            <div id="reader"></div>
            <p class="mt-4 scanner-text text-center">Find the QR code & Scan to check in</p>
            {{-- <div>
                <a href="{{ route('dashboard') }}" class="button">
                    BACK
                </a>
            </div> --}}
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
                    error_image: '{{ asset('images/error.webp') }}'
                },
                station_id: {{ $station->id }}
            };
        </script>
        @vite(['resources/js/station.js'])
    @endpush
</x-app-layout>
