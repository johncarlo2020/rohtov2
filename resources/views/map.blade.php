<x-app-layout>

    <div id="map-page" class="main-backgroundd-flex flex-column p-0 content-box"
        data-yes-image="{{ asset('files/main/yes.png') }}"
        data-no-image="{{ asset('files/main/no.png') }}"
        data-pledge-image="{{ asset('files/main/pledge.webp') }}"
        data-default-pledge-image="{{ asset('files/main/ocean_or_platic.webp') }}">

         <div>
            <a href="{{ route('map') }}">
                @include('components.branding')
            </a>
        </div>

        <h2 class="map-text">Begin your journey</h2>
        <div class="welcome-content">
            <p class="mt-0 discover-text">DISCOVER</p>
            <h1 class="more-text">MORE</h1>
        </div>

        <div class="mb-5 map">
            <img class="map-img" src="{{ asset('files/main/map.webp') }}" alt="" />
            {{-- loop trough the $stations --}}
            {{-- <a class="map-pin start-pin"><span class="start-text">Start</span></a> --}}
            @foreach ($stations as $station)
                @if ($canStation6 == false && $station->id == 6)
                    <a href="javascript:void(0);"
                        class="map-pin station-{{ $station->id }} @if ($station->status == true) completed @endif @if ($nextStation && $station->id === $nextStation->id) breathing @endif"
                        data-bs-toggle="modal" data-bs-target="#redemption">
                        @if ($station->status != true)
                            {{ $station->id }}
                        @else
                            <i class="fa-solid fa-check"></i>
                        @endif
                    </a>
                @else
                    <a href="{{ route('station', $station) }}"
                        class="map-pin station-{{ $station->id }} @if ($station->status == true) completed @endif @if ($nextStation && $station->id === $nextStation->id) breathing @endif">
                        @if ($station->status != true)
                            {{ $station->id }}
                        @else
                            <i class="fa-solid fa-check"></i>
                        @endif
                    </a>
                @endif
            @endforeach
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/gh/davidshimjs/qrcodejs/qrcode.min.js"></script>
    @vite('resources/js/map.js')
</x-app-layout>
