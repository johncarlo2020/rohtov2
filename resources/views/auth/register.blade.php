<x-guest-layout>
    <div class="register-main">
        <div class="justify-content-center w-100">
            <div class="mt-5 col-12 d-flex justify-content-center">
                @include('components.branding')
            </div>
            <div class="mt-3 w-100 px-4">
                <h1 class="mb-4 text-center login-text">SIGN UP</h1>
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
                            <label for="">Age Group</label>

                            <select class="form-select input-text" name="dob" aria-label="Default select example">
                                <option selected disabled></option>

                                <option value="Below 18 years old">Below 18 years old</option>
                                <option value="18-24 years old">18-24 years old</option>
                                <option value="25-30 years old">25-30 years old</option>
                                <option value="31-40 years old">31-40 years old</option>
                                <option value="41-45 years old">41-45 years old</option>
                                <option value="45 years and above">45 years and above</option>

                            </select>

                            @error('lname')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
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
                                name="number" value="{{ old('number') }}" required autocomplete="number" autofocus />
                        </div>
                        <div class="mt-2 col-12">
                            <span id="valid-msg" class="d-none text-danger"></span>
                            <span id="error-msg" class="d-none text-danger"></span>
                        </div>
                    </div>

                    <div class="mb-2 row">
                        <label for="">Have you heard about Hada Labo?</label>

                        <div class="col-12 input-group">

                            <select class="form-select input-text" name="heard" aria-label="Default select example" required>
                                <option value="No" selected>No</option>
                                <option value="Yes">Yes</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-2 row">
                        <label for="">Are you currently a Hada Labo user?</label>

                        <div class="col-12 input-group">
                            <select class="form-select input-text" name="existing" aria-label="Default select example" required>
                                <option value="No" selected>No</option>
                                <option value="Yes">Yes</option>
                            </select>
                        </div>
                    </div>



                    <!-- First Dropdown: Do you follow us on social media? -->
                    <div class="mb-2 row">
                        <label for="">Do you follow us on social media?</label>

                        <div class="col-12 input-group">
                            <select id="follow-select" class="form-select input-text" name="follow" required>
                                <option value="No" selected>No</option>
                                <option value="Yes">Yes</option>

                            </select>
                        </div>
                    </div>

                    <!-- Second Dropdown: What social media do you follow? -->
                    <div id="social-dropdown-group" class="mb-2 row d-none">
                        <label for="">What social media do you follow? <span class="text-danger">*Multiple</span></label>

                        <div class="col-12 input-group">
                            <select id="social-select" class="form-select" name="social_media[]" multiple>
                                <option value="Facebook">Facebook</option>
                                <option value="TikTok">TikTok</option>
                                <option value="Instagram">Instagram</option>
                                <option value="XiaoHongShu">XiaoHongShu (小红书)</option>
                            </select>
                        </div>
                    </div>


                    <div class="mb-2 row">
                        <div class="col-12 input-group">

                                <label for="">Which of the following appeals to you the most? <span class="text-danger"> *Choose 1 only</span></label>

                            <select class="form-select input-text" name="appeal" aria-label="Default select example" required>
                                 <option value="Promotional Discounts" selected> Promotional Discounts</option>
                                <option value="Event design/theme"> Event design/theme</option>
                                <option value="Free gifts and merchandise"> Free gifts and merchandise</option>
                            </select>
                        </div>
                    </div>



                    <div class="mb-2 row">
                        <div class="col-12 input-group">
                        <label for="">Where did you find out about our concourse? <span class="text-danger"> *Choose 1 only</span></label>

                            <select class="form-select input-text" name="find" aria-label="Default select example" required>
                                <option value="Facebook">Facebook</option>
                                <option value="TikTok">TikTok</option>
                                <option value="Instagram">Instagram</option>
                                <option value="XiaoHongShu (小红书)">
                                    XiaoHongShu (小红书)
                                </option>
                                <option value="Passby">
                                    Passby
                                </option>
                                <option value="Word of mouth" selected>Word of mouth</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4 mb-2 row">
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="privacy_policy" value="1"
                                    id="privacyPolicy" required />
                                <label class="form-check-label" for="privacyPolicy">
                                    I agree to the
                                    <a href="http://mentholatum.com.my/sunplay/personal-data-protection-notice.pdf">Privacy
                                        Policy</a>.
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
                                    I agree to receive marketing and promotional
                                    communications from Hadalabo Experience Malaysia (RMM) via e-mail and
                                    text messages (including SMS/WhatsApp).
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 mb-0 row">
                        <div class="col-12">
                            <button id="submitButton" type="submit" class="main-btn btn btn-primary">
                                {{ __('SUBMIT') }}
                            </button>
                            <div class="bottom-text">
                                <p class="already-register">
                                    Already Registered
                                </p>
                                <p class="already-register">
                                    Please Login
                                    <a href="{{ route('login') }}" class="">here</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>

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

        const followSelect = document.getElementById('follow-select');
        const socialSection = document.getElementById('social-dropdown-group');

        followSelect.addEventListener('change', function () {
            if (this.value === 'Yes') {
                socialSection.classList.remove('d-none');
            } else {
                socialSection.classList.add('d-none');
                // Reset checkboxes
                socialSection.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
            }
        });
    });
</script>
