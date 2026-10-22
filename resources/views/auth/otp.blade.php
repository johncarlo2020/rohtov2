<x-guest-layout>
    <style>
        .rsvp-otp-page { min-height: 100dvh; background: #f2efe9; color: #111; padding: 35px 31px 60px; font-family: 'Courier Prime', monospace; }
        .rsvp-otp-page .mm-mobile-container { max-width: 420px; min-height: auto; padding: 0; background: transparent; }
        .rsvp-otp-page .branding { text-align: center; margin: 0 auto 40px; padding: 0; }
        .rsvp-otp-page .branding .logo { display: block; width: 200px; max-width: 85%; height: auto; margin: 0 auto; }
        .rsvp-otp-page .mm-page-title { font-family: inherit; font-size: 23px !important; font-weight: 400; letter-spacing: .3px; line-height: 1.3; margin: 0 0 24px !important; text-align: center; }
        .rsvp-otp-page .rsvp-otp-label { font-family: inherit !important; font-size: 11px !important; letter-spacing: .5px !important; line-height: 1.4; margin-bottom: 8px !important; }
        .rsvp-otp-page .rsvp-otp-copy { font-family: inherit !important; font-size: 10px !important; line-height: 1.6 !important; letter-spacing: .1px; margin: 0 0 8px !important; padding: 0 4px !important; }
        .rsvp-otp-page .rsvp-otp-help { font-family: inherit !important; font-size: 10px !important; line-height: 1.6 !important; letter-spacing: .1px; margin: 0 0 18px !important; }
        .rsvp-otp-page .rsvp-otp-status, .rsvp-otp-page .mm-error-text { font-size: 10px !important; line-height: 1.6; }
        .rsvp-otp-page .rsvp-otp-timer, .rsvp-otp-page .rsvp-otp-resend { font-family: inherit !important; font-size: 10px !important; line-height: 1.5; }
        .rsvp-otp-page .rsvp-otp-back { font-family: inherit !important; font-size: 10px !important; line-height: 1.5; }
        .rsvp-otp-page .mm-otp-inputs { display: flex; justify-content: center; gap: 9px; margin: 24px 0 20px; }
        .rsvp-otp-page .mm-otp-digit { box-sizing: border-box; width: 40px; height: 46px; border: 0; border-bottom: 1px solid #111; border-radius: 0; background: #fff; font-family: inherit; font-size: 20px; text-align: center; }
        .rsvp-otp-page #verifyOtpBtn { width: 185px; max-width: 100%; min-height: 36px !important; height: auto !important; margin: 0 auto; padding: 8px !important; border-radius: 6px !important; font-family: inherit !important; font-size: 12px !important; letter-spacing: .5px; }
        .rsvp-otp-page #verifyOtpBtn:disabled { background: #999; opacity: 1; }
        @media (max-width: 767px) {
            .rsvp-otp-page .mm-mobile-container { width: 100%; }
        }
        @media (min-width: 768px) {
            .rsvp-otp-page { min-height: 100dvh; background: #f2efe9; padding: 35px 40px 50px; }
            .rsvp-otp-page .mm-mobile-container { max-width: 420px; min-height: auto; padding: 0; background: transparent; }
            .rsvp-otp-page .branding .logo { width: 200px; max-width: 100%; }
            .rsvp-otp-page .mm-page-title { font-size: 28px !important; margin-bottom: 22px !important; }
            .rsvp-otp-page .rsvp-otp-label { font-size: 12px !important; }
            .rsvp-otp-page .rsvp-otp-copy, .rsvp-otp-page .rsvp-otp-help { font-size: 11px !important; }
            .rsvp-otp-page .rsvp-otp-status, .rsvp-otp-page .mm-error-text { font-size: 11px !important; }
            .rsvp-otp-page .rsvp-otp-timer, .rsvp-otp-page .rsvp-otp-resend, .rsvp-otp-page .rsvp-otp-back { font-size: 11px !important; }
            .rsvp-otp-page .mm-otp-inputs { gap: 10px; margin: 26px 0 22px; }
            .rsvp-otp-page .mm-otp-digit { width: 44px; height: 48px; font-size: 22px; }
            .rsvp-otp-page #verifyOtpBtn { width: 100%; min-height: 40px !important; font-size: 13px !important; }
        }
    </style>
    <div class="rsvp-otp-page">
    <div class="mm-mobile-container">
        <div>
            {{-- Header --}}
            @include('components.branding')

            {{-- Title --}}
            <h1 class="mm-page-title">
                VERIFY YOUR EMAIL
            </h1>

            {{-- Subtitle --}}
            <div class="text-center mb-2">
                <div class="rsvp-otp-label" style="font-family: 'Courier Prime', monospace; font-size: 0.85rem; letter-spacing: 2px; text-transform: uppercase; font-weight: 700; color: #333333; margin-bottom: 0.5rem;">
                    ENTER YOUR OTP
                </div>
                <p class="rsvp-otp-copy" style="font-family: 'Courier Prime', monospace; font-size: 0.75rem; line-height: 1.5; color: #444444; margin-bottom: 0.35rem; padding: 0 0.5rem;">
                    An OTP (One Time Passcode) has been sent to <strong style="color: #000000;">{{ session('email') ?? session('otp_email') ?? 'joshuanick@gmail.com' }}</strong>.
                </p>
                <p class="rsvp-otp-help" style="font-family: 'Courier Prime', monospace; font-size: 0.725rem; line-height: 1.4; color: #666666; margin-bottom: 0.75rem;">
                    Please enter the OTP below to verify your contact details.
                </p>
            </div>

            {{-- OTP Form --}}
            <form id="otpForm" method="POST" action="{{ route('verify.otp') }}">
                @csrf

                @if(session('error'))
                    <div class="mm-error-text text-center mb-3">{{ session('error') }}</div>
                @endif
                @if(session('success'))
                    <div class="rsvp-otp-status" style="font-family: 'Courier Prime', monospace; font-size: 0.75rem; color: #2E7D32; text-align: center; margin-bottom: 1rem;">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Hidden input for full OTP --}}
                <input type="hidden" name="otp" id="fullOtpInput" required>

                {{-- 6-Digit OTP Inputs --}}
                <div class="mm-otp-inputs">
                    <input type="text" maxlength="1" class="mm-otp-digit" data-index="0" inputmode="numeric" autofocus pattern="[0-9]*">
                    <input type="text" maxlength="1" class="mm-otp-digit" data-index="1" inputmode="numeric" pattern="[0-9]*">
                    <input type="text" maxlength="1" class="mm-otp-digit" data-index="2" inputmode="numeric" pattern="[0-9]*">
                    <input type="text" maxlength="1" class="mm-otp-digit" data-index="3" inputmode="numeric" pattern="[0-9]*">
                    <input type="text" maxlength="1" class="mm-otp-digit" data-index="4" inputmode="numeric" pattern="[0-9]*">
                    <input type="text" maxlength="1" class="mm-otp-digit" data-index="5" inputmode="numeric" pattern="[0-9]*">
                </div>

                {{-- Timer & Resend --}}
                <div class="text-center mb-4">
                    <span id="resendContainer" class="rsvp-otp-timer" style="font-family: 'Courier Prime', monospace; font-size: 0.725rem; color: #555555;">
                        Resend OTP in <span id="timer" style="color: #111111; font-weight: 700;">60</span>s
                    </span>
                    <a id="resendBtn" href="{{ route('resend.otp') }}" class="d-none rsvp-otp-resend" style="font-family: 'Courier Prime', monospace; font-size: 0.725rem; color: #000000; font-weight: 700; text-decoration: underline;">
                        Resend OTP Now
                    </a>
                </div>

                {{-- Submit Button --}}
                <div class="mt-4">
                    <button id="verifyOtpBtn" type="submit" class="mm-btn-black" disabled>
                        VERIFY OTP
                    </button>
                </div>
            </form>

            {{-- Back button --}}
            <div class="text-center mt-3">
                <a class="rsvp-otp-back" href="{{ route('register') }}" style="font-family: 'Courier Prime', monospace; font-size: 0.75rem; color: #444444; text-decoration: none;">
                    Back
                </a>
            </div>
        </div>
    </div>

    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const digits = document.querySelectorAll('.mm-otp-digit');
            const hiddenInput = document.getElementById('fullOtpInput');
            const verifyBtn = document.getElementById('verifyOtpBtn');
            const otpForm = document.getElementById('otpForm');

            function updateHiddenInput() {
                let code = '';
                digits.forEach(d => code += d.value.trim());
                hiddenInput.value = code;
                if (code.length === 6) {
                    verifyBtn.disabled = false;
                } else {
                    verifyBtn.disabled = true;
                }
            }

            digits.forEach((digit, index) => {
                digit.addEventListener('input', function (e) {
                    if (this.value.length === 1) {
                        if (index < digits.length - 1) {
                            digits[index + 1].focus();
                        }
                    }
                    updateHiddenInput();
                });

                digit.addEventListener('keydown', function (e) {
                    if (e.key === 'Backspace' && !this.value && index > 0) {
                        digits[index - 1].focus();
                    }
                });

                digit.addEventListener('paste', function (e) {
                    e.preventDefault();
                    const pasted = (e.clipboardData || window.clipboardData).getData('text').trim();
                    if (/^\d{6}$/.test(pasted)) {
                        pasted.split('').forEach((char, i) => {
                            if (digits[i]) digits[i].value = char;
                        });
                        updateHiddenInput();
                        digits[5].focus();
                    }
                });
            });

            // Countdown timer for 60s
            let seconds = 60;
            const timerEl = document.getElementById('timer');
            const resendContainer = document.getElementById('resendContainer');
            const resendBtn = document.getElementById('resendBtn');

            const countdown = setInterval(function () {
                seconds--;
                if (timerEl) timerEl.textContent = seconds;
                if (seconds <= 0) {
                    clearInterval(countdown);
                    if (resendContainer) resendContainer.classList.add('d-none');
                    if (resendBtn) resendBtn.classList.remove('d-none');
                }
            }, 1000);
        });
    </script>
    @endpush
</x-guest-layout>
