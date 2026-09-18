<x-app-layout>
    @php
        $journey = [1 => ['Shop for More', 83, 36], 2 => ['More to Enjoy', 47, 19], 3 => ['More to Unwind', 64, 21], 4 => ['More to Stream', 17, 17], 5 => ['Maybank Cafe', 9, 11]];
        $rewards = [6 => ['Flight Simulator', 56, 6], 7 => ['Giant Gashapon Surprise', 94, 11], 8 => ['Exclusive Merchandise', 85, 3]];
        $booths = [[22, 43], [45, 59], [79, 76], [23, 94], [83, 10]];
    @endphp
    <main id="map-page" class="content-box" data-view="journey">
        <div class="map-shell">
            <a class="map-brand" href="{{ route('map') }}" aria-label="Maybank map">
                <img src="{{ asset('files/main/logo.webp') }}" alt="Maybank" />
            </a>
            <header class="map-heading">
                <p>Begin your journey</p>
                <h1>DISCOVER <strong>MORE</strong></h1>
            </header>

            <div class="journey-map">
                <img class="journey-map-image" src="{{ asset('files/main/map.webp') }}" alt="Maybank event floor map" />
                @foreach (['journey' => $journey, 'rewards' => $rewards] as $view => $locations)
                    <div data-map-layer="{{ $view }}" @if ($view === 'rewards') hidden @endif>
                        @foreach ($locations as $id => [$label, $left, $top])
                            <a class="journey-pin {{ $view === 'rewards' ? 'reward-pin' : '' }}"
                                href="{{ route('station', $id) }}" data-location="{{ $id }}"
                                style="--pin-left: {{ $left }}%; --pin-top: {{ $top }}%"
                                aria-label="{{ $label }} — open station">
                                <span>{{ $view === 'rewards' ? $loop->iteration : $id }}</span>
                            </a>
                        @endforeach
                        @if ($view === 'journey')
                            @foreach ($booths as [$left, $top])
                                <button type="button" class="card-map-pin" data-location="booth"
                                    style="--pin-left: {{ $left }}%; --pin-top: {{ $top }}%" aria-label="Card Sales Booth" aria-pressed="false">
                                    <img src="{{ asset('files/main/maybank_card.webp') }}" alt="" />
                                </button>
                            @endforeach
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="map-legend-wrap">
                <section class="map-legend" data-map-panel="journey" aria-labelledby="journey-title">
                    <h2 id="journey-title">MAP LEGEND</h2>
                    <div class="legend-scroll" tabindex="0" aria-label="Journey locations">
                        <h3 class="legend-banner">Your Journey Begins Here!</h3>
                        @foreach ($journey as $id => [$label])
                            <button type="button" class="legend-location" data-location="{{ $id }}" aria-pressed="false">
                                <span class="legend-number">{{ $id }}</span><span>{{ $label }}</span>
                            </button>
                        @endforeach
                        <h3 class="legend-banner legend-banner-card">More Rewards When You Apply!</h3>
                        <button type="button" class="legend-location legend-booth" data-location="booth" aria-pressed="false">
                            <img src="{{ asset('files/main/maybank_card.webp') }}" alt="" /><span>Card Sales Booth</span>
                        </button>
                    </div>
                </section>
                <section class="map-legend rewards-legend" data-map-panel="rewards" aria-labelledby="rewards-title" hidden>
                    <img class="rewards-card" src="{{ asset('files/main/maybank_card.webp') }}" alt="Maybank Cash Back Mastercard Platinum Credit Card" />
                    <p class="rewards-card-caption">Maybank Cash Back Mastercard<br>Platinum Credit Card</p>
                    <div class="legend-scroll" tabindex="0" aria-label="Reward locations">
                        <h2 id="rewards-title">MORE REWARDS</h2>
                        @foreach ($rewards as $id => [$label])
                            <button type="button" class="legend-location" data-location="{{ $id }}" aria-pressed="false">
                                <span class="legend-number">{{ $loop->iteration }}</span><span>{{ $label }}</span>
                            </button>
                        @endforeach
                    </div>
                </section>
                <button type="button" class="map-view-toggle" aria-label="Show more rewards"><span aria-hidden="true"></span></button>
            </div>
        </div>
    </main>
    @vite('resources/js/map.js')
</x-app-layout>
