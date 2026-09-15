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
            <div class="mid">
                <div class="text-center">
                    <h1 class="text-dark text-uppercase mb-3" style="font-size: 1.15rem; line-height: 1.45; letter-spacing: 0.5px; font-weight:400;">
                        LONGCHAMP INVITES YOU TO "<strong class="fw-bold">BE CREATIVE</strong>" <br>
                        THIS WINTER 2026, EXCLUSIVELY IN MALAYSIA
                    </h1>

                    <p class="text-dark mb-4" style="font-size: 0.725rem; line-height: 1.55; letter-spacing: 0.3px; color: #444444;">
                        The creative experience extends beyond the collection. In this workshop, discover the art of collage through an interactive activity inspired by <strong>Caroline Hélain’s expressive landscapes.</strong> Create your own layered landscape using paper, colour and texture to bring your composition to life.
                    </p>

                    <div class="d-flex flex-column gap-2 align-items-center mb-3">
                        <a href="{{ route('register') }}" class="custom-btn custom-btn-primary" style="max-width: 220px; width: 100%;">
                            REGISTER
                        </a>
                        <a href="{{ route('login') }}" class="custom-btn custom-btn-primary" style="max-width: 220px; width: 100%;">
                            LOGIN
                        </a>
                    </div>
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
