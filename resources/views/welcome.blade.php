<x-guest-layout>
    <main class="participant-welcome">
        <div class="participant-welcome-shell">
            <img class="participant-welcome-logo" src="{{ asset('files/main/logo-space.svg') }}" alt="" aria-hidden="true" />
            <h1>DISCOVER <strong>MORE</strong></h1>
            <p>Your journey to more<br>starts here.</p>
            <p>Explore the experiences,<br>collect digital stamps and<br>unlock rewards along the way.</p>
            <div class="participant-welcome-actions">
                <a href="{{ route('register') }}">REGISTER</a>
                <a href="{{ route('login') }}" class="is-outlined">LOGIN</a>
            </div>
        </div>
    </main>
</x-guest-layout>
