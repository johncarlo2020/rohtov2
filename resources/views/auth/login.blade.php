<x-guest-layout>
    <div class="login">
        <h1>LOGIN</h1>
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block w-full mt-1" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <x-text-input id="password" class="block w-full mt-1"
                                type="hidden"
                                name="password"
                                value="password"
                                required autocomplete="current-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />

            <div class="flex items-center justify-end mt-4">
                <x-primary-button class="button">
                    {{ __('LOGIN') }}
                </x-primary-button>
            </div>
        </form>
        <div class="bottom-text">
            <p>Don’t have account yet! Register <a class="" href="{{ route('register') }}">
                {{ __('REGISTER') }}
            </a></p>
        </div>
    </div>
</x-guest-layout>
