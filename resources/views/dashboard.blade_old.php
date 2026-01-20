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
    <div class="py-4 map-page main-content main-background">
        <div class="animate-entry">
            @include('components.branding')
        </div>
        
        <div class="text-center animate-entry">
           <img class="dashboard-text" src="{{ asset('images/brand/dashboardtext.png') }}" alt="">
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
    <div class="tile-grid">

        @foreach ($stations as $station)
            @php
                // Decide image based on status
                if ($station->status) {
                    $image = asset("images/station/ST{$station->id}-checked.webp");
                } else {
                    $image = asset("images/station/ST{$station->id}.webp");
                }
            @endphp

           
                <a
                    href="javascript:void(0)"
                    class="station-card d-block tile-grid-item"
                    onclick="gotoStation({{ $station->id }})"
                >
                    <img
                        src="{{ $image }}"
                        alt="Station {{ $station->id }}"
                        class="tile-grid-image station-image"
                    >
                </a>
          
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
