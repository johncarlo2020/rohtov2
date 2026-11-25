<x-guest-layout>
    <div class="rwg-page-container d-flex flex-column justify-content-between"
         style="min-height: 100vh; min-height: 100dvh;"
         x-data="{ step: 1 }">
        
        <!-- Top Section: RWG White Logo -->
        <div class="d-flex justify-content-center pt-2 mb-3">
            @include('components.branding')
        </div>

        <!-- Middle Section: Title & Content Cards -->
        <div class="w-100 my-auto py-2">
            <!-- Reusable Title -->
            <h1 class="rwg-title mb-4">YOUR MISSION:</h1>

            <!-- Step 1: 3 Mission Points -->
            <div x-show="step === 1"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-x-4"
                 x-transition:enter-end="opacity-100 transform translate-x-0"
                 class="w-100">
                <div class="p-4 rounded-4 text-center shadow-lg mb-4"
                     style="background: rgba(0, 0, 0, 0.75); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.12);">
                    
                    <div class="mb-4">
                        <h3 class="fw-bold text-white mb-2" style="font-size: 1.15rem;">
                            Explore the Magic:
                        </h3>
                        <p class="mb-0 text-white" style="font-size: 0.9rem; line-height: 1.45; opacity: 0.92;">
                            Follow the map and visit all 6 locations to complete your winter adventure.
                        </p>
                    </div>

                    <div class="mb-4">
                        <h3 class="fw-bold text-white mb-2" style="font-size: 1.15rem;">
                            Hunt for Clues:
                        </h3>
                        <p class="mb-0 text-white" style="font-size: 0.9rem; line-height: 1.45; opacity: 0.92;">
                            Keep an eye out for the treasure hunt QR code at each location.
                        </p>
                    </div>

                    <div>
                        <h3 class="fw-bold text-white mb-2" style="font-size: 1.15rem;">
                            Scan to unlock:
                        </h3>
                        <p class="mb-0 text-white" style="font-size: 0.9rem; line-height: 1.45; opacity: 0.92;">
                            Scan all 6 QR codes to complete your mission.
                        </p>
                    </div>
                </div>

                <div class="text-center">
                    <button type="button" class="rwg-btn-gold" @click="step = 2">
                        NEXT
                    </button>
                </div>
            </div>

            <!-- Step 2: Final Quest & Start Hunt -->
            <div x-show="step === 2"
                 x-cloak
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-x-4"
                 x-transition:enter-end="opacity-100 transform translate-x-0"
                 class="w-100">
                <div class="p-4 rounded-4 text-center shadow-lg mb-4"
                     style="background: rgba(0, 0, 0, 0.75); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.12);">
                    
                    <p class="mb-4 text-white" style="font-size: 0.95rem; line-height: 1.55; opacity: 0.95;">
                        Once you've successfully tracked down every location and scanned all the codes, you will instantly unlock an exclusive, limited-edition gift from Resorts World Genting!
                    </p>

                    <p class="fw-bold mb-0 text-white" style="font-size: 1.15rem; line-height: 1.4;">
                        Are you ready to brave the chill and claim your prize?
                    </p>
                </div>

                <div class="text-center">
                    @auth
                        <a href="{{ route('dashboard', ['start' => 1]) }}" class="rwg-btn-gold text-decoration-none">
                            START THE HUNT
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="rwg-btn-gold text-decoration-none">
                            START THE HUNT
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Footer Spacing -->
        <div class="pb-2"></div>
    </div>
</x-guest-layout>
