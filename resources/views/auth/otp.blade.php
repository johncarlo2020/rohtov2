<x-guest-layout>
    <div class="register-main with-scroll">
        <!-- Desktop Left Hero Image -->
        <div class="desktop-image-main">
            <img src="{{ asset('images/brand/main_img.webp') }}" alt="Longchamp Workshop">
        </div>

        <!-- Mobile Top Hero Image -->
        <div class="mobile-image-main">
            <img src="{{ asset('images/brand/main_img.webp') }}" alt="Longchamp Workshop">
        </div>

        <!-- Right / Bottom Content Area -->
        <div class="flex-parent">
            <!-- Top Logo (Desktop Only) -->
            <div class="top">
                @include('components.branding')
            </div>

            <!-- Middle Content -->
            <div class="mid">
                <form method="POST" action="{{ route('verify.otp') }}">
                    @csrf
                    <div class="text-center mb-4">
                        <h1 class="fw-bold text-dark text-center mb-2 text-uppercase" style="font-size: 1.35rem; letter-spacing: 1px;">
                            OTP VERIFICATION
                        </h1>
                        <p class="text-dark text-center mb-0" style="font-size: 0.8rem; line-height: 1.5; color: #444444;">
                            We've sent a 6-digit verification code to your registered E-mail. Please enter it below.
                        </p>
                        @if($errors->has('otp'))
                            <div class="alert alert-danger text-center my-3 py-2 small fw-bold">
                                {{ $errors->first('otp') }}
                            </div>
                        @endif
                    </div>

                    <!-- 6 OTP Input Boxes -->
                    <div class="d-flex justify-content-center otp-inputs mb-4" style="gap: 8px;">
                        @for($i = 0; $i < 6; $i++)
                            <input type="text" name="otp[]" class="form-control otp-input text-center"
                                maxlength="1" pattern="[0-9]" inputmode="numeric" required
                                style="width: 44px; height: 50px; border-radius: 8px; border: 1.5px solid #d1d5db; font-size: 20px; font-weight: bold; background: #ffffff;">
                        @endfor
                    </div>

                    <!-- Submit Button & Timer -->
                    <div class="d-flex flex-column align-items-center justify-content-center mt-4">
                        <button type="submit" class="custom-btn custom-btn-primary mb-3" style="max-width: 220px; width: 100%;">
                            SUBMIT OTP
                        </button>

                        <div class="text-center">
                            <small id="resendTimer" class="text-dark text-uppercase d-none" style="font-size: 11px; letter-spacing: 0.5px;">
                                DIDN'T RECEIVE THE CODE? <span class="fw-bold text-dark">RESEND</span> OTP IN <span class="fw-bold text-dark" id="timerValue">180</span>S
                            </small>
                            <a id="resendOtpLink" href="#" class="text-dark text-decoration-none fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">
                                RESEND OTP
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Bottom Horse Logo -->
            <div class="col-12 bot">
                <div class="logo-bot d-flex justify-content-center mt-3">
                    <img src="{{ asset('images/brand/bot_logo.webp') }}" class="img-fluid" alt="Footer Image" srcset="" style="width: 4rem;">
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const otpInputs = document.querySelectorAll('.otp-input');
            const resendOtpLink = document.getElementById('resendOtpLink');
            const resendTimer = document.getElementById('resendTimer');
            const timerValue = document.getElementById('timerValue');
            let countdown = 180;
            let interval = null;

            // OTP Auto-tab + highlight
            otpInputs.forEach((input, index) => {
                input.addEventListener('input', (e) => {
                    if (e.target.value.length === 1 && index < otpInputs.length - 1) {
                        otpInputs[index + 1].focus();
                    }
                    e.target.style.backgroundColor = e.target.value ? '#F2E9DA' : '#ffffff';
                });

                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' && !e.target.value && index > 0) {
                        otpInputs[index - 1].focus();
                    }
                });

                // Handle paste of 6 digits
                input.addEventListener('paste', (e) => {
                    e.preventDefault();
                    const pastedData = (e.clipboardData || window.clipboardData).getData('text').trim();
                    if (/^\d{6}$/.test(pastedData)) {
                        pastedData.split('').forEach((char, i) => {
                            if (otpInputs[i]) {
                                otpInputs[i].value = char;
                                otpInputs[i].style.backgroundColor = '#F2E9DA';
                            }
                        });
                        otpInputs[5].focus();
                    }
                });
            });

            function startTimer() {
                resendOtpLink.classList.add('d-none');
                resendTimer.classList.remove('d-none');
                timerValue.textContent = countdown;

                if (interval) clearInterval(interval);

                interval = setInterval(() => {
                    countdown--;
                    timerValue.textContent = countdown;

                    if (countdown <= 0) {
                        clearInterval(interval);
                        resendOtpLink.classList.remove('d-none');
                        resendTimer.classList.add('d-none');
                        countdown = 180; // Reset for next use
                    }
                }, 1000);
            }

            // Trigger timer immediately on load
            startTimer();

            // On Resend OTP click
            resendOtpLink.addEventListener('click', function (e) {
                e.preventDefault();

                fetch('{{ route('resend.otp') }}', {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('OTP resent!');
                            countdown = 180;
                            startTimer(); // restart the countdown
                        } else {
                            alert(data.message || 'Please wait before resending OTP.');
                        }
                    });
            });
        });
    </script>
</x-guest-layout>
