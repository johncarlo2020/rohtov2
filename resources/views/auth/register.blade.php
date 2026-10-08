<x-guest-layout>
    <div class="mm-mobile-container">
        <div>
            {{-- Header --}}
            @include('components.branding')

            {{-- Title --}}
            <h1 class="mm-page-title">
                REGISTER
            </h1>

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
                            *Looks like you already have an account! Please <a href="{{ route('login') }}" style="color: #D93838; text-decoration: underline; font-weight: 700;">log in</a> to continue.
                        </div>
                    @else
                        <div class="mm-helper-text">
                            *A one-time password (OTP) will be sent to this email address.
                        </div>
                    @enderror
                </div>

                {{-- Divider Line --}}
                <div style="width: 100%; height: 1px; background-color: #666666; margin: 1.75rem 0 1.5rem 0;"></div>

                {{-- Terms & Privacy Checkbox --}}
                <div class="mb-4">
                    <label class="mm-checkbox-label">
                        <input id="termsCheckbox" type="checkbox" name="terms" value="1" required>
                        <span style="font-family: 'Courier Prime', monospace; font-size: 0.725rem; color: #444444; line-height: 1.5;">
                            I have read and agree to the <a href="#" onclick="event.preventDefault();" style="color: #111111; font-weight: 700; text-decoration: underline;">Terms and Conditions</a> and <a href="#" onclick="event.preventDefault();" style="color: #111111; font-weight: 700; text-decoration: underline;">Privacy Policy</a>.
                        </span>
                    </label>
                    @error('terms')
                        <div class="mm-error-text">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Submit Button --}}
                <div class="mt-4 pt-1 text-center">
                    <button id="submitBtn" type="submit" class="mm-btn-black mx-auto" style="width: 60%;" disabled>
                        SUBMIT
                    </button>
                </div>
            </form>

            {{-- Footer Link --}}
            <div class="mm-link-sub mt-4" style="font-size: 0.725rem; letter-spacing: 1px; color: #111111;">
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
