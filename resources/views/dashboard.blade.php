<x-app-layout>
    <div class="container py-5 dash-board">
        <div class="d-flex justify-content-center align-item-center">
            @include('components.branding')
        </div>
        <div id="startpage" class="d-flex justify-content-center align-items-center h-100 flex-column mt-4 {{ $stations->firstWhere('status', true) ? 'd-none' : '' }}">
            <img class="welcome_img" src="{{ asset('images/hadalabobabies/welcome_image.webp') }}" alt="" />
            <button id="start" class="home-btn welcome-sign-btn btn rounded-pill mt-5"><span>Start</span></button>
        </div>
<!-- Modal -->
<div class="modal fade" id="notAllowedModal" tabindex="-1" aria-labelledby="notAllowedModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="notAllowedModalLabel">Access Denied</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                You must complete Stations 1 to 4 before accessing Station 5.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Okay</button>
            </div>
        </div>
    </div>
</div>


        <div class="sliders {{ $stations->firstWhere('status', true) ? '' : 'd-none' }} w-100">
            <div class="container-fluid p-0 m-0">
                <div class="row p-0 m-0 justify-content-center">
                    <div class="col-md-10 slider-container">
                        <div class="slider-prev slider-navigation">
                            <button id="prev" class="slider-btn"><i class="fa-solid fa-caret-left"></i></button>
                        </div>
                        <div class="slider-next slider-navigation">
                            <button id="next" class="slider-btn"><i class="fa-solid fa-caret-right"></i></button>
                        </div>
                        <!-- Slick Slider Component -->
                        <div class="slick-carousel mt-4" style="visibility: hidden;">
                            @foreach ($stations as $station)
                            <div class="slick-slide-item">
                                <div class="staion-container {{ $station->status ? 'completed' : '' }}" @if ($station->id == 5 &&
                                    !$canAccessStation5)
                                    data-bs-toggle="modal" data-bs-target="#notAllowedModal"
                                    @else
                                    onclick="gotoStation({{ $station->id }})"
                                    @endif
                                    >
                                    <img src="{{ asset('images/hadalabobabies/station'.$station->id.'.webp') }}" class="station-img"
                                        alt="Slide {{ $station->id }}">
                                    <p class="staion-name text-center font-medium main-color text-md mt-4">
                                        {{ $station->name }}
                                    </p>
                                    <div class="complete-indicator {{ $station->status ? 'active' : '' }}">
                                        <p>CHECK-IN SUCCESSFUL</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach



                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
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
        document.addEventListener('DOMContentLoaded', function() {

            const completedStation = window.stationsData.find(station => station.status);
            const startButton = document.getElementById('start');
            const sliders = document.querySelector('.sliders');
            const startPage = document.getElementById('startpage');

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
                        customPaging: function(slider, i) {
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

            if (completedStation) {
                // Sliders are already visible due to Blade directives.
                if (sliders && !sliders.classList.contains('d-none')) {
                     requestAnimationFrame(() => { // Defer to next frame
                        initializeSlickSlider(); // Initializes and calls setPosition
                    });
                }
            } else {
                // No station completed, startPage is visible.
                // Set up the start button.
                if (startButton && startPage) { // Ensure elements exist
                    startButton.addEventListener('click', function() {
                        if (startPage) {
                            startPage.classList.add('fade-out');
                            setTimeout(() => {
                                startPage.classList.add('d-none');
                                displayAndInitSliders(); // Show sliders and init
                            }, 500); // Matches fade-out duration
                        } else {
                            // Fallback if startPage somehow isn't there but button was clicked
                            displayAndInitSliders();
                        }
                    });
                }
            }

            // Slider navigation buttons
            const prevButton = document.getElementById('prev');
            const nextButton = document.getElementById('next');
            prevButton.addEventListener('click', function() {
                $('.slick-carousel').slick('slickPrev');
            });
            nextButton.addEventListener('click', function() {
                $('.slick-carousel').slick('slickNext');
            });
        });
    </script>
    <style>
        .slick-dots li button.completed-dot {
            background-color: green; /* Or any color/style you prefer */
            color: white;
            border-radius: 50%; /* Example style */
        }
        .slick-dots li button.slick-dot-number {
            /* Ensure default styles are distinct enough */
            background-color: #ccc;
            color: black;
        }
        .slick-dots li.slick-active button.slick-dot-number {
            background-color: #555; /* Active dot style */
            color: white;
        }
        .slick-dots li.slick-active button.completed-dot {
            background-color: darkgreen; /* Active and completed dot style */
            color: white;
        }
        .slick-slide-item {
            min-height: 180px; /* Adjust as needed, helps stabilize layout */
            display: flex;
            align-items: center;
            justify-content: center;
            /* Optional: background-color: #f0f0f0; to see item bounds during load */
        }
        .station-img {
            max-width: 100%;
            height: auto; /* Maintain aspect ratio */
            display: block; /* Prevents extra space below image */
        }
        /* Ensure the carousel itself is rendered if its parent is not d-none */
        .sliders:not(.d-none) .slick-carousel {
            visibility: visible;
        }
    </style>
</x-app-layout>
