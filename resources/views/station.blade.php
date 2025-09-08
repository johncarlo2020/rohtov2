<style>
    #start-scanner {
        border-radius: 50%;
        width: 50px !important;
        height: 50px;
        background-color: transparent;
        color: black;
        border-color: black;
    }
</style>
<x-app-layout>
    <div id="stationPage" class="station-page main-content main-background with-scroll">
        <div class="modal fade custom-modal" id="scanCompleteModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content card rounded-1">
                    <div class="modal-body">
                        <div class="text-center">
                            <img class="check mx-auto mb-4" id="badge" src="">
                            <div class="text-content mt-0">
                                <p class="mb-2 text-uppercase text-black"><span class="station_name text-black"></span></p>
                                <p class="my-4 message text-black">
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
            @if($station->id != 5)
                <p class="text-black mb-3"><span class="text-black">MONOCHROME</span>.<span class="text-black">MINIMALIST</span>.<span class="text-black">THE MULTIPLE</span></p>
            @endif
                <p class="heading mb-3 fw-thin text-uppercase text-black">
                    {{ isset($station->name) ? $station->name : '' }}
                </p>
                <span class="mb-4 fw-thin text-center text-uppercase text-black">
                    {!! $station->description !!}
                </span>
            <div id="{{ $user ? '' : 'forceQr' }}" class="icon-container">
            </div>
            <img class="station-image w-100" src="{{ asset('images/station/nars_station_' . $station->id . '.webp') }}"
                alt="Station Image">
            @if ($user != true && $station->id != 6)
                <button id="start-scanner" class="mx-auto my-3 w-auto px-4 custom-btn custom-btn-secondary"
                    style="font-size:20px;">
                    <i class="fa-solid fa-camera"></i>
                </button>
                <p class="px-4 bottom-text scanner-text text-center text-black">Scan the QR Code at the station to proceed</p>
                <a href="{{ route('dashboard') }}" class="custom-btn custom-btn-secondary mt-3">
                        Back
                    </a>
            @endif
            @if($user)
            <p class="px-4 bottom-text scanner-text text-center text-black">Checked In</p>
                <a href="{{ route('dashboard') }}" class="custom-btn custom-btn-secondary mt-3">
                    Back
                </a>
            @endif
        </div>
        <div id="scannerContainer" class="scanner-container d-none mt-4">
            <!-- <button id="close" class="mx-auto mt-4 camera-btn">x</button>  -->
            <div class="text-center mb-5">
                <p class="text-black"><span class="text-black">MONOCHROME</span> . <span class="text-black">MINIMALIST</span> . <span class="text-black">THE MULTIPLE</span></p>
            </div>
            <div id="reader"></div>
            <p class="mt-4 scanner-text text-center text-black">Find the QR code & Scan to check into the station</p>
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

                    // if (id === 5 && !canAccessStation5) {
                    //     // Show the not allowed modal if trying to access station 6 without permission
                    //     var notAllowedModal = new bootstrap.Modal(document.getElementById('notAllowedModal'));
                    //     notAllowedModal.show();
                    //     return;
                    // }

                    // Redirect to the generated URL
                    window.location.href = url;
                }
        </script>
        @vite(['resources/js/station.js'])
    @endpush
</x-app-layout>
