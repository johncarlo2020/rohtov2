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
            <!-- Top Logo -->
            <div class="top">
                @include('components.branding')
            </div>

            <!-- Middle Content Area -->
            <div class="mid d-flex flex-column justify-content-center text-center my-auto py-5">
                <h2 class="fw-bold mb-4 text-uppercase" style="color: #F26522; font-family: 'Gill Sans MT Pro', sans-serif; letter-spacing: 1.5px; font-size: 1.5rem;">
                    BOOKING CANCEL!
                </h2>

                <div class="mb-4" style="color: #222222; font-family: 'Gill Sans MT Pro', sans-serif; font-weight: 500; letter-spacing: 0.8px; line-height: 1.8;">
                    <p class="mb-1 text-uppercase" style="font-size: 1.05rem;">
                        HI {{ $firstName ?? 'GUEST' }},
                    </p>
                    <p class="mb-0 text-uppercase" style="font-size: 1.05rem;">
                        YOUR BOOKING HAS BEEN CANCELLED
                    </p>
                </div>

                <div class="mt-4" style="color: #222222; font-family: 'Gill Sans MT Pro', sans-serif; font-weight: 500; letter-spacing: 0.8px;">
                    <p class="mb-0 text-uppercase" style="font-size: 1.05rem;">
                        THANK YOU!
                    </p>
                </div>
            </div>

            <!-- Bottom Logo -->
            <div class="bot">
                <div class="logo-bot">
                    <img src="{{ asset('images/brand/bot_logo.webp') }}" alt="Longchamp Logo">
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
