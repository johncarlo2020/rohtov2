<x-guest-layout>
    <style>
    span.iti__country-name {
        color: #000000 !important;
    }

    /* Apply Poppins font to the register page */
    /* .register-main * {
        font-family: 'Poppins', sans-serif !important;
    } */

    /* Specific styling for form elements */
    .register-main h4,
    .register-main label,
    .register-main input,
    .register-main span {
        font-family: 'GothamBold' !important;
    }
    </style>
    <div class="register-main main-content with-scroll">
        <div class="justify-content-center w-100">
            <div class="col-12 animate-entry mb-4">
                @include('components.branding')
            </div>
            <h2 class="mx-4 text-center sub-heading-text animate-entry">SIGN UP</h2>
            <div class=" mt-4 mb-5 w-100  animate-entry delay-3 bg-white p-3 card-parent">
                <div class="py-3 register-form-parent">
                    <form id="form" method="POST" action="{{ route('register') }}">
                        @csrf
                        <input type="hidden" name="dialCode" id="dialCode" ></input>
                        <input type="hidden" name="countryIso" id="countryIso">
                        <div class="mb-3 row">
                            <div class="col-12">
                                <label for="name" class="text-main">Full Name <span class="text-danger">*</span></label>
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


                        <!-- <div class="mb-3 row">
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
                        </div> -->

                        <div class="mb-3 row">
                            <div class="col-12 input-group w-100">
                                <label for="number" class="text-main">Phone Number <span class="text-danger">*</span></label>

                                <input id="number" type="phone"
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

                        <div class="mb-3 row">
                            <div class="col-12">
                                <label for="company" class="text-main">Company Name <span class="text-danger">*</span></label>

                                <input id="company" placeholder="Enter your company name" type="text"
                                    class="input-text form-control @error('company') is-invalid @enderror" name="company"
                                    value="{{ old('company') }}" required autocomplete="company" />

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                         @error('country')
                            <div class="text-danger text-center mb-2">{!! $message !!}</div> 
                        @enderror

                       
                        <div class="mb-0 row">
                            <div class="col-12 text-center">
                                <button id="submitButton" type="submit"
                                    class="w-75 custom-btn custom-btn-primary animate-entry delay-3">
                                    {{ __('Submit') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bottom-text text-center">
                    <span class="already-register text-white">
                        <strong>Already Registered</strong>
                    </span>
                    <br>
                    <span class="already-register text-white">
                        Please Login
                        <a href="{{ route('login') }}" class="text-white"><strong>here</strong></a>
                    </span>
                </div>
        </div>
        <x-footer/>
    </div>
</x-guest-layout>
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/intlTelInput.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.querySelector("#form");
        const input = document.querySelector("#number");
        const errorMsg = document.querySelector("#error-msg");
        const validMsg = document.querySelector("#valid-msg");
        const dialInput = document.querySelector("#dialCode");
        const isoInput = document.querySelector("#countryIso");
        const errorMap = [
            "Invalid number",
            "Invalid country code",
            "Too short",
            "Too long",
            "Invalid number",
        ];
        const submitButton = document.querySelector("#submitButton");
        const iti = window.intlTelInput(input, {
            initialCountry: "my",
            preferredCountries: ["my"],
            hiddenInput: "country",
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

        // Function to update dial code in the div
            function updateCountryData() {
                const countryData = iti.getSelectedCountryData();
                dialInput.value = "+" + countryData.dialCode;  // e.g., +1
                isoInput.value = countryData.iso2;             // e.g., us, my, ca
                console.log("Dial code:", dialInput.value, "ISO:", isoInput.value);
                submitButton.disabled = false;
            }

            // Set initial default dial code on page load
            updateCountryData();

            // Update dial code whenever country changes
            input.addEventListener("countrychange", updateCountryData);

            input.addEventListener("keypress", function (e) {
            const char = String.fromCharCode(e.which);
            if (!/[0-9+]/.test(char)) {
                e.preventDefault();
            }
        });

        // Prevent form submission if not Malaysian number
        form.addEventListener("submit", function (e) {
            const countryData = iti.getSelectedCountryData();
            console.log(countryData);
            const number = input.value.trim();

            // Check if number is valid for the selected country
            if (!iti.isValidNumber()) {
                const msg = `Please enter a valid phone number for ${countryData.name}`;
                showError(msg);
                e.preventDefault();
                submitButton.disabled = true;
            } else {
                submitButton.disabled = false; // enable submit if valid
            }
        });
    });
</script>
