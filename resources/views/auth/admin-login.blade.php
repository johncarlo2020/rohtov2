<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin sign in | Maybank</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @include('components.fonts')
</head>
<body class="admin-signin">
    <main class="admin-signin-shell">
        <section class="admin-signin-brand" aria-label="Maybank event administration">
            <a href="{{ url('/') }}" class="admin-signin-logo" aria-label="Maybank home">
                <img src="{{ asset('files/main/logo.webp') }}" alt="Maybank" width="165" />
            </a>
            <div class="admin-signin-story">
                <p class="admin-signin-eyebrow">THE EXPERIENCE STARTS HERE</p>
                <h1>Behind every <br>journey.<br><span>More possibilities.</span></h1>
                <p>Bring the Maybank experience to life.<br>Your event, all in one place.</p>
                <img class="admin-signin-map" src="{{ asset('files/main/map.webp') }}" alt="" />
            </div>
            <div class="admin-signin-brand-footer"><span></span> MAYBANK EVENT MANAGEMENT</div>
        </section>

        <section class="admin-signin-form-panel" aria-labelledby="admin-signin-title">
            <div class="admin-signin-form-wrap">
                <span class="admin-signin-badge">ADMIN PORTAL</span>
                <h2 id="admin-signin-title">Welcome back.</h2>
                <p class="admin-signin-intro">Sign in to manage your event experience.</p>

                @if ($errors->any())
                    <div class="admin-signin-error" role="alert">{{ $errors->first() }}</div>
                @endif
                @if (session('status'))
                    <p role="status">{{ session('status') }}</p>
                @endif

                <form method="POST" action="{{ route('authenticateAdmin') }}">
                    @csrf
                    <div class="admin-signin-field">
                        <label for="admin-email">Email address</label>
                        <input id="admin-email" type="email" name="email" value="{{ old('email') }}"
                            placeholder="Enter your admin email" autocomplete="username" required
                            @if ($errors->has('email')) aria-invalid="true" @endif />
                    </div>
                    <div class="admin-signin-field" x-data="{ showPassword: false }">
                        <label for="admin-password">Password</label>
                        <div class="admin-signin-password">
                            <input id="admin-password" type="password" :type="showPassword ? 'text' : 'password'"
                                name="password" placeholder="Enter your password" autocomplete="current-password" required
                                @if ($errors->has('password')) aria-invalid="true" @endif />
                            <button type="button" @click="showPassword = !showPassword" :aria-pressed="showPassword.toString()"
                                aria-controls="admin-password" x-text="showPassword ? 'Hide' : 'Show'">Show</button>
                        </div>
                    </div>
                    <button class="admin-signin-submit" type="submit">Sign in <span aria-hidden="true">→</span></button>
                </form>
                <p class="admin-signin-help">Need access? Contact your event administrator.</p>
            </div>
            <footer class="admin-signin-footer">© {{ date('Y') }} Maybank. <span>Event administration</span></footer>
        </section>
    </main>
    <script>
        // Remove credentials saved by the previous login page.
        ['email', 'password'].forEach(name => {
            document.cookie = `${name}=; Max-Age=0; path=/`;
        });
    </script>
</body>
</html>
