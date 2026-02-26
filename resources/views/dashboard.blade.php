<x-app-layout>
    <div class="py-3 map-page main-content main-background with-scroll">
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
            <div class="modal-dialog modal-dialog-centered w-90 m-auto">
                <div class="modal-content card rounded-1">
                    <div class="modal-body">
                        <div class="text-center content">
                            <div class="text-content mt-4 mb-3">
                                <div class="d-flex justify-content-center">
                                    <img class="map-img pb-2" src="{{ asset('images/warning.png') }}" alt="" style="width:30px"/>
                                </div>
                                <div class="message">
                                    <h1 class="welcome-title dashboard-face-title dashboard-face-title-modal mb-0">
                                        <span>FACE</span>
                                        <span>EVERYTHING</span>
                                    </h1>
                                </div>
                                <p class="dashboard-face-subtitle">Kindly complete all station
                                    to proceed to Gift Redemption
                                </p>
                            </div>
                            <button type="button" class="w-100 custom-btn rounded-1 not-allowed-close-btn"
                                data-bs-dismiss="modal" aria-label="Close">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="map mb-5 animate-entry delay-2 mx-10 w-100">
            <div class="my-3 text-center">
                <h1 class="welcome-title dashboard-face-title mb-0">
                    <span>FACE</span>
                    <span>EVERYTHING</span>
                </h1>
                <p class="dashboard-face-subtitle">Kindly complete all station<br> to proceed to Gift Redemption.</p>
            </div>
            <div class="station-list">
                @foreach ($stations as $station)
                    <a href="javascript:void(0);" onclick="gotoStation({{ $station->id }})"
                        class="station-card station-{{ $station->id }} @if ($station->status == true) completed @endif">
                        <div class="station-card-left">
                            <img class="station-card-image" src="{{ asset('images/brand/pin' . $station->id . '.webp') }}"
                                alt="Station {{ $station->id }}" />
                            @if ($station->status == true)
                                <span class="station-card-status">CHECK-IN SUCCESSFUL</span>
                            @endif
                        </div>
                        <div class="station-card-right">
                            <span class="station-card-title">{{ $station->id }}. {{ strtoupper($station->name) }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                let canAccessStation3 = @json($canAccessStation3);
                window.gotoStation = function(id,) {
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
