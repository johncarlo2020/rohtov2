<x-guest-layout>
    <div class="mm-mobile-container justify-content-between">
        <div>
            {{-- Header --}}
            @include('components.branding')

            {{-- Main Content --}}
            <div class="text-center px-2" style="margin-top: 4rem;">
                <h2 style="font-family: 'Courier Prime', monospace; font-size: 1.15rem; letter-spacing: 1px; color: #111111; font-weight: 400; margin-bottom: 2rem;">
                    Hi {{ Auth::user()->fname ?? Auth::user()->name ?? 'Joshua' }},
                </h2>

                <p style="font-family: 'Courier Prime', monospace; font-size: 0.85rem; line-height: 1.6; color: #444444; max-width: 300px; margin: 0 auto 3rem auto;">
                    {{ session('is_login') ? 'Login successful' : 'Thank you for your time. Your registration is now complete.' }}
                </p>
            </div>
        </div>

        {{-- Action Button --}}
        <div class="w-100 mb-4">
            <a href="{{ route('dashboard') }}" class="mm-btn-black">
                {{ session('is_login') ? 'CONTINUE' : 'BEGIN YOUR JOURNEY' }}
            </a>
        </div>
    </div>
</x-guest-layout>
