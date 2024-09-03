<x-app-layout>
<div class="container-fluid start home dashboard">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="col-12 d-flex justify-content-center">
                @include('components.branding')
            </div>
            <div class="heading-main">
                <h1>
                    Start your <span>Journey</span> now
                </h1>
            </div>
            <div class="icon-girl">
                <img onclick="puzzle()" class=""  src="{{ asset('images/girlIcon.png') }}" alt="">
            </div>
            <p class="station-progress-heading">Station Progress</p>
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
                <div class="map">
                    <img class="path-image" src="{{ asset('images/map.png') }}" alt="Station Image">
                    @foreach ($stations as $station)
                    <div class="step step__{{ $station->id }}">
                        <div class="content">
                                   
                                        <img onclick="gotoStation({{$station->id}})" class="boot-img" src="{{ asset('images/step/step-img-'.$station->id.'.png') }}" alt="">
                                 
                            <div class="details-container">
                                <div class="details">
                                    <span class="step-number {{ $station->status ? 'completed' : '' }}">
                                        @if($station->status == false )
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
    var url = "{{ route('station', ['station' => ':id']) }}".replace(':id', id);
    // Redirect to the generated URL
    window.location.href = url;
}
function puzzle(){
    var url = "{{ route('station.puzzle')}}";
    // Redirect to the generated URL
    window.location.href = url;
}
</script>
</x-app-layout>
