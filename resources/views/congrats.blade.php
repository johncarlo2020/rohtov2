<x-app-layout>
    <main class="journey-complete">
        <img class="journey-complete-logo" src="{{ asset('files/main/logo-space.svg') }}" alt="" aria-hidden="true" />
        <h1>DISCOVER <strong>MORE</strong></h1>
        <p class="journey-complete-message">Congratulations!<br>You’ve completed the journey.</p>
        <a class="journey-complete-continue" href="{{ route('map') }}">CONTINUE JOURNEY</a>
    </main>
</x-app-layout>
