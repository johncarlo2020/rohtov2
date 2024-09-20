<x-app-layout>
    <style>
        .cloud {
            position: absolute;
            background-size: contain;
            z-index: 1;
        }

        .cloud img {
            width: 100%;
            height: 100%;
        }

        .first {
            animation-duration: 130s;
            top: 100px;
            left: 20px;
            width: 40px;
            height: 20px;
            animation: moveCloudLeft 100s linear infinite;
        }

        .second {
            animation-duration: 120s;
            top: 100px;
            right: 20px;
            width: 60px;
            height: 30px;
            animation: moveCloudRight 100s linear infinite;
        }

        .third {
            animation: moveCloudLeft 100s linear infinite;
            animation-duration: 100s;
            top: 200px;
            left: 20px;
            width: 80px;
            height: 40px;
        }

        .fourth {
            top: 250px;
            right: 20px;
            width: 50px;
            height: 30px;
            animation: moveCloudRight 100s linear infinite;
            animation-duration: 120s;
        }

        .gift-icon {
            width: 38px;
        }

        @keyframes moveCloudLeft {
            0% {
                left: 0px;
                opacity: 0.6;
            }

            30% {
                left: 30%;
                opacity: 0.4;
                z-index: 1;
            }

            60% {
                left: 60%;
                opacity: 0;
            }

            100% {
                left: 0px;
                opacity: 0;
            }
        }

        @keyframes moveCloudRight {
            0% {
                right: 0px;
                opacity: 0.6;
            }

            30% {
                right: 30%;
                opacity: 0.4;
            }

            60% {
                right: 60%;
                opacity: 0;
            }

            100% {
                right: 0px;
                opacity: 0;
            }
        }

        .ct-animate-blink {
            animation: blink 2s infinite;
            animation-fill-mode: both;
        }

        @keyframes blink {
            0% {
                opacity: 0;
            }

            50% {
                opacity: 1;
            }

            100% {
                opacity: 0;
            }
        }

        @keyframes blink2 {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }

        .metol-logo {
            position: absolute;
            bottom: -70%;
            left: 50%;
            transform: translateX(-50%);
            height: 60px;
            width: 59px !important;
            object-fit: contain;
            z-index: 2;
            animation: blink2 1s infinite;
        }

        .logo-135 {
            bottom: 54%;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            height: 60px;
            width: 37px !important;
            object-fit: contain;
            z-index: 2;
            animation: blink2 0.5s infinite;
        }
    </style>
    <div class="modal fade" id="scanCompleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center content">
                        <div class="image-check">
                            <i class="fa-regular check"></i>
                        </div>
                        <div class="text-content">
                            <p class="station-text">
                                Collect badges from 4 green stations and 2
                                yellow stations
                            </p>
                            <p class="message">
                                Head to redemption counter once completed
                            </p>
                        </div>
                        <div class="">
                            <button type="button" class="button" data-dismiss="modal">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid start home dashboard">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="col-12 d-flex justify-content-center">
                    @include('components.branding')
                </div>
                <div class="where">
                    <h1>Where is the <span>Little Nurse</span></h1>
                </div>
                <div class="heading-main">
                    <h1>Start your <span>Journey</span> now</h1>
                </div>
                <div class="icon-girl ct-animate-blink">
                    <img onclick="puzzle()" class="" src="{{ asset('images/girlIcon.png') }}" alt="" />
                </div>
                <p class="station-progress-heading">Station Progress</p>
                <div class="badge-container">
                    @foreach ($stations as $station)
                        @if ($station->id <= 4)
                            {{-- <div class="badge {{ $item->is_gotten == 1 ? 'completed' : '' }}"> --}}
                            <div class="badge {{ !$station->status ? '' : 'completed' }}"
                                onclick="gotoStation({{ $station->id }})">

                                <span>{{ $station->id }}</span>
                                <img src="{{ asset('images/badge' . $station->id . '.png') }}" alt="" />
                            </div>
                        @endif
                    @endforeach
                    @foreach ($notRequired as $item)
                        <div class="badge {{ $item->is_gotten == 1 ? 'completed' : '' }}">
                            <span>?</span>
                            <img src="{{ asset('images/badge' . $item->station_id . '.png') }}" alt="" />
                        </div>
                    @endforeach

                    <div class="badge with-img {{ $claim >= 6 ? 'completed' : '' }}">
                        <span>
                            <img class="gift-icon" src="{{ asset('images/gift.png') }}" onclick="modalOPen()" />
                        </span>
                    </div>
                </div>
                <div class="mt-3 text-center col-12 text-content">
                    <div class="cloud first">
                        <img src="{{ asset('images/cloud.png') }}" alt="" />
                    </div>
                    <div class="cloud second">
                        <img src="{{ asset('images/cloud.png') }}" alt="" />
                    </div>
                    <div class="cloud third">
                        <img src="{{ asset('images/cloud.png') }}" alt="" />
                    </div>
                    <div class="cloud fourth">
                        <img src="{{ asset('images/cloud.png') }}" alt="" />
                    </div>
                    <div class="map">
                        <img class="path-image" src="{{ asset('images/map.png') }}" alt="Station Image" />
                        @foreach ($stations as $station)
                            <div class="step step__{{ $station->id }}">
                                <div class="content">
                                    <img @if ($station->id != 9 || ($station->id == 9 && $claim == 6)) onclick="gotoStation({{ $station->id }})" @endif
                                        class="boot-img"
                                        src="{{ asset('images/step/step-img-' . $station->id . '.png') }}"
                                        alt="" />
                                    <img
                                        @if ($station->id === 9) class="metol-logo"  src="{{ asset('images/metol.png') }}" alt="" @endif>
                                    <img
                                        @if ($station->id === 9) class="logo-135"  src="{{ asset('images/135.png') }}" alt="" @endif>

                                    <div class="details-container">
                                        <div class="details">
                                            <span class="step-number {{ $station->status ? 'completed' : '' }}">
                                                @if (!$station->status)
                                                    {{ $station->id }}
                                                @else
                                                    <i class="fa-solid fa-check"></i>
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <!-- Ensure Bootstrap JS is included -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.4.0/dist/confetti.browser.min.js"></script>

    <script>
        function modalOPen() {
            $(scanCompleteModal).modal("show");
        }

        function gotoStation(id) {
            // Construct the URL with the 'id' parameter dynamically
            var url = "{{ route('station', ['station' => ':id']) }}".replace(
                ":id",
                id
            );
            // Redirect to the generated URL
            window.location.href = url;
        }

        function puzzle() {
            var url = "{{ route('station.puzzle') }}";
            // Redirect to the generated URL
            window.location.href = url;
        }

        function congrats() {
            var url = "{{ route('congrats') }}";
            // Redirect to the generated URL
            window.location.href = url;
        }
    </script>
</x-app-layout>
