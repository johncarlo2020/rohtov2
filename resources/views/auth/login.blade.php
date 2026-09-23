<x-guest-layout>
    <div class="register-main with-scroll">
        <!-- Desktop Left Hero Image -->
        <div class="desktop-image-main">
            <img src="{{ asset('images/brand/main_img.webp') }}" alt="Longchamp Workshop">
        </div>

        <!-- Mobile Top Hero Image -->
        <div class="mobile-image-main">
            <img src="{{ asset('images/brand/main_img.webp') }}" alt="Longchamp Workshop">
        </div>

        <!-- Right / Bottom Content Area -->
        <div class="flex-parent">
            <!-- Top Logo (Desktop Only) -->
            <div class="top">
                @include('components.branding')
            </div>

            <!-- Middle Content -->
            <div class="mid d-flex flex-column justify-content-center my-auto">
                <h1 class="mb-4 text-center fw-bold text-dark text-uppercase" style="font-size: 1.5rem; letter-spacing: 1px;">
                    LOGIN
                </h1>

                <div class="register-form-parent">
                    <x-auth-session-status class="mb-3" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="d-flex flex-column align-items-center">
                        @csrf

                        <div class="w-100" style="margin-bottom:10vh;">
                            <input id="email" type="email"
                                class="form-control input-text @error('email') is-invalid @enderror" name="email"
                                value="{{ old('email') }}" required autocomplete="email"
                                placeholder="EMAIL ADDRESS" style="text-align: center;" />
                            @error('email')
                                <span class="invalid-feedback text-center d-block mt-1" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            @if (session('error'))
                                <span class="invalid-feedback text-center d-block mt-1" role="alert">
                                    <strong>{{ session('error') }}</strong>
                                </span>
                            @endif
                        </div>

                        <input type="hidden" name="password" value="password" />

                        <div class="text-center mb-3 w-100" style="max-width: 320px;">
                            <button type="submit" class="custom-btn custom-btn-primary w-100">
                                {{ __('LOGIN') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Bottom Horse Logo -->
            <div class="col-12 bot">
                <div class="logo-bot d-flex justify-content-center mt-3">
                    <img src="{{ asset('images/brand/bot_logo.webp') }}" class="img-fluid" alt="Footer Image" srcset="" style="width: 4rem;">
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
