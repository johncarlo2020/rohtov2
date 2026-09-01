<x-guest-layout>
    <div class="register-main with-scroll row">
        <div class="col-lg-8 desktop-image-main">
            <img src="{{ asset('images/brand/main_img.webp') }}" alt="Login Image" srcset="">
        </div>
        <div class="flex-parent col-lg-4 d-flex flex-column justify-content-between">
                <div class="top">
                    <div class="d-flex justify-content-center mt-3 col-12">
                        @include('components.branding')
                    </div>
                </div>
                <div class="mid-top">
                    <div class="col-lg-8 mobile-image-main">
                        <img src="{{ asset('images/brand/main_img.webp') }}" alt="Login Image" srcset="">
                    </div>
                </div>
                <div class="mid">
                    <div class="mt-3 px-2 w-100">
                        <h1 class="mt-5 mb-3 text-center fw-bold text-dark">REGISTRATION</h1> 
                        <div class="px-4 py-5 pt-1 register-form-parent">
                            <form id="form" method="POST" action="{{ route('register') }}">
                                @csrf

                                {{-- Full Name --}}
                                <div class="mb-3">
                                    <input id="fname" placeholder="NAME" type="text"
                                        class="form-control input-text @error('fname') is-invalid @enderror" name="fname"
                                        value="{{ old('fname') }}" required autocomplete="fname" autofocus />
                                    @error('fname')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                {{-- Email --}}
                                <div class="mb-3">
                                    <input id="email" placeholder="EMAIL ADDRESS" type="email"
                                        class="form-control input-text @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" required autocomplete="email" />
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                {{-- Privacy Policy --}}
                                <div x-data="{ agreed: false }">
                                    <div class="mt-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="privacy_policy"
                                                value="1" id="privacyPolicy" x-model="agreed" required />
                                            <small class="text-dark form-check-label" for="privacyPolicy">
                                                <a href="https://www.iproperty.com.my/terms-and-conditions/"
                                                    class="text-dark">TERMS AND CONDITION</a>
                                            </small>
                                        </div>
                                    </div>

                                    <div class="mb-0 text-center">
                                        <button id="submitButton" type="submit"
                                            class="mt-4 mb-2 w-50 custom-btn custom-btn-primary pulse-slow" :disabled="!agreed"
                                            :class="{ 'opacity-50': !agreed }">
                                            {{ __('REGISTER') }}
                                        </button>
                                        <br>
                                        <small class="already-register text-dark">ALREADY REGISTERED? <a href="{{ route('login') }}" class="text-dark fw-bold">LOGIN HERE</a></small>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-12 bot">
                    <div class="logo-bot d-flex justify-content-center">
                        <img src="{{ asset('images/brand/bot_logo.webp') }}" class="img-fluid w-25" alt="Login Image" srcset="">
                    </div>
                </div>
        <div>
    </div>
</x-guest-layout>
