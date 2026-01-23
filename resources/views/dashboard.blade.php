<x-app-layout>
    <style>
       @keyframes floatIdle {
            0%   { transform: translateY(0); }
            50%  { transform: translateY(-4px); }
            100% { transform: translateY(0); }
        }

        .station-card {
            display: block;
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

        h2, .tile-title,.tile-number {
            font-weight: 700 !important;
        }

        .tile-image-wrapper {
    position: relative;
    width: 100%;
    overflow: hidden;
}

.tile-image-wrapper img {
    width: 100%;
    display: block;
}

/* Overlay only when status = true */
.tile-image-wrapper .overlay {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.65); /* black overlay */
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    pointer-events: none; /* keep card clickable */
}

/* Text styling */
.tile-image-wrapper .overlay span {
    color: #fff;
    font-size: 14px;
    font-weight: 600;
    letter-spacing: 2px;
    line-height: 1.4;
    text-transform: uppercase;
}
        

    </style>
    <div class="py-4 map-page main-content main-background">
        <div class="animate-entry">
            @include('components.branding')
        </div>
        
        <div class="text-center animate-entry">
          <h2 class="text-center my-5" >YSL BEAUTY LIGHT CLUB</h2>
        </div>
        <!-- login Modal -->

        <!-- Modal -->
        <div class="modal fade custom-modal animate-entry" id="notAllowedModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content card modal-parent">
                    <div class="modal-body">
                        <div class="text-center content">
                            <img class="check mx-auto mb-4" id="badge" src="{{asset('images/error.png') }}" style="filter:brightness(0);">
                            <div class="text-content mt-4 mb-4">
                                <h5 class="text-dark mb-4">YSL BEAUTY LIGHT CLUB</h5>
                                <p class="text-dark">
                                    Kindly complete Station 1-3 to proceed to <br> Gift Redemption station
                                </p>
                            </div>
                            <button type="button" class="w-75 custom-btn custom-btn-primary" data-bs-dismiss="modal"
                                aria-label="Close">CLOSE</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="container">
            <div class="tile-grid">
                @foreach ($stations as $station)
                    @php
                        $image = $station->status
                            ? asset("images/station/ST{$station->id}.webp")
                            : asset("images/station/ST{$station->id}.webp");
                    @endphp

                    <a
                        href="javascript:void(0)"
                        class="tile-grid-item station-card"
                        onclick="gotoStation({{ $station->id }})"
                    >
                        <p class="tile-number">{{ $station->id }}</p>

                        <div class="tile-image-wrapper">
                            <img
                                src="{{ $image }}"
                                alt="Station {{ $station->id }}"
                            >
                            @if ($station->status)
                                <div class="overlay">
                                    <span style="font-weight:300">CHECK-IN<br>SUCCESSFUL</span>
                                </div>
                            @endif
                        </div>

                        <p class="tile-title">
                            {{ strtoupper($station->name) }}
                        </p>
                    </a>
                @endforeach
            </div>
        </div>

    </div>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let canAccessStation4 = @json($canAccessStation4);
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

                    if (id === 4 && !canAccessStation4) {
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
