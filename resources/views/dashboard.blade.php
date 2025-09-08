<x-app-layout>
    <div class="py-5 map-page main-content main-background">
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
            <div class="modal-dialog modal-dialog-centered m-auto">
                <div class="modal-content card rounded-1">
                    <div class="modal-body">
                        <div class="text-center content">
                            <div class="text-content mt-4 mb-4">
                                <div class="d-flex justify-content-center">
                                    <img class="map-img pb-2" src="{{ asset('images/warning.png') }}" alt="" style="width:30px"/>
                                </div>
                                <p class="message">
                                    <p class="small-text text-black"><span class="text-black ">MONOCHROME</span> . <span class="text-black">MINIMALIST</span> . <span class="text-black">THE MULTIPLE</span></p>
                                </p>
                                <br>
                                <p class="text-black small-text">Kindly complete all station to proceed to the <br>
                                    Gift Redemption Station
                                </p>
                            </div>
                            <button type="button" class="w-100 custom-btn custom-btn-secondary rounded-1"
                                data-bs-dismiss="modal" aria-label="Close">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="map mb-5 animate-entry delay-2 mx-10 w-100">
            <div class="my-5">
                 <p class="text-center text-black"><span class="text-black ">MONOCHROME</span> . <span class="text-black">MINIMALIST</span> . <span class="text-black">THE MULTIPLE</span></p>
            </div>
            <img class="map-img" src="{{ asset('images/brand/nars_map.webp') }}" alt="" />
            {{-- loop trough the $stations --}}
            {{-- <a class="map-pin start-pin"><span class="start-text">Start</span></a> --}}
            @foreach ($stations as $station)
                @if ($station->id == 5)
                    <a href="javascript:void(0);" onclick="gotoStation({{ $station->id }})"
                        class="map-pin station-{{ $station->id }} @if ($station->status == true) completed @endif @if ($nextStation && $station->id === $nextStation->id) breathing @endif"
                        data-bs-toggle="modal" data-bs-target="#redemption">
                          @if ($station->status != true && $canAccessStation5 == true)
                             <!-- <img class="map-img" src="{{ asset('images/brand/pin' . $station->id . '.webp') }}" alt="" /> -->
                              <div class="d-flex align-items-start">
                                    <span class="pe-2">{{ $station->id }}</span>
                                    <span class="text-start text-uppercase">{{ $station->name }}</span>
                               </div>
                        @elseif ($canAccessStation5 != true && $station->status != true)
                            <div class="d-flex align-items-start">
                                    <span class="pe-2">{{ $station->id }}</span>
                                    <span class="text-start text-uppercase">{{ $station->name }}</span>
                               </div>
                        @else
                             <div class="d-flex align-items-center">
                                    <img class="map-img pe-2" src="{{ asset('images/check.png') }}" alt="" style="width:30px"/>
                                    <span class="text-start text-uppercase">CHECK IN SUCCESSFUL</span>
                               </div>
                        @endif
                    </a>
                @else
                    <a href="javascript:void(0);" onclick="gotoStation({{ $station->id }})"
                        class="map-pin station-{{ $station->id }} @if ($station->status == true) completed @endif @if ($nextStation && $station->id === $nextStation->id) breathing @endif">
                        @if ($station->status != true)
                             <!-- <img class="map-img" src="{{ asset('images/brand/pin' . $station->id . '.webp') }}" alt="" /> -->
                              <!-- <p>{{ $station->id }} {{ $station->name }}</p> -->
                               <div class="d-flex align-items-start">
                                    <span class="pe-2">{{ $station->id }}</span>
                                    <span class="text-start text-uppercase">{{ $station->name }}</span>
                               </div>
                        @else
                            <!-- <img class="map-img" src="{{ asset('images/brand/checkpin.webp') }}" alt="" /> -->
                             <div class="d-flex align-items-center">
                                    <img class="map-img pe-2" src="{{ asset('images/check.png') }}" alt="" style="width:30px"/>
                                    <span class="text-start text-uppercase">CHECK IN SUCCESSFUL</span>
                               </div>
                        @endif
                    </a>
                @endif
                
            @endforeach
        </div>
    </div>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                let canAccessStation5 = @json($canAccessStation5);
                window.gotoStation = function(id,) {
                    var url = "{{ route('station', ['station' => ':id']) }}".replace(
                        ":id",
                        id
                    );

                    if (id === 5 && !canAccessStation5) {
                        // Show the not allowed modal if trying to access station 6 without permission
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
