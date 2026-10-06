<x-guest-layout>
    <div class="mm-mobile-container justify-content-between">
        <div>
            {{-- Header --}}
            @include('components.branding')

            {{-- Title --}}
            <div class="mm-page-title mt-2">
                LOGIN
            </div>

            {{-- Login Form --}}
            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Session Status / Error --}}
                <x-auth-session-status class="mb-3" :status="session('status')" />

                @if(session('error'))
                    <div class="mm-error-text text-center mb-3">{{ session('error') }}</div>
                @endif

                {{-- Email Address --}}
                <div class="mb-4">
                    <label for="email" class="mm-label">EMAIL ADDRESS</label>
                    <input id="email" type="email" name="email"
                           class="mm-input @error('email') is-invalid @enderror"
                           placeholder="Enter your email"
                           value="{{ old('email') }}" required autocomplete="email">

                    @error('email')
                        <div class="mm-error-text">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Submit Button --}}
                <div class="mt-4">
                    <button type="submit" class="mm-btn-black">
                        LOGIN
                    </button>
                </div>
            </form>
        </div>

        {{-- Footer Link --}}
        <div class="mm-link-sub mb-3">
            HAVEN'T? <a href="{{ route('register') }}">REGISTER HERE</a>
        </div>
    </div>
</x-guest-layout>
