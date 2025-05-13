<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Hadalabo Experience</title>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap"
        rel="stylesheet" />
</head>

<body class="antialiased welcome-page">
    <div class="content-box main-background px-3 d-flex flex-column min-vh-100">
        <div class="container mb-5">
            <div>
                @include('components.branding')
            </div>
        </div>
        <div class="otp-form-container bg-white px-3 py-4 rounded">
            <form method="POST">
                @csrf
                <div class="text-center mb-4 px-1">
                    <h2 class="heading-text text-center mb-2">ENTER YOUR OTP</h2>
                    <p class="sub-heading-text text-center">An OTP (One Time Passcode) has been sent to the number
                        registered.</p>
                    <p class="sub-heading-text text-center">Please enter the OTP below to verify your contact details.
                    </p>
                </div>
                <div class="d-flex justify-content-center otp-inputs mb-4 px-1">
                    <input type="text" name="otp[]" class="form-control otp-input mx-1 text-center py-2"
                        maxlength="1" pattern="[0-9]" required>
                    <input type="text" name="otp[]" class="form-control otp-input mx-1 text-center" maxlength="1"
                        pattern="[0-9]" required>
                    <input type="text" name="otp[]" class="form-control otp-input mx-1 text-center" maxlength="1"
                        pattern="[0-9]" required>
                    <input type="text" name="otp[]" class="form-control otp-input mx-1 text-center" maxlength="1"
                        pattern="[0-9]" required>
                    <input type="text" name="otp[]" class="form-control otp-input mx-1 text-center" maxlength="1"
                        pattern="[0-9]" required>
                    <input type="text" name="otp[]" class="form-control otp-input mx-1 text-center" maxlength="1"
                        pattern="[0-9]" required>
                </div>
                <div class="text-center mb-4">
                    <a id="resendOtpLink">Resend OTP</a>
                    <p id="resendTimer" class="d-none">Resend OTP in <span id="timerValue">60</span>s</p>
                </div>
                <div class="text-center mb-3">
                    <button type="submit" class="button button-primary w-100">Verify OTP</button>
                </div>
                <div class="bottom-text text-center">
                    <a class="button-text" href="{{ route('login') }}" class="">Back</a>
                </div>

            </form>
        </div>

        <div class="footer-container p-4 mt-auto">
            @include('components.footer')
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const otpInputs = document.querySelectorAll('.otp-input');
            otpInputs.forEach((input, index) => {
                input.addEventListener('input', (e) => {
                    if (e.target.value.length === 1 && index < otpInputs.length - 1) {
                        otpInputs[index + 1].focus();
                    }
                    // Change background color if input has value
                    if (e.target.value.length > 0) {
                        e.target.style.backgroundColor = '#F2E9DA';
                    } else {
                        e.target.style.backgroundColor = ''; // Revert to default
                    }
                });

                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' && e.target.value.length === 0 && index > 0) {
                        otpInputs[index - 1].focus();
                    }
                });
            });

            const resendOtpLink = document.getElementById('resendOtpLink');
            const resendTimer = document.getElementById('resendTimer');
            const timerValue = document.getElementById('timerValue');
            let countdown = 60;

            function startTimer() {
                resendOtpLink.classList.add('d-none');
                resendTimer.classList.remove('d-none');
                timerValue.textContent = countdown;

                const interval = setInterval(() => {
                    countdown--;
                    timerValue.textContent = countdown;
                    if (countdown <= 0) {
                        clearInterval(interval);
                        resendOtpLink.classList.remove('d-none');
                        resendTimer.classList.add('d-none');
                        countdown = 60; // Reset for next click
                    }
                }, 1000);
            }

            if (resendOtpLink) {
                resendOtpLink.addEventListener('click', function(e) {
                    e.preventDefault(); // Prevent default link behavior
                    // Add your AJAX call here to resend OTP
                    // For now, just start the timer
                    startTimer();
                    // Example: fetch(this.href).then(...)
                });
            }
        });
    </script>
</body>

</html>
