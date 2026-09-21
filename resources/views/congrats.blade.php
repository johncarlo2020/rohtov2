<x-app-layout>
    <main class="journey-complete">
        <img class="journey-complete-logo" src="{{ asset('files/main/logo.webp') }}" alt="Maybank" />
        <h1>DISCOVER <strong>MORE</strong></h1>
        <p class="journey-complete-message">Congratulations!<br>You’ve completed the journey.</p>
        <figure>
            <img src="{{ asset('files/main/maybank_card.webp') }}" alt="Maybank Cash Back Mastercard Platinum" />
            <figcaption>Maybank Cash Back<br>Mastercard Platinum</figcaption>
        </figure>
        <a class="journey-complete-continue" href="{{ route('map') }}">CONTINUE JOURNEY</a>
    </main>
</x-app-layout>
