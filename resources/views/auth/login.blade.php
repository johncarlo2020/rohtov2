<x-guest-layout>
    <div class="register-main with-scroll row">
        <div class="col-lg-8 desktop-image-main">
            <img src="{{ asset('images/brand/main_img.webp') }}" alt="Login Image" srcset="">
        </div>
        <div class="col-lg-4 d-flex flex-column justify-content-start gap-5">
                <div class="d-flex justify-content-center col-12 top">
                    @include('components.branding')
                </div>
                <div class="col-lg-8 mobile-image-main mid-top">
                    <img src="{{ asset('images/brand/main_img.webp') }}" alt="Login Image" srcset="">
                </div>
                <div class="px-2 w-100 mid">
                    <h1 class="mb-3 text-center fw-bold text-dark">LOGIN</h1>
                    <div class="register-form-parent">
                        <x-auth-session-status class="mb-4" :status="session('status')" />

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-3">
                                <input id="email" type="email"
                                    class="form-control input-text @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" required autocomplete="email"
                                    placeholder="EMAIL ADDRESS" />
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                @if (session('error'))
                                    <span class="d-block invalid-feedback" role="alert">
                                        <strong>{{ session('error') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <input type="hidden" name="password" value="password" />

                            <div class="mb-0 text-center" style="margin-top:5svh !important;">
                                <button type="submit" class="custom-btn custom-btn-primary pulse-slow w-50 m-auto">
                                    {{ __('LOGIN') }}
                                </button>
                            </div>
                            <div class="bottom-text">
                                <span class="already-register text-dark text-uppercase">Haven't Registered?</span>
                                <span class="already-register">
                                    <a href="{{ route('register') }}" class="text-dark text-uppercase fw-bold">REGISTER HERE</a>
                                </span>
                            </div>
                            <div class="logo-bot d-flex justify-content-center mt-3">
                                <img src="{{ asset('images/brand/bot_logo.webp') }}" class="img-fluid" alt="Footer Image" srcset="" style="width:4rem;">
                            </div>
                        </form>
                    </div>
                </div>
                <!-- <div class="col-12 bot">
                    <div class="logo-bot d-flex justify-content-center">
                        <img src="{{ asset('images/brand/bot_logo.webp') }}" class="img-fluid w-25" alt="Login Image" srcset="">
                    </div>
                </div> -->
        </div>
    </div>
</x-guest-layout>
