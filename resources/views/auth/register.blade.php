<x-guest-layout>
    <style>
    span.iti__country-name {
        color: #000000 !important;
    }

    /* Apply Poppins font to the register page */
    /* .register-main * {
        font-family: 'Poppins', sans-serif !important;
    } */

    input#number::placeholder {
        font-family: 'Singulier';
        font-size: 16px;
    }

    /* Specific styling for form elements */
    .register-main h4,
    .register-main label,
    .register-main input,
    .register-main input::placeholder
    .register-main span {
        font-family: 'Singulier' !important;
    }
    .find-select
    {
        font-size:10px;
        line-height:2.5;
    }
    </style>
    <div class="register-main main-content with-scroll">
        <div class="justify-content-center w-100">
            <div class="col-12 animate-entry mb-4">
                @include('components.branding')
            </div>

            <div class=" mt-4 mb-3 w-100  animate-entry delay-3 bg-white p-3 card-parent">
                <div class="py-3 register-form-parent">
                    <form id="registerForm" method="POST" action="">
                        @csrf
                        <input type="hidden" name="dialCode" id="dialCode" ></input>
                        <input type="hidden" name="countryIso" id="countryIso">
                        <input type="hidden" name="code" value="" id="code"></input>

                        <div class="mb-3 row">
                                <h2 class="text-center text-dark mb-4" style="font-weight:300">WELCOME!</h2>
                                <p class="text-center text-dark mb-4" style="font-size:16px;letter-spacing:1.2px;font-weight:300;">
                                    Please fill in your mobile number below.
                                </p>
                            <div class="col-12 input-group w-100">
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
                         @error('country')
                            <div class="text-danger text-center mb-2">{!! $message !!}</div> 
                        @enderror
                        <input class="d-none" type="hidden" name="password" value="password" />
                       
                        <div class="mb-0 row">
                            <div class="col-12 text-center">
                                <button id="submitButton"
                                    class="custom-btn custom-btn-primary animate-entry delay-3" style="font-weight:300;">
                                    {{ __('Start Your Journey Now') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- <div class="bottom-text text-center mb-3">
                    <span class="already-register text-white">
                        <strong>Sudah Daftar!</strong>
                    </span>
                    <br>
                    <span class="already-register text-white">
                        Log Masuk
                        <a href="{{ route('login') }}" class="text-white"><strong>disini</strong></a>
                    </span>
                </div> -->
        </div>
    </div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/intlTelInput.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.querySelector("#registerForm");
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
            initialCountry: "sg",
            preferredCountries: ["sg"],
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
        // form.addEventListener("submit", function (e) {
        //     const countryData = iti.getSelectedCountryData();
        //     console.log(countryData);
        //     const number = input.value.trim();

        //     // Check if number is valid for the selected country
        //     if (!iti.isValidNumber()) {
        //         const msg = `Please enter a valid phone number for ${countryData.name}`;
        //         showError(msg);
        //         e.preventDefault();
        //         submitButton.disabled = true;
        //     } else {
        //         submitButton.disabled = false; // enable submit if valid
        //     }
        // });

        var csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Check if there's an 'id' parameter in the URL for backward compatibility
            const urlParams = new URLSearchParams(window.location.search);
            const urlId = urlParams.get("id");

            if (urlId) {
                // If ID is in URL, auto-fill and submit (old behavior)
                $("#code").val(urlId);
                processRegistration(urlId);
            }

            // Handle form submission
            $("#submitButton").click(function(e) {
                e.preventDefault();

                if (!iti.isValidNumber()) {
                    alert("Please enter a valid phone number");
                    return;
                }

                const fullNumber = iti.getNumber();
    
                $("#code").val(fullNumber);
                console.log(fullNumber);
                processRegistration(fullNumber);
            });

            function processRegistration(code) {
                $.ajax({
                    url: '{{ route('checkExisting') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    data: {
                        code: code,
                    },
                    success: function(response) {
                        console.log(response);
                        if (response == 1) {
                            $("#registerForm").attr("action", "{{ route('login') }}");
                            $("#registerForm").submit();
                        } else {
                            $("#registerForm").attr("action", "{{ route('register') }}");
                            $("#registerForm").submit();
                        }
                        console.log(response);
                    },
                    error: function(xhr, status, error) {
                        alert("An error occurred. Please try again.");
                        console.error(error);
                    }
                });
            }
    });
</script>
</x-guest-layout>