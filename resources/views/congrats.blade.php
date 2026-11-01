<x-app-layout>
    <div class="container-fluid congrats start completed-screen main-content main-background with-scroll pt-4"
         style="padding-bottom: max(1.5rem, calc(env(safe-area-inset-bottom, 0px) + 1.25rem)) !important;">
        <div class="congrats-container">
            <div class="product-image text-center w-100">
                <div class="text-center animate-entry mt-3" style="margin-bottom: 12vh;">
                    <div class="animate-entry mb-4">
                        @include('components.branding')
                    </div>
                    <h2 class="text-center fw-bold my-4" style="font-size: clamp(1.2rem, 5vw, 1.5rem); letter-spacing: 2px; line-height: 1.4;">
                        THANK YOU FOR JOINING<br>THE YSL HOLIDAY SHOW
                    </h2>
                </div>
                <div class="row text-center">
                    <span style="font-size: clamp(13px, 3.5vw, 15px);">Visit</span>
                    <a href="https://www.yslbeauty.com.sg/en_SG/libre.html" class="my-2 animate-entry">
                        <img class="logo w-50 m-auto"
                            src="{{ asset('images/brand/logo.webp') }}" alt="YSL Logo" />
                    </a>
                    <span style="font-size: clamp(13px, 3.5vw, 15px);">for more information.</span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
