<x-guest-layout>
    <div class="join-main main-content with-scroll">
        <div class="d-flex flex-column justify-content-between h-100">
            <div class="col-12 d-flex justify-content-center animate-entry">
                @include('components.branding')
            </div>
                <div class="mt-4 w-100  animate-entry delay-3">
                    <div class="pt-2">
                        <div class="mb-0 row">
                            <div class="col-12 text-center">
                                <button type="button" onclick="window.location.href='{{ route('avatar.select') }}'"
                                    class="w-50 custom-btn custom-btn-primary animate-entry delay-3">
                                    {{ __('JOIN') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <x-footer /> --}}
        </div>
    </div>
</x-guest-layout>
<script>

</script>
