<x-guest-layout>
    <style>
    span.iti__country-name {
        color: #000000 !important;
    }
    </style>
    <div class="register-main main-content with-scroll">
        <div class="justify-content-center w-100 px-3">
            <div class="col-12 d-flex justify-content-center animate-entry">
                @include('components.branding')
            </div>
            <div class="card mt-4 w-100  animate-entry delay-3 py-5 px-3">
                <h4 class="mb-4 text-center text-black fw-bold">SIGN UP</h4>
                <div class="pt-2 pb-4 register-form-parent">
                    <form id="form" method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="mb-3 row">
                            <div class="col-12">
                                <label for="" class="text-black">Firstname <span class="text-danger">*</span></label>
                                <input id="fname" placeholder="Enter your firstname" type="text"
                                    class="input-text form-control  rounded-1 @error('fname') is-invalid @enderror" name="fname"
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
                                <label for="" class="text-black">Lastname <span class="text-danger">*</span></label>
                                <input id="lname" placeholder="Enter your lastname" type="text"
                                    class="input-text form-control  rounded-1 @error('lname') is-invalid @enderror" name="lname"
                                    value="{{ old('lname') }}" required autocomplete="lname" autofocus />
                                @error('lname')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <div class="col-12">
                                <label for="" class="text-black">Date of Birth</label>
                                <input id="dob" placeholder="Enter your full name" type="date"
                                    class="input-text form-control  rounded-1 @error('dob') is-invalid @enderror" name="dob"
                                    value="{{ old('dob') }}" required autocomplete="dob" autofocus />
                                @error('dob')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <div class="col-12">
                                <label for="" class="text-black">Email Address <span class="text-danger">*</span></label>

                                <input id="email" placeholder="example@email.com" type="email"
                                    class="input-text form-control  rounded-1 @error('email') is-invalid @enderror" name="email"
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
                                <label for="" class="text-black">Phone Number <span class="text-danger">*</span></label>

                                <input id="number" type="number"
                                    class="input-text form-control w-100  rounded-1 @error('number') is-invalid @enderror"
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

                        <!-- <hr style="border-top: 2px dotted; opacity: 0.25;"> -->
                        <div class="mt-4 mb-2 row">
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privacy_policy" value="1"
                                        id="privacyPolicy" required />
                                    <label class="form-check-label text-black small-text" for="privacyPolicy">
                                       I agree that the collection and processing of my personal data will be in compliance with the Shiseido <a href="{{ asset('pdfs/privacy-policy.pdf') }}" target="_blank" class="text-primary">Privacy Policy</a> .
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <p class="text-black small-text mb-3 ">
                                I would like to receive information on Shiseido and Shiseido Group products and campaigns from Shiseido, 
                                Shiseido Group and Shiseido's third party business partners via the following channels:
                            </p>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="contact_methods[]" value="email" id="email">
                                    <label class="form-check-label text-black" for="email" >
                                    Email
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="contact_methods[]" value="text" id="text">
                                    <label class="form-check-label text-black" for="text">
                                    Text Message (including SMS/Whatsapp)
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="contact_methods[]" value="call" id="call">
                                    <label class="form-check-label text-black" for="call">
                                    Call
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-0 row">
                            <div class="col-12 text-center">
                                <button id="submitButton" type="submit"
                                    class="w-100 custom-btn custom-btn-secondary animate-entry delay-3">
                                    {{ __('REGISTER') }}
                                </button>
                            </div>
                            <div class="col-12 text-center">
                                <a href="{{ route('welcome') }}" 
                                class="w-100 custom-btn custom-btn-transparent animate-entry delay-3 text-center d-block">
                                    {{ __('BACK') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
                <div class="bottom-text text-center pt-4">
                    <p class="already-register text-black fw-bold">
                        Already Registered!
                    </p>
                    <p class="already-register text-black">
                        Please Login
                        <a href="{{ route('login') }}" class="">here</a>
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
            if (!iti.isValidNumber()) {
                showError("Please enter a valid phone number");
                e.preventDefault();
                submitButton.disabled = true;
            }
        });
    });
</script>
