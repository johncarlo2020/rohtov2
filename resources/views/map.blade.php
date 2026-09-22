<x-app-layout>
    @php
        $journey = [1 => ['Shop for More', 83, 29], 2 => ['More to Enjoy', 47, 9], 3 => ['More to Unwind', 64, 12], 4 => ['More to Stream', 35, 17], 5 => ['Maybank Cafe', 15, 6]];
        $rewards = [6 => ['Flight Simulator', 57, -2], 7 => ['Giant Gashapon Surprise', 95, 4], 8 => ['Exclusive Merchandise', 87, -6]];
        $lockedStationIds = $user->isCardApply ? [] : $stations->where('is_mandatory', false)->pluck('id')->all();
        $completedStationIds = $stations->where('status', true)->pluck('id')->all();
        $booths = [[23, 33], [46, 52], [79, 67], [23, 87], [83, 0]];
        $iscardApplied = $user->isCardApply;
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
                            @continue(in_array($id, $lockedStationIds))
                            @php($isCompleted = in_array($id, $completedStationIds))
                            <a class="journey-pin {{ $view === 'rewards' ? 'reward-pin' : '' }}"
                                @if (!in_array($id, $lockedStationIds)) href="{{ route('station', $id) }}" @else aria-disabled="true" tabindex="-1" @endif data-location="{{ $id }}"
                                style="--pin-left: {{ $left }}%; --pin-top: {{ $top }}%"
                                aria-label="{{ $label }} — {{ $isCompleted ? 'completed' : 'open station' }}">
                                <svg class="journey-pin-shape" viewBox="0 0 30 48" aria-hidden="true" focusable="false">
                                    <path d="M15 1C7.27 1 1 7.27 1 15c0 6 3.8 11.3 9.5 13.3L15 46l4.5-17.7C25.2 26.3 29 21 29 15 29 7.27 22.73 1 15 1Z" />
                                </svg>
                                <span aria-hidden="true">@if ($isCompleted)<i class="fa-solid fa-check"></i>@else{{ $view === 'rewards' ? $loop->iteration : $id }}@endif</span>
                            </a>
                        @endforeach
                        @if ($view === 'rewards' && !$iscardApplied)
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
                            @php($isCompleted = in_array($id, $completedStationIds))
                            <button type="button" class="legend-location" data-location="{{ $id }}" data-station-url="{{ route('station', $id) }}" @disabled(in_array($id, $lockedStationIds))>
                                <span class="legend-number">@if ($isCompleted)<i class="fa-solid fa-check"></i>@else{{ $id }}@endif</span><span>{{ $label }}</span>@if (in_array($id, $lockedStationIds))<i class="fa-solid fa-lock station-lock" aria-label="Locked"></i>@endif
                            </button>
                        @endforeach
                        <button type="button" class="legend-banner legend-banner-card" data-show-rewards aria-controls="rewards-panel">More rewards when you apply!</button>
                        <button type="button" class="legend-location legend-booth" data-location="booth" aria-pressed="false">
                            <img src="{{ asset('files/main/maybank_card.webp') }}" alt="" /><span>Card Sales Booth</span>
                        </button>
                    </div>
                </section>
                <section id="rewards-panel" class="map-legend rewards-legend" data-map-panel="rewards" aria-labelledby="rewards-title" hidden>
                    <img class="rewards-card" src="{{ asset('files/main/maybank_card.webp') }}" alt="Maybank Cash Back Mastercard Platinum Credit Card" />
                    <p class="rewards-card-caption">Maybank Cash Back Mastercard<br>Platinum Credit Card</p>
                    @if (!$user->isCardApply)
                        <button type="button" class="card-apply-button" id="card-apply-open">Click here to apply!</button>
                    @endif
                    <div class="legend-scroll" tabindex="0" aria-label="Reward locations">
                        <h2 id="rewards-title">MORE REWARDS</h2>
                        @foreach ($rewards as $id => [$label])
                            @php($isCompleted = in_array($id, $completedStationIds))
                            <button type="button" class="legend-location" data-location="{{ $id }}" data-station-url="{{ route('station', $id) }}" @disabled(in_array($id, $lockedStationIds))>
                                <span class="legend-number">@if ($isCompleted)<i class="fa-solid fa-check"></i>@else{{ $id }}@endif</span><span>{{ $label }}</span>@if (in_array($id, $lockedStationIds))<i class="fa-solid fa-lock station-lock" aria-label="Locked"></i>@endif
                            </button>
                        @endforeach
                    </div>
                </section>
                <button type="button" class="map-view-toggle" aria-label="Show more rewards"><span aria-hidden="true"></span></button>
            </div>
        </div>
    </main>
    <dialog id="card-apply-dialog" class="card-apply-dialog" aria-labelledby="card-apply-message" data-scan-url="{{ route('card-application.scan') }}">
        <div id="card-apply-result" class="card-apply-result" aria-hidden="true" hidden></div>
        <p id="card-apply-message" role="status">Head over to Card Sales Booth to scan QR and continue your journey</p>
        <div id="card-apply-reader"></div>
        <button type="button" class="card-apply-button" id="card-apply-close">CLOSE</button>
    </dialog>
    @vite('resources/js/map.js')
</x-app-layout>
