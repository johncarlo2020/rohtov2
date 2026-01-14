<x-guest-layout>
    <div class="justify-content-center w-100 px-3">
        <div class="my-5 col-12 d-flex justify-content-center">
            @include('components.branding')
        </div>
        <div class="otp-form-container card bg-white px-3 py-4 rounded">
            <form method="POST" action="{{ route('verify.otp') }}">
                @csrf
                <div class="text-center mb-4 px-1">
                    <h2 class="heading text-dark text-center mb-2">ENTER YOUR OTP</h2>
                    <p class="text-dark text-center mb-2">An OTP (One Time Passcode) has been sent to the number
                        registered.</p>
                    <p class="text-dark text-center">Please enter the OTP below to verify your contact details.
                    </p>
                    @if($errors->has('otp'))
                    <div class="alert alert-danger text-center mb-3">
                        {{ $errors->first('otp') }}
                    </div>
                    @endif

                </div>
                <div class="d-flex justify-content-center otp-inputs mb-4">
                    @for($i = 0; $i < 6; $i++) <input type="text" name="otp[]" class="form-control otp-input mx-1 text-center"
                        maxlength="1" pattern="[0-9]" required>
                        @endfor
                </div>
                <div class="text-center mb-4">
                    <a id="resendOtpLink" href="#" class="text-dark no-underline">Resend OTP</a>
                    <p id="resendTimer" class="text-dark d-none">Resend OTP in <span class="text-dark" id="timerValue">180</span>s</p>
                </div>
                <button type="submit" class="btn custom-btn-primary w-100">Verify OTP</button>
            </form>
            <a class="text-center no-underline mt-3 fw-bold" href="{{ route('welcome') }}">Back</a>
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
                    e.target.style.backgroundColor = e.target.value ? '#F2E9DA' : '';
                });

                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' && !e.target.value && index > 0) {
                        otpInputs[index - 1].focus();
                    }
                });
            });

            function startTimer() {
                resendOtpLink.classList.add('d-none');
                resendTimer.classList.remove('d-none');
                timerValue.textContent = countdown;

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
