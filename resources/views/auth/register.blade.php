<x-guest-layout>
    <style>
    span.iti__country-name {
        color: #000000 !important;
    }

    /* Apply Poppins font to the register page */
    .register-main * {
        font-family: 'Poppins', sans-serif !important;
    }

    /* Specific styling for form elements */
    .register-main h4,
    .register-main label,
    .register-main input,
    .register-main button,
    .register-main p,
    .register-main span {
        font-family: 'Poppins', sans-serif !important;
    }
    </style>
    <div class="register-main main-content with-scroll">
        <div class="justify-content-center w-100">
            <div class="col-12 d-flex justify-content-center animate-entry">
                @include('components.branding')
            </div>
            <div class=" mt-4 mb-5 w-100  animate-entry delay-3 bg-white p-3 ">
                <h4 class="mx-4 text-center text-dark">SIGN UP</h4>
                <div class="py-3 register-form-parent">
                    <form id="form" method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="mb-3 row">
                            <div class="col-12">
                                <label for="name" class="text-dark">Name <span class="text-danger">*</span></label>
                                <input id="fname" placeholder="Enter your full name" type="text"
                                    class="input-text form-control @error('fname') is-invalid @enderror" name="fname"
                                    value="{{ old('fname') }}" required autocomplete="fname" autofocus />
                                @error('fname')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>


                        <div class="mb-3 row">
                            <div class="col-12">
                                <label for="email" class="text-dark">Email Address <span class="text-danger">*</span></label>

                                <input id="email" placeholder="example@email.com" type="email"
                                    class="input-text form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" required autocomplete="email" />

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>


                        <div class="mb-3 row">
                            <div class="col-12 input-group w-100">
                                <label for="phone" class="text-dark">Phone Number <span class="text-danger">*</span></label>

                                <input id="number" type="number"
                                    class="input-text form-control w-100 @error('number') is-invalid @enderror"
                                    name="number" value="{{ old('number') }}" required autocomplete="number"
                                    autofocus />
                                @error('number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="mt-2 col-12">
                                <span id="valid-msg" class="d-none text-danger"></span>
                                <span id="error-msg" class="d-none text-danger"></span>
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <div class="col-12">
                                <div class="form-check">
                                    <label class="form-check-label text-dark small-text" for="marketing">
                                        By submitting this form, you agree that Ryt Bank may contact you
                                        regarding our campaign, including any relevant offers and updates.
                                        Your personal data will be collected,
                                        handled and processed in accordance with Ryt Bank's Privacy Notice.
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-0 row">
                            <div class="col-12 text-center mb-3">
                                <button id="submitButton" type="submit"
                                    class="w-100 custom-btn custom-btn-primary animate-entry delay-3">
                                    {{ __('REGISTER') }}
                                </button>
                            </div>
                            <div class="col-12 text-center">
                                <button id="submitButton" type="submit"
                                    class="w-100 custom-btn custom-btn-secondary animate-entry delay-3" onclick="if(document.referrer){ history.back(); } else { window.location.href='{{ url('/') }}'; }">
                                    {{ __('BACK') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bottom-text text-center">
                    <p class="already-register text-dark">
                        <strong>Already Registered</strong>
                    </p>
                    <p class="already-register text-grey">
                        Please Login
                        <a href="{{ route('login') }}" class="text-grey"><strong>here</strong></a>
                    </p>
                </div>
        </div>
    </div>
</x-guest-layout>
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
