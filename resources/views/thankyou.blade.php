<x-guest-layout>
    <div class="mm-mobile-container text-center">
        {{-- Main Center Area --}}
        <div class="w-100 my-auto py-3">
            {{-- Title Box --}}
            <div class="text-center mb-3">
                <h1 style="font-size: 1.85rem; letter-spacing: 3px; font-weight: 400; text-transform: uppercase; color: #111111; margin-bottom: 0.75rem;">
                    THANK YOU
                </h1>
                <p style="font-size: 0.95rem; letter-spacing: 1.5px; font-weight: 400; text-transform: uppercase; color: #333333; margin: 0;">
                    FOR VISITING<br>HOUSE OF MEMORIES
                </p>
            </div>

            {{-- Divider Line --}}
            <div style="width: 54px; height: 1px; background-color: #888888; margin: 1.5rem auto 1.75rem auto;"></div>

            {{-- Two Feature Icons --}}
            <div class="mm-thankyou-icons" style="margin: 1.5rem 0; gap: 2rem;">
                {{-- 1. Purchase Fragrance --}}
                <a href="https://line.me/R/ti/p/@572ucrnz" target="_blank" rel="noopener noreferrer" class="mm-circle-btn">
                    <div class="mm-circle-icon" style="width: 80px; height: 80px; background-color: #000000; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 8h12l-1.5 12H7.5L6 8z"></path>
                            <path d="M9 8V6a3 3 0 0 1 6 0v2"></path>
                        </svg>
                    </div>
                    <div class="mm-circle-label" style="font-family: 'Courier Prime', monospace; font-size: 0.7rem; color: #666666; margin-top: 8px; text-transform: none;">
                        Purchase fragrance
                    </div>
                </a>

                {{-- 2. Store Around Me --}}
                <a href="{{ route('reservation.create') }}" class="mm-circle-btn">
                    <div class="mm-circle-icon" style="width: 80px; height: 80px; background-color: #000000; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 32 32" fill="none" stroke="#FFFFFF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M7 11h18"></path>
                            <path d="M9 11V9a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v2"></path>
                            <path d="M12 8V6.5a0.5 0.5 0 0 1 0.5-0.5h7a0.5 0.5 0 0 1 0.5 0.5V8"></path>
                            <rect x="7" y="11" width="18" height="4" rx="0.5"></rect>
                            <line x1="14" y1="13" x2="18" y2="13" stroke-width="1.2"></line>
                            <path d="M8 15v11"></path>
                            <path d="M13 15v11"></path>
                            <path d="M19 15v11"></path>
                            <path d="M24 15v11"></path>
                            <line x1="8" y1="19" x2="13" y2="19"></line>
                            <line x1="19" y1="19" x2="24" y2="19"></line>
                            <path d="M6 26h20"></path>
                        </svg>
                    </div>
                    <div class="mm-circle-label" style="font-family: 'Courier Prime', monospace; font-size: 0.7rem; color: #666666; margin-top: 8px; text-transform: none;">
                        Store around me
                    </div>
                </a>
            </div>
        </div>

        {{-- Bottom Logo --}}
        <div class="w-100 text-center mt-auto pb-2">
            @include('components.branding')
        </div>
    </div>
</x-guest-layout>
