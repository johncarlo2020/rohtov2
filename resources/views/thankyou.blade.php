<x-guest-layout>
    <div class="mm-mobile-container text-center">
        {{-- Main Center Area --}}
        <div class="w-100 mb-auto py-3">
            {{-- Title Box --}}
            <div class="text-center mb-3">
                <h1 class="mm-page-title" >
                    THANK YOU
                </h1>
                <p style="font-size: 0.95rem; letter-spacing: 1.5px; font-weight: 400; text-transform: uppercase; color: #333333; margin: 0;">
                    FOR VISITING<br>HOUSE OF MEMORIES
                </p>
            </div>

            {{-- Divider Line --}}
            <div style="width: 54px; height: 1px; background-color: #888888; margin: 1.5rem auto 1.75rem auto;"></div>

            {{-- Feature Button: Store Around Me --}}
            <div class="mm-thankyou-icons" style="margin: 2.25rem 0;">
                <a href="{{ asset('pdf/maison_margiela_bangkok_boutique_locations.pdf') }}" target="_blank" rel="noopener noreferrer" class="mm-circle-btn">
                    <div class="mm-circle-icon" style="width: 100px; height: 100px; background-color: transparent;">
                        <img src="{{ asset('images/store_btn.webp') }}" alt="Store around me" style="width: 100%; height: 100%; object-fit: contain; display: block;">
                    </div>
                    <div class="mm-circle-label" style="font-family: 'Courier New', Courier, monospace; font-size: 0.72rem; color: #444444; margin-top: 12px; text-transform: none; letter-spacing: 0.5px;">
                        Store around me
                    </div>
                </a>
            </div>
        </div>

        {{-- Bottom Logo --}}
        <div class="w-100 text-center my-auto pb-2">
            @include('components.branding')
        </div>
    </div>
</x-guest-layout>
