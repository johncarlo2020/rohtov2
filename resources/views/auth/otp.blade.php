<x-guest-layout>
    <div class="mm-mobile-container justify-content-between">
        <div>
            {{-- Header --}}
            @include('components.branding')

            {{-- Title --}}
            <h1 class="mm-page-title">
                VERIFY YOUR EMAIL
            </h1>

            {{-- Subtitle --}}
            <div class="text-center mb-3">
                <div style="font-family: 'Courier Prime', monospace; font-size: 0.85rem; letter-spacing: 2px; text-transform: uppercase; font-weight: 700; color: #333333; margin-bottom: 0.75rem;">
                    ENTER YOUR OTP
                </div>
                <p style="font-family: 'Courier Prime', monospace; font-size: 0.75rem; line-height: 1.5; color: #444444; margin-bottom: 0.5rem; padding: 0 0.5rem;">
                    An OTP (One Time Passcode) has been sent to <strong style="color: #000000;">{{ session('email') ?? session('otp_email') ?? 'joshuanick@gmail.com' }}</strong>.
                </p>
                <p style="font-family: 'Courier Prime', monospace; font-size: 0.725rem; line-height: 1.4; color: #666666; margin-bottom: 1rem;">
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
                    <div style="font-family: 'Courier Prime', monospace; font-size: 0.75rem; color: #2E7D32; text-align: center; margin-bottom: 1rem;">
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
                    <span id="resendContainer" style="font-family: 'Courier Prime', monospace; font-size: 0.725rem; color: #555555;">
                        Resend OTP in <span id="timer" style="color: #111111; font-weight: 700;">60</span>s
                    </span>
                    <a id="resendBtn" href="{{ route('resend.otp') }}" class="d-none" style="font-family: 'Courier Prime', monospace; font-size: 0.725rem; color: #000000; font-weight: 700; text-decoration: underline;">
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
                <a href="{{ route('register') }}" style="font-family: 'Courier Prime', monospace; font-size: 0.75rem; color: #444444; text-decoration: none;">
                    Back
                </a>
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
