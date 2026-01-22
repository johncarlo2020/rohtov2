<x-app-layout>
    <style>
        .login-page h4,
        .login-page label,
        .login-page input,
        .login-page p,
        .login-page a,
        .login-page span {
            font-family: 'PlusJakartaSans' !important;
        }

        .card-container {
    position: relative;   /* anchor */
    width: 100%;
    margin-top:-30px;
    }

    .bg-img {
    width: 100%;
    height: auto;
    display: block;
    }

    .content {
    position: absolute;   /* overlays image */
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;

    /* optional */
    display: flex;
    align-items: start;
    justify-content: space-between;
    }

    label
    {
        font-weight: 900;
    }

    .brand-container
    {
        position: relative;
        z-index: 99;
    }
    </style>
    <div class="login-page vh-100">
        <div class="main-content main-background with-scroll">
            <div class="col-12 animate-entry brand-container">
                @include('components.branding')
            </div>
            <div class="col-12 animate-entry delay-2">
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />
                <div class="container card-container">
                    <img src="{{ asset('images/brand/card_bg.webp') }}" class="bg-img">
                        <div class="content px-2">
                            <div class="form-parent px-4 mt-5">
                                <div class="heading-container text-center">
                                <div class="d-flex justify-content-center align-items-center mb-3">
                                    <img class="img-fluid check-img" src="{{asset('images/brand/check.webp')}}" alt="" style="width:50px;">
                                </div>
                                <h2 class="text-center sub-heading-text animate-entry">REDEMPTION DONE !</h2>
                                <h4 class="sub-heading-text text-center animate-entry">Thank you</h4>
                            </div>
                            <div class="thankyou">
                                <img class="img-fluid thankyou-img" src="{{asset('images/brand/thankyou.webp')}}" alt="">
                            </div>
                            <div class="button-container text-center">
                                <a href="{{ route('congrats') }}" class="custom-btn custom-btn-primary mt-5">
                                    DONE
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
