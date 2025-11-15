<x-app-layout>
    <div class="py-4 map-page main-content dashbord-page">
        <div class="animate-entry">
            @include('components.branding')
        </div>

        <!-- login Modal -->

        <!-- Modal -->
        <div class="modal fade custom-modal" id="notAllowedModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered w-75 m-auto">
                <div class="modal-content card">
                    <div class="modal-body">
                        <div class="text-center content">
                            <div class="text-content mt-4 mb-4">
                                <p class="message text-dark">
                                    Ready for Treasure Spot 3? <br>First, complete Treasure Spot 1 & Treasure Spot 2 to unlock it!
                                </p>
                            </div>
                            <button type="button" class="w-50 custom-btn custom-btn-primary" data-bs-dismiss="modal"
                                aria-label="Close">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="station-selection-container mb-2 animate-entry delay-2">
            @foreach ($stations as $station)

                <a class="station-custom-btn station-custom-btn-{{ $station->id }}"
                    type="button"
                    @if($station->status)
                        @if($station->id == 3)
                            @if($isRedeemed)
                                onclick="window.location.href='{{ route('congrats') }}'"
                            @else 
                                onclick="window.location.href='{{ route('station.giftselection') }}'"
                            @endif
                        @else
                             onclick="gotoStamping({{ $station->id }})"
                        @endif
                    @else
                            onclick="gotoStation({{ $station->id }})"
                    @endif
                    >

                    <div class="station-image-container">
                        <img class="station-icon station-{{ $station->id }} pulse-slow" 
                            data-id="station-{{ $station->id }}" 
                            src="@if($station->status)
                                {{asset('images/station/STBM' . $station->id . 'GLOW.webp');}}
                            @else
                                {{asset('images/station/STBM' . $station->id . '.webp');}}
                            @endif"
                            alt="Station {{ $station->id }}"
                            style="@if($station->status) filter: grayscale(0); @endif"> <!-- grayscale only if NOT completed -->
                    </div>
                    <div class="station-details station-{{ $station->id }}">
                    </div>
                </a>
            @endforeach

        </div>
    </div>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let canAccessStation3 = @json($canAccessStation3);
                window.gotoStamping = function(id,)
                {
                    var url = "{{ route('station.stamping', ['station' => ':id']);}}".replace(
                        ":id",id
                    );
                     window.location.href = url;
                }
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
