<x-guest-layout>
    <style>
        .welcome-rsvp { display: grid; grid-template-areas: 'header' 'image' 'content'; background: #f2efe9; color: #111; min-height: 100dvh; font-family: 'Courier New', Courier, monospace; text-align: center; }
        .welcome-rsvp-header { grid-area: header; padding: 35px 20px 18px; }
        .welcome-rsvp-logo { display: block; width: 220px; max-width: 80%; margin: 0 auto 46px; }
        .welcome-rsvp-title { margin: 0; font-family: inherit; font-size: clamp(23px, 6.8vw, 32px); line-height: 1.3; font-weight: 400; }
        .welcome-rsvp-image { grid-area: image; display: block; width: 100%; height: auto; aspect-ratio: 1.27; object-fit: cover; }
        .welcome-rsvp-content { grid-area: content; padding: 22px 25px 60px; }
        .welcome-rsvp-copy { max-width: 330px; margin: 0 auto; }
        .welcome-rsvp-heading { font-family: inherit; font-weight: 700; font-size: 12px; line-height: 1.4; margin: 0 0 10px; }
        .welcome-rsvp-copy p { font-family: inherit; font-size: 10px; line-height: 1.15; letter-spacing: .2px; margin: 0 0 13px; }
        .welcome-rsvp-signup { display: flex; align-items: center; justify-content: center; width: 176px; min-height: 35px; margin: 22px auto 0; padding: 8px 12px; border-radius: 5px; background: #000; color: #fff; font-family: inherit; font-size: 13px; font-weight: 700; text-decoration: none; }
        .welcome-rsvp-signup:hover { color: #fff; background: #222; }
        .welcome-rsvp-signup:focus-visible { outline: 2px solid #111; outline-offset: 4px; }
        @media (min-width: 768px) {
            .welcome-rsvp { grid-template-columns: 1fr 1fr; grid-template-rows: auto 1fr; grid-template-areas: 'image header' 'image content'; }
            .welcome-rsvp-image { height: 100%; min-height: 100dvh; aspect-ratio: auto; }
            .welcome-rsvp-header { padding: clamp(35px, 6vh, 70px) 30px 0; }
            .welcome-rsvp-logo { width: 220px; margin-bottom: clamp(45px, 8vh, 90px); }
            .welcome-rsvp-title { font-size: clamp(24px, 2.4vw, 36px); }
            .welcome-rsvp-content { padding: 36px 30px 60px; }
            .welcome-rsvp-copy { max-width: 400px; }
            .welcome-rsvp-heading { font-size: 15px; margin-bottom: 16px; }
            .welcome-rsvp-copy p { font-size: 12px; line-height: 1.25; margin-bottom: 18px; }
            .welcome-rsvp-signup { margin-top: 40px; }
        }
    </style>
    <div class="welcome-rsvp">
        <header class="welcome-rsvp-header">
            <img class="welcome-rsvp-logo" src="{{ asset('images/brand/logo.webp') }}" alt="Maison Margiela Paris">
            <h1 class="welcome-rsvp-title">HOUSE OF MEMORIES<br>MASTERCLASS</h1>
        </header>
        <img class="welcome-rsvp-image" src="{{ asset('RSVP_landing_2x.webp') }}" alt="REPLICA fragrance jar beside a white mortar and pestle" fetchpriority="high">
        <section class="welcome-rsvp-content" aria-labelledby="welcome-rsvp-heading">
            <div class="welcome-rsvp-copy">
                <h2 class="welcome-rsvp-heading" id="welcome-rsvp-heading">An exploration of scent and memory.</h2>
                <p>The familiarity of a scent.<br>The intimacy of a memory.</p>
                <p>Visit the House of Memories, where scents evoke moments once lived.</p>
                <p>Sign up for an exclusive masterclass to explore aromatic botanical and compose your own personalised herbal inhaler — a memory made tangible through scent.</p>
                <a class="welcome-rsvp-signup" href="{{ route('register') }}">SIGN-UP NOW</a>
            </div>
        </section>
    </div>
</x-guest-layout>
