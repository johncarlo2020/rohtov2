<x-guest-layout>
    <div class="content-box main-background">
        <div class="container">
            <div>
                @include('components.branding')
            </div>
        </div>
        <div class="form-container px-4 mt-5">
            <h1 class="heading-text mb-1 text-center">
                YOU ARE ONE STEP AWAY
            </h1>
            <p class="sub-heading-text text-center">
                Please fill in your details below to complete the registration.
            </p>
            <form id="form" method="POST" action="{{ route('register') }}">
                @csrf
                <div class="mb-2 row">
                    <div class="col-12">
                        <label class="form-label" for="">First Name</label>

                        <input id="fname" placeholder="Enter your first name" type="text"
                            class="input-text form-control @error('fname') is-invalid @enderror" name="fname"
                            value="{{ old('fname') }}" required autocomplete="fname" autofocus />
                        @error('fname')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="mb-2 row">
                    <div class="col-12">
                        <label class="form-label" for="">Last Name</label>

                        <input id="lname" placeholder="Enter your last name" type="text"
                            class="input-text form-control @error('lname') is-invalid @enderror" name="lname"
                            value="{{ old('lname') }}" required autocomplete="lname" autofocus />

                        @error('lname')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="mb-2 row">
                    <div class="col-12">
                        <label class="form-label" for="">Date of Birth</label>
                        <input id="dob" placeholder="Date of Birth" type="date"
                            class="input-text form-control @error('dob') is-invalid @enderror" name="dob"
                            value="{{ old('dob') }}" required autocomplete="dob" autofocus />

                        @error('lname')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="mb-2 row">
                    <div class="col-12">
                        <label class="form-label" for="">Email Address</label>

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

                <div class="mb-2 row">
                    <div class="col-12 input-group w-100 phone-number-input">
                        <label class="form-label" for="">Phone Number</label>

                        <input id="number" type="tel" class="input-text form-control w-100 @error('number') is-invalid @enderror d-block"
                            name="number" value="{{ old('number') }}" required autocomplete="tel" autofocus />

                    </div>
                    <div class="mt-2 col-12">
                        <span id="valid-msg" class="d-none text-danger"></span>
                        <span id="error-msg" class="d-none text-danger"></span>
                    </div>
                </div>
                <p class="pharagraph-text">
                    *Please make sure you are using an active phone number. Please fill in your mobile number. An OTP
                    (One Time Passcode) will be sent for verification.
                </p>

                <p class="pharagraph-text">
                    I agree to receive marketing information, latest promotions, products and services from Loccitane
                    Malaysia via the following channels:
                </p>

                <div class="mt-4 mb-1 row">
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="privacy_policy" value="1"
                                id="privacyPolicy" required />
                            <label class="form-check-label" for="privacyPolicy">
                                Email
                            </label>
                        </div>
                    </div>
                </div>
                <div class="mb-2 row">
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="privacy_policy" value="1"
                                id="privacyPolicy" required />
                            <label class="form-check-label" for="privacyPolicy">
                                Text Message (SMS/Whatsapp)
                            </label>
                            <p class="small-text pl-4">Note: Please tick to receive details on your GUESS & WIN
                                submission; failure to do so will result in forfeiture.</p>
                        </div>
                    </div>
                </div>

                <p class="sub-heading-text"><Strong>Data Protection and Privacy Policy</Strong></p>
                <div class="box p-2 bg-white rounded mb-2">
                    <p class="pharagraph-text">By submitting your particulars and/or by signing this form, you agree
                        that L’OCCITANE Malaysia Sdn Bhd may collect, use and disclose your personal data obtained by us
                        as a result of your membership, for purposes in accordance with the Personal Data Protection Act
                        2010 and our privacy policy (available at our website https://my.loccitane.com). You understand
                        that by signing this form, you consent to us processing your data. Please visit our website
                        https://my.loccitane.com for how you may access and correct your personal data or withdraw
                        consent to the collection, use or disclosure of your personal data.</p>
                </div>


                <div class="mb-2 row">
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="privacy_policy" value="1"
                                id="privacyPolicy" required />
                            <label class="form-check-label" for="privacyPolicy">
                                *I agree to L'OCCITANE Malaysia group using and disclosing your personal information to
                                contact you about other goods and services and using your information for direct
                                marketing purposes including contact by phone, email, SMS or other electronic means.
                            </label>
                        </div>
                    </div>
                </div>
                <div class="mb-2 row">
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="marketing" value="1"
                                id="marketing" required />
                            <label class="form-check-label" for="marketing">
                                *I hereby consent to the Processing of my Personal Data for the above Purpose and agree
                                to the terms in the Data Protection and Privacy Policy Notice.
                            </label>
                        </div>
                    </div>
                </div>
                <div class="capcha-box bg-white p-5 rounded mb-3"></div>
                <div class="mb-0 row">
                    <div class="col-12">
                        <button id="submitButton" type="submit" class="button button-primary w-100 mb-2">
                            {{ __('SUBMIT') }}
                        </button>
                        <div class="bottom-text text-center">
                            <a class="button-text" href="{{ route('login') }}" class="">Back</a>
                        </div>
                    </div>
                </div>
            </form>
            <div class="footer-container p-4">
                @include('components.footer')
            </div>
        </div>
    </div>
</x-guest-layout>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.querySelector("#form");
        const input = document.querySelector("#number");

        const errorMsg = document.querySelector("#error-msg");
        const validMsg = document.querySelector("#valid-msg");

        // here, the index maps to the error code returned from getValidationError - see readme
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
            hiddenInput: "country",
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js", // just for formatting/placeholders etc
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

        input.addEventListener("keyup", function() {
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
    });
</script>
