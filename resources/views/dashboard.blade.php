<x-app-layout>
    <div class="container py-5 map-page">
        <div class="d-flex justify-content-center align-item-center">
            @include('components.branding')
        </div>

        <!-- login Modal -->
        <!-- Welcome Modal -->
        <div class="modal fade transparent-modal" id="welcomeModal" tabindex="-1" aria-labelledby="welcomeModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content text-center position-relative">

                    <!-- Close Button (top-right) -->
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-dismiss="modal"
                        aria-label="Close"></button>

                    <!-- Image -->
                    <img src="{{ asset('images/dutchlady/dutchLadyWelcomeModal.webp') }}" alt="Welcome"
                        class="img-fluid">

                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade custom-modal" id="notAllowedModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content ">
                    <div class="modal-body">
                        <div class="text-center content">
                            <div class="text-content mt-0">
                                <p class="message text-dark">
                                    Hit all the stations to redeem your free gift!
                                </p>
                            </div>
                            <button type="button" class="w-auto main-btn button-dutch button-dutch-primary"
                                data-dismiss="modal" aria-label="Close">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="map mb-5">
            <img class="map-img" src="{{ asset('images/brand/KOSE STB Map.webp') }}" alt="" />
            {{-- loop trough the $stations --}}
            {{-- <a class="map-pin start-pin"><span class="start-text">Start</span></a> --}}
            @foreach ($stations as $station)
                @if ($station->id == 6)
                    <a href="javascript:void(0);"
                        class="map-pin station-{{ $station->id }} @if ($station->status == true) completed @endif @if ($nextStation && $station->id === $nextStation->id) breathing @endif"
                        data-bs-toggle="modal" data-bs-target="#redemption">
                          @if ($station->status != true && $canAccessStation6 == true)
                             <img class="map-img" src="{{ asset('images/brand/pin' . $station->id . '.webp') }}" alt="" />
                        @elseif ($canAccessStation6 != true )
                            <img class="map-img" src="{{ asset('images/brand/locked pin') }}" alt="" />
                        @else
                            <img class="map-img" src="{{ asset('images/brand/checkpin.webp') }}" alt="" />
                        @endif
                    </a>
                @else
                    <a href="{{ route('station', $station) }}"
                        class="map-pin station-{{ $station->id }} @if ($station->status == true) completed @endif @if ($nextStation && $station->id === $nextStation->id) breathing @endif">
                        @if ($station->status != true)
                             <img class="map-img" src="{{ asset('images/brand/pin' . $station->id . '.webp') }}" alt="" />
                        @else
                            <img class="map-img" src="{{ asset('images/brand/checkpin.webp') }}" alt="" />
                        @endif
                    </a>
                @endif
            @endforeach
        </div>
        <x-footer />
    </div>
    @push('scripts')
        <script>
            function gotoStation(id) {
                var url = "{{ route('station', ['station' => ':id']) }}".replace(
                    ":id",
                    id
                );
                // Redirect to the generated URL
                window.location.href = url;
            }
        </script>
    @endpush
</x-app-layout>
