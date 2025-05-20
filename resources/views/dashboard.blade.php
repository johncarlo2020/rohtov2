<x-app-layout>

    <head>
        {{-- Slick Carousel CSS is already in app.blade.php --}}
        {{-- Styles moved to resources/sass/custom.scss --}}
        <style>
            .spinner-overlay {
                /* New style for overlay */
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                /* Semi-transparent black */
                z-index: 9998;
                /* Below spinner, above content */
            }

            .spinner {
                position: fixed;
                /* Or absolute if you prefer */
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 56px;
                height: 56px;
                border-radius: 50%;
                background: radial-gradient(farthest-side, #474bff 94%, #0000) top/9px 9px no-repeat,
                    conic-gradient(#0000 30%, #474bff);
                -webkit-mask: radial-gradient(farthest-side, #0000 calc(100% - 9px), #000 0);
                animation: spinner-c7wet2 1s infinite linear;
                z-index: 9999;
                /* Ensure it's on top */
            }

            @keyframes spinner-c7wet2 {
                100% {
                    transform: translate(-50%, -50%) rotate(1turn);
                    /* Keep translate for centering */
                }
            }

            .content-box.loading-content {
                visibility: hidden;
                /* Hide content initially */
            }
        </style>
    </head>
    <div class="content-box main-background px-3 d-flex flex-column min-vh-100 loading-content">
        <!-- Added loading-content class -->
        <div class="spinner-overlay"></div> <!-- Overlay HTML added here -->
        <div class="spinner"></div> <!-- Spinner HTML added here -->
        <div class="container mb-5">
            <div>
                @include('components.branding')
            </div>
        </div>
        <div class="dashboard-img mb-4">
            <img src="{{ asset('files/main/dashboard_img.webp') }}" alt="" />
        </div>
        <div id="stationSelector" class="carosel">
            @foreach ($stations as $station)
                <div class="item">
                    <a onclick="gotoStation({{ $station->id }})"
                        class="station-item @if ($station->status === true) completed @endif">
                        {{-- <img class="station-bg" src="{{ asset('files/main/station_slection_background.webp') }}"
                            alt="" /> --}}
                        <img class="station-img" src="{{ asset('files/station/' . $station->id . '.webp') }}"
                            alt="">
                        <p class="complete-text">
                            CHECK-IN SUCCESSFUL
                        </p>
                    </a>
                </div>
            @endforeach
        </div>

        <div class="station-name mt-5">
            <h1 class="heading-text text-center"></h1>
        </div>
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body complete-progress">
                        <div class="info-progress d-flex gap-3">
                            <div class="station-progress border-right px-4">
                                <div class="circular-progress-container">
                                    <div class="circular-progress"
                                        style="--progress-percent: {{ ($station->id / 4) * 100 }}%;">
                                        <div class="progress-value-center">
                                            <span class="current-step-display">{{ $stationDone }}</span><span
                                                class="separator">/</span><span class="total-steps-display">4</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="progress-label-below">
                                    {{ $station->id }}/4 Check-In Completed
                                </div>
                            </div>
                            <div class="info-text px-2 mt-3">
                                <h2 class="mb-0">Oppss!</h2>
                                <h1 class="mb-0">The journey’s still going!</h1>
                                <p class="mb-0">Complete all checkpoints to redeem an exclusive gift.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-container p-4 mt-auto">
            @include('components.footer')
        </div>
    </div>

    @push('scripts')
        {{-- jQuery and Slick JS are already in app.blade.php --}}
        <script type="text/javascript">
            // Make stations data available to JS
            var stations = @json($stations);
            var stationDone = {{ $stationDone }};

            function gotoStation(stationId) {
                if (stationDone !== 3 && stationId === 4) {
                    $('#exampleModal').modal('show');
                    return;
                }

                window.location.href = "{{ url('station') }}/" + stationId;
            }

            $(document).ready(function() {
                var stationNameH1 = $('.station-name h1');

                $('#stationSelector').slick({
                    dots: true,
                    arrows: true,
                    infinite: false,
                    speed: 300,
                    slidesToShow: 1,
                    adaptiveHeight: true,
                    customPaging: function(slider, i) {
                        // Use station ID for the dot, fallback to index + 1
                        var station = stations[i];
                        if (station && station.status === true) {
                            return '<button class="station-done" type="button"><i class="fa-solid fa-check"></i></button>';
                        }
                        var stationId = station && station.id !== undefined ? station.id : (i + 1);
                        return '<button type="button">' + stationId + '</button>';
                    }
                });

                // Function to update station name
                function updateStationName(currentIndex) {
                    if (stations[currentIndex] && stations[currentIndex].name) {
                        stationNameH1.text(stations[currentIndex].name);
                    } else {
                        stationNameH1.text(''); // Clear name if not found
                    }
                }

                // Set initial station name
                if (stations.length > 0) {
                    updateStationName(0); // Display name of the first station
                }

                // Update station name on slide change
                $('#stationSelector').on('afterChange', function(event, slick, currentSlide) {
                    updateStationName(currentSlide);
                });

                // Hide spinner and overlay, and show content after all assets are loaded and Slick is ready
                $(window).on('load', function() {
                    $('#stationSelector').slick('setPosition'); // Ensure slider is correctly positioned
                    $('.spinner').hide(); // Hide the spinner
                    $('.spinner-overlay').hide(); // Hide the overlay
                    $('.content-box').removeClass('loading-content'); // Show the content
                });
            });
        </script>
    @endpush
</x-app-layout>
