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

    <div id="stationPage" class="station-page home">

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
                <img src="{{ asset('images/dutchlady/back-btn.webp') }}" alt="Back" />
            </a>
        </div>
        <div class="mb-3 branding-container">
            @include('components.branding')
        </div>
        <div id="mainContent" class="mt-1 mb-2 text-center col-12 text-content">
            @if ($user)
                <p class="my-0 mt-3 curve heading-dutch small">Checked-in</p>
                <p class="my-0 curve heading-dutch ">Succesful</p>
            @else
                  <img src="{{ asset('images/dutchlady/dutchLadyStation' . $station->id . '.webp') }}"
                class="station-img img-fluid w-25" alt="Slide {{ $station->id }}">
            @endif
            <div id="{{ $user ? '' : 'forceQr' }}" class="icon-container">
            </div>
            <img class="mt-2 station-image"
                src="{{ asset('images/hadalabobabies/DL Station Page (' . $station->id . ').webp') }}" alt="Station Image">
            @if ($user != true)
                <button id="start-scanner" class="mx-auto mt-5 mb-3 camera-btn">
                    <i class="fa-solid fa-camera"></i>
                </button>
                <p class="px-4 mt-4 bottom-text scanner-text">Scan the QR code to check in</p>
            @else
                <div class="scanner-button">
                    <a href="{{ route('dashboard') }}" class="button-dutch button-dutch-primary">
                        done
                    </a>
                </div>
            @endif

        </div>
        <div id="scannerContainer" class="scanner-container d-none">
            <!-- <button id="close" class="mx-auto mt-4 camera-btn">x</button> -->
            <div id="reader"></div>
            <p class="mt-4 scanner-text">Scan the QR code to check in</p>
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
