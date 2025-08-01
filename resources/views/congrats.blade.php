<x-app-layout>
    <div class="container-fluid congrats start completed-screen main-content main-background with-scroll animate-entry">
        <div class="">
            <div class="col-12 d-flex justify-content-center align-items-center mt-3">
                @include('components.branding')
            </div>

            <h2 class="my-5 text-center congrats-text">Thanks for being part of something impactful!</h2>
            <img class="waiting-img my-5" src="{{ asset('images/brand/donate gif.gif') }}" alt="Waiting for Game" />
            <img class="cat-walking mt-5" src="{{ asset('images/brand/cat-walking.webp') }}" alt="Waiting for Game" />
        </div>
    </div>
</x-app-layout>
