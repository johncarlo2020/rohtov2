<x-guest-layout>
    <div class="mm-mobile-container" style="overflow-y: auto;">
        
        {{-- VIEW 1: MEMORY MAP PAGE --}}
        <div id="mapViewSection" class="w-100 d-flex flex-column justify-content-between">
            <div>
                {{-- Header Logo --}}
                @include('components.branding')

                {{-- Title --}}
                <div class="mm-page-title text-center">
                    YOUR MEMORY MAP
                </div>

                {{-- Memory Map Visual Floorplan with Leader Lines --}}
                <div class="mm-map-container my-1" style="position: relative; width: 100%; max-width: 440px; margin: 0 auto; user-select: none;">
                    <div class="mm-floorplan-stage" style="position: relative; width: 100%; overflow: visible; background-color: #F4F0EA;">
                        
                        {{-- Centered 3D Floorplan Image --}}
                        <img src="{{ asset('images/brand/dashboard_bg.webp') }}" 
                             alt="House of Memories Map" 
                             fetchpriority="high"
                             decoding="async"
                             style="width: 54%; height: auto; display: block; margin: 0 auto; user-select: none; pointer-events: none;">

                        {{-- Stations Overlay --}}
                        @php
                            $stationConfigs = [
                                'photobooth' => [
                                    'relX' => 35.3, 'relY' => 12.5,
                                    'side' => 'left',
                                    'label' => "PHOTOBOOTH",
                                ],
                                'redemption-counter' => [
                                    'relX' => 80.1, 'relY' => 12.5,
                                    'side' => 'right',
                                    'label' => "COMPLIMENTARY<br>GIFT",
                                ],
                                'complimentary-gift' => [
                                    'relX' => 80.1, 'relY' => 12.5,
                                    'side' => 'right',
                                    'label' => "COMPLIMENTARY<br>GIFT",
                                ],
                                'lazy-sunday-morning' => [
                                    'relX' => 35.3, 'relY' => 25.8,
                                    'side' => 'left',
                                    'label' => "LAZY<br>SUNDAY<br>MORNING",
                                ],
                                'scentsorium' => [
                                    'relX' => 80.1, 'relY' => 36.4,
                                    'side' => 'right',
                                    'label' => "SCENTSORIUM",
                                ],
                                'jazz-club' => [
                                    'relX' => 83.8, 'relY' => 52.5,
                                    'side' => 'right',
                                    'label' => "JAZZ CLUB",
                                ],
                                'chasing-sunset' => [
                                    'relX' => 30.1, 'relY' => 65,
                                    'side' => 'left',
                                    'label' => "CHASING<br>SUNSETS",
                                ],
                                'by-the-fireplace' => [
                                    'relX' => 64.7, 'relY' => 68,
                                    'side' => 'right',
                                    'label' => "BY THE<br>FIREPLACE",
                                ],
                                'up-at-dawn' => [
                                    'relX' => 64.0, 'relY' => 82,
                                    'side' => 'right',
                                    'label' => "UP AT DAWN",
                                ],
                                'replica-cafe' => [
                                    'relX' => 21.3, 'relY' => 87,
                                    'side' => 'left',
                                    'label' => "REPLICA<br>CAFE",
                                ],
                            ];
                        @endphp

                        @foreach($stations as $stn)
                            @php
                                $isCompleted = in_array($stn->id, $completedStationIds);
                                $isRedemption = !empty($stn->is_redemption) || $stn->slug === 'complimentary-gift' || $stn->slug === 'redemption-counter';
                                $isLocked = $isRedemption && empty($isLastStationUnlocked) && !$isCompleted;
                                
                                $cfg = null;
                                if ($stn->is_redemption) {
                                    $cfg = $stationConfigs['redemption-counter'];
                                } elseif (isset($stationConfigs[$stn->slug])) {
                                    $cfg = $stationConfigs[$stn->slug];
                                } else {
                                    $normalizedSlug = str_replace(['sunsets', 'sunset'], 'sunset', $stn->slug);
                                    $cfg = $stationConfigs[$normalizedSlug] ?? [
                                        'relX' => 50, 'relY' => 50,
                                        'side' => 'right',
                                        'label' => strtoupper($stn->name),
                                    ];
                                }

                                // Container geometry: building width = 54%, centered (offset = 23%)
                                $pinX = 23 + ($cfg['relX'] * 0.54);
                                $pinY = $cfg['relY'];
                                $isRight = ($cfg['side'] === 'right');
                            @endphp

                            <div class="mm-station-node {{ $isLocked ? 'locked' : '' }}"
                                 @if(!$isLocked) onclick="handleStationClick({{ $stn->id }}, '{{ addslashes($stn->name) }}', {{ $stn->is_redemption ? 'true' : 'false' }})" @endif
                                 title="{{ $stn->name }}">
                                
                                {{-- 1. Pin Circle on the room --}}
                                <div class="mm-node-pin {{ $isCompleted ? 'completed' : ($isLocked ? 'locked' : 'uncompleted') }}"
                                     style="left: {{ number_format($pinX, 2) }}%; top: {{ number_format($pinY, 2) }}%;">
                                    @if($isLocked)
                                        <svg xmlns="http://www.w3.org/2000/svg" width="8" height="8" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                        </svg>
                                    @elseif($isCompleted)
                                        <span class="mm-check">✓</span>
                                    @endif
                                </div>

                                {{-- 2. Leader Line with Arrowhead --}}
                                @if($isRight)
                                    {{-- Right line: starts at pinX, ends at 78%, arrow pointing right --}}
                                    <div class="mm-node-line right"
                                         style="left: {{ number_format($pinX, 2) }}%; width: calc(78% - {{ number_format($pinX, 2) }}%); top: {{ number_format($pinY, 2) }}%;">
                                        <span class="mm-arrow-head right"></span>
                                    </div>

                                    {{-- 3. Label on Right --}}
                                    <div class="mm-node-label right"
                                         style="left: 79%; top: {{ number_format($pinY, 2) }}%;">
                                        {!! $cfg['label'] !!}
                                    </div>
                                @else
                                    {{-- Left line: starts at 22%, ends at pinX, arrow pointing left --}}
                                    <div class="mm-node-line left"
                                         style="left: 22%; width: calc({{ number_format($pinX, 2) }}% - 22%); top: {{ number_format($pinY, 2) }}%;">
                                        <span class="mm-arrow-head left"></span>
                                    </div>

                                    {{-- 3. Label on Left --}}
                                    <div class="mm-node-label left"
                                         style="right: calc(100% - 21%); top: {{ number_format($pinY, 2) }}%;">
                                        {!! $cfg['label'] !!}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Subtext below map --}}
                <p class="text-center mm-map-subtext fw-bold" style="font-size: 0.8rem; color: #555555; margin: 1rem auto 1rem auto; font-family: 'Courier New', Courier, monospace; line-height: 1.4;">
                    Complete all stations to redeem your complimentary fragrance samples.
                </p>
            </div>

            <style>
                .mm-station-node {
                    cursor: pointer;
                    user-select: none;
                }
                .mm-node-pin {
                    position: absolute;
                    transform: translate(-50%, -50%);
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    z-index: 10;
                    transition: transform 0.2s ease, box-shadow 0.2s ease;
                }
                .mm-node-pin.uncompleted {
                    width: 12px;
                    height: 12px;
                    background-color: #111111;
                    border: none;
                }
                .mm-node-pin.completed {
                    width: 14px;
                    height: 14px;
                    background-color: #FFFFFF;
                    border: 1.5px solid #111111;
                    color: #111111;
                }
                .mm-node-pin.locked {
                    width: 14px;
                    height: 14px;
                    background-color: #888888;
                    border: none;
                    cursor: not-allowed;
                }
                .mm-node-pin .mm-check {
                    font-size: 9px;
                    font-weight: 800;
                }
                .mm-node-line {
                    position: absolute;
                    height: 1px;
                    background-color: #222222;
                    transform: translateY(-50%);
                    pointer-events: none;
                    z-index: 5;
                }
                .mm-arrow-head {
                    position: absolute;
                    top: 50%;
                    transform: translateY(-50%);
                    width: 0;
                    height: 0;
                }
                .mm-arrow-head.right {
                    right: 0;
                    border-top: 3px solid transparent;
                    border-bottom: 3px solid transparent;
                    border-left: 4.5px solid #222222;
                }
                .mm-arrow-head.left {
                    left: 0;
                    border-top: 3px solid transparent;
                    border-bottom: 3px solid transparent;
                    border-right: 4.5px solid #222222;
                }
                .mm-node-label {
                    position: absolute;
                    transform: translateY(-50%);
                    font-family: 'Courier New', Courier, monospace;
                    font-size: clamp(8px, 2.5vw, 11.5px);
                    font-weight: 400;
                    line-height: 1.15;
                    letter-spacing: 0.5px;
                    color: #222222;
                    white-space: nowrap;
                    z-index: 8;
                    transition: color 0.15s ease;
                }
                .mm-node-label.left {
                    text-align: right;
                }
                .mm-node-label.right {
                    text-align: left;
                }
                .mm-station-node:hover .mm-node-pin {
                    transform: translate(-50%, -50%) scale(1.25);
                    box-shadow: 0 0 6px rgba(0, 0, 0, 0.4);
                }
                .mm-station-node:hover .mm-node-label {
                    color: #000000;
                }
                .mm-station-node.locked {
                    cursor: not-allowed;
                }
                .mm-station-node.locked .mm-node-line {
                    background-color: #AAAAAA;
                }
                .mm-station-node.locked .mm-arrow-head.right {
                    border-left-color: #AAAAAA;
                }
                .mm-station-node.locked .mm-arrow-head.left {
                    border-right-color: #AAAAAA;
                }
                .mm-station-node.locked .mm-node-label {
                    color: #888888 !important;
                    cursor: not-allowed;
                }
                .mm-station-node.locked:hover .mm-node-pin {
                    transform: translate(-50%, -50%) !important;
                    box-shadow: none !important;
                }
                .mm-station-node.locked:hover .mm-node-label {
                    color: #888888 !important;
                }
            </style>

            {{-- Action Button: MY MEMORY PASSPORT (font-size: 1rem, no arrow) --}}
            <div class="mt-2 mb-2 w-100">
                <button type="button" onclick="showPassportView()" class="mm-btn-black">
                    MY MEMORY PASSPORT
                </button>
            </div>
        </div>

        {{-- VIEW 2: MEMORY PASSPORT LIST PAGE ("YOUR HOUSE OF MEMORIES") --}}
        <div id="passportViewSection" class="w-100 d-flex flex-column justify-content-between d-none">
            <div>
                {{-- Top Bar with Back Arrow --}}
                <div class="d-flex align-items-center justify-content-between pb-2">
                    <button type="button" onclick="showMapView()" class="btn p-0 border-0" style="font-size: 1.6rem; font-weight: 400; color: #111; text-decoration: none; line-height: 1;">
                        ‹
                    </button>
                    <div class="flex-grow-1 text-center pe-4">
                        @include('components.branding')
                    </div>
                </div>

                {{-- Title --}}
                <div class="mm-page-title mt-2 mb-3 text-center" style="font-size: 1.4rem; letter-spacing: 3px; line-height: 1.3;">
                    YOUR HOUSE<br>OF MEMORIES
                </div>

                {{-- Station Flat List --}}
                <div style="margin-top: 1.25rem;">
                    @foreach($stations as $stn)
                        @php
                            $isCompleted = in_array($stn->id, $completedStationIds);
                            $isRedemption = !empty($stn->is_redemption) || $stn->slug === 'complimentary-gift' || $stn->slug === 'redemption-counter';
                            $isLocked = $isRedemption && empty($isLastStationUnlocked) && !$isCompleted;
                        @endphp

                        {{-- Row: [image] [name] [circle] --}}
                        <div @if(!$isLocked) onclick="handleStationClick({{ $stn->id }}, '{{ addslashes($stn->name) }}', {{ $stn->is_redemption ? 'true' : 'false' }})" @endif
                             style="display: flex;
                                    align-items: center;
                                    gap: 14px;
                                    padding: 10px 0;
                                    cursor: {{ $isLocked ? 'not-allowed' : 'pointer' }};
                                    background-color: #F4F0EA;
                                    {{ $isLocked ? 'opacity: 0.5;' : '' }}">

                            {{-- Station Photo Thumbnail --}}
                            <div style="width: 88px; height: 65px; flex-shrink: 0; border-radius: 4px; overflow: hidden; background-color: #DDD; {{ $isLocked ? 'filter: grayscale(100%); opacity: 0.7;' : '' }}">
                                <img src="{{ asset('images/station/STD' . $stn->id . '.webp') }}"
                                     loading="lazy"
                                     decoding="async"
                                     onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1547887537-6158d64c35b3?auto=format&fit=crop&w=200&q=60';"
                                     alt="{{ $stn->name }}"
                                     style="width: 100%; height: 100%; object-fit: cover; display: block;">
                            </div>

                            {{-- Station Name --}}
                            <div style="flex: 1;
                                        font-size: 0.8rem;
                                        font-weight: 700;
                                        text-transform: uppercase;
                                        letter-spacing: 1.5px;
                                        color: {{ $isLocked ? '#888888' : '#111111' }};
                                        line-height: 1.4;">
                                {{ $stn->name }}
                            </div>

                            {{-- Status Circle --}}
                            <div style="width: 31px;
                                        height: 31px;
                                        min-width: 31px;
                                        border-radius: 50%;
                                        flex-shrink: 0;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        background-color: {{ $isLocked ? '#EAE6DF' : 'transparent' }};
                                        border: 1.5px solid {{ $isCompleted ? '#111111' : ($isLocked ? '#888888' : '#AAAAAA') }};">
                                @if($isLocked)
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#666666" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                    </svg>
                                @elseif($isCompleted)
                                    <span style="color: #111111; font-size: 13px; font-weight: 700; line-height: 1;">✓</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Back to map button --}}
            <div class="mt-4 w-100">
                <button type="button" onclick="showMapView()" class="mm-btn-black" style="width: 100%; border-radius: 8px;">
                    BACK TO MAP
                </button>
            </div>
        </div>

    </div>

    @push('scripts')
    <script>
        const isLastStationUnlocked = {{ !empty($isLastStationUnlocked) ? 'true' : 'false' }};
        const isAllCompleted = @json($isAllCompleted);

        if (isAllCompleted) {
            window.location.href = "{{ route('thankyou') }}";
        }

        function showPassportView() {
            document.getElementById('mapViewSection').classList.add('d-none');
            document.getElementById('passportViewSection').classList.remove('d-none');
            window.scrollTo(0, 0);
        }

        function showMapView() {
            document.getElementById('passportViewSection').classList.add('d-none');
            document.getElementById('mapViewSection').classList.remove('d-none');
            window.scrollTo(0, 0);
        }

        function handleStationClick(stationId, stationName, isRedemption) {
            if (isRedemption && !isLastStationUnlocked) {
                return; // Locked and not clickable
            }

            // Redirect each station to its own scanning area using station.blade.php template
            window.location.href = "{{ url('/station') }}/" + stationId;
        }
    </script>
    @endpush
</x-guest-layout>
