<x-guest-layout>
    <div class="mm-mobile-container justify-content-start text-center">
        {{-- Header --}}
        <div class="w-100 text-center">
            @include('components.branding')
        </div>

        {{-- Main Verification Success Content --}}
        <div class="w-100 mt-4 pt-2 d-flex flex-column align-items-center justify-content-start">
            <h2 style="font-family: 'Courier Prime', monospace; font-size: 1.15rem; letter-spacing: 1px; color: #111111; font-weight: 400; margin-bottom: 1.5rem; text-align: center;">
                Hi {{ Auth::user()->fname ?? Auth::user()->name ?? 'Guest' }},
            </h2>

            <p style="font-family: 'Courier Prime', monospace; font-size: 0.85rem; line-height: 1.6; color: #333333; max-width: 320px; margin: 0 auto 2.5rem auto; text-align: center;">
                Thank you for your time. Your<br>registration is now complete.
            </p>

            {{-- Action Button --}}
            <div class="w-100">
                <a href="{{ route('dashboard') }}" class="mm-btn-black">
                    BEGIN YOUR JOURNEY
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
