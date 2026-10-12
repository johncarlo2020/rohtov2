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
                    <div class="mm-circle-icon" style="width: 80px; height: 80px; background-color: transparent;">
                        <img src="{{ asset('images/purchase_btn.webp') }}" alt="Purchase fragrance" style="width: 100%; height: 100%; object-fit: contain; display: block;">
                    </div>
                    <div class="mm-circle-label" style="font-family: 'Courier Prime', monospace; font-size: 0.7rem; color: #666666; margin-top: 8px; text-transform: none;">
                        Purchase fragrance
                    </div>
                </a>

                {{-- 2. Store Around Me --}}
                <a href="{{ asset('pdf/maison_margiela_bangkok_boutique_locations.pdf') }}" target="_blank" rel="noopener noreferrer" class="mm-circle-btn">
                    <div class="mm-circle-icon" style="width: 80px; height: 80px; background-color: transparent;">
                        <img src="{{ asset('images/store_btn.webp') }}" alt="Store around me" style="width: 100%; height: 100%; object-fit: contain; display: block;">
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
