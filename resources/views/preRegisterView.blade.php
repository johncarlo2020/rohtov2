<x-app-layout>
    <div class="content-box main-background d-flex flex-column min-vh-100">
        <div class="container mb-5">
            <div>
                @include('components.branding')
            </div>
        </div>
        <div class="ocean-img mb-3">
            <img src="{{ asset('files/main/ocean_photo.webp') }}" />
        </div>

        <div class="button-container px-3 mt-5">
            <a id="homeButton" href="{{ route('embarckJourney') }}" class="button button-primary w-100 mb-3">
               Be Part of the Change
            </a>
            <a id="homeButton" href="{{ route('guestAndWin') }}" class="button button-primary w-100 mb-3">
               Guess & Win
            </a>
            <a id="homeButton" href="{{ route('preRegEvent') }}" class="button button-primary w-100 mb-3 disabled">
                Ocean Or Plastic Roadshow
            </a>
        </div>

        <div class="footer-container p-4 mt-auto">
            @include('components.footer')
        </div>
    </div>
</x-app-layout>
