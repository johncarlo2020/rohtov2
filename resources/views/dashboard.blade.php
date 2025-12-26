<x-app-layout>
    <style>
       @keyframes floatIdle {
            0%   { transform: translateY(0); }
            50%  { transform: translateY(-4px); }
            100% { transform: translateY(0); }
        }

        .station-card {
            display: block;
            border-radius: 20px;
            overflow: hidden;
            animation: floatIdle 3.5s ease-in-out infinite;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .station-card:hover {
            animation-play-state: paused; /* pause idle float */
            transform: translateY(-10px) scale(1.03);
            box-shadow: 0 22px 45px rgba(0, 0, 0, 0.18);
        }

        .station-image {
            width: 100%;
            display: block;
            transition: transform 0.25s ease, filter 0.25s ease;
        }

        .station-card:hover .station-image {
            transform: scale(1.01);
            filter: brightness(1.05);
        }


    </style>
    <div class="py-4 map-page main-content main-background with-scroll">
        <div class="animate-entry">
            @include('components.branding')
        </div>

        <div class="text-center animate-entry">
            <svg viewBox="0 0 600 160" width="100%" height="120" aria-label="Sedia untuk bermain?">
                <path
                    id="archPath"
                    d="M 50 120 Q 300 20 550 120"
                    fill="transparent"
                />
                <text
                    font-size="48"
                    font-weight="900"
                    fill="#ff7a00"
                    stroke="#ffffff"
                    stroke-width="8"
                    paint-order="stroke"
                    text-anchor="middle"
                >
                    <textPath href="#archPath" startOffset="50%">
                        Sedia untuk bermain?
                    </textPath>
                </text>
            </svg>
        </div>
        <!-- login Modal -->

        <!-- Modal -->
        <div class="modal fade custom-modal animate-entry" id="notAllowedModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered w-75 m-auto">
                <div class="modal-content card modal-parent">
                    <div class="modal-body">
                        <div class="text-center content">
                            <div class="text-content mt-4 mb-4">
                                <p class="message text-white">
                                   Terokai semua stesen untuk menebus hadiah percuma anda!
                                </p>
                            </div>
                            <button type="button" class="w-50 custom-btn custom-btn-primary" data-bs-dismiss="modal"
                                aria-label="Close">TUTUP</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="container">
    <div class="row justify-content-center g-4">

        @foreach ($stations as $station)
            @php
                // Decide image based on status
                if ($station->status) {
                    $image = asset("images/station/ST{$station->id}-checked.webp");
                } else {
                    $image = asset("images/station/ST{$station->id}.webp");
                }
            @endphp

            <div class="col-6 col-lg-6">
                <a
                    href="javascript:void(0)"
                    class="station-card d-block"
                    onclick="gotoStation({{ $station->id }})"
                >
                    <img
                        src="{{ $image }}"
                        alt="Station {{ $station->id }}"
                        class="img-fluid station-image"
                    >
                </a>
            </div>
        @endforeach

    </div>
</div>


        

    </div>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let canAccessStation6 = @json($canAccessStation6);
                window.gotoStamping = function(id,)
                {
                    var url = "{{ route('station', ['station' => ':id']);}}".replace(
                        ":id",id
                    );
                     window.location.href = url;
                }
                window.gotoStation = function(id, ) {
                    var url = "{{ route('station', ['station' => ':id']) }}".replace(
                        ":id",
                        id
                    );

                    if (id === 6 && !canAccessStation6) {
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
