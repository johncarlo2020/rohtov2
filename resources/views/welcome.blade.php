<x-guest-layout>
    <div class="mm-mobile-container text-center d-flex flex-column justify-content-between py-4" style="min-height: 100dvh; max-width: 420px; margin: 0 auto;">
        
        {{-- Header Branding --}}
        <div class="w-100 text-center pt-2">
            @include('components.branding')
        </div>

        {{-- Center Content Block --}}
        <div class="w-100 my-auto py-3 d-flex flex-column align-items-center justify-content-center">
            {{-- Title --}}
            <h1 class="mm-page-title">
                HOUSE OF MEMORIES
            </h1>

            {{-- Divider Line --}}
            <div style="width: 58px; height: 1px; background-color: #BDB7AB; margin: 1.5rem auto 2rem auto;"></div>

            {{-- Text Content --}}
            <div style="max-width: 320px; margin: 0 auto;">
                <p style="font-size: 1rem; color: #222222; text-align: center; margin-bottom: 2rem; letter-spacing: 0.3px;">
                    Step into a world of<br>memories inspired by the<br>iconic REPLICA fragrances.
                </p>

                <p style="font-size: 1rem; color: #222222; text-align: center; margin-bottom: 0; letter-spacing: 0.3px;">
                    Collect each memory as<br>you explore the popup.
                </p>
            </div>
        </div>

        {{-- Bottom Actions --}}
        <div class="w-100 mb-auto pb-3 text-center">
            <a href="{{ route('register') }}" class="mm-btn-black mx-auto" style="width: 60%; max-width: 240px; height: 35px; border-radius: 6px; font-size: 0.85rem; font-weight: 700; letter-spacing: 2px; text-decoration: none; display: flex; align-items: center; justify-content: center; background-color: #000000; color: #FFFFFF;">
                REGISTER
            </a>

            <div class="mt-3" style="font-size: 0.65rem; letter-spacing: 0.75px; color: #111111; text-align: center;">
                ALREADY REGISTERED? <a href="{{ route('login') }}" style="color: #111111; font-weight: 700; text-decoration: underline;">LOGIN HERE</a>
            </div>
        </div>

    </div>
</x-guest-layout>
