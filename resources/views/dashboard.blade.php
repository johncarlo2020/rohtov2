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

                {{-- Memory Map Visual Floorplan with Background Image (Enlarged by 20%) --}}
                <div class="mm-map-container my-2" style="position: relative; width: 100%; max-width: 456px; margin: 0 auto;">
                    <div style="position: relative; width: 100%; overflow: hidden; background-color: #F4F0EA;">
                        {{-- Floorplan Image Background --}}
                        <img src="{{ asset('images/brand/dashboard_bg.webp') }}" 
                             alt="House of Memories Map" 
                             fetchpriority="high"
                             decoding="async"
                             style="width: 100%; height: auto; display: block;">

                        {{-- Station Pin Overlays (Matching Design Image) --}}
                        @foreach($stations as $stn)
                            @php
                                $isCompleted = in_array($stn->id, $completedStationIds);

                                /**
                                 * Exact coordinates & orientation matching new design reference:
                                 * - isVertical: true for stacked upright text, false for horizontal
                                 * - circlePosition: 'top' | 'bottom'
                                 */
                                $coords = match($stn->slug) {

                                    'redemption-counter', 'redemption-room' => [
                                        'left' => '75%', 'top' => '17%',
                                        'isVertical' => true,
                                        'circlePosition' => 'bottom',
                                        'textAlign' => 'start',
                                        'displayName' => "REDEMPTION<br>ROOM",
                                    ],
                                    'lazy-sunday-morning', 'last-sunday-morning' => [
                                        'left' => '34%', 'top' => '27%',
                                        'isVertical' => true,
                                        'circlePosition' => 'bottom',
                                        'textAlign' => 'start',
                                        'displayName' => "LAZY SUNDAY<br>MORNING",
                                    ],
                                    'scentsorium' => [
                                        'left' => '74%', 'top' => '36%',
                                        'isVertical' => true,
                                        'circlePosition' => 'bottom',
                                        'displayName' => "SCENTSORIUM",
                                    ],
                                    'jazz-club' => [
                                        'left' => '75%', 'top' => '50%',
                                        'isVertical' => false,
                                        'circlePosition' => 'top',
                                        'displayName' => 'JAZZ CLUB',
                                    ],
                                    'chasing-sunset', 'chasing-sunsets' => [
                                        'left' => '33%', 'top' => '61%',
                                        'isVertical' => true,
                                        'circlePosition' => 'bottom',
                                        'displayName' => "CHASING SUNSET",
                                    ],
                                    'by-the-fireplace' => [
                                        'left' => '63%', 'top' => '65%',
                                        'isVertical' => false,
                                        'circlePosition' => 'top',
                                        'displayName' => "BY THE<br>FIREPLACE",
                                    ],
                                    'up-at-dawn', '37-at-dawn' => [
                                        'left' => '62%', 'top' => '79%',
                                        'isVertical' => false,
                                        'circlePosition' => 'top',
                                        'displayName' => 'UP AT DAWN',
                                    ],
                                    'replica-cafe' => [
                                        'left' => '27%', 'top' => '86%',
                                        'isVertical' => true,
                                        'circlePosition' => 'bottom',
                                        'displayName' => "REPLICA CAFE",
                                    ],
                                    default => [
                                        'left' => '50%', 'top' => '50%',
                                        'isVertical' => false,
                                        'circlePosition' => 'bottom',
                                        'displayName' => strtoupper($stn->name),
                                    ],
                                };
                            @endphp

                            {{-- PIN WRAPPER --}}
                            <div class="mm-station-pin"
                                 onclick="handleStationClick({{ $stn->id }}, '{{ addslashes($stn->name) }}', {{ $stn->is_redemption ? 'true' : 'false' }})"
                                 style="position: absolute;
                                        left: {{ $coords['left'] }};
                                        top: {{ $coords['top'] }};
                                        transform: translate(-50%, -50%);
                                        display: flex;
                                        flex-direction: column;
                                        align-items: center;
                                        gap: 3px;
                                        z-index: 10;">

                                @if($coords['circlePosition'] === 'top')
                                    <div class="pin-circle {{ $isCompleted ? 'completed' : 'uncompleted' }}">
                                        @if($isCompleted)
                                            <span>✓</span>
                                        @endif
                                    </div>
                                @endif

                                {{-- White Boxed Label (Not bold) --}}
                                <div class="pin-label {{ $coords['isVertical'] ? 'vertical' : 'horizontal' }}"
                                     style="{{ isset($coords['textAlign']) ? 'text-align: ' . $coords['textAlign'] . ' !important;' : '' }}">
                                    {!! $coords['displayName'] !!}
                                </div>

                                @if($coords['circlePosition'] === 'bottom')
                                    <div class="pin-circle {{ $isCompleted ? 'completed' : 'uncompleted' }}">
                                        @if($isCompleted)
                                            <span>✓</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <style>
                .mm-station-pin {
                    cursor: pointer;
                    user-select: none;
                    transition: transform 0.2s ease;
                }
                .mm-station-pin .pin-label {
                    background-color: #FFFFFF;
                    color: #000000;
                    font-size: 16px;
                    font-weight: 400 !important;
                    line-height: 1.2;
                    letter-spacing: 0.5px;
                    text-align: center;
                    padding: 3px 6px;
                    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
                    border: none;
                    white-space: nowrap;
                }
                .mm-station-pin .pin-label.vertical {
                    writing-mode: vertical-rl;
                    -webkit-writing-mode: vertical-rl;
                    transform: rotate(180deg);
                    padding: 4px 4px;
                }
                .mm-station-pin .pin-label.horizontal {
                    text-transform: uppercase;
                }
                .mm-station-pin .pin-circle {
                    width: 20px;
                    height: 20px;
                    min-width: 20px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
                    position: relative;
                }
                .mm-station-pin .pin-circle.uncompleted {
                    background-color: #000000;
                    border: none;
                }
                .mm-station-pin .pin-circle.completed {
                    background-color: #FFFFFF;
                    border: 1.5px solid #000000;
                    color: #000000;
                    font-size: 11px;
                    font-weight: 700;
                    line-height: 1;
                }
                /* Blur animation on hover */
                .mm-station-pin:hover .pin-circle {
                    animation: mmPinBlurGlow 1.2s infinite alternate ease-in-out;
                }
                @keyframes mmPinBlurGlow {
                    0% {
                        filter: blur(0.5px) drop-shadow(0 0 4px rgba(0, 0, 0, 0.5));
                        transform: scale(1.12);
                    }
                    100% {
                        filter: blur(2px) drop-shadow(0 0 10px rgba(0, 0, 0, 0.9));
                        transform: scale(1.24);
                    }
                }
            </style>

            {{-- Sticky Footer Button --}}
            <div class="mt-4 w-100">
                <button type="button" onclick="showPassportView()" class="mm-btn-black" style="width: 100%; border-radius: 8px;">
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
                        @php $isCompleted = in_array($stn->id, $completedStationIds); @endphp

                        {{-- Row: [image] [name] [circle] --}}
                        <div onclick="handleStationClick({{ $stn->id }}, '{{ addslashes($stn->name) }}', {{ $stn->is_redemption ? 'true' : 'false' }})"
                             style="display: flex;
                                    align-items: center;
                                    gap: 14px;
                                    padding: 10px 0;
                                    cursor: pointer;
                                    background-color: #F4F0EA;">

                            {{-- Station Photo Thumbnail --}}
                            <div style="width: 88px; height: 65px; flex-shrink: 0; border-radius: 4px; overflow: hidden; background-color: #DDD;">
                                <img src="{{ asset('images/station/ST' . $stn->id . '.webp') }}"
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
                                        color: #111111;
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
                                        background-color: transparent;
                                        {{ $isCompleted
                                            ? 'border: 1.5px solid #111111;'
                                            : 'border: 1.5px solid #AAAAAA;' }}">
                                @if($isCompleted)
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

    {{-- MODAL ALERT FOR GIFT REDEMPTION --}}
    <div class="modal fade" id="redemptionAlertModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered px-3" style="max-width: 380px;">
            <div class="modal-content text-center p-4" style="background-color: #FFFFFF; border-radius: 4px; border: 1px solid #111;">
                {{-- Exclamation Icon --}}
                <div class="mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; border-radius: 50%; border: 2px solid #111; font-size: 1.5rem; font-weight: bold; color: #111;">
                    !
                </div>

                {{-- Modal Header --}}
                <div style="font-size: 1.05rem; letter-spacing: 2px; font-weight: 700; text-transform: uppercase; color: #111; margin-bottom: 1rem;">
                    YOUR HOUSE OF MEMORIES
                </div>

                {{-- Modal Body Message --}}
                <p style="font-size: 0.8rem; line-height: 1.5; color: #444444; margin-bottom: 1.75rem;">
                    Complete all stations in the <br> Memory Map to proceed to the <br>Redemption Room
                </p>

                {{-- Close Button --}}
                <button type="button" class="mm-btn-black" data-bs-dismiss="modal" onclick="closeRedemptionModal()">
                    CLOSE
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const completedCount = {{ $completedCount }};
        let redemptionModalInstance = null;

        function closeRedemptionModal() {
            const modalEl = document.getElementById('redemptionAlertModal');
            const instance = bootstrap.Modal.getInstance(modalEl) || redemptionModalInstance;
            if (instance) {
                instance.hide();
            }
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
            if (isRedemption) {
                if (completedCount < 5) {
                    const modalEl = document.getElementById('redemptionAlertModal');
                    if (!redemptionModalInstance) {
                        redemptionModalInstance = new bootstrap.Modal(modalEl);
                    }
                    redemptionModalInstance.show();
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
