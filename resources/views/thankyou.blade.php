<x-guest-layout>
    <div class="mm-mobile-container justify-content-between">
        <div>
            {{-- Header --}}
            @include('components.branding')

            {{-- Title Box --}}
            <div class="text-center mt-4 mb-2">
                <h1 style="font-family: 'Courier Prime', monospace; font-size: 1.25rem; letter-spacing: 2px; font-weight: 700; text-transform: uppercase; color: #111111; line-height: 1.4;">
                    THANK YOU
                </h1>
                <p style="font-family: 'Courier Prime', monospace; font-size: 0.85rem; letter-spacing: 1.5px; font-weight: 700; text-transform: uppercase; color: #444444; margin-top: 4px;">
                    FOR VISITING<br>HOUSE OF MEMORIES
                </p>
            </div>

            {{-- Divider Line --}}
            <div class="mm-divider" style="margin: 1.5rem auto 2.5rem auto;"></div>

            {{-- Two Feature Icons (Image 5) --}}
            <div class="mm-thankyou-icons">
                {{-- 1. Purchase Fragrance --}}
                <a href="https://line.me/R/ti/p/@572ucrnz" target="_blank" rel="noopener noreferrer" class="mm-circle-btn">
                    <div class="mm-circle-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <div class="mm-circle-label">
                        Purchase fragrance
                    </div>
                </a>

                {{-- 2. Store Around Me / Masterclass --}}
                <a href="{{ route('reservation.create') }}" class="mm-circle-btn">
                    <div class="mm-circle-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div class="mm-circle-label">
                        Store around me
                    </div>
                </a>
            </div>
        </div>

        {{-- Bottom Logo --}}
        <div class="w-100 mb-4 text-center">
            @include('components.branding')
        </div>
    </div>
</x-guest-layout>
