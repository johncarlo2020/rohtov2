<x-app-layout>
    <div id="stationPage" class="station-page main-content main-background with-scroll">
        <div class="modal fade custom-modal" id="scanCompleteModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered w-75 m-auto">
                <div class="modal-content card">
                    <div class="modal-body">
                        <div class="text-center">
                            <img class="check mx-auto mb-4" id="badge" src="">
                            <div class="text-content mt-0">
                                <p class="sub-heading fw-bold mb-2 station-text "><span class="station_name text-dark"></span></p>
                                <p class="mb-4 message text-grey">
                                    Check-In Successful
                                </p>
                            </div>
                            <div class="text-content mt-3">
                                <a href="{{ route('dashboard') }}" id="routeBtn"
                                    class="custom-btn w-auto px-5 fw-regular custom-btn-primary text-white">
                                    Back
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
            <h1 class="heading mb-3 mt-3 text-dark">Station {{ $station->id }}</h1>
                <p class="sub-heading mb-1 fw-thin text-dark">
                    {{ isset($station->name) ? $station->name : '' }}
                </p>
            <div id="{{ $user ? '' : 'forceQr' }}" class="icon-container">
            </div>
            <img class="mt-5 station-image w-75" src="{{ asset('images/station/ST' . $station->id . '.webp') }}"
                alt="Station Image">
            @if ($user)
                <div class="scanner-button">
                    <p class="my-0 mt-5 text-center mb-3 text-dark">Checked in</p>
                    <a href="{{ route('dashboard') }}" class="custom-btn custom-btn-primary">
                        Back
                    </a>
                </div>
            @elseif ($station->id != 3)
                <button id="start-scanner"
                    class="camera-btn mx-auto mt-5 mb-3"
                    title="Start Scanner">
                    <i class="fa-solid fa-camera"></i>
                </button>
                <p class="px-4 mt-3 bottom-text scanner-text text-center text-dark">Scan the QR code to check in</p>
                <div class="text-center my-3">
                    <a href="{{ route('dashboard') }}" class="custom-btn custom-btn-primary">
                        BACK
                    </a>
                </div>
            @elseif ($station->id == 3)
                <div class="gift-selection mt-5">
                    <label for="giftSelect" class="form-label text-dark fw-bold">Select your gift:</label>
                    <select id="giftSelect" class="form-select mb-3" style="width: 80%; margin: 0 auto;" required>
                        <option value="">Select a gift</option>
                        @foreach($gifts as $gift)
                            <option value="{{ $gift->id }}">{{ $gift->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button id="start-scanner" class="camera-btn mx-auto mt-5 mb-3" title="Start Scanner">
                    <i class="fa-solid fa-camera"></i>
                </button>
                <p class="px-4 mt-3 bottom-text scanner-text text-center text-dark">Scan the QR code to check in</p>
                <div class="text-center my-3">
                    <a href="{{ route('dashboard') }}" class="custom-btn custom-btn-secondary">
                        BACK
                    </a>
                </div>
            @endif
        </div>
        <div id="scannerContainer" class="scanner-container d-none mt-4">
            <!-- <button id="close" class="mx-auto mt-4 camera-btn">x</button> -->
            <div id="reader"></div>
            <p class="mt-4 scanner-text text-center text-dark">Find the QR code & Scan to check in</p>
            <div class="text-center my-3">
                <a href="{{ route('dashboard') }}" class="custom-btn custom-btn-primary">
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
                    dashboard: '{{ route('dashboard') }}'
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

            // Gift selection functionality for station 3
            document.addEventListener('DOMContentLoaded', function() {
                const giftSelect = document.getElementById('giftSelect');
                const redeemBtn = document.getElementById('redeemGiftBtn');

                if (giftSelect && redeemBtn) {
                    giftSelect.addEventListener('change', function() {
                        if (this.value) {
                            redeemBtn.disabled = false;
                        } else {
                            redeemBtn.disabled = true;
                        }
                    });

                    redeemBtn.addEventListener('click', function() {
                        const selectedGift = giftSelect.value;
                        if (selectedGift) {
                            // Redirect to gift selection page with selected gift
                            const url = "{{ route('station.gift.selection', ['station' => $station->id]) }}" + "?gift=" + selectedGift;
                            window.location.href = url;
                        }
                    });
                }
            });

        </script>
        @vite(['resources/js/station.js'])
    @endpush
</x-app-layout>
