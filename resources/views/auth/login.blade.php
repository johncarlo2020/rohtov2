<x-guest-layout>
    <div class="login-page vh-100">
        <div class="main-content main-background with-scroll">
            <div class="col-12 d-flex justify-content-center animate-entry ">
                @include('components.branding')
            </div>
            <div class="col-12 animate-entry delay-2 bg-white p-3" style="margin-top:13vh;margin-bottom:20vh;">
                <h1 class="heading mb-2 text-dark">LOG IN</h1>
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />
                <form method="POST" action="{{ route('login') }}" >
                    @csrf
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="text-left text-dark mb-2" for="email">Email Address</label>

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
                        <x-primary-button class="custom-btn custom-btn-primary w-100">
                            {{ __('LOG IN') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
             <div class="bottom-text text-center">
                    <p class="already-register text-white">
                        <strong>Haven't register yet?</strong>
                    </p>
                    <p class="already-register text-white">
                        <a href="{{ route('register') }}" class="text-white"><strong>Sign Up</strong></a>
                    </p>
                </div>
            <x-footer/>
        </div>
    </div>
</x-guest-layout>
