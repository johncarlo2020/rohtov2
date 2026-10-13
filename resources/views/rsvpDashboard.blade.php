<x-guest-layout>
    <style>
        .rsvp-dashboard { min-height: 100vh; background: #f2efe9; color: #111; font-family: 'Courier Prime', monospace; padding: 44px 30px 60px; }
        .rsvp-content { max-width: 360px; margin: 0 auto; }
        .rsvp-logo { display: block; width: 250px; max-width: 80%; margin: 0 auto 62px; }
        .rsvp-title { font-family: inherit; font-size: 32px; line-height: 1.25; font-weight: 400; text-align: center; margin: 0 -10px 43px; }
        .rsvp-greeting { font-family: inherit; font-size: 21px; font-weight: 400; text-align: center; margin-bottom: 20px; }
        .rsvp-intro { font-size: 12px; line-height: 1.7; text-align: center; margin-bottom: 20px; }
        .rsvp-notice { color: #ff5555; font-size: 12px; line-height: 1.8; }
        .rsvp-availability { text-align: center; margin-bottom: 36px; }
        .rsvp-label { display: block; font-size: 15px; font-weight: 700; letter-spacing: .5px; margin-bottom: 4px; }
        .rsvp-select { width: 100%; min-height: 55px; border: 0; border-bottom: 1px solid #111; border-radius: 0; background-color: #fff; color: #333; font-family: inherit; font-size: 14px; padding: 12px 14px; }
        .rsvp-select:focus-visible, .rsvp-next:focus-visible { outline: 2px solid #111; outline-offset: 4px; }
        .rsvp-time-label { margin-top: 25px; }
        .rsvp-time { font-size: 15px; padding: 17px 14px 0; margin-bottom: 27px; }
        .rsvp-next { display: block; width: 230px; max-width: 100%; margin: 28px auto 22px; padding: 12px; border: 0; border-radius: 8px; background: #000; color: #fff; font-family: inherit; font-size: 17px; font-weight: 700; }
        .rsvp-next:disabled { opacity: .4; cursor: not-allowed; }
        .rsvp-confirmation { width: calc(100% - 60px); max-width: 380px; padding: 52px 22px 35px; border: 0; border-radius: 7px; background: #f2efe9; color: #111; text-align: center; font-family: 'Courier Prime', monospace; }
        .rsvp-confirmation::backdrop { background: rgba(0, 0, 0, .53); }
        .rsvp-confirmation h2 { font-family: inherit; font-size: 24px; font-weight: 700; margin-bottom: 26px; }
        .rsvp-confirmation-intro { font-size: 14px; letter-spacing: 3px; line-height: 1.2; margin-bottom: 20px; }
        .rsvp-confirmation dl { font-size: 15px; letter-spacing: 3px; line-height: 1.2; margin: 0; }
        .rsvp-confirmation dt { font-weight: 400; }
        .rsvp-confirmation dd { font-weight: 700; margin: 0 0 18px; }
        .rsvp-back { background: transparent; border: 0; font-family: inherit; font-size: 13px; padding: 12px 20px; margin: 2px auto 10px; }
        .rsvp-confirmation .rsvp-next { margin: 4px auto 0; }
        .rsvp-terms { display: block; text-align: center; color: #111; text-decoration: none; font-size: 12px; font-weight: 700; letter-spacing: 1px; }
        @media (max-width: 767px) {
            .rsvp-intro { font-size: 16px; }
            .rsvp-notice { font-size: 15px; }
            .rsvp-label { font-size: 16px; }
            .rsvp-select, .rsvp-time { font-size: 16px; }
            .rsvp-terms { font-size: 14px; }
            .rsvp-confirmation h2 { font-size: 26px; }
            .rsvp-confirmation-intro, .rsvp-confirmation dl { font-size: 16px; letter-spacing: 1.5px; line-height: 1.5; }
            .rsvp-back { font-size: 15px; }
        }
        @media (max-width: 380px) { .rsvp-dashboard { padding-left: 20px; padding-right: 20px; } .rsvp-title { font-size: 27px; } }
        @media (min-width: 768px) {
            .rsvp-dashboard { min-height: 100dvh; padding: 35px 40px 50px; }
            .rsvp-content { max-width: 420px; }
            .rsvp-logo { width: 180px; margin-bottom: 40px; }
            .rsvp-title { font-size: 25px; margin: 0 0 25px; }
            .rsvp-greeting { font-size: 18px; margin-bottom: 14px; }
            .rsvp-availability { margin-bottom: 26px; }
            .rsvp-label { font-size: 13px; }
            .rsvp-select { min-height: 44px; font-size: 13px; }
            .rsvp-time-label { margin-top: 22px; }
            .rsvp-time { font-size: 13px; padding-top: 12px; margin-bottom: 22px; }
            .rsvp-next { width: 100%; padding: 10px; border-radius: 5px; font-size: 14px; margin-top: 24px; }
            .rsvp-confirmation { max-width: 480px; padding: 40px 40px 30px; max-height: calc(100dvh - 60px); overflow-y: auto; }
            .rsvp-confirmation-intro { letter-spacing: 1.5px; line-height: 1.5; }
            .rsvp-confirmation dl { letter-spacing: 1.5px; line-height: 1.5; }
            .rsvp-confirmation .rsvp-next { width: 260px; }
        }
    </style>

    @php
        $hasAvailableDates = $bookingDates->contains(fn ($date) => $date->remaining_slots > 0);
    @endphp
    <div class="rsvp-dashboard">
        <div class="rsvp-content">
            <img class="rsvp-logo" src="{{ asset('images/brand/logo.webp') }}" alt="Maison Margiela Paris">
            <h1 class="rsvp-title">HOUSE OF MEMORIES<br>MASTERCLASS</h1>
            <h2 class="rsvp-greeting">Hi {{ Auth::user()->fname ?? Auth::user()->name ?? 'Guest' }},</h2>
            <p class="rsvp-intro">Choose your preferred date to join the<br>masterclass.</p>
            <p class="rsvp-notice rsvp-availability">*Subjected to availability</p>

            <form id="rsvp-form" method="POST" action="{{ route('rsvp.store') }}">
                @csrf
                @error('date')
                    <p class="rsvp-notice" role="alert">{{ $message }}</p>
                @enderror
                <label class="rsvp-label" for="rsvp-date">Dates Available:</label>
                <select class="rsvp-select" id="rsvp-date" name="date" required @disabled(!$hasAvailableDates)>
                    @if (!$hasAvailableDates)
                        <option value="" selected>No dates available</option>
                    @endif
                    @foreach ($bookingDates as $bookingDate)
                        <option value="{{ $bookingDate->date->format('Y-m-d') }}" data-display-date="{{ $bookingDate->date->format('l, jS F Y') }}" @disabled($bookingDate->remaining_slots === 0)>
                            {{ $bookingDate->date->format('jS F') }} — {{ $bookingDate->remaining_slots }} {{ $bookingDate->remaining_slots === 1 ? 'slot' : 'slots' }} available
                        </option>
                    @endforeach
                </select>
                <p class="rsvp-label rsvp-time-label">Time Slots:</p>
                <p class="rsvp-time">2:00PM - 3:00PM</p>
                <p class="rsvp-notice">*Please arrive at least 30 minutes before your scheduled masterclass session.</p>
                <button class="rsvp-next" type="submit" @disabled(!$hasAvailableDates)>NEXT</button>
            </form>
            <a class="rsvp-terms" href="{{ asset('pdf/terms_and_conditions.pdf') }}" target="_blank" rel="noopener noreferrer">TERM &amp; CONDITIONS</a>
        </div>
    </div>

    <dialog class="rsvp-confirmation" id="rsvp-confirmation" aria-labelledby="rsvp-confirmation-title" aria-describedby="rsvp-confirmation-intro">
        <h2 id="rsvp-confirmation-title">YOUR MOMENT AWAITS</h2>
        <p class="rsvp-confirmation-intro" id="rsvp-confirmation-intro">Please review the details of your visit before confirming your masterclass.</p>
        <dl>
            <dt>Date:</dt>
            <dd id="rsvp-confirmation-date"></dd>
            <dt>Time:</dt>
            <dd>2:00PM - 3:00PM</dd>
            <dt>Venue:</dt>
            <dd>1278 Songwat Road, Bangkok</dd>
        </dl>
        <button type="button" class="rsvp-back" id="rsvp-back" autofocus>BACK</button>
        <button type="button" class="rsvp-next" id="rsvp-confirm">CONFIRM MY VISIT</button>
    </dialog>

    @push('scripts')
        <script>
            (() => {
                const form = document.getElementById('rsvp-form');
                const dateSelect = document.getElementById('rsvp-date');
                const modal = document.getElementById('rsvp-confirmation');

                form.addEventListener('submit', (event) => {
                    event.preventDefault();
                    const selected = dateSelect.selectedOptions[0];
                    if (!selected || selected.disabled || !selected.value) return;
                    document.getElementById('rsvp-confirmation-date').textContent = selected.dataset.displayDate;
                    modal.showModal();
                });

                document.getElementById('rsvp-back').addEventListener('click', () => modal.close());
                document.getElementById('rsvp-confirm').addEventListener('click', () => {
                    if (form.reportValidity()) {
                        document.getElementById('rsvp-confirm').disabled = true;
                        form.submit();
                    }
                });
            })();
        </script>
    @endpush
</x-guest-layout>
