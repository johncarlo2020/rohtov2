<x-guest-layout>
    <div class="mm-mobile-container justify-content-between p-0" style="max-width: 440px; margin: 0 auto; background-color: #F4F0EA; min-height: 100vh; overflow-y: auto;">
        
        {{-- VIEW 1: MEMORY MAP PAGE --}}
        <div id="mapViewSection" class="w-100 px-3 pt-3 pb-5">
            {{-- Header Logo --}}
            @include('components.branding')

            {{-- Title --}}
            <div class="mm-page-title mt-2 mb-4 text-center">
                YOUR MEMORY MAP
            </div>

            {{-- Memory Map Visual Floorplan with Background Image --}}
            <div class="mm-map-container my-3" style="position: relative; width: 100%; max-width: 360px; margin: 0 auto;">
                <div style="position: relative; width: 100%; overflow: hidden; background-color: #F4F0EA;">
                    {{-- Floorplan Image Background --}}
                    <img src="{{ asset('images/brand/dashboard_bg.webp') }}" 
                         alt="House of Memories Map" 
                         fetchpriority="high"
                         decoding="async"
                         style="width: 100%; height: auto; display: block;">

                    {{-- Station Pin Overlays — Plain text labels + solid black circles (final design) --}}
                    @foreach($stations as $stn)
                        @php
                            $isCompleted = in_array($stn->id, $completedStationIds);

                            /**
                             * isVertical = true  → vertical text (writing-mode top→bottom), label above circle
                             * isVertical = false → horizontal text, circle left of label
                             * left/top = position of the combined element center on the floorplan image
                             */
                            $coords = match($stn->slug) {

                                'redemption-counter' => [
                                    'left' => '68%', 'top' => '12%',
                                    'isVertical' => true,
                                    'displayName' => "REDEMPTION\nCOUNTER",
                                ],
                                'lazy-sunday-morning', 'last-sunday-morning' => [
                                    'left' => '27%', 'top' => '26%',
                                    'isVertical' => true,
                                    'displayName' => "LAZY SUNDAY\nMORNING",
                                ],
                                'scentsorium' => [
                                    'left' => '70%', 'top' => '39%',
                                    'isVertical' => true,
                                    'displayName' => "SCENTSORIUM",
                                ],
                                'jazz-club' => [
                                    'left' => '55%', 'top' => '51%',
                                    'isVertical' => false,
                                    'displayName' => 'JAZZ CLUB',
                                ],
                                'chasing-sunset', 'chasing-sunsets' => [
                                    'left' => '20%', 'top' => '59%',
                                    'isVertical' => true,
                                    'displayName' => "CHASING SUNSET",
                                ],
                                'by-the-fireplace' => [
                                    'left' => '55%', 'top' => '66%',
                                    'isVertical' => false,
                                    'displayName' => "BY THE\nFIREPLACE",
                                ],
                                'up-at-dawn', '37-at-dawn' => [
                                    'left' => '50%', 'top' => '79%',
                                    'isVertical' => false,
                                    'displayName' => 'UP AT DAWN',
                                ],
                                'replica-cafe' => [
                                    'left' => '22%', 'top' => '87%',
                                    'isVertical' => true,
                                    'displayName' => "REPLICA CAFE",
                                ],
                                default => [
                                    'left' => '50%', 'top' => '50%',
                                    'isVertical' => false,
                                    'displayName' => strtoupper($stn->name),
                                ],
                            };

                            $isVert   = $coords['isVertical'];
                            $circleBg = $isCompleted ? '#666666' : '#000000';
                        @endphp

                        {{-- PIN WRAPPER --}}
                        <div onclick="handleStationClick({{ $stn->id }}, '{{ addslashes($stn->name) }}', {{ $stn->is_redemption ? 'true' : 'false' }})"
                             style="position: absolute;
                                    left: {{ $coords['left'] }};
                                    top: {{ $coords['top'] }};
                                    transform: translate(-50%, -50%);
                                    cursor: pointer;
                                    z-index: 10;
                                    display: flex;
                                    flex-direction: {{ $isVert ? 'column' : 'row' }};
                                    align-items: center;
                                    gap: {{ $isVert ? '2px' : '4px' }};">

                            @if($isVert)
                                {{-- VERTICAL LABEL: top-to-bottom flow, no border --}}
                                <div style="font-family: 'Courier Prime', monospace;
                                            font-size: 1rem;
                                            font-weight: 700;
                                            text-transform: uppercase;
                                            color: #111111;
                                            letter-spacing: 0.5px;
                                            line-height: 1.1;
                                            white-space: pre-line;
                                            writing-mode: vertical-lr;
                                            text-orientation: mixed;">{{ $coords['displayName'] }}</div>
                            @else
                                {{-- HORIZONTAL LABEL: standard text, no border --}}
                                <div style="font-family: 'Courier Prime', monospace;
                                            font-size: 1rem;
                                            font-weight: 700;
                                            text-transform: uppercase;
                                            color: #111111;
                                            letter-spacing: 0.5px;
                                            line-height: 1.25;
                                            white-space: pre-line;
                                            order: 2;">{{ $coords['displayName'] }}</div>
                            @endif

                            {{-- SOLID BLACK CIRCLE PIN --}}
                            <div style="width: 16px;
                                        height: 16px;
                                        min-width: 16px;
                                        border-radius: 50%;
                                        background-color: {{ $circleBg }};
                                        position: relative;
                                        flex-shrink: 0;
                                        {{ !$isVert ? 'order: 1;' : '' }}">
                                @if($isCompleted)
                                    <span style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: #fff; font-size: 8px; line-height: 1; font-weight: 700;">✓</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Sticky Footer Button --}}
            <div class="mt-4 px-1">
                <button type="button" onclick="showPassportView()" class="mm-btn-black py-3" style="width: 100%; border-radius: 8px;">
                    MY MEMORY PASSPORT
                </button>
            </div>
        </div>

        {{-- VIEW 2: MEMORY PASSPORT LIST PAGE ("YOUR HOUSE OF MEMORIES") --}}
        <div id="passportViewSection" class="w-100 pb-5 d-none">
            {{-- Top Bar with Back Arrow --}}
            <div class="d-flex align-items-center justify-content-between pt-3 pb-2 px-3">
                <button type="button" onclick="showMapView()" class="btn p-0 border-0" style="font-size: 1.6rem; font-weight: 400; color: #111; text-decoration: none; line-height: 1;">
                    ‹
                </button>
                <div class="flex-grow-1 text-center pe-5">
                    @include('components.branding')
                </div>
            </div>

            {{-- Title --}}
            <div class="mm-page-title mt-2 mb-3 text-center px-3" style="font-size: 1.4rem; letter-spacing: 3px; line-height: 1.3;">
                YOUR HOUSE<br>OF MEMORIES
            </div>

            {{-- Station Flat List --}}
            <div style="margin-top: 1.25rem;">
                @foreach($stations as $stn)
                    @php $isCompleted = in_array($stn->id, $completedStationIds); @endphp

                    {{-- Row: [image] [name] [circle] --}}
                    <div onclick="handleStationClick({{ $stn->id }}, '{{ addslashes($stn->name) }}', {{ $stn->is_redemption ? 'true' : 'false' }})"
                         style="display: flex;
                                align-items: center;
                                gap: 14px;
                                padding: 10px 16px;
                                cursor: pointer;
                                border-bottom: 1px solid #E4E0D8;
                                background-color: #F4F0EA;">

                        {{-- Station Photo Thumbnail --}}
                        <div style="width: 72px; height: 54px; flex-shrink: 0; border-radius: 3px; overflow: hidden; background-color: #DDD;">
                            <img src="{{ asset('images/station/ST' . $stn->id . '.webp') }}"
                                 loading="lazy"
                                 decoding="async"
                                 onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1547887537-6158d64c35b3?auto=format&fit=crop&w=200&q=60';"
                                 alt="{{ $stn->name }}"
                                 style="width: 100%; height: 100%; object-fit: cover; display: block;">
                        </div>

                        {{-- Station Name --}}
                        <div style="flex: 1;
                                    font-family: 'Courier Prime', monospace;
                                    font-size: 0.65rem;
                                    font-weight: 700;
                                    text-transform: uppercase;
                                    letter-spacing: 1.5px;
                                    color: #111111;
                                    line-height: 1.4;">
                            {{ $stn->name }}
                        </div>

                        {{-- Status Circle --}}
                        <div style="width: 26px;
                                    height: 26px;
                                    min-width: 26px;
                                    border-radius: 50%;
                                    flex-shrink: 0;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    {{ $isCompleted
                                        ? 'background-color: #111111; border: 1.5px solid #111111;'
                                        : 'background-color: transparent; border: 1.5px solid #AAAAAA;' }}">
                            @if($isCompleted)
                                <span style="color: #FFFFFF; font-size: 11px; font-weight: 700; line-height: 1;">✓</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Back to map button --}}
            <div class="mt-4 px-3">
                <button type="button" onclick="showMapView()" class="mm-btn-black py-3" style="border-radius: 8px;">
                    BACK TO MAP
                </button>
            </div>
        </div>

    </div>

    {{-- MODAL ALERT FOR GIFT REDEMPTION --}}
    <div class="modal fade" id="redemptionAlertModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered px-3" style="max-width: 380px;">
            <div class="modal-content text-center p-4" style="background-color: #FFFFFF; border-radius: 4px; border: 1px solid #111;">
                {{-- Exclamation Icon --}}
                <div class="mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; border-radius: 50%; border: 2px solid #111; font-family: 'Courier Prime', monospace; font-size: 1.5rem; font-weight: bold; color: #111;">
                    !
                </div>

                {{-- Modal Header --}}
                <div style="font-family: 'Courier Prime', monospace; font-size: 1.05rem; letter-spacing: 2px; font-weight: 700; text-transform: uppercase; color: #111; margin-bottom: 1rem;">
                    YOUR HOUSE OF MEMORIES
                </div>

                {{-- Modal Body Message --}}
                <p style="font-family: 'Courier Prime', monospace; font-size: 0.8rem; line-height: 1.5; color: #444444; margin-bottom: 1.75rem;">
                    Kindly complete 5 station in Memory Map to proceed to Gift Redemption station
                </p>

                {{-- Close Button --}}
                <button type="button" class="mm-btn-black py-2" data-bs-dismiss="modal">
                    CLOSE
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const completedCount = {{ $completedCount }};

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
            if (isRedemption) {
                if (completedCount < 5) {
                    const modal = new bootstrap.Modal(document.getElementById('redemptionAlertModal'));
                    modal.show();
                    return;
                } else {
                    window.location.href = "{{ route('thankyou') }}";
                    return;
                }
            }

            // Redirect each station to its own scanning area using station.blade.php template
            window.location.href = "{{ url('/station') }}/" + stationId;
        }
    </script>
    @endpush
</x-guest-layout>
