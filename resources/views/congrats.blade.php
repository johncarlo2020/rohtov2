<x-app-layout>
    <div class="container-fluid congrats start completed-screen main-content main-background with-scroll pt-4">
        <div class="congrats-container">
            <div class="col-12 d-flex justify-content-center align-items-center mt-3">
                <!-- <img class="welcome_img w-75" src="{{ asset('images/dutchlady/thankYouMessage.webp') }}" alt="" /> -->
            </div>
                <div class="product-image px-5 text-center">
                    <div class="mb-4">
                        <span class="text-dark">Visit</span>
                    </div>
                    <a href="https://www.rytbank.my/" class="mt-5">
                        <img class="logo "
                            src="{{ asset('images/brand/logo.webp') }}" alt="sekkisei Logo" />
                    </a>
                    <div class="mt-4">
                        <span class="text-dark">for more information</span>
                    </div>
                </div>
                <div class="next-button-container text-center mt-5">
                    <button onclick="window.location.href='{{ route('dashboard') }}'" class="custom-btn custom-btn-secondary"><strong class="text-dark">BACK</strong></button>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
