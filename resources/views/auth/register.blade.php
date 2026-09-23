<x-guest-layout>
    <main class="registration-page">
        <div class="registration-shell">

            <div class="registration-brand" aria-hidden="true">
                <img src="{{ asset('files/main/logo-space.svg') }}" alt="" aria-hidden="true" />
            </div>
            <h1>SIGN UP</h1>

            <form method="POST" action="{{ route('register') }}" class="registration-form">
                @csrf
                <input type="hidden" name="utm_source" value="{{ session('utm.source') }}">
                <input type="hidden" name="utm_medium" value="{{ session('utm.medium') }}">

                <div class="registration-field">
                    <label for="full_name">FULL NAME</label>
                    <input id="full_name" name="full_name" type="text" placeholder="Enter your full name"
                        value="{{ old('full_name') }}" autocomplete="name" maxlength="255" required
                        @error('full_name') aria-invalid="true" aria-describedby="full_name-error" @enderror>
                    @error('full_name') <p class="registration-error" id="full_name-error" role="alert">{{ $message }}</p> @enderror
                </div>

                <div class="registration-field">
                    <label for="email">EMAIL ADDRESS</label>
                    <input id="email" name="email" type="email" placeholder="Enter your email"
                        value="{{ old('email') }}" autocomplete="email" required
                        @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                    @error('email') <p class="registration-error" id="email-error" role="alert">{{ $message }}</p> @enderror
                </div>

                <div class="registration-field">
                    <label for="number">CONTACT NUMBER</label>
                    <div class="registration-phone">
                        <span class="registration-flag" aria-label="Malaysia">🇲🇾</span>
                        <input id="number" name="number" type="tel" placeholder="+60 12-3739 1590"
                            value="{{ old('number') }}" autocomplete="tel" required
                            @error('number') aria-invalid="true" aria-describedby="number-error" @enderror>
                    </div>
                    @error('number') <p class="registration-error" id="number-error" role="alert">{{ $message }}</p> @enderror
                </div>

                <div class="registration-consents">
                    <label class="registration-check" for="terms">
                        <input id="terms" name="terms" type="checkbox" value="1" @checked(old('terms')) required>
                        <span>I have read and agree to the
                            <a href="{{ asset('files/Maybank_Your_Journey_for_More_PDPA_Revised.pdf') }}" target="_blank" rel="noopener noreferrer">Terms and Conditions</a>
                            and <a href="#" onclick="event.preventDefault()">Privacy Policy</a>.
                        </span>
                    </label>
                    @error('terms') <p class="registration-error" role="alert">{{ $message }}</p> @enderror

                    <label class="registration-check" for="marketing">
                        <input id="marketing" name="marketing" type="checkbox" value="1" @checked(old('marketing'))>
                        <span>I agree to receive marketing communications from the event organizer.</span>
                    </label>

                    <label class="registration-check" for="age_confirmed">
                        <input id="age_confirmed" name="age_confirmed" type="checkbox" value="1" @checked(old('age_confirmed')) required>
                        <span>Are you 21 and above?</span>
                    </label>
                    @error('age_confirmed') <p class="registration-error" role="alert">{{ $message }}</p> @enderror
                </div>

                <button class="registration-submit" type="submit">SUBMIT</button>
            </form>

            <p class="registration-login">Registered?<br>
                <a href="{{ route('login') }}">Please login <u>here</u></a>
            </p>
        </div>
    </main>
</x-guest-layout>
