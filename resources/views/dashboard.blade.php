<x-app-layout>
    <div class="container py-5 dash-board">
        <div class="d-flex justify-content-center align-item-center">
            @include('components.branding')
        </div>
        <div id="startpage" class="d-flex justify-content-center align-items-center h-100 flex-column mt-4">
            <img class="welcome_img" src="{{ asset('images/hadalabobabies/welcome_image.png') }}" alt="" />
            <button id="start" class="home-btn welcome-sign-btn btn rounded-pill mt-5"><span>Start</span></button>
        </div>
        <div class="sliders d-none w-100">
            <div class="container-fluid p-0 m-0">
                <div class="row p-0 m-0 justify-content-center">
                    <div class="col-md-10">
                        <!-- Slick Slider Component -->
                        <div class="slick-carousel mt-4">
                            <div class="slick-slide-item">
                                <div class="staion-container">
                                    <img src="{{ asset('images/hadalabobabies/station1.png') }}" class="station-img"
                                        alt="Slide 1">
                                </div>
                            </div>
                            <div class="slick-slide-item">
                                <div class="staion-container">
                                    <img src="{{ asset('images/hadalabobabies/station1.png') }}" class="station-img"
                                        alt="Slide 1">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
                        arrows: true,
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
                        responsive: [{
                            breakpoint: 768,
                            settings: {
                                arrows: false,
                            }
                        }]
                    });
                }, 500);
            });
        });
    </script>
</x-app-layout>
