<x-app-layout>
    <di v class="container-fluid congrats start completed-screen main-content main-background with-scroll pt-4">
        <div class="congrats-container">
            <div class="col-12 d-flex justify-content-center align-items-center mt-3">
                <img class="welcome_img w-75" src="{{ asset('images/dutchlady/thankYouMessage.webp') }}" alt="" />
            </div>
                <div class="product-image mb-3 px-5 text-center">
                    <span >Visit</span>
                    <a href="">

                        <img class="logo "
                            src="{{ asset('images/brand/logo.webp') }}" alt="sekkisei Logo" />

                    </a>
                    <span>for more information</span>
                </div>
                <div class="next-button-container text-center mt-5">
                    <button onclick="window.location.href='{{ route('dashboard') }}'" class="custom-btn custom-btn-secondary"><strong class="text-dark">BACK</strong></button>
                </div>
            </div>
        </di>
        <x-footer />
    </div>
</x-app-layout>
