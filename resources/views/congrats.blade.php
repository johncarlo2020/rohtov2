<x-guest-layout>
    <div class="justify-content-start text-center mm-mobile-container">
        {{-- Header --}}
        <div class="w-100 text-center">
            @include('components.branding')
        </div>

        {{-- Main Verification Success Content --}}
        <div class="d-flex flex-column align-items-center justify-content-start mt-4 pt-2 w-100">
            <h2 style="font-size: 1.15rem; letter-spacing: 1px; color: #111111; font-weight: 400; margin-bottom: 1.5rem; text-align: center;">
                Hi {{ Auth::user()->fname ?? Auth::user()->name ?? 'Guest' }},
            </h2>

            <p style="font-size: 0.85rem; color: #333333; max-width: 320px; margin: 0 auto 2.5rem auto; text-align: center;">
                Please continue to proceed with <br>booking the masterclass.
            </p>

            {{-- Action Button --}}
            <div class="w-100">
                <a href="{{ route('dashboard') }}" class="mm-btn-black">
                    CONTINUE
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
