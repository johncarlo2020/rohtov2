<x-app-layout>

    <head>
        {{-- Slick Carousel CSS is already in app.blade.php --}}
        {{-- Styles moved to resources/sass/custom.scss --}}
    </head>
    <div class="content-box main-background px-3 d-flex flex-column min-vh-100">
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
                    <a href="{{ route('station', ['station' => $station->id]) }}" class="station-item">
                        {{-- <img class="station-bg" src="{{ asset('files/main/station_slection_background.webp') }}"
                            alt="" /> --}}
                          <img class="station-img" src="{{ asset('files/station/' . $station->id . '.webp') }}" alt="">
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
                    <div class="modal-body">
                        <a type="button" class="modal-close" data-bs-dismiss="modal" aria-label="Close"><i
                                class="fa-solid fa-xmark"></i></a>
                        <div class="info-icon mb-3">
                            <img src="{{ asset('files/main/info.png') }}" alt="" />
                        </div>
                        <p class="modal-main-text mb-1">Do you want to reschedule your visit ?</p>
                        <p class="warning-text text-center px-5">Note: You may reschedule your selected date
                            <strong>only once</strong>.
                        </p>
                        <div class="">
                            <button id="confirmVisitButton" type="submit" class="button button-primary w-100 mb-2">
                                YES
                            </button>
                            <button id="cancelModalButton" type="button" class="button button-secondary w-100 mb-2"
                                data-bs-dismiss="modal">
                                NO
                            </button>
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
            $(document).ready(function() {
                var stations = @json($stations); // Make stations data available to JS
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
                        var stationId = stations[i] && stations[i].id !== undefined ? stations[i].id : (i + 1);
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
                $('#stationSelector').on('afterChange', function(event, slick, currentSlide){
                    updateStationName(currentSlide);
                });

                // Refresh Slick's position after all assets are loaded
                // $(window).on('load', function() {
                //     $('#stationSelector').slick('setPosition');
                // });
            });
        </script>
    @endpush
</x-app-layout>
