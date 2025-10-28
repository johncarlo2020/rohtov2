<x-app-layout>
    <div class="py-5 map-page main-content main-background with-scroll dashbord-page">
        <div class="d-flex justify-content-center align-item-center animate-entry">
            @include('components.branding')
        </div>

        <!-- login Modal -->
        <!-- Welcome Modal -->
        <div class="modal fade transparent-modal" id="welcomeModal" tabindex="-1" aria-labelledby="welcomeModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content text-center position-relative card">

                    <!-- Close Button (top-right) -->
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"
                        aria-label="Close"></button>

                    <!-- Image -->
                    <img src="{{ asset('images/dutchlady/dutchLadyWelcomeModal.webp') }}" alt="Welcome"
                        class="img-fluid">

                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade custom-modal" id="notAllowedModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered w-75 m-auto">
                <div class="modal-content card">
                    <div class="modal-body">
                        <div class="text-center content">
                            <div class="text-content mt-4 mb-4">
                                <p class="message">
                                    Please Complete all <br> the station
                                </p>
                            </div>
                            <button type="button" class="w-50 custom-btn custom-btn-primary" data-bs-dismiss="modal"
                                aria-label="Close">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <h1 class="sub-heading text-center px-5 py-3 mb-3 animate-entry delay-1 dashboard-heading">Ready to start your
            new banking experience</h1>
        <div class="station-selection-container mb-2 animate-entry delay-2">
            <img class="station-bg-map" src="{{ asset('images/station/map-lines.svg') }}" alt="">
            @foreach ($stations as $station)
                <a class="station-custom-btn  done station-custom-btn-{{ $station->id }} @if ($station->status) done @endif"
                    type="button" onclick="gotoStation({{ $station->id }})">
                    <div class="station-image-container">
                        <img class="station-icon" src="{{ asset('images/station/ST' . $station->id . '.webp') }}"
                            alt="">
                    </div>
                    <div class="station-details station-{{ $station->id }}">
                        <h3 class="station-number">Station {{ $station->id }}
                            <i class="check-icon fa-solid fa-circle-check text-success"></i>
                        </h3>
                        <h5 class="station-name">{{ $station->name }}</h5>
                    </div>
                </a>
            @endforeach

        </div>
    </div>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let canAccessStation3 = @json($canAccessStation3);
                window.gotoStation = function(id, ) {
                    var url = "{{ route('station', ['station' => ':id']) }}".replace(
                        ":id",
                        id
                    );

                    if (id === 3 && !canAccessStation3) {
                        // Show the not allowed modal if trying to access station 3 without permission
                        var notAllowedModal = new bootstrap.Modal(document.getElementById('notAllowedModal'));
                        notAllowedModal.show();
                        return;
                    }

                    // Redirect to the generated URL
                    window.location.href = url;
                }
            });
        </script>
    @endpush
</x-app-layout>
