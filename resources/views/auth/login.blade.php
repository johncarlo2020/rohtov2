<x-guest-layout>
    <head>
        <!-- ... existing head elements ... -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"></script>
    </head>
    <div class="content-box main-background px-3 d-flex flex-column min-vh-100">
        <div class="container">
            <div>
                @include('components.branding')
            </div>
        </div>
        <div class="form-container p-4 mt-5 fade-in min-100">
            <h1 class="heading-text mb-3 text-center">
                LOG IN
            </h1>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-2 row">
                    <div class="col-12 w-100 phone-number-input w-100">
                        <label class="form-label d-block" for="number">Mobile Number</label>

                        <input id="number" type="tel"
                            class="input-text form-control w-100 @error('number') is-invalid @enderror d-block" placeholder="Enter your phone number"
                            name="number" value="{{ old('number') }}" required autocomplete="tel" autofocus />
                        @error('number')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mt-2 col-12">
                        <span id="valid-msg" class="d-none text-danger"></span>
                        <span id="error-msg" class="d-none text-danger"></span>
                        @error('email') {{-- Keep this for general auth errors if backend returns 'email' key --}}
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <!-- Password -->
                <x-text-input id="password" class="block w-full mt-1" type="hidden" name="password" value="password"
                    required autocomplete="current-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />

                <div class="flex items-center justify-end">
                    <x-primary-button class="button button-secondary w-100">
                        Login
                    </x-primary-button>
                </div>
            </form>

        </div>
        <div class="bottom-text text-center mt-auto">
                            <p class="pharagraph-text mb-0">Don’t have account yet!</p>
                            <p class="pharagraph-text">Register <a class="" href="{{ route('register') }}" class="">here</a></p>
                        </div>
        <div class="footer-container p-4">
            @include('components.footer')
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const phoneInputField = document.querySelector("#number");
            const errorMsg = document.querySelector("#error-msg");
            const validMsg = document.querySelector("#valid-msg");

            // here, the index maps to the error code returned from getValidationError - see readme
            const errorMap = ["Invalid number", "Invalid country code", "Too short", "Too long", "Invalid number"];

            const phoneInput = window.intlTelInput(phoneInputField, {
                initialCountry: "auto",
                geoIpLookup: function(callback) {
                    fetch("https://ipapi.co/json")
                        .then(function(res) {
                            return res.json();
                        })
                        .then(function(data) {
                            callback(data.country_code);
                        })
                        .catch(function() {
                            callback("us");
                        });
                },
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
            });

            const reset = function() {
                phoneInputField.classList.remove("is-invalid");
                errorMsg.innerHTML = "";
                errorMsg.classList.add("d-none");
                validMsg.classList.add("d-none");
            };

            phoneInputField.addEventListener('blur', function() {
                reset();
                if (phoneInputField.value.trim()) {
                    if (phoneInput.isValidNumber()) {
                        validMsg.classList.remove("d-none");
                    } else {
                        phoneInputField.classList.add("is-invalid");
                        const errorCode = phoneInput.getValidationError();
                        errorMsg.innerHTML = errorMap[errorCode] || "Invalid number";
                        errorMsg.classList.remove("d-none");
                    }
                }
            });

            // on keyup / change flag: reset
            phoneInputField.addEventListener('change', reset);
            phoneInputField.addEventListener('keyup', reset);

            // Store the full international number on form submit
            const form = phoneInputField.closest("form");
            form.addEventListener("submit", function() {
                if (phoneInput.isValidNumber()) {
                    phoneInputField.value = phoneInput.getNumber();
                }
            });
        });
    </script>
</x-guest-layout>

