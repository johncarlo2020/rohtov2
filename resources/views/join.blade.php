<x-guest-layout>
    <div class="join-main main-content with-scroll">
        <img class="paw-welcome" src="{{ asset('images/brand/paw-welcome.webp') }}" alt="Brand Logo" />
        <div class=" mx-auto h-100">
            <div class="animate-entry">
                @include('components.branding')
            </div>
            <div class="cat">
                <img class="cat-welcome" src="{{ asset('images/brand/cat-welcome.webp') }}" alt="Brand Logo" />
            </div>
            <div class="mt-4 w-100  animate-entry delay-3">
                <div class="pt-2">
                    <div class="mb-0 row">
                        <div class="col-12 text-center">
                            <button type="button" onclick="window.location.href='{{ route('avatar.select') }}'"
                                class="w-50 custom-btn custom-btn-primary animate-entry delay-3 btn-with-icon">
                                {{ __('JOIN') }}

                                <img src="{{ asset('images/brand/cat-icon-button.webp') }}" alt=""
                                    class="cat-button-icon">
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <x-footer /> --}}
        </div>
    </div>
</x-guest-layout>
<script></script>
