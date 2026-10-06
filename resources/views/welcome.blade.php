<x-guest-layout>
    <div class="mm-mobile-container justify-content-between">
        <div>
            {{-- Header --}}
            @include('components.branding')

            {{-- Title Box --}}
            <div class="mm-box-header mt-4 mb-2">
                HOUSE OF MEMORIES
            </div>

            {{-- Divider --}}
            <div class="mm-divider"></div>

            {{-- Text content --}}
            <div class="text-center px-2 my-4">
                <p style="font-family: 'Courier Prime', monospace; font-size: 0.85rem; line-height: 1.6; color: #333333; margin-bottom: 2rem;">
                    Step into a world of memories inspired by iconic REPLICA fragrances.
                </p>

                <p style="font-family: 'Courier Prime', monospace; font-size: 0.8rem; line-height: 1.5; color: #555555; margin-bottom: 3rem;">
                    Collect each memory as you explore the <span style="color: #D93838; text-decoration: underline;">login</span>.
                </p>
            </div>
        </div>

        {{-- Actions --}}
        <div class="w-100 mb-4">
            <a href="{{ route('register') }}" class="mm-btn-black mb-3">
                REGISTER
            </a>

            <div class="mm-link-sub">
                ALREADY REGISTERED? <a href="{{ route('login') }}">LOGIN HERE</a>
            </div>
        </div>
    </div>
</x-guest-layout>
