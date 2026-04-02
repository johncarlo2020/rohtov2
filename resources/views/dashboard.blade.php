<x-app-layout>
    <style>
    /* Floating idle animation */
    @keyframes floatIdle {
        0%   { transform: translateY(0); }
        50%  { transform: translateY(-4px); }
        100% { transform: translateY(0); }
    }

    .station-card {
        animation: floatIdle 3.5s ease-in-out infinite;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .station-card:hover {
        transform: translateX(4px);
    }

    /* Layout (MATCH IMAGE DESIGN) */
    .station-row {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 22px;
        cursor: pointer;
    }

    /* Image */
    .tile-image-wrapper {
        position: relative;
        width: 95px;
        height: 95px;
        flex-shrink: 0;
        overflow: hidden;
    }

    .tile-image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    /* Overlay */
    .tile-image-wrapper .overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.65);
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        pointer-events: none;
    }

    .tile-image-wrapper .overlay span {
        color: #fff;
        font-size: 10px;
        font-weight: 500;
        letter-spacing: 1px;
        line-height: 1.4;
        text-transform: uppercase;
    }

    /* Text */
    .station-text {
        color: #fff;
        text-align: left;
    }

    .station-number {
        font-weight: 700;
        margin-right: 6px;
        font-size: 14px;
    }

    .station-title {
        font-weight: 600;
        letter-spacing: 1px;
        font-size: 13px;
        line-height: 1.4;
    }

    h2 {
        font-weight: 700 !important;
        letter-spacing: 2px;
    }
</style>

<div class="py-4 map-page main-content main-background with-scroll">

    <!-- Branding -->
    <div class="animate-entry">
        @include('components.branding')
    </div>

    <!-- Title -->
    <div class="text-center animate-entry">
        <h2 class="text-center my-5">FREEDOM HAS A TASTE</h2>
    </div>

    <!-- Modal -->
    <div class="modal fade custom-modal animate-entry" id="notAllowedModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content card modal-parent">
                <div class="modal-body">
                    <div class="text-center content">
                        <img class="check mx-auto mb-4" id="badge"
                             src="{{ asset('images/error.png') }}"
                             style="filter:brightness(0);">

                        <div class="text-content mt-4 mb-4">
                            <h5 class="text-dark mb-4">FREEDOM HAS A TASTE</h5>
                            <p class="text-dark">
                                Kindly complete Station 1-4 to proceed to <br>
                                Gift Redemption station
                            </p>
                        </div>

                        <button type="button"
                                class="w-75 custom-btn custom-btn-primary"
                                data-bs-dismiss="modal">
                            CLOSE
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stations -->
    <div class="container" style="max-width: 420px;">
        @foreach ($stations as $station)

            @php
                $image = asset("images/station/ST{$station->id}.webp");
            @endphp

            <div class="station-row station-card"
                 onclick="gotoStation({{ $station->id }})">

                <!-- Image -->
                <div class="tile-image-wrapper">
                    <img src="{{ $image }}" alt="Station {{ $station->id }}">

                    @if ($station->status)
                        <div class="overlay">
                            <span>CHECK-IN<br>SUCCESSFUL</span>
                        </div>
                    @endif
                </div>

                <!-- Text -->
                <div class="station-text">
                    <span class="station-number">{{ $station->id }}.</span>
                    <span class="station-title">
                        {!! strtoupper($station->name) !!}
                    </span>
                </div>

            </div>

        @endforeach
    </div>

</div>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let canAccessStation5 = @json($canAccessStation5);
                console.log(canAccessStation5);
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

                    if (id == 5 && !canAccessStation5) {
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
