<x-app-layout>
    <div class="content-box main-background d-flex flex-column min-vh-100">
        <div class="container mb-3s fade-in">
            <div>
                @include('components.branding')
            </div>
        </div>
        <div class="ocean-img mb-4 w-100">
            <img src="{{ asset('files/main/ocean or plastic_adaptation_v2.webp') }}" />
        </div>

        <div class="info-box px-2 mb-3">
            <p class="pharagraph-text text-center mb-0">Discover our very first <strong>Ocean or Plastic</strong> Roadshow—.</p>
            <p class="pharagraph-text text-center"> an immersive exploration of where your plastic ends
                up
                and how small choices
                lead to lasting impact.</p>
            <p class="pharagraph-text text-center">
                As part of the journey, uncover beauty that cares: enjoy personalised services for hair, skin, and body,
                and discover
                thoughtful ways to make choices that are gentler on the planet.
            </p>

        </div>

        <div class="button-container px-3 mt-5 fade-in">
            <a id="homeButton" href="{{ route('embarckJourney') }}" class="button button-primary w-100 mb-3">
               Be Part of the Change
            </a>
            <a id="homeButton" href="{{ route('guestAndWin') }}" class="button button-primary w-100 mb-3">
               Guess & Win
            </a>
            <a id="homeButton" href="#" class="button button-primary w-100 mb-3 disabled">
                Ocean Or Plastic Roadshow
            </a>
            <a id="reschedule" href="{{ route('appointment') }}" class="button button-secondary w-100 mb-3"">
                Reschedule
            </a>
        </div>

        <div class="spacer"></div>
    </div>

    <script>
         @if ($userAppointment > 0)
            @if ($selectedAppointment -> rescheduled == 1)
                document.getElementById('reschedule').classList.add('d-none');
            @endif
        @endif
    </script>
</x-app-layout>
