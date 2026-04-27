<x-guest-layout>
    <div class="register-main with-scroll">
        <div class="justify-content-center w-100">
            <div class="d-flex justify-content-center mt-3 col-12">
                @include('components.branding')
            </div>
            <div class="mt-3 px-2 w-100">
                <h1 class="mt-5 mb-3 text-center fw-bold heading-dutch">LOGIN</h1>
                <div class="px-4 py-5 pt-1 register-form-parent">
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email">Email Address</label>
                            <input id="email" type="email"
                                class="form-control input-text @error('email') is-invalid @enderror" name="email"
                                value="{{ old('email') }}" required autocomplete="email"
                                placeholder="Enter your email" />
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            @if (session('error'))
                                <span class="d-block invalid-feedback" role="alert">
                                    <strong>{{ session('error') }}</strong>
                                </span>
                            @endif
                        </div>

                        <input type="hidden" name="password" value="password" />

                        <div class="mb-0 text-center">
                            <button type="submit" class="mt-4 custom-btn custom-btn-primary pulse-slow">
                                {{ __('LOGIN') }}
                            </button>
                        </div>
                    </form>
                </div>

                <div class="bottom-text">
                    <p class="already-register">Haven't Registered?</p>
                    <p class="already-register">
                        Click <a href="{{ route('register') }}">here</a> to register
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
