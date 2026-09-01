<x-guest-layout>
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
                        <img src="{{ asset('images/brand/main_img.webp') }}" alt="Login Image" srcset="">
                    </div>
                </div>
                <div class="mid">
                    <div class="px-2 w-75 m-auto">
                        <div class="bottom-text">
                            <a href="{{ route('register') }}" class="custom-btn custom-btn-primary mb-2 pulse-slow">REGISTER</a>
                            <a href="{{ route('login') }}" class="custom-btn custom-btn-primary pulse-slow">LOGIN</a>
                        </div>
                    </div>
                </div>
                <div class="col-12 bot">
                    <div class="logo-bot d-flex justify-content-center">
                        <img src="{{ asset('images/brand/bot_logo.webp') }}" class="img-fluid w-25" alt="Login Image" srcset="">
                    </div>
                </div>
            </div>
        </div>
</x-guest-layout>
