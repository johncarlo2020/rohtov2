<x-app-layout>
    <style>
        .icon-badge {
            width: 150px;
            height: auto;
            margin-bottom: 25px;
        }

        .iconNew {
            width: 60px;
        }

        .logo-img {
            width: 100px;
        }
    </style>

    <div id="stationPage" class="station-page home with-scroll">
        <div class="modal fade custom-modal" id="scanCompleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center content">
                        <img class="check mx-auto mb-4" id="badge" src="">
                        <div class="text-content mt-0">
                            <p class="station-text mb-2 text-dark">Station <span class="station_id"></span></p>
                            <p class="message text-dark">
                                Check-in Successful
                            </p>
                        </div>
                        <div class="">
                            <a href="{{ route('dashboard') }}" id="routeBtn" class="button-dutch button-dutch-primary">
                                okay
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <div class="back-btn">
            <a href="{{ route('dashboard') }}" class="">
                <i class="fas fa-chevron-left"></i>
            </a>
        </div>
        <div class="my-5 col-12 d-flex justify-content-center">
            @include('components.branding')
        </div>
        <div id="mainContent" class="mt-1 mb-2 d-flex flex-column align-items-center justify-content-center">
            @if ($user)
                <p class="my-0 mt-3 curve heading-dutch small">Checked-in</p>
                <p class="my-0 curve heading-dutch ">Succesful</p>
            @else
                <h3 class="mb-4">STATION {{$station->id}}</h3>
                <h2>
                    {{ isset($station->name) ? $station->name : '' }}
                </h2>
                <span class="mb-4">
                    {{ isset($station->description) ? $station->description : '' }}
                </span>

            @endif
            <div id="{{ $user ? '' : 'forceQr' }}" class="icon-container">
            </div>
            <img class="mt-2 station-image w-75"
                src="{{ asset('images/station/station_' . $station->id . '.webp') }}" alt="Station Image">
            @if ($user != true)
                <button id="start-scanner" class="mx-auto mt-5 mb-3 custom-btn custom-btn-secondary" style="font-size:20px;">
                    <i class="fa-solid fa-camera"></i>
                </button>
                <p class="px-4 mt-4 bottom-text scanner-text text-center">Scan the QR code to check in</p>
            @else
                <div class="scanner-button">
                    <p class="my-0 mt-3 curve heading-dutch small text-center mb-3">Checked-in Successful</p>
                    <a href="{{ route('dashboard') }}" class="custom-btn custom-btn-secondary">
                        Back
                    </a>
                </div>
            @endif
        </div>
        <div id="scannerContainer" class="scanner-container d-none">
            <!-- <button id="close" class="mx-auto mt-4 camera-btn">x</button> -->
            <div id="reader"></div>
            <p class="mt-4 scanner-text text-center">Scan the QR code to check in</p>
            {{-- <div>
                <a href="{{ route('dashboard') }}" class="button">
                    BACK
                </a>
            </div> --}}
        </div>
        <x-footer />
    </div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
    <script>
        // Pass data from Blade to JavaScript
        window.stationConfig = {
            urls: {
                process_qr_code: '{{ route("process_qr_code") }}',
                congrats: '{{ route("congrats") }}'
            },
            assets: {
                check_image: '{{ asset("images/check.png") }}',
                error_image: '{{ asset("images/error.webp") }}'
            },
            station_id: {{ $station->id }}
        };
    </script>
    @vite(['resources/js/station.js'])
@endpush
</x-app-layout>
