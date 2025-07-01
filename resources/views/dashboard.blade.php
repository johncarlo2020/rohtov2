<x-app-layout>
    <div class="container py-5 dash-board">
        <div class="d-flex justify-content-center align-item-center">
            @include('components.branding')
        </div>

        <!-- login Modal -->
        <!-- Welcome Modal -->
        <div class="modal fade" id="welcomeModal" tabindex="-1" aria-labelledby="welcomeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content text-center position-relative">

                    <!-- Close Button (top-right) -->
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-dismiss="modal"
                        aria-label="Close"></button>

                    <!-- Image -->
                    <img src="{{ asset('images/dutchlady/dutchLadyWelcomeModal.webp') }}" alt="Welcome"
                        class="img-fluid">

                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="notAllowedModal" tabindex="-1" aria-labelledby="notAllowedModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="notAllowedModalLabel">Access Denied</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        You must complete Stations 1 to 4 before accessing Station 5.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Okay</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 d-flex justify-content-center align-items-center mt-3">
            <img class="welcome_img" src="{{ asset('images/dutchlady/dashboardTextImg1.png') }}" alt="" />
        </div>
        <div class="container">
            @php
            $station5 = $stations->firstWhere('id', 5);
            @endphp

            @if ($station5)
            <div class="row justify-content-center">
                <div class="col-12 d-flex justify-content-center">
                    <div class="station-container {{ $station5->status ? 'completed' : '' }}" @if (!$canAccessStation5)
                        onclick="showNotAllowedModal()" @else onclick="gotoStation({{ $station5->id }})" @endif>
                        <img src="{{ asset('images/hadalabobabies/station' . $station5->id . '.webp') }}"
                            class="station-img img-fluid w-50 m-auto mb-4" alt="Slide {{ $station5->id }}">
                    </div>
                </div>
            </div>
            @endif
            <div class="row row-cols-2 row-cols-md-2 g-4 mb-5">
                @foreach ($stations as $station)
                @if ($station->id != 5)
                <div class="col">
                    <div class="station-container {{ $station->status ? 'completed' : '' }}"
                        onclick="gotoStation({{ $station->id }})">
                        @if ($station->status == 'completed')
                        <img src="{{ asset('images/hadalabobabies/DL Station Map (' . $station->id . ') Check.webp') }}"
                            class="station-img img-fluid " alt="Slide {{ $station->id }}">
                    </div>
                    @else
                    <img src="{{ asset('images/hadalabobabies/station' . $station->id . '.webp') }}"
                        class="station-img img-fluid " alt="Slide {{ $station->id }}">
                </div>
                @endif

            </div>
            @endif
            @endforeach

            <div class="col">
                <div class="station-container" onclick="window.location.href='{{ route('workshop'); }}'">
                    <img src="{{ asset('images/dutchlady/workshopImg.webp') }}" class="station-img img-fluid"
                        alt="Route Workshop">
                </div>
            </div>

            <div class="col">
                <div class="station-container" onclick="window.location.href=''">
                    <img src="{{ asset('images/dutchlady/promotionImg.webp') }}" class="station-img img-fluid"
                        alt="Promotions">
                </div>
            </div>
        </div>
    </div>

    <div class="bottom-text ">
        <a class="footer-text text-dark" href="https://wowsome.com.my/">Powered by WOWSOME®2025</a>
    </div>
    <script>

        @if (session('showWelcomeModal'))
            document.addEventListener('DOMContentLoaded', function () {
                var myModal = new bootstrap.Modal(document.getElementById('welcomeModal'));
                myModal.show();
            });
        @endif

        // Pass stations data to JavaScript
        window.stationsData = @json($stations);

        function gotoStation(id) {
            var url = "{{ route('station', ['station' => ':id']) }}".replace(
                ":id",
                id
            );
            // Redirect to the generated URL
            window.location.href = url;
        }

        function showNotAllowedModal() {
            var notAllowedModal = new bootstrap.Modal(document.getElementById('notAllowedModal'));
            notAllowedModal.show();
        }

        document.addEventListener('DOMContentLoaded', function() {



            const completedStation = window.stationsData.find(station => station.status);
            const startButton = document.getElementById('start');
            const sliders = document.querySelector('.sliders');
            displayAndInitSliders();
            function initializeSlickSlider() {
                const $carousel = $('.slick-carousel');
                if (!$carousel.hasClass('slick-initialized')) {
                    $carousel.slick({
                        dots: true,
                        arrows: false,
                        infinite: true,
                        speed: 500,
                        cssEase: 'linear',
                        autoplay: false,
                        autoplaySpeed: 4000,
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        customPaging: function (slider, i) {
                            const station = window.stationsData[i];
                            let dotClass = 'slick-dot-number';
                            if (station && station.status) { // Check if station is completed
                                return '<button type="button" class="' + dotClass + '"><i class="fa-solid fa-check"></i></button>';
                            } else {
                                return '<button type="button" class="' + dotClass + '">' + (
                                    i + 1) + '</button>';
                            }
                        },
                    });
                }
                // Always ensure position is updated if it's supposed to be visible and initialized
                if ($carousel.is(':visible') && $carousel.hasClass('slick-initialized')) {
                    $carousel.slick('setPosition');
                }
                // After initialization and setPosition, make it visible
                $carousel.css('visibility', 'visible');
            }

            // This function is called when sliders should become visible and initialized
            function displayAndInitSliders() {
                if (sliders) {
                    sliders.classList.remove('d-none'); // Ensure it's visible
                    // Defer initialization to allow browser to render visibility change
                    requestAnimationFrame(() => {
                        initializeSlickSlider(); // Initializes and calls setPosition
                    });
                }
            }


        });
    </script>

</x-app-layout>
