<x-guest-layout>
    <style>
    @media (max-width: 430px) {
        .bottom-text
        {
            margin-bottom:5%;
        }

        .hero-image
        {
            margin-top:5% !important;
            margin-bottom:5% !important;
        }
    }
    </style>
    <div class="register-main with-scroll row">
        <div class="col-lg-8 desktop-image-main">
            <img src="{{ asset('images/brand/main_img.webp') }}" alt="Login Image" srcset="">
        </div>
        <div class="flex-parent col-lg-4 d-flex flex-column justify-content-between">
                <div class="top">
                    <div class="d-flex justify-content-center col-12">
                        @include('components.branding')
                    </div>
                </div>
                <div class="mid-top">
                    <div class="col-lg-8 mobile-image-main">
                        <img src="{{ asset('images/brand/main_img.webp') }}" class="w-75 m-auto img-fluid hero-image" alt="Login Image" srcset="">
                    </div>
                </div>
                <div class="mid">
                    <div class="px-2 w-85 m-auto text-center">
                        <h2 class="text-dark text-uppercase mb-4" style="font-size: 1.25rem; line-height: 1.4; letter-spacing: 0.5px;">
                            LONGCHAMP INVITES YOU TO "<strong class="fw-bold">BE CREATIVE</strong>" THIS WINTER 2026, EXCLUSIVELY IN MALAYSIA
                        </h2>

                        <p class="text-dark text-uppercase mb-3" style="font-size: 0.82rem; line-height: 1.5; letter-spacing: 0.3px;">
                            THIS WINTER 2026, LONGCHAMP INVITES YOU TO "<strong>BE CREATIVE</strong>" &mdash; A CELEBRATION OF ART, CRAFTSMANSHIP AND IMAGINATION INSPIRED BY THE MAISON'S GLOBAL THEME OF <strong>CREATIVE CURIOSITY.</strong>
                        </p>

                        <p class="text-dark text-uppercase mb-4" style="font-size: 0.82rem; line-height: 1.5; letter-spacing: 0.3px;">
                            THE CREATIVE EXPERIENCE EXTENDS BEYOND THE COLLECTION. IN THIS WORKSHOP, DISCOVER THE ART OF COLLAGE THROUGH AN INTERACTIVE ACTIVITY INSPIRED BY <strong>CAROLINE HÉLAIN'S EXPRESSIVE LANDSCAPES.</strong> CREATE YOUR OWN LAYERED LANDSCAPE USING PAPER, COLOUR AND TEXTURE TO BRING YOUR COMPOSITION TO LIFE.
                        </p>

                        <div class="bottom-text">
                            <a href="{{ route('register') }}" class="custom-btn custom-btn-primary mb-2 pulse-slow w-50 m-auto">REGISTER</a>
                            <a href="{{ route('login') }}" class="custom-btn custom-btn-primary pulse-slow w-50 m-auto">LOGIN</a>
                        </div>
                    </div>
                </div>
                <div class="col-12 bot">
                    <div class="logo-bot d-flex justify-content-center">
                        <img src="{{ asset('images/brand/bot_logo.webp') }}" class="img-fluid w-25" alt="Footer Image" srcset="">
                    </div>
                </div>
            </div>
        </div>
</x-guest-layout>
