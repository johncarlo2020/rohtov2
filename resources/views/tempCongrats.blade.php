<x-app-layout>
    <style>
        .icon-badge {
            width: 150px;
            height: auto;
            margin-bottom: 25px;
        }

        .iconNew {
            width: 60px;
        }

        .logo-img {
            width: 100px;
        }
    </style>
    <div class="station-page home content-box main-background d-flex flex-column min-vh-100 pt-5 ">
        <div class="mb-3 branding-container">
            @include('components.branding')
        </div>
        <div class="content p-4">
            <img class="heart-with-hand" src="{{ asset('files/main/hand-heart.webp') }}" alt="">

            <p class="text-center heading-text">
                Thanks for participating! <br>
                We appreciate your support. However, we've noticed you already registered for and completed the journey
                in a previous event.
            </p>

            <p class="text-center heading-text fw-normal">Feel free to browse our products in the meantime, and we hope you have a great day.
            </p>
        </div>

        <div class="footer-container p-0 mt-auto">
            @include('components.footer')
        </div>
    </div>
</x-app-layout>
