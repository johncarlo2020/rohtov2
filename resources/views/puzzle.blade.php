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
        .old{
            -webkit-filter: grayscale(100%); /* Safari 6.0 - 9.0 */
            filter: grayscale(100%); 
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
                @foreach ($required as $item)
                        <div class="badge {{ $item->is_gotten == 1 ? 'completed' : '' }}">
                            <span>?</span>
                            <img src="{{ asset('images/badge'.$item->station_id.'.png') }}" alt="">
                        </div>
                @endforeach
                @foreach ($notRequired as $item)
                        <div class="badge {{ $item->is_gotten == 1 ? 'completed' : '' }}">
                            <span>?</span>
                            <img src="{{ asset('images/badge'.$item->station_id.'.png') }}" alt="">
                        </div>
                @endforeach
                
                        <div class="badge with-img completed ">
                            <span>
                                <img src="{{ asset('images/gift.png') }}" alt="">
                            </span>
                        </div>
            </div>
            <div class="mt-3 text-center col-12 text-content">
                <div class="puzzle-container">
                @foreach ($puzzleRequired as $item)
                        <div class="puzzle-piece">
                            <img class="{{ $item->is_gotten == 1 ? '' : 'd-none' }}" src="{{ asset('images/puzzle/' .$item->station_id.'.png') }}" alt="">
                        </div>
                @endforeach
                @foreach ($puzzleNotRequired as $item)
                    <div class="puzzle-piece">
                        <img class="{{ $item->is_gotten == 1 ? '' : 'd-none' }}" src="{{ asset('images/puzzle/' . $loop->iteration+4 . '.png') }}" alt="">
                    </div>
                @endforeach
                </div>
                <p class="mt-3 station-progress-heading">Little Nurse</p>
                <div class="badge-container-bottom">
                    @foreach ($nurse as $item)
                        <div class="badge-piece">
                            <img class="{{ $item->is_gotten == 1 ? '' : 'old' }}" src="{{ asset('images/badge'.$item->station_id.'.png') }}">
                            <p class="badge-text ">{{$item->station_nurse}} Nurse</p>
                        </div> 
                    @endforeach
                   
                </div>
            </div>
        </div>
    </div>
    </div>
</x-app-layout>
