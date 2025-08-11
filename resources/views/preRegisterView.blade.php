<x-app-layout>
    <div class="content-box main-background d-flex flex-column min-vh-100">
        <div class="container mb-3s fade-in">
            <div>
                @include('components.branding')
            </div>
        </div>
        <div class="ocean-img mb-4 w-100">
            <img src="{{ asset('files/main/dashboardBG.webp') }}" />
        </div>

        <div class="info-box px-2 mb-3">
            <p class="pharagraph-text text-center mb-0">Join us to discover <strong>L’Occitane’s Ocean or Plastic Roadshow</strong></p>
            <p class="pharagraph-text text-center"> – an immersive exploration of where your plastic ends up and how small choices lead to lasting impact.</p>
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
            {{-- <a id="homeButton" href="{{ route('guestAndWin') }}" class="button button-primary w-100 mb-3">
               Guess & Win
            </a> --}}
            <a id="homeButton" href="{{ route('map') }}" class="button button-primary w-100 mb-3">
                Ocean Or Plastic Roadshow
            </a>
                <!-- <button class="button button-primary w-100 mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Ocean Or Plastic Roadshow
            </button> -->
            <a id="reschedule" href="{{ route('appointment') }}" class="button button-secondary w-100 mb-3 {{ auth()->user()->type != 'pre-reg' ? 'd-none' : '' }}">
                Reschedule
            </a>
        </div>

        <div class="spacer"></div>
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <a type="button" class="modal-close" data-bs-dismiss="modal" aria-label="Close"><i
                                class="fa-solid fa-xmark"></i></a>

                        <p class="heading-text text-center mb-2">Ocean Or Plastic Roadshow</p>
                        <div class="map-img mb-3">
                            <img src="{{ asset('files/main/locci map_np shadow.webp') }}" alt="" />
                        </div>
                        <p class="modal-main-text mb-4 px-2">This section will be available starting 28 July, see you
                            then!</p>
                        {{-- <p class="warning-text text-center px-5">Note: You may reschedule your selected date
                        <strong>only once</strong>.
                    </p> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        @if ($userAppointment > 0)
            @if ($selectedAppointment->rescheduled == 1)
                document.getElementById('reschedule').classList.add('d-none');
            @endif
        @endif
    </script>
</x-app-layout>
