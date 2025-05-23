<x-guest-layout>
    <div class="content-box main-background">
        <div class="container">
            <div>
                @include('components.branding')
            </div>
        </div>
        <div class="form-container px-4 mt-5 fade-in">
            <form id="form" method="POST" action="{{ route('register') }}">
                @csrf
                <h1 class="heading-text text-center">SIGN UP</h1>
                {{-- <div class="upload-picture text-center mb-3">
                    <img id="imagePreview" src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTUwIiBoZWlnaHQ9IjE1MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48Y2lyY2xlIGN4PSI3NSIgY3k9Ijc1IiByPSI3MCIgZmlsbD0iI2VlZSIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBkb21pbmFudC1iYXNlbGluZT0ibWlkZGxlIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmb250LWZhbWlseT0ic2Fucy1zZXJpZiIgZm9udC1zaXplPSIxMnB4IiBmaWxsPSIjNzc3Ij5VcGxvYWQ8L3RleHQ+PC9zdmc+" alt="Image Preview" class="img-fluid rounded-circle mb-2"
                        style="width: 150px; height: 150px; object-fit: cover; display: block; margin: 0 auto;" />
                    <input type="file" name="profile_picture" id="profile_picture" style="display: none;" accept="image/*">
                    <label for="profile_picture" class="button button-secondary w-100 mt-2">Upload Picture</label>
                </div> --}}
                <!-- reCAPTCHA token -->
                <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response" />
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
                        <label class="form-label" for="age_group">Age Group</label>
                        <select id="age_group" name="age_group" class="form-select input-text form-control @error('age_group') is-invalid @enderror" required>
                            <option value="" selected>Select Age Group</option>
                            <option value="13-19">13-19</option>
                            <option value="20-29">20-29</option>
                            <option value="30-39">30-39</option>
                            <option value="40-49">40-49</option>
                            <option value="50-59">50-59</option>
                            <option value="60-69">60-69</option>
                            <option value="Others">Others</option>
                        </select>

                        @error('age_group')
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

                        <input id="number" type="tel"
                            class="input-text form-control w-100 @error('country') is-invalid @enderror d-block"
                            name="number" value="{{ old('number') }}" required autocomplete="tel" autofocus />

                    </div>
                    <div class="mt-2 col-12">
                        <span id="valid-msg" class="d-none text-danger"></span>
                        <span id="error-msg" class="d-none text-danger"></span>
                        @error('country')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="devider w-100 h-25 border-dashed border-bottom mb-3"></div>
                <div class="mb-2 row">
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="privacy_policy" value="1"
                                id="privacyPolicy" required />
                            <label class="form-check-label" for="privacyPolicy">
                               I have read and agree to the Terms and Conditions and Privacy Policy.
                            </label>

                        </div>
                    </div>
                </div>
                <div class="mb-2 row">
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="privacy_policy" value="1"
                                id="privacyPolicy" />
                            <label class="form-check-label" for="privacyPolicy">
                                I agree to receive marketing and promotional communications from Adidas via e-mail and text messages (including SMS/WhatsApp).
                            </label>
                        </div>
                    </div>
                </div>
                {{-- <div class="mb-3 d-flex justify-content-center">
                    <!-- Visible reCAPTCHA v2 widget -->
                    <div class="g-recaptcha" data-sitekey="6LfSnzorAAAAABAcoPooh89ujm8IKf5eyCsqm25y"
                        data-callback="onRecaptchaSuccess" data-expired-callback="onRecaptchaExpired"></div>
                </div> --}}
                <div class="mb-0 row">
                    <div class="col-12">
                        <button id="submitButton" type="submit" class="button button-primary w-100 mb-4" >
                            {{ __('SUBMIT') }}
                        </button>
                        <div class="bottom-text text-center">
                            <p class="pharagraph-text mb-0">Already Register</p>
                            <p class="pharagraph-text">Please log in here  <a class="" href="{{ route('login') }}" class="">here</a></p>
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
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        /*
        const profilePictureInput = document.querySelector("#profile_picture");
        const imagePreview = document.querySelector("#imagePreview");
        const placeholderSrc = "data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTUwIiBoZWlnaHQ9IjE1MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48Y2lyY2xlIGN4PSI3NSIgY3k9Ijc1IiByPSI3MCIgZmlsbD0iI2VlZSIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBkb21pbmFudC1iYXNlbGluZT0ibWlkZGxlIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmb250LWZhbWlseT0ic2Fucy1zZXJpZiIgZm9udC1zaXplPSIxMnB4IiBmaWxsPSIjNzc3Ij5VcGxvYWQ8L3RleHQ+PC9zdmc+";

        profilePictureInput.addEventListener("change", function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = \'block\';
                }
                reader.readAsDataURL(file);
            } else {
                imagePreview.src = placeholderSrc;
                imagePreview.style.display = \'block\';
            }
        });
        */

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
            autoPlaceholder: "aggressive",
            nationalMode: false,
            separateDialCode: true, // Add this line
            customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
              // selectedCountryPlaceholder will now be the national part, e.g., "12-345 6789"
              // This will remove only the dashes and spaces
              return selectedCountryPlaceholder.replace(/-|\s/g, "");
            }
        });

        const reset = () => {
            // Client-side intl-tel-input error reset
            input.classList.remove("error"); // Removes client-side error class from intl-tel-input
            errorMsg.innerHTML = ""; // Clears client-side JS error message span
            errorMsg.classList.add("d-none");
            validMsg.classList.add("d-none");

            // Server-side Laravel validation error reset for the phone number
            input.classList.remove("is-invalid"); // Removes Bootstrap's 'is-invalid' class from the input

            // Find and hide the server-side error message span for the 'country' (phone number) field
            const backendErrorContainer = input.closest('.mb-2.row'); // Get the parent row of the input
            if (backendErrorContainer) {
                const backendErrorSpan = backendErrorContainer.querySelector('.mt-2.col-12 .invalid-feedback[role="alert"]');
                if (backendErrorSpan) {
                    backendErrorSpan.classList.add("d-none"); // Hide the span
                    backendErrorSpan.classList.remove("d-block"); // Ensure d-block is removed if present
                }
            }
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
                // submitButton.disabled = true;
            } else if (iti.isValidNumber()) {
                validMsg.classList.remove("d-none");
                // leave disabled until reCAPTCHA is completed
            } else {
                const errorCode = iti.getValidationError();
                const msg = errorMap[errorCode] || "Invalid number";
                showError(msg);
                // submitButton.disabled = true;
            }
        }); // End of keyup listener for #number (phone)

        // Handle validation message for email input
        const emailInput = document.querySelector("#email");
        if (emailInput) {
            emailInput.addEventListener("keyup", function() {
                // Remove the 'is-invalid' class from the input field
                emailInput.classList.remove("is-invalid");

                // Find the corresponding error message span and hide it
                // The error span is a sibling of the input within the same parent div.col-12
                const parentCol = emailInput.parentElement;
                if (parentCol) {
                    const emailErrorSpan = parentCol.querySelector('span.invalid-feedback[role="alert"]');
                    if (emailErrorSpan) {
                        // Add 'd-none' to hide the span. Bootstrap 5 should also hide it
                        // when 'is-invalid' is removed from the input, but this is a safeguard.
                        emailErrorSpan.classList.add("d-none");
                    }
                }
            });
        }

    }); // End of DOMContentLoaded

    // reCAPTCHA callback functions
    function onRecaptchaSuccess(token) {
        // document.getElementById('submitButton').disabled = false;
    }

    function onRecaptchaExpired() {
        // document.getElementById('submitButton').disabled = true;
    }
</script>
