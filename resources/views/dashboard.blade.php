<x-app-layout>
    <style>
        .cloud {
            position: absolute;
            background-size: contain;
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
            z-index: 9999;
            animation: moveCloudRight 100s linear infinite;
        }

        .third {
            animation: moveCloudLeft 100s linear infinite;
            animation-duration: 100s;
            top: 200px;
            left: 20px;
            width: 80px;
            height: 40px;
            z-index: 9999;
        }

        .fourth {
            top: 250px;
            right: 20px;
            width: 50px;
            height: 30px;
            z-index: 3;
            animation: moveCloudRight 100s linear infinite;
            animation-duration: 120s;
        }

        .gift-icon {
            width: 38px;
        }

        @keyframes moveCloudLeft {
            0% {
                left: 0px;
                opacity: 1;
            }

            30% {
                left: 30%;
                opacity: 1;
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
                opacity: 1;
            }

            30% {
                right: 30%;
                opacity: 1;
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
    </style>
    <div class="container-fluid start home dashboard">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="col-12 d-flex justify-content-center">
                    @include('components.branding')
                </div>
                <div class="heading-main">
                    <h1>Start your <span>Journey</span> now</h1>
                </div>
                <div class="icon-girl">
                    <img onclick="puzzle()" class="" src="{{ asset('images/girlIcon.png') }}" alt="" />
                </div>
                <p class="station-progress-heading">Station Progress</p>
                <div class="badge-container">
                    @foreach ($required as $item)
                        <div class="badge {{ $item->is_gotten == 1 ? 'completed' : '' }}">
                            <span>?</span>
                            <img src="{{ asset('images/badge' . $item->station_id . '.png') }}" alt="" />
                        </div>
                        @endforeach @foreach ($notRequired as $item)
                            <div class="badge {{ $item->is_gotten == 1 ? 'completed' : '' }}">
                                <span>?</span>
                                <img src="{{ asset('images/badge' . $item->station_id . '.png') }}" alt="" />
                            </div>
                        @endforeach

                        <div class="badge with-img {{ $claim >= 6 ? 'completed' : '' }}">
                            <span>

                                <img class="gift-icon" src="{{ asset('images/gift.png') }}" {!! $claim >= 6 ? 'onclick="congrats()"' : '' !!} />

                            </span>
                        </div>
                </div>
                <div class="mt-3 text-center col-12 text-content">
                    <div class="cloud first">
                        <img src="{{ asset('images/cloud.png') }}" alt="">
                    </div>
                    <div class="cloud second">
                        <img src="{{ asset('images/cloud.png') }}" alt="">
                    </div>
                    <div class="cloud third">
                        <img src="{{ asset('images/cloud.png') }}" alt="">
                    </div>
                    <div class="cloud fourth">
                        <img src="{{ asset('images/cloud.png') }}" alt="">
                    </div>
                    <div class="map">
                        <img class="path-image" src="{{ asset('images/map.png') }}" alt="Station Image" />
                        @foreach ($stations as $station)
                            <div class="step step__{{ $station->id }}">
                                <div class="content">
                                    <img onclick="gotoStation({{ $station->id }})" class="boot-img"
                                        src="{{ asset('images/step/step-img-' . $station->id . '.png') }}"
                                        alt="" />

                                    <div class="details-container">
                                        <div class="details">
                                            <span class="step-number {{ $station->status ? 'completed' : '' }}">
                                                @if ($station->status == false)
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

    <script>
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
