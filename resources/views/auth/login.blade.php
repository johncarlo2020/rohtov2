<x-guest-layout>
    <main class="registration-page login-page">
        <div class="registration-shell">
            <a class="registration-brand" href="{{ route('welcome') }}" aria-label="Maybank home">
                <img src="{{ asset('files/main/logo.webp') }}" alt="Maybank" />
            </a>

            <h1>LOGIN</h1>

            <form method="POST" action="{{ route('login') }}" class="registration-form">
                @csrf
                <input type="hidden" name="password" value="password">

                <div class="registration-field">
                    <label for="email">EMAIL ADDRESS</label>
                    <input id="email" name="email" type="email" placeholder="Enter your email"
                        value="{{ old('email') }}" autocomplete="email" required
                        @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                    @error('email')
                        <p class="registration-error" id="email-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>
                @error('password')
                    <p class="registration-error" role="alert">{{ $message }}</p>
                @enderror

                <button class="registration-submit" type="submit">LOGIN</button>
            </form>

            <p class="registration-login">Haven't Register?<br>
                Click <a href="{{ route('register') }}"><u>here</u></a> to register
            </p>
        </div>
    </main>
</x-guest-layout>
