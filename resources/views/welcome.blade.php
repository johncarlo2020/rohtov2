<x-guest-layout>
    <div class="mm-mobile-container text-center">
        
        {{-- Header Branding --}}
        <div class="w-100 text-center">
            @include('components.branding')
        </div>

        {{-- Center Content Block --}}
        <div class="w-100 my-3 d-flex flex-column align-items-center justify-content-center">
            {{-- Title --}}
            <h1 class="mm-page-title">
                HOUSE OF MEMORIES
            </h1>

            {{-- Divider Line --}}
            <div style="width: 64px; height: 1px; background-color: #A09C94; margin: 0 auto 1.5rem auto;"></div>

            {{-- Text Content --}}
            <div style="max-width: 320px; margin: 0 auto;">
                <p style="font-family: 'Courier Prime', monospace; font-size: 0.95rem; line-height: 1.5; color: #222222; text-align: center; margin-bottom: 1.5rem;">
                    Step into a world of<br>memories inspired by<br>iconic REPLICA fragrances.
                </p>

                <p style="font-family: 'Courier Prime', monospace; font-size: 0.95rem; line-height: 1.5; color: #222222; text-align: center; margin-bottom: 0;">
                    Collect each memory as<br>you explore the popup.
                </p>
            </div>
        </div>

        {{-- Bottom Actions --}}
        <div class="w-100 mt-auto pt-3">
            <a href="{{ route('register') }}" class="mm-btn-black py-3 mb-3 w-100" style="border-radius: 8px; font-family: 'Courier Prime', monospace; font-size: 0.9rem; font-weight: 700; letter-spacing: 2px; text-decoration: none; display: flex; align-items: center; justify-content: center;">
                REGISTER
            </a>

            <div style="font-family: 'Courier Prime', monospace; font-size: 0.725rem; letter-spacing: 1px; font-weight: 700; color: #111111; text-align: center;">
                ALREADY REGISTERED? <a href="{{ route('login') }}" style="color: #111111; text-decoration: underline; font-weight: 700;">LOGIN HERE</a>
            </div>
        </div>

    </div>
</x-guest-layout>
