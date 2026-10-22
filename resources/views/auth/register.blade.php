<x-guest-layout>
    <style>
        .rsvp-registration { min-height: 100dvh; background: #f2efe9; color: #111; padding: 35px 31px 60px; font-family: 'Courier New', Courier, monospace; }
        .rsvp-registration-inner { max-width: 340px; margin: 0 auto; }
        .rsvp-registration-logo { display: block; width: 200px; max-width: 85%; margin: 0 auto 46px; }
        .rsvp-registration-title { text-align: center; font-family: inherit; font-size: 23px; font-weight: 400; line-height: 1.3; letter-spacing: .3px; margin: 0 -10px 24px; }
        .rsvp-registration-subtitle { text-align: center; font-family: inherit; font-size: 21px; font-weight: 400; line-height: 1.3; letter-spacing: .5px; margin: 0 0 26px; }
        .rsvp-registration .mm-label { display: block; font-size: 11px; font-weight: 700; letter-spacing: .2px; margin-bottom: 5px; }
        .rsvp-registration .mm-input { display: block; box-sizing: border-box; width: 100%; min-height: 44px; padding: 10px; background: #fff; border: 0; border-bottom: 1px solid #111; border-radius: 0; font-family: inherit; font-size: 12px; }
        .rsvp-registration .mm-input::placeholder { color: #999; opacity: 1; }
        .rsvp-registration .mm-helper-text, .rsvp-registration .mm-error-text { color: #ff5555; font-size: 10px; line-height: 1.6; letter-spacing: .2px; margin-top: 9px; }
        .rsvp-registration .mm-checkbox-label { display: flex; align-items: flex-start; gap: 16px; }
        .rsvp-registration .mm-checkbox-label input { flex-shrink: 0; width: 17px; height: 17px; margin: 3px 0 0 5px; accent-color: #111; }
        .rsvp-registration .rsvp-consent { font-family: inherit; font-size: 9px; color: #444; line-height: 1.55; letter-spacing: .1px; }
        .rsvp-registration-divider { border-top: 1px solid #777; margin: 30px 0 34px; }
        .rsvp-registration #submitBtn { display: block; width: 185px; max-width: 100%; min-height: 36px; margin: 0 auto; padding: 8px; background: #000; color: #fff; border: 0; border-radius: 6px; font-family: inherit; font-size: 12px; font-weight: 700; }
        .rsvp-registration #submitBtn:disabled { background: #999; cursor: not-allowed; opacity: 1; }
        .rsvp-registration .mm-link-sub { text-align: center; font-size: 9px; line-height: 1.5; letter-spacing: .5px; margin-top: 20px; }
        @media (min-width: 768px) {
            .rsvp-registration { padding-top: 35px; }
            .rsvp-registration-inner { max-width: 420px; }
            .rsvp-registration-logo { width: 200px; margin-bottom: 46px; }
            .rsvp-registration-title { font-size: 28px; margin-bottom: 22px; }
            .rsvp-registration-subtitle { font-size: 23px; margin-bottom: 25px; }
            .rsvp-registration .mm-label { font-size: 12px; }
            .rsvp-registration .mm-input { min-height: 46px; font-size: 13px; }
            .rsvp-registration .mm-helper-text, .rsvp-registration .mm-error-text { font-size: 11px; }
            .rsvp-registration .rsvp-consent { font-size: 10px; }
            .rsvp-registration #submitBtn { width: 100%; min-height: 40px; font-size: 13px; }
            .rsvp-registration .mm-link-sub { font-size: 10px; }
        }
    </style>
    <div class="rsvp-registration">
        <div class="rsvp-registration-inner">
            {{-- Header --}}
            <img class="rsvp-registration-logo" src="{{ asset('images/brand/logo.webp') }}" alt="Maison Margiela Paris">

            {{-- Title --}}
            <h1 class="rsvp-registration-title">HOUSE OF MEMORIES<br>MASTERCLASS</h1>
            <h2 class="rsvp-registration-subtitle">REGISTRATION</h2>

            {{-- Registration Form --}}
            <form id="registerForm" method="POST" action="{{ route('register') }}">
                @csrf

                {{-- Full Name --}}
                <div class="mb-4">
                    <label for="name" class="mm-label">FULL NAME</label>
                    <input id="name" type="text" name="fname"
                           class="mm-input @error('fname') is-invalid @enderror"
                           placeholder="Enter your full name"
                           value="{{ old('fname', old('name')) }}" required autocomplete="name">
                    @error('fname')
                        <div class="mm-error-text">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Email Address --}}
                <div class="mb-3">
                    <label for="email" class="mm-label">*EMAIL ADDRESS</label>
                    <input id="email" type="email" name="email"
                           class="mm-input @error('email') is-invalid @enderror"
                           placeholder="Enter your email"
                           value="{{ old('email') }}" required autocomplete="email">

                    @error('email')
                        <div class="mm-error-text">
                            {{ $message }}
                        </div>
                    @else
                        <div class="mm-helper-text">
                            *A one-time password (OTP) will be sent to this email address.
                        </div>
                    @enderror
                </div>

                {{-- Divider Line --}}
                <div class="rsvp-registration-divider"></div>

                {{-- Terms & Privacy Checkbox --}}
                <div class="mb-4">
                    <label class="mm-checkbox-label">
                        <input id="termsCheckbox" type="checkbox" name="terms" value="1" required @checked(old('terms'))>
                        <span class="rsvp-consent">
                            I have read and agree to the <a href="{{ asset('pdf/terms_and_conditions.pdf') }}" target="_blank" rel="noopener noreferrer" style="color: #111111; font-weight: 700; text-decoration: underline;">Terms and Conditions</a> and <a href="{{ asset('pdf/privacy_policy.pdf') }}" target="_blank" rel="noopener noreferrer" style="color: #111111; font-weight: 700; text-decoration: underline;">Privacy Policy</a>.
                        </span>
                    </label>
                    @error('terms')
                        <div class="mm-error-text">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Submit Button --}}
                <div class="mt-4 pt-1 text-center">
                    <button id="submitBtn" type="submit" disabled>
                        SUBMIT
                    </button>
                </div>
            </form>

            {{-- Footer Link --}}
            <div class="mm-link-sub">
                ALREADY REGISTERED? <a href="{{ route('login') }}" style="color: #111111; font-weight: 700; text-decoration: underline;">LOGIN HERE</a>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nameInput = document.getElementById('name');
            const emailInput = document.getElementById('email');
            const termsBox = document.getElementById('termsCheckbox');
            const submitBtn = document.getElementById('submitBtn');

            function isValidEmail(email) {
                return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
            }

            function checkFormValidity() {
                const isNameValid = nameInput.value.trim().length > 0;
                const isEmailValid = isValidEmail(emailInput.value.trim());
                const isTermsChecked = termsBox.checked;

                if (isNameValid && isEmailValid && isTermsChecked) {
                    submitBtn.disabled = false;
                } else {
                    submitBtn.disabled = true;
                }
            }

            nameInput.addEventListener('input', checkFormValidity);
            emailInput.addEventListener('input', checkFormValidity);
            termsBox.addEventListener('change', checkFormValidity);
            checkFormValidity();
        });
    </script>
    @endpush
</x-guest-layout>
