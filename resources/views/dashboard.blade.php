<x-guest-layout>
    <div class="mm-mobile-container justify-content-between p-0" style="max-width: 440px; margin: 0 auto; background-color: #F4F0EA; min-height: 100vh;">
        
        {{-- VIEW 1: MEMORY MAP PAGE --}}
        <div id="mapViewSection" class="w-100 px-3 pb-5">
            {{-- Header --}}
            @include('components.branding')

            {{-- Title --}}
            <div class="mm-page-title mt-2 mb-3">
                YOUR MEMORY MAP
            </div>

            {{-- Memory Map Visual Graphic --}}
            <div class="mm-map-wrapper text-center my-3" style="background-color: #FAF8F5; border: 1px solid #E0DDD7; border-radius: 4px; padding: 1.5rem 1rem; position: relative;">
                <svg viewBox="0 0 320 420" width="100%" height="auto" style="max-height: 52vh; display: block; margin: 0 auto;">
                    <!-- Outer Floorplan Walls -->
                    <rect x="20" y="20" width="280" height="380" fill="#F4F0EA" stroke="#333333" stroke-width="2" rx="4"/>
                    
                    <!-- Rooms & Divider Walls -->
                    <!-- Top Room (Photobooth & Redemption) -->
                    <line x1="20" y1="90" x2="300" y2="90" stroke="#333333" stroke-width="1.5" stroke-dasharray="4,2"/>
                    <line x1="160" y1="20" x2="160" y2="90" stroke="#333333" stroke-width="1.5"/>
                    
                    <!-- Middle Section Rooms -->
                    <line x1="20" y1="180" x2="300" y2="180" stroke="#333333" stroke-width="1.5" stroke-dasharray="4,2"/>
                    <line x1="180" y1="90" x2="180" y2="180" stroke="#333333" stroke-width="1.5"/>
                    
                    <!-- Lower Section Rooms -->
                    <line x1="20" y1="290" x2="300" y2="290" stroke="#333333" stroke-width="1.5" stroke-dasharray="4,2"/>
                    <line x1="140" y1="180" x2="140" y2="290" stroke="#333333" stroke-width="1.5"/>

                    <!-- Room Text Labels -->
                    <text x="90" y="45" font-family="Courier Prime, monospace" font-size="9" font-weight="bold" fill="#555" text-anchor="middle">PHOTOBOOTH</text>
                    <text x="230" y="45" font-family="Courier Prime, monospace" font-size="8" font-weight="bold" fill="#777" text-anchor="middle">REDEMPTION COUNTER</text>

                    <text x="100" y="125" font-family="Courier Prime, monospace" font-size="9" font-weight="bold" fill="#555" text-anchor="middle">LAST SUNDAY MORNING</text>
                    <text x="240" y="125" font-family="Courier Prime, monospace" font-size="9" font-weight="bold" fill="#555" text-anchor="middle">SCENTSORIUM</text>

                    <text x="80" y="220" font-family="Courier Prime, monospace" font-size="9" font-weight="bold" fill="#555" text-anchor="middle">JAZZ CLUB</text>
                    <text x="220" y="235" font-family="Courier Prime, monospace" font-size="9" font-weight="bold" fill="#555" text-anchor="middle">BY THE FIREPLACE</text>

                    <text x="80" y="325" font-family="Courier Prime, monospace" font-size="9" font-weight="bold" fill="#555" text-anchor="middle">REPLICA CAFE</text>
                    <text x="220" y="340" font-family="Courier Prime, monospace" font-size="9" font-weight="bold" fill="#555" text-anchor="middle">37 AT DAWN</text>

                    <text x="160" y="275" font-family="Courier Prime, monospace" font-size="9" font-weight="bold" fill="#555" text-anchor="middle">CHASING SUNSETS</text>

                    <!-- Interactive Station Markers (Dots) -->
                    @foreach($stations as $stn)
                        @php
                            $isCompleted = in_array($stn->id, $completedStationIds);
                            // Define coordinates for map pins based on station name/slug
                            $coords = match($stn->slug) {
                                'photobooth' => ['x' => 90, 'y' => 62],
                                'redemption-counter' => ['x' => 230, 'y' => 62],
                                'last-sunday-morning' => ['x' => 100, 'y' => 145],
                                'scentsorium' => ['x' => 240, 'y' => 145],
                                'jazz-club' => ['x' => 80, 'y' => 240],
                                'by-the-fireplace' => ['x' => 220, 'y' => 255],
                                'chasing-sunsets' => ['x' => 160, 'y' => 295],
                                'replica-cafe' => ['x' => 80, 'y' => 355],
                                '37-at-dawn' => ['x' => 220, 'y' => 365],
                                default => ['x' => 160, 'y' => 200],
                            };
                        @endphp

                        <g class="station-pin-group" onclick="handleStationClick({{ $stn->id }}, '{{ addslashes($stn->name) }}', {{ $stn->is_redemption ? 'true' : 'false' }})" style="cursor: pointer;">
                            <circle cx="{{ $coords['x'] }}" cy="{{ $coords['y'] }}" r="8" fill="{{ $isCompleted ? '#000000' : '#111111' }}" stroke="#FFFFFF" stroke-width="2"/>
                            @if($isCompleted)
                                <text x="{{ $coords['x'] }}" y="{{ $coords['y'] + 3 }}" font-family="sans-serif" font-size="8" fill="#FFFFFF" text-anchor="middle" font-weight="bold">✓</text>
                            @else
                                <circle cx="{{ $coords['x'] }}" cy="{{ $coords['y'] }}" r="3" fill="#FFFFFF"/>
                            @endif
                        </g>
                    @endforeach
                </svg>
            </div>

            {{-- Sticky Footer Button --}}
            <div class="mt-4 px-2">
                <button type="button" onclick="showPassportView()" class="mm-btn-black">
                    MY MEMORY PASSPORT
                </button>
            </div>
        </div>

        {{-- VIEW 2: MEMORY PASSPORT LIST PAGE ("YOUR HOUSE OF MEMORIES") --}}
        <div id="passportViewSection" class="w-100 px-3 pb-5 d-none">
            {{-- Top Bar with Back Arrow --}}
            <div class="d-flex align-items-center justify-content-between pt-3 pb-2">
                <button type="button" onclick="showMapView()" class="btn p-0 border-0" style="font-size: 1.25rem; font-weight: 700; color: #111;">
                    ←
                </button>
                <div class="flex-grow-1 text-center pe-4">
                    @include('components.branding')
                </div>
            </div>

            {{-- Title --}}
            <div class="mm-page-title mt-1 mb-4">
                YOUR HOUSE OF MEMORIES
            </div>

            {{-- Station Cards List --}}
            <div class="px-1">
                @foreach($stations as $stn)
                    @php
                        $isCompleted = in_array($stn->id, $completedStationIds);
                        // Mock thumbnail background colors or imagery
                        $bgGradient = match($stn->slug) {
                            'replica-cafe' => 'linear-gradient(135deg, #E6D7C3 0%, #CBB396 100%)',
                            '37-at-dawn' => 'linear-gradient(135deg, #E2E4E9 0%, #BCC3D0 100%)',
                            'by-the-fireplace' => 'linear-gradient(135deg, #D9B18F 0%, #9E6A47 100%)',
                            'chasing-sunsets' => 'linear-gradient(135deg, #F4C493 0%, #D88358 100%)',
                            'jazz-club' => 'linear-gradient(135deg, #A89B91 0%, #5E5249 100%)',
                            'last-sunday-morning' => 'linear-gradient(135deg, #F7F5F0 0%, #DED8CC 100%)',
                            'scentsorium' => 'linear-gradient(135deg, #3A3735 0%, #1A1817 100%)',
                            'photobooth' => 'linear-gradient(135deg, #D4C9BC 0%, #A39788 100%)',
                            'redemption-counter' => 'linear-gradient(135deg, #ECE6DD 0%, #C4B9A9 100%)',
                            default => '#CCCCCC',
                        };
                    @endphp

                    <div class="mm-passport-card {{ $isCompleted ? 'completed' : '' }}" onclick="handleStationClick({{ $stn->id }}, '{{ addslashes($stn->name) }}', {{ $stn->is_redemption ? 'true' : 'false' }})" style="cursor: pointer;">
                        {{-- Thumbnail --}}
                        <div class="mm-passport-thumb" style="background: {{ $bgGradient }}; display: flex; align-items: center; justify-content: center; color: #555; font-size: 10px; font-weight: bold;">
                            {{ substr($stn->name, 0, 2) }}
                        </div>

                        {{-- Name --}}
                        <div class="mm-passport-name">
                            {{ $stn->name }}
                        </div>

                        {{-- Checkbox Circle --}}
                        <div class="mm-passport-status {{ $isCompleted ? 'checked' : '' }}"></div>
                    </div>
                @endforeach
            </div>

            {{-- Back to map button --}}
            <div class="mt-4 px-1">
                <button type="button" onclick="showMapView()" class="mm-btn-black">
                    BACK TO MAP
                </button>
            </div>
        </div>

    </div>

    {{-- MODAL ALERT FOR GIFT REDEMPTION (IMAGE 4) --}}
    <div class="modal fade" id="redemptionAlertModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered px-3" style="max-width: 360px;">
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
