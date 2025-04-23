<x-app-layout>
    <div class="container py-5">
        <div class="mb-3 branding-container">
            @include('components.branding')
        </div>

        <div id="nameContainer" class="get-name-container">
            <h5 class="text-center text-primary">PLEASE INSERT YOUR NAME</h5>
            <div class="mb-3">
                <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="your name here">
            </div>
            <button id="nextStep" class="main-btn btn btn-primary">
                Next
            </button>
        </div>
        <div id="characterStyle" class="d-none">
            <div class="sliders w-100">
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
                                <div id="1" class="slick-slide-item">
                                    <div class="staion-container d-flex justify-content-center align-items-center">
                                        <img src="{{ asset('images/hadalabobabies/station1.png') }}" class="station-img"
                                            alt="Slide 1">
                                    </div>
                                </div>
                                <div id="2" class="slick-slide-item">
                                    <div  class="staion-container d-flex justify-content-center align-items-center">
                                        <img src="{{ asset('images/hadalabobabies/station2.png') }}" class="station-img"
                                            alt="Slide 1">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const nextStepButton = document.getElementById('nextStep');
        const nameContainer = document.getElementById('nameContainer');
        const characterStyleContainer = document.getElementById('characterStyle');

        document.addEventListener('DOMContentLoaded', function() {
            let currentSlide = 0;
            nextStepButton.addEventListener('click', function() {
                // Add fade-out animation class
                nameContainer.classList.add('fade-out');

                // Wait for animation to complete before hiding start page and showing sliders
                setTimeout(() => {
                    nameContainer.classList.add('d-none');
                    characterStyleContainer.classList.remove('d-none');

                    // Initialize Slick Slider after it becomes visible
                    $('.slick-carousel').slick({
                        dots: false,
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
