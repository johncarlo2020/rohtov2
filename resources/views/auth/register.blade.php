<x-guest-layout>
    <div class="register-main with-scroll">
        <div class="justify-content-center w-100">
            <div class="mt-5 col-12 d-flex justify-content-center">
                @include('components.branding')
            </div>
            <div class="mt-3 w-100 px-2">
                <h1 class="mb-4 text-center heading-dutch">SIGN UP</h1>
                <div class="py-5 px-4 register-form-parent">
                    <form id="form" method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="mb-2 row">
                            <div class="col-12">
                                <label for="">First Name</label>
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
                                <label for="">Last Name</label>

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
                                <label for="dob">Date of Birth </label>

                                <input id="dob" placeholder="Enter your date of birth" type="date"
                                    class="input-text form-control @error('dob') is-invalid @enderror" name="dob"
                                    value="{{ old('dob') }}" required autocomplete="bday" autofocus />

                                @error('dob')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-2 row">
                            <div class="col-12 input-group">
                                <!-- <label for="">Where did you find this event?</label> -->

                                <select class="form-select input-text" name="race" aria-label="Default select example"
                                    required>
                                    <option value="" selected disabled>What's your race? </option>

                                    <option value="Malay">Malay</option>
                                    <option value="Indian">Indian</option>
                                    <option value="Chinese">Chinese</option>
                                    <option value="Kadazan">
                                        Kadazan
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-2 row">
                            <div class="col-12">
                                <label for="">Email Address</label>

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
                            <div class="col-12 input-group w-100">
                                <label for="">Phone Number</label>

                                <input id="number" type="number"
                                    class="input-text form-control w-100 @error('number') is-invalid @enderror"
                                    name="number" value="{{ old('number') }}" required autocomplete="number"
                                    autofocus />
                            </div>
                            <div class="mt-2 col-12">
                                <span id="valid-msg" class="d-none text-danger"></span>
                                <span id="error-msg" class="d-none text-danger"></span>
                            </div>
                        </div>

                        <div class="mb-2 row">
                            <div class="col-12 input-group">
                                <!-- <label for="">Where did you find this event?</label> -->

                                <select class="form-select input-text" name="find" aria-label="Default select example"
                                    required>
                                    <option value="" selected disabled>Where did you find this event? </option>

                                    <option value="Facebook">Facebook</option>
                                    <option value="TikTok">TikTok</option>
                                    <option value="Instagram">Instagram</option>
                                    <option value="XiaoHongShu (小红书)">
                                        XiaoHongShu (小红书)
                                    </option>
                                    <option value="Walk-in">
                                        Walk-in
                                    </option>
                                </select>
                            </div>
                        </div>
                        <hr>
                        <div class="mt-4 mb-2 row">
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privacy_policy" value="1"
                                        id="privacyPolicy" required />
                                    <label class="form-check-label text-white small-text" for="privacyPolicy">
                                        I have read and agree to the <a href="" class="text-white">Terms and
                                            Conditions</a> and <a href="" class="text-white">Privacy Policy</a>.
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4 row">
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="marketing" value="1"
                                        id="marketing" />
                                    <label class="form-check-label text-white small-text" for="marketing">
                                        I agree to receive marketing and promotional
                                        communications from Dutch Lady via e-mail and
                                        text messages (including SMS/WhatsApp).
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-0 row">
                            <div class="col-12 text-center">
                                <button id="submitButton" type="submit"
                                    class="w-100 custom-btn custom-btn-secondary animate-entry delay-3">
                                    {{ __('SUBMIT') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="bottom-text text-center">
                    <p class="already-register">
                        Already Registered
                    </p>
                    <p class="already-register">
                        Please Login
                        <a href="{{ route('login') }}" class="">here</a>
                    </p>
                </div>
            </div>
            <x-footer />
        </div>
    </div>
</x-guest-layout>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
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

    });
</script>
