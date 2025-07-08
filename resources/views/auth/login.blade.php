<x-guest-layout>
    <div class="login-page">
        <div class="main-content with-scroll">
            <div class="col-12 d-flex justify-content-center animate-entry ">
                @include('components.branding')
            </div>
            <div class="col-12 animate-entry delay-2">
                <h1 class="heading mb-2">LOG IN</h1>
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="text-left mb-2" for="">Email</label>

                            <input id="email" placeholder="example@email.com" type="email"
                                class="input-text form-control @error('email') is-invalid @enderror" name="email"
                                value="{{ old('email') }}" required autocomplete="email" />

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <!-- Password -->
                    <x-text-input id="password" class="block w-full mt-1" type="hidden" name="password"
                        value="password" required autocomplete="current-password" />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />

                    <div class="d-flex justify-center">
                        <x-primary-button class="custom-btn custom-btn-secondary w-100">
                            {{ __('LOGIN') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
            <div class="text-center register-text animate-entry delay-3">
                        <p class="d-block">Haven't register yet?</p>
                        <p>Sign up <a href="{{ route('register') }}">here</a></p>
            </div>
        </div>
    </div>
</x-guest-layout>
