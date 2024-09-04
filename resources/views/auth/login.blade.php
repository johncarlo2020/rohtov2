<x-guest-layout>
    <div class="login-main">
        <div class="row justify-content-center">
            <div class="col-12 d-flex justify-content-center mt-5">
                @include('components.branding')
            </div>
            <div class="col-12 mt-5 px-5">
                <h1 class="login-heading">LOGIN</h1>
                <!-- Session Status -->
                <x-auth-session-status
                    class="mb-4"
                    :status="session('status')"
                />

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="row mb-2">
                        <div class="col-12">
                            <label class="text-left" for=""
                                >Email Address</label
                            >

                            <input
                                id="email"
                                placeholder="example@email.com"
                                type="email"
                                class="input-text form-control @error('email') is-invalid @enderror"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autocomplete="email"
                            />

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <!-- Password -->
                    <x-text-input
                        id="password"
                        class="block w-full mt-1"
                        type="hidden"
                        name="password"
                        value="password"
                        required
                        autocomplete="current-password"
                    />

                    <x-input-error
                        :messages="$errors->get('password')"
                        class="mt-2"
                    />

                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button class="main-btn btn btn-primary">
                            {{ __("LOGIN") }}
                        </x-primary-button>
                    </div>
                </form>
                <div class="bottom-text">
                    <p>
                        Don’t have account yet! Register
                        <a class="" href="{{ route('register') }}">
                            {{ __("REGISTER") }}
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
