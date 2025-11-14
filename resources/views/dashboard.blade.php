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
                    
                    // Define responsive positioning based on viewport size and device type
                    const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent);
                    const isSmallScreen = vw < 414;
                    const safeAreaBottom = isIOS ? 20 : 0; // Account for iOS safe area
                    
                    // Station positioning configurations
                    const stationConfigs = {
                        1: {
                            // Top left position
                            top: isSmallScreen ? Math.max(vh * 0.05, 20) : vh * 0.08,
                            left: isSmallScreen ? vw * 0.05 : vw * 0.08,
                            transform: 'translate(0, 0)'
                        },
                        2: {
                            // Middle right position
                            top: vh * 0.4,
                            right: isSmallScreen ? vw * 0.05 : vw * 0.08,
                            transform: 'translate(0, -50%)'
                        },
                        3: {
                            // Bottom center position
                            bottom: Math.max(vh * 0.1, safeAreaBottom + 40),
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
                    });
                    
                    // Adjust station details positioning based on station position
                    const station2 = document.querySelector('.station-custom-btn-2');
                    if (station2) {
                        station2.style.flexDirection = 'row-reverse';
                    }
                }
                
                // Initial positioning
                positionStations();
                
                // Re-position on resize and orientation change
                let resizeTimeout;
                function handleResize() {
                    clearTimeout(resizeTimeout);
                    resizeTimeout = setTimeout(positionStations, 150);
                }
                
                window.addEventListener('resize', handleResize);
                window.addEventListener('orientationchange', function() {
                    setTimeout(positionStations, 300); // Delay for orientation change
                });
                
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
