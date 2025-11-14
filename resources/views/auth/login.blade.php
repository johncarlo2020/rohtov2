<x-guest-layout>
    <style>
        .login-page h4,
        .login-page label,
        .login-page input,
        .login-page p,
        .login-page a,
        .login-page span {
            font-family: 'GothamBold' !important;
        }
    </style>
    <div class="login-page vh-100">
        <div class="main-content main-background with-scroll">
            <div class="col-12 animate-entry mb-4">
                @include('components.branding')
            </div>
                <h2 class="mx-4 text-center sub-heading-text animate-entry">LOGIN</h2>
            <div class="col-12 animate-entry delay-2 bg-white p-3 mt-4 card-parent" style="margin-bottom:20vh;">
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />
                <form method="POST" action="{{ route('login') }}" >
                    @csrf
                    <div class="row mb-3">
                        <div class="col-12 input-group w-100">
                                <label for="number" class="text-main">Contact Number <span class="text-danger">*</span></label>

                                <input id="number" type="number"
                                    class="input-text form-control w-100 @error('number') is-invalid @enderror"
                                    name="number" value="{{ old('number') }}" required autocomplete="number"
                                    autofocus />
                                @error('number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                @if(session('error'))
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ session('error') }}</strong>
                                </span>
                                @endif
                            </div>
                    </div>

                    <!-- Password -->
                    <x-text-input id="password" class="block w-full mt-1" type="hidden" name="password"
                        value="password" required autocomplete="current-password" />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />

                    <div class="d-flex justify-center">
                        <x-primary-button class="custom-btn custom-btn-primary" style="width:95%;margin:auto;">
                            {{ __('Next') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
             <div class="bottom-text text-center">
                    <p class="already-register text-white">
                        <strong>Haven't register yet?</strong>
                    </p>
                    <p class="already-register text-white">
                        <a href="{{ route('register') }}" class="text-white"><strong>Sign Up</strong></a>
                    </p>
                </div>
            <x-footer/>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/intlTelInput.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.querySelector("#form");
        const input = document.querySelector("#number");
        const errorMsg = document.querySelector("#error-msg");
        const validMsg = document.querySelector("#valid-msg");
        const errorMap = [
            "Invalid number",
            "Invalid country code",
            "Too short",
            "Too long",
            "Invalid number",
        ];
        const submitButton = document.querySelector("#submitButton");
        const iti = window.intlTelInput(input, {
            hiddenInput: "country",
            onlyCountries: ["my"],
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/utils.js"
        });

        const reset = () => {
            input.classList.remove("error");
            errorMsg.innerHTML = "";
            errorMsg.classList.add("d-none");
            validMsg.classList.add("d-none");
        };

        const showError = (msg) => {
            input.classList.add("error");
            errorMsg.innerHTML = msg;
            errorMsg.classList.remove("d-none");
        };

        input.addEventListener("keyup", function () {
            reset();
            if (!input.value.trim()) {
                showError("Required");
                submitButton.disabled = true;
            } else if (iti.isValidNumber()) {
                validMsg.classList.remove("d-none");
                submitButton.disabled = false;
            } else {
                const errorCode = iti.getValidationError();
                const msg = errorMap[errorCode] || "Invalid number";
                showError(msg);
                submitButton.disabled = true;
            }
        });

        // Prevent form submission if not Malaysian number
        form.addEventListener("submit", function (e) {
            const countryData = iti.getSelectedCountryData();
            if (!iti.isValidNumber() || countryData.iso2 !== 'my') {
                let msg = "Please enter a valid Malaysian phone number";
                if (countryData.iso2 !== 'my') {
                    msg = "Only Malaysian phone numbers are allowed.";
                }
                showError(msg);
                 e.preventDefault();
                submitButton.disabled = true;
            }
        });
    });
</script>
</x-guest-layout>
