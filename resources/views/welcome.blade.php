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
        <div class="flex-parent col-lg-4 d-flex flex-column justify-content-between p-0">
                <div class="top mb-4">
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
                    <div class="text-center">
                        <h2 class="text-dark text-uppercase mb-4" style="font-size: 1.25rem; line-height: 1.4; letter-spacing: 0.5px;">
                            LONGCHAMP INVITES YOU TO <br>"<strong class="fw-bold">BE CREATIVE</strong>" THIS WINTER <br> 2026, EXCLUSIVELY IN MALAYSIA
                        </h2>

                        <!-- <p class="text-dark text-uppercase mb-3" style="font-size: 0.75rem; line-height: 1.5; letter-spacing: 0.3px;">
                            THIS WINTER 2026, LONGCHAMP INVITES YOU TO <br>"<strong>BE CREATIVE</strong>" &mdash; A CELEBRATION OF ART <br>, CRAFTSMANSHIP AND IMAGINATION INSPIRED BY <br>THE MAISON'S GLOBAL THEME OF <strong>CREATIVE <br>CURIOSITY.</strong>
                        </p>

                        <p class="text-dark text-uppercase mb-4" style="font-size: 0.75rem; line-height: 1.5; letter-spacing: 0.3px;">
                            THE CREATIVE EXPERIENCE EXTENDS BEYOND THE <br>COLLECTION. IN THIS WORKSHOP, DISCOVER THE <br> ART OF COLLAGE THROUGH AN INTERACTIVE <br>ACTIVITY INSPIRED BY <strong>CAROLINE HÉLAIN'S <br> EXPRESSIVE LANDSCAPES.</strong> CREATE YOUR OWN <br>LAYERED LANDSCAPE USING PAPER, COLOUR AND <br>TEXTURE TO BRING YOUR COMPOSITION TO LIFE.
                        </p> -->

                        <p class="text-dark  text-uppercase mb-4" style="font-size: 0.75rem; line-height: 1.5; letter-spacing: 0.3px;">
                            The creative experience extends beyond the<br> collection In this workshop, discover the art of <br> collage through an interactive activity inspired<br>
                            by <strong>Caroline Hélain’s expressive landscapes.</strong><br> Create your own layered landscape using <br> paper, colour and tetxure to bring your <br> 
                            composition to life.
                        </p>

                        <div class="bottom-text mb-3">
                            <a href="{{ route('register') }}" class="custom-btn custom-btn-primary mb-2 pulse-slow w-50 m-auto">REGISTER</a>
                            <a href="{{ route('login') }}" class="custom-btn custom-btn-primary pulse-slow w-50 m-auto">LOGIN</a>
                        </div>
                        <div class="logo-bot d-flex justify-content-center">
                            <img src="{{ asset('images/brand/bot_logo.webp') }}" class="img-fluid" alt="Footer Image" srcset="" style="width:4rem;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
</x-guest-layout>
