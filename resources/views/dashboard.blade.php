<x-app-layout>
<div class="container-fluid start home">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="col-12 d-flex justify-content-center mt-5">
                @include('components.branding')
            </div>
            <div class="col-12 mt-3 text-content text-center">

                <img class="w-100 mt-5"  src="{{ asset('images/banner.png') }}" alt="">
                <div class="map">
                    <img class="path-image" src="{{ asset('images/map.png') }}" alt="Station Image">
                    @foreach ($stations as $station)


                    <div class="step step__{{ $station->id }}">
                        <div class="content">
                                    @if($stationDone < 5 && $station->id ==6)
                                        <img  class="boot-img" src="{{ asset('images/step/step-img-'.$station->id.'.png') }}" alt="">
                                    @else
                                        <img onclick="gotoStation({{$station->id}})" class="boot-img" src="{{ asset('images/step/step-img-'.$station->id.'.png') }}" alt="">
                                    @endif
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
    function gotoStation(id){
        console.log(id);
    }
</script>
</x-app-layout>
