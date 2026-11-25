<x-guest-layout>
    <div class="rwg-page-container">
        <!-- Top Section: Resorts World Genting Logo -->
        <div class="d-flex justify-content-center mb-4 pt-2">
            @include('components.branding')
        </div>

        <!-- Page Title -->
        <h1 class="rwg-title mb-4">LOGIN</h1>

        <form method="POST" action="{{ route('login') }}" class="w-100">
            @csrf

            <!-- Email Address -->
            <div class="mb-4">
                <label for="email" class="rwg-label">Email Address</label>
                <input id="email"
                       type="email"
                       class="rwg-input @error('email') is-invalid @enderror"
                       name="email"
                       value="{{ old('email') }}"
                       required
                       autofocus
                       autocomplete="email"
                       placeholder="Enter your email" />
                @error('email')
                    <div class="text-danger small mt-1 fw-bold text-center">{{ $message }}</div>
                @enderror
                @if (session('error'))
                    <div class="text-danger small mt-1 fw-bold text-center">{{ session('error') }}</div>
                @endif
                @if (session('status'))
                    <div class="text-white small mt-1 fw-bold text-center">{{ session('status') }}</div>
                @endif
            </div>

            <!-- Default participant password -->
            <input type="hidden" name="password" value="password" />

            <!-- Submit Button -->
            <div class="mb-4 text-center">
                <button type="submit" class="rwg-btn-gold">
                    LOGIN
                </button>
            </div>
        </form>

        <!-- Bottom Link -->
        <div class="rwg-footer-link mt-4">
            HAVEN'T? <a href="{{ route('register') }}">REGISTER HERE</a>
        </div>
    </div>
</x-guest-layout>
