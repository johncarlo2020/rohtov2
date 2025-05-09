<x-app-layout>
    <div class="container py-5 dash-board">
        <div class="d-flex justify-content-center align-item-center">
            @include('components.branding')
        </div>
        <div id="startpage" class="d-flex justify-content-center align-items-center h-100 flex-column mt-4">
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


        <div class="sliders d-none w-100">
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
                        <div class="slick-carousel mt-4">
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


           function gotoStation(id) {
                var url = "{{ route('station', ['station' => ':id']) }}".replace(
                    ":id",
                    id
                );
                // Redirect to the generated URL
                window.location.href = url;
            }
        document.addEventListener('DOMContentLoaded', function() {
            let currentSlide = 0;
            const startButton = document.getElementById('start');
            const sliders = document.querySelector('.sliders');
            const startPage = document.getElementById('startpage');

            startButton.addEventListener('click', function() {
                // Add fade-out animation class
                startPage.classList.add('fade-out');

                // Wait for animation to complete before hiding start page and showing sliders
                setTimeout(() => {
                    startPage.classList.add('d-none');
                    sliders.classList.remove('d-none');

                    // Initialize Slick Slider after it becomes visible
                    $('.slick-carousel').slick({
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
                            return '<button type="button" class="slick-dot-number">' + (
                                i + 1) + '</button>';
                        },
                    });
                }, 500);
            });

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
</x-app-layout>
