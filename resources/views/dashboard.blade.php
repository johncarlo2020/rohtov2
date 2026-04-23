<x-app-layout>
    <style>
    /* Floating idle animation */
    @keyframes floatIdle {
        0%   { transform: translateY(0); }
        50%  { transform: translateY(-4px); }
        100% { transform: translateY(0); }
    }

    .station-card {
        border-radius: 18px;
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
    .developer-card .overlay,
    .station-card .overlay {
        position: absolute;
        border-radius: 18px;
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
        letter-spacing: 1px;
        font-size: 9px;
        text-align: center;
    }

    h2 {
        font-weight: 700 !important;
        letter-spacing: 2px;
    }

/* developers */
.developer-card {
    background: #eef3f8;
    border-radius: 18px;
    padding: 25px 20px;
    text-align: center;

    /* soft shadow like image */
    box-shadow: 0 6px 14px rgba(0,0,0,0.08);

    /* subtle border glow */

    transition: 0.25s ease;
    
}

/* spacing between cards */
.developer-card + .developer-card {
    margin-top: 15px;
}

/* logo */
.developer-logo {
    max-height: 60px;
    object-fit: contain;
}

/* hover (subtle lift) */
.developer-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.12);
}
</style>

<div class="py-4 map-page main-content main-background with-scroll">

    <!-- Branding -->
    <div class="animate-entry">
        @include('components.branding')
    </div>

    <!-- Title -->
    <div class="text-center animate-entry">
        <h2 class="text-center my-5">Explore</h2>
    </div>

    <!-- Modal -->
    <div class="modal fade custom-modal animate-entry " id="notAllowedModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content card modal-parent">
                <div class="modal-body">
                    <div class="text-center content">
                        <img class="check mx-auto mb-4" id="badge"
                             src="{{ asset('images/error.png') }}"
                             style="filter:brightness(0);">

                        <div class="text-content mt-4 mb-4">
                            <p class="text-dark">
                                Unlock this by completing<br>
                                your visits to 3 developer stations.
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


    <div class="container py-3">



        @foreach (auth()->user()->developers as $developer)

            @php
                $imageDev = asset("images/developer/DEV{$developer->id}.webp");
            @endphp

            <div class="developer-card mb-3 d-flex justify-content-center align-items-center animate-entry" onclick="gotoQuiz({{$developer->id}})">
                <img src="{{ $imageDev }}" 
                    alt="Developer {{ $developer->id }}" 
                    class="developer-logo">

                    @if ($developer->pivot->isCompleted)
                        <div class="overlay">
                            <span>COMPLETED</span>
                        </div>
                    @endif
            </div>

        @endforeach

    </div>

    <!-- Stations -->
    <div class="container" style="max-width: 420px;">

        <div class="d-flex justify-content-between station-row animate-entry">
            @foreach ($stations as $station)
                @php
                    $image = asset("images/station/ST{$station->id}.webp");
                @endphp
                <div class="station-row station-card bg-primary "
                    onclick="gotoStation({{ $station->id }})">

                    <!-- Image -->
                    <div class=" text-center p-2">
                        <img src="{{ $image }}" alt="Station {{ $station->id }}">
                        <span class="station-title">
                            {{($station->name)}}
                        </span>

                            @if($station->id == 3 && !$canAccessStation3)
                                <div class="overlay">
                                    <span class="small">LOCKED</span>
                                </div>
                            @endif

                            @if($station->status)
                                <div class="overlay">
                                    <span class="small">REDEEMED</span>
                                </div>
                            @endif
                    </div>

                    <!-- Text -->
                    {{-- <div class="station-text">
                        <span class="station-number">{{ $station->id }}.</span>
                        <span class="station-title">
                            {!! strtoupper($station->name) !!}
                        </span>
                    </div> --}}

                </div>

            @endforeach
        </div>
    </div>

</div>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let canAccessStation5 = @json($canAccessStation5);
                let canAccessStation3 = @json($canAccessStation3);
                console.log(canAccessStation5);
                window.gotoStamping = function(id,)
                {
                    var url = "{{ route('station', ['station' => ':id']);}}".replace(
                        ":id",id
                    );
                     window.location.href = url;
                }

                window.gotoQuiz = function(id,) {
                    var url = "{{ route('developer', ['developer' => ':id']) }}".replace(
                        ":id",id
                    );
                    // Redirect to the generated URL
                    window.location.href = url;
                }

                window.gotoStation = function(id, ) {
                    var url = "{{ route('station', ['station' => ':id']) }}".replace(
                        ":id",
                        id
                    );

                    if (id == 3 && !canAccessStation3) {
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
