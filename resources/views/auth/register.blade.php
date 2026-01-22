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
        font-family: 'PlusJakartaSans' !important;
    }
    .find-select
    {
        font-size:10px;
        line-height:2.5;
    }

    .card-container {
    position: relative;   /* anchor */
    width: 100%;
    margin-top:-30px;
    }

    .bg-img {
    width: 100%;
    height: auto;
    display: block;
    }

    .content {
    position: absolute;   /* overlays image */
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;

    /* optional */
    display: flex;
    align-items: center;
    justify-content: center;
    }

    label
    {
        font-weight: 900;
    }

    .brand-container
    {
        position: relative;
        z-index: 99;
    }
    

    </style>
    <div class="register-main main-content with-scroll">
        <div class="justify-content-center w-100">
            <div class="col-12 animate-entry position-relative brand-container">
                @include('components.branding')
            </div>
            <div class="container card-container">
            <img src="{{ asset('images/brand/card_bg.webp') }}" class="bg-img">
            <div class="content">
                <div class="w-100  animate-entry delay-3 mx-2">
                <div class="mx-4 register-form-parent">
                    <div class="heading-container">
                        <h2 class="mx-4 text-center sub-heading-text animate-entry mb-4">REGISTRATION</h2>
                    </div>
                    <form id="form" method="POST" action="{{ route('register') }}">
                        @csrf
                        <input type="hidden" name="dialCode" id="dialCode" ></input>
                        <input type="hidden" name="countryIso" id="countryIso">
                        <div class="fields-container">
                            <div class="mb-3 row">
                                <div class="col-12">
                                    <label for="fname" class="text-primary">Full Name: <span class="text-danger">*</span></label>
                                    <input id="fname" placeholder="Your name" type="text"
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
                                    <label for="email" class="text-primary">E-mail: <span class="text-danger">*</span></label>

                                    <input id="email" placeholder="Email Address" type="email"
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
                                    <label for="number" class="text-primary">Phone Number: <span class="text-danger">*</span></label>

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
                            </div>

                                <div class="col-12 text-center">
                                    <span id="valid-msg" class="d-none text-danger"></span>
                                    <span id="error-msg" class="d-none text-danger"></span>
                                </div>

                            <div class="mb-3 row">
                                <div class="col-12">
                                    <label for="age" class="text-primary">Age: <span class="text-danger">*</span></label>
                                    <input id="age" type="number" class="input-text form-control " name="age" value="" required="" placeholder="18" autofocus="">
                                </div>
                            </div>
                        
                            @error('country')
                                <div class="text-danger text-center mb-2">{!! $message !!}</div> 
                            @enderror
                        </div>

                       
                        <div class="button-container">
                            <div class="mb-0 row">
                                <div class="col-12 text-center">
                                    <button id="submitButton" type="submit"
                                        class="custom-btn custom-btn-primary animate-entry delay-3 mt-4">
                                        {{ __('SUBMIT') }}
                                    </button>
                                </div>
                                <div class="col-12 text-center">
                                    <a href="{{ route('login') }}" class="sub-heading-text fw-normal fs-5">Login</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            </div>
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
