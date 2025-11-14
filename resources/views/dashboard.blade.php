<x-app-layout>
    <div class="py-4 map-page main-content dashbord-page">
        <div class="animate-entry">
            @include('components.branding')
        </div>

        <!-- login Modal -->

        <!-- Modal -->
        <div class="modal fade custom-modal" id="notAllowedModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered w-75 m-auto">
                <div class="modal-content card">
                    <div class="modal-body">
                        <div class="text-center content">
                            <div class="text-content mt-4 mb-4">
                                <p class="message text-dark">
                                    Please Complete all <br> the station
                                </p>
                            </div>
                            <button type="button" class="w-50 custom-btn custom-btn-primary" data-bs-dismiss="modal"
                                aria-label="Close">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="station-selection-container mb-2 animate-entry delay-2">
            @foreach ($stations as $station)

                <a class="station-custom-btn station-custom-btn-{{ $station->id }}"
                    type="button"
                    @if($station->status)
                        @if($station->id == 3)
                            @if($isRedeemed)
                                onclick="window.location.href='{{ route('congrats') }}'"
                            @else 
                                onclick="window.location.href='{{ route('station.giftselection') }}'"
                            @endif
                        @else
                             onclick="gotoStamping({{ $station->id }})"
                        @endif
                    @else
                            onclick="gotoStation({{ $station->id }})"
                    @endif
                    >

                    <div class="station-image-container">
                        <img class="station-icon station-{{ $station->id }} pulse-slow" 
                            data-id="station-{{ $station->id }}" 
                            src="@if($station->status)
                                {{asset('images/station/STBM' . $station->id . 'GLOW.webp');}}
                            @else
                                {{asset('images/station/STBM' . $station->id . '.webp');}}
                            @endif"
                            alt="Station {{ $station->id }}"
                            style="@if($station->status) filter: grayscale(0); @endif"> <!-- grayscale only if NOT completed -->
                    </div>
                    <div class="station-details station-{{ $station->id }}">
                    </div>
                </a>
            @endforeach

        </div>
    </div>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let canAccessStation3 = @json($canAccessStation3);
                
                // Station positioning function
                function positionStations() {
                    const container = document.querySelector('.station-selection-container');
                    if (!container) return;
                    
                    const containerRect = container.getBoundingClientRect();
                    const containerWidth = containerRect.width;
                    const containerHeight = containerRect.height || window.innerHeight * 0.8;
                    
                    // Get viewport dimensions
                    const vw = window.innerWidth;
                    const vh = window.innerHeight;
                    
                    // Enhanced browser and platform detection
                    const userAgent = navigator.userAgent;
                    const platform = navigator.platform;
                    
                    // Detect iOS devices (iPhone, iPad, iPod)
                    const isIOS = /iPad|iPhone|iPod/.test(userAgent) || 
                                  (platform === 'MacIntel' && navigator.maxTouchPoints > 1); // Detect iPad with iPadOS 13+
                    
                    // Detect Safari browser (including iOS Safari and macOS Safari)
                    const isSafari = /^((?!chrome|android).)*safari/i.test(userAgent) || 
                                     /Safari/.test(userAgent) && !/Chrome/.test(userAgent);
                    
                    // Detect iPhone specifically
                    const isIPhone = /iPhone/.test(userAgent);
                    
                    // Detect iPad specifically
                    const isIPad = /iPad/.test(userAgent) || 
                                   (platform === 'MacIntel' && navigator.maxTouchPoints > 1);
                    
                    // Detect mobile Safari specifically
                    const isMobileSafari = isIOS && isSafari;
                    
                    // Screen size detection
                    const isSmallScreen = vw < 414;
                    const isMediumScreen = vw >= 414 && vw < 768;
                    const isLargeScreen = vw >= 768;
                    
                    // Calculate safe areas for iOS
                    const safeAreaTop = isIOS ? (window.screen.height - window.innerHeight) / 2 : 0;
                    const safeAreaBottom = isIOS ? Math.max(20, safeAreaTop) : 0;
                    
                    // Debug logging (you can remove this in production)
                    if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
                        console.log('Station Positioning Debug:', {
                            userAgent: userAgent,
                            platform: platform,
                            isIOS: isIOS,
                            isSafari: isSafari,
                            isIPhone: isIPhone,
                            isIPad: isIPad,
                            isMobileSafari: isMobileSafari,
                            viewport: { width: vw, height: vh },
                            safeAreas: { top: safeAreaTop, bottom: safeAreaBottom },
                            cssEnvSupport: CSS.supports('padding', 'env(safe-area-inset-top)')
                        });
                    }
                    
                    // Station positioning configurations based on device and browser
                    const stationConfigs = {
                        1: {
                            // Top left position - adjust for iOS safe area
                            top: (() => {
                                if (isIPhone) return Math.max(vh * 0.08, safeAreaTop + 30);
                                if (isIPad) return vh * 0.06;
                                if (isMobileSafari) return Math.max(vh * 0.07, safeAreaTop + 25);
                                if (isSmallScreen) return Math.max(vh * 0.05, 20);
                                return vh * 0.08;
                            })(),
                            left: (() => {
                                if (isIOS) return vw * 0.06;
                                if (isSmallScreen) return vw * 0.05;
                                return vw * 0.08;
                            })(),
                            transform: 'translate(0, 0)'
                        },
                        2: {
                            // Middle right position
                            top: (() => {
                                if (isIPhone) return vh * 0.45;
                                if (isIPad) return vh * 0.4;
                                return vh * 0.4;
                            })(),
                            right: (() => {
                                if (isIOS) return vw * 0.06;
                                if (isSmallScreen) return vw * 0.05;
                                return vw * 0.08;
                            })(),
                            transform: 'translate(0, -50%)'
                        },
                        3: {
                            // Bottom center position - critical for iOS safe area
                            bottom: (() => {
                                if (isIPhone) return Math.max(vh * 0.15, safeAreaBottom + 50);
                                if (isIPad) return Math.max(vh * 0.12, safeAreaBottom + 40);
                                if (isMobileSafari) return Math.max(vh * 0.12, safeAreaBottom + 45);
                                return Math.max(vh * 0.1, safeAreaBottom + 40);
                            })(),
                            left: '50%',
                            transform: 'translate(-50%, 0)'
                        }
                    };
                    
                    // Apply positioning to each station
                    Object.keys(stationConfigs).forEach(stationId => {
                        const station = document.querySelector(`.station-custom-btn-${stationId}`);
                        if (!station) return;
                        
                        const config = stationConfigs[stationId];
                        
                        // Reset positioning
                        station.style.position = 'absolute';
                        station.style.top = 'auto';
                        station.style.right = 'auto';
                        station.style.bottom = 'auto';
                        station.style.left = 'auto';
                        
                        // Apply new positioning
                        if (config.top !== undefined) {
                            station.style.top = typeof config.top === 'number' ? `${config.top}px` : config.top;
                        }
                        if (config.right !== undefined) {
                            station.style.right = typeof config.right === 'number' ? `${config.right}px` : config.right;
                        }
                        if (config.bottom !== undefined) {
                            station.style.bottom = typeof config.bottom === 'number' ? `${config.bottom}px` : config.bottom;
                        }
                        if (config.left !== undefined) {
                            station.style.left = typeof config.left === 'number' ? `${config.left}px` : config.left;
                        }
                        if (config.transform) {
                            station.style.transform = config.transform;
                        }
                        
                        // Ensure proper z-index and display
                        station.style.zIndex = '2';
                        station.style.display = 'flex';
                        
                        // iOS-specific adjustments
                        if (isIOS) {
                            // Use CSS environment variables for safe areas when available
                            if (CSS.supports('padding-top', 'env(safe-area-inset-top)')) {
                                if (stationId === '1') {
                                    station.style.paddingTop = 'env(safe-area-inset-top)';
                                }
                                if (stationId === '3') {
                                    station.style.paddingBottom = 'env(safe-area-inset-bottom)';
                                }
                            }
                            
                            // Prevent iOS Safari bounce effect interference
                            station.style.touchAction = 'manipulation';
                            station.style.webkitTouchCallout = 'none';
                        }
                    });
                    
                    // Adjust station details positioning based on station position
                    const station2 = document.querySelector('.station-custom-btn-2');
                    if (station2) {
                        station2.style.flexDirection = 'row-reverse';
                    }
                    
                    // Apply container adjustments for iOS
                    if (isIOS && container) {
                        container.style.paddingTop = CSS.supports('padding', 'env(safe-area-inset-top)') 
                            ? 'env(safe-area-inset-top)' : `${safeAreaTop}px`;
                        container.style.paddingBottom = CSS.supports('padding', 'env(safe-area-inset-bottom)') 
                            ? 'env(safe-area-inset-bottom)' : `${safeAreaBottom}px`;
                    }
                }
                
                // Initial positioning
                positionStations();
                
                // Enhanced orientation and resize handling
                let resizeTimeout;
                function handleResize() {
                    clearTimeout(resizeTimeout);
                    resizeTimeout = setTimeout(positionStations, 150);
                }
                
                function handleOrientationChange() {
                    // iOS needs more time to properly calculate dimensions after orientation change
                    const delay = isIOS ? 500 : 300;
                    setTimeout(positionStations, delay);
                }
                
                // Event listeners
                window.addEventListener('resize', handleResize);
                window.addEventListener('orientationchange', handleOrientationChange);
                
                // iOS specific: listen for viewport changes
                if (isIOS) {
                    // Handle iOS keyboard appearance/disappearance
                    window.addEventListener('focusin', function() {
                        setTimeout(positionStations, 200);
                    });
                    window.addEventListener('focusout', function() {
                        setTimeout(positionStations, 200);
                    });
                }
                
                // Re-position when images load (affects container size)
                const stationImages = document.querySelectorAll('.station-icon');
                stationImages.forEach(img => {
                    if (img.complete) return;
                    img.addEventListener('load', positionStations, { once: true });
                });
                
                window.gotoStamping = function(id,)
                {
                    var url = "{{ route('station.stamping', ['station' => ':id']);}}".replace(
                        ":id",id
                    );
                     window.location.href = url;
                }
                window.gotoStation = function(id, ) {
                    var url = "{{ route('station', ['station' => ':id']) }}".replace(
                        ":id",
                        id
                    );

                    if (id === 3 && !canAccessStation3) {
                        // Show the not allowed modal if trying to access station 3 without permission
                        var notAllowedModal = new bootstrap.Modal(document.getElementById('notAllowedModal'));
                        notAllowedModal.show();
                        return;
                    }

                    // Redirect to the generated URL
                    window.location.href = url;
                }
            });
        </script>
    @endpush
</x-app-layout>
