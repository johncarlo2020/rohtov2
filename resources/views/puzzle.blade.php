<x-app-layout>
    <style>
        .puzzle-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-template-rows: repeat(3, 1fr);
            width: 215px;
            height: 215px;
            justify-content: center;
            align-items: center;
            background: #fff;
            padding: 20px;
            border: 2px solid #8BC28C;
            border-radius: 20px;
            margin: 0 auto;
            margin-top: 40px;
        }

        .puzzle-piece {
           height: 100%;
           margin-bottom: 2px;
        }

        .badge-container-bottom {
            grid-template-columns: repeat(4, 1fr);
            grid-template-rows: repeat(2, 1fr);
            display: grid;
        }
        .badge-piece {
            width: 100%;
            height: 92px;
        }
        .badge-text {
            font-size: 9px;
            color: #303030;
            text-align: center;
            margin-top: 5px;
            font-weight: 700;
        }
        .badge-piece img {
            width: 58px;
            height: 58px;
        }
    </style>
    <div class="container-fluid start home dashboard">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="col-12 d-flex justify-content-center">
                    @include('components.branding')
                </div>
                <p class="mt-5 station-progress-heading">Station Progress</p>
                <div class="badge-container">
                    @for ($i = 1; $i <= 7; $i++) @if($i !=7) <div
                        class="badge {{ $i <= $stationDone ? 'completed' : '' }}">
                        <span>?</span>
                        <img src="{{ asset('images/badge1.png') }}" alt="">
                </div>
                @else
                <div class="badge with-img completed {{ $i <= $stationDone ? 'completed' : '' }}">
                    <span>
                        <img src="{{ asset('images/gift.png') }}" alt="">
                    </span>
                </div>
                @endif
                @endfor
            </div>
            <div class="mt-3 text-center col-12 text-content">
                <div class="puzzle-container">
                    @for ($i = 1; $i <= 6; $i++)
                        <div class="puzzle-piece">
                            <img src="{{ asset('images/puzzle/' .$i.'.png') }}" alt="">
                        </div>
                    @endfor
                </div>
                <p class="mt-3 station-progress-heading">Little Nurse</p>
                <div class="badge-container-bottom">
                    @for ($i = 1; $i <= 8; $i++)
                    <div class="badge-piece">
                        <img src="{{ asset('images/badge1.png') }}">
                        <p class="badge-text">Little Nurse</p>
                    </div>
                @endfor
                </div>
            </div>
        </div>
    </div>
    </div>
</x-app-layout>
