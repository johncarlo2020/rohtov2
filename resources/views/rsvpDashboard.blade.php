<x-guest-layout>
    <style>
        .rsvp-dashboard { min-height: 100dvh; background: #f2efe9; color: #111; font-family: 'Courier Prime', monospace; padding: 35px 31px 60px; }
        .rsvp-content { max-width: 340px; margin: 0 auto; }
        .rsvp-logo { display: block; width: 200px; max-width: 85%; margin: 0 auto 46px; }
        .rsvp-title { font-family: inherit; font-size: 23px; line-height: 1.3; letter-spacing: .3px; font-weight: 400; text-align: center; margin: 0 -10px 24px; }
        .rsvp-greeting { font-family: inherit; font-size: 15px; line-height: 1.4; font-weight: 400; text-align: center; margin: 0 0 12px; }
        .rsvp-intro { font-size: 11px; line-height: 1.6; letter-spacing: .1px; text-align: center; margin-bottom: 12px; }
        .rsvp-notice { color: #ff5555; font-size: 10px; line-height: 1.6; letter-spacing: .1px; }
        .rsvp-availability { text-align: center; margin-bottom: 26px; }
        .rsvp-label { display: block; font-size: 11px; font-weight: 700; letter-spacing: .2px; margin-bottom: 5px; }
        .rsvp-select { width: 100%; min-height: 44px; border: 0; border-bottom: 1px solid #111; border-radius: 0; background-color: #fff; color: #333; font-family: inherit; font-size: 12px; padding: 10px; }
        .rsvp-select:focus-visible, .rsvp-next:focus-visible { outline: 2px solid #111; outline-offset: 4px; }
        .rsvp-time-label { margin-top: 20px; }
        .rsvp-time { font-size: 12px; padding: 12px 10px 0; margin-bottom: 20px; }
        .rsvp-next { display: block; width: 185px; max-width: 100%; min-height: 36px; margin: 22px auto 20px; padding: 8px; border: 0; border-radius: 6px; background: #000; color: #fff; font-family: inherit; font-size: 12px; font-weight: 700; }
        .rsvp-next:disabled { opacity: .4; cursor: not-allowed; }
        .rsvp-confirmation { box-sizing: border-box; width: min(340px, calc(100vw - 32px)); max-width: 100%; max-height: calc(100dvh - 32px); overflow-y: auto; padding: 25px 18px 18px; border: 0; border-radius: 5px; background: #f2efe9; color: #111; text-align: center; font-family: 'Courier Prime', monospace; }
        .rsvp-confirmation::backdrop { background: rgba(0, 0, 0, .53); }
        .rsvp-confirmation h2 { font-family: inherit; font-size: 12px; font-weight: 700; letter-spacing: 1.4px; line-height: 1.4; margin: 0 0 15px; }
        .rsvp-confirmation-intro { font-size: 8px; letter-spacing: 1.2px; line-height: 1.35; text-transform: uppercase; margin: 0 0 12px; }
        .rsvp-confirmation dl { font-size: 8px; letter-spacing: 1.2px; line-height: 1.4; text-transform: uppercase; margin: 0; }
        .rsvp-confirmation dt { font-weight: 400; }
        .rsvp-confirmation dd { font-weight: 700; margin: 0 0 9px; }
        .rsvp-back { background: transparent; border: 0; font-family: inherit; font-size: 7px; letter-spacing: .5px; padding: 5px 12px; margin: 1px auto 8px; }
        .rsvp-confirmation .rsvp-next { width: 125px; min-height: 24px; height: auto; margin: 4px auto 0; padding: 6px 8px; border-radius: 4px; font-size: 8px; letter-spacing: .3px; }
        .rsvp-terms { display: block; text-align: center; color: #111; text-decoration: none; font-size: 9px; font-weight: 700; letter-spacing: .5px; }
        @media (max-width: 380px) { .rsvp-dashboard { padding-left: 20px; padding-right: 20px; } }
        @media (min-width: 768px) {
            .rsvp-dashboard { min-height: 100dvh; padding: 35px 40px 50px; }
            .rsvp-content { max-width: 420px; }
            .rsvp-logo { width: 200px; margin-bottom: 46px; }
            .rsvp-title { font-size: 28px; margin: 0 0 22px; }
            .rsvp-greeting { font-size: 17px; margin-bottom: 14px; }
            .rsvp-intro { font-size: 12px; }
            .rsvp-notice { font-size: 11px; }
            .rsvp-availability { margin-bottom: 24px; }
            .rsvp-label { font-size: 12px; }
            .rsvp-select { min-height: 46px; font-size: 13px; }
            .rsvp-time-label { margin-top: 20px; }
            .rsvp-time { font-size: 13px; padding-top: 12px; margin-bottom: 20px; }
            .rsvp-next { width: 100%; min-height: 40px; padding: 10px; border-radius: 5px; font-size: 13px; margin-top: 22px; }
            .rsvp-confirmation { width: min(360px, calc(100vw - 48px)); max-height: calc(100dvh - 48px); padding: 30px 24px 22px; }
            .rsvp-confirmation h2 { font-size: 13px; }
            .rsvp-confirmation-intro, .rsvp-confirmation dl { font-size: 9px; }
            .rsvp-back { font-size: 8px; }
            .rsvp-terms { font-size: 10px; }
        }
    </style>

    @php
        $availableBookingDates = $bookingDates->filter(fn ($date) => $date->remaining_slots > 0);
        $hasAvailableDates = $availableBookingDates->isNotEmpty();
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
                    @foreach ($availableBookingDates as $bookingDate)
                        <option value="{{ $bookingDate->date->format('Y-m-d') }}" data-display-date="{{ $bookingDate->date->format('l, jS F Y') }}">
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
