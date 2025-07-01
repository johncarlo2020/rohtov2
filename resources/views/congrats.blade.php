<x-app-layout>
    <div class="container-fluid home start completed-screen pt-4">

        <div class="congrats-container">
            <div class="col-12 d-flex justify-content-center align-items-center mt-3">
                <img class="welcome_img w-75" src="{{ asset('images/dutchlady/thankYouMessage.webp') }}" alt="" />
            </div>
            <div class="product-image mb-3 px-5">
                <a href="">

                    <img class="logo "
                        src="{{ asset('images/dutchlady/dutchLadyLogo.png') }}" alt="Dutch Lady Logo" />

                </a>
            </div>
            <div class="next-button-container text-center mt-5">
                <button onclick="window.location.href='{{ route('dashboard') }}'" class="button-dutch button-dutch-primary"><span>Done</span></button>
            </div>
        </div>
    </div>
    </div>
</x-app-layout>
