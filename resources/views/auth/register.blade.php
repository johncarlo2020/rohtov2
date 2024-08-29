<x-guest-layout>
<div class=" register-main">
        <div class="row justify-content-center">
            <div class="col-12 d-flex justify-content-center mt-5">
                @include('components.branding')
            </div>
            <div class="col-12 mt-5 px-5">

                <h1 class="text-center login-text mb-4">SIGN UP</h1>
                <form id="form" method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="row mb-2">
                        <div class="col-12">
                        <label for="">First Name</label>

                            <input id="fname" placeholder="John" type="text"
                                class="input-text form-control @error('fname') is-invalid @enderror" name="fname"
                                value="{{ old('fname') }}" required autocomplete="fname" autofocus>
                            @error('fname')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-12">
                        <label for="">Last Name</label>

                            <input id="lname" placeholder="Doe" type="text"
                                class="input-text form-control @error('lname') is-invalid @enderror" name="lname"
                                value="{{ old('lname') }}" required autocomplete="lname" autofocus>

                            @error('lname')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-12">
                            <label for="">Date of Birth</label>
                            <input id="dob" placeholder="Date of Birth" type="date"
                                class="input-text form-control @error('dob') is-invalid @enderror" name="dob"
                                value="{{ old('dob') }}" required autocomplete="dob" autofocus>

                            @error('lname')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-2">
                        <div class="col-12">
                        <label for="">Email Address</label>

                            <input id="email" placeholder="example@email.com" type="email"
                                class="input-text form-control @error('email') is-invalid @enderror" name="email"
                                value="{{ old('email') }}" required autocomplete="email">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-12 input-group w-100">
                        <label for="">Phone Number</label>

                            <input id="number" type="number"
                                class="input-text form-control w-100 @error('number') is-invalid @enderror" name="number"
                                value="{{ old('number') }}" required autocomplete="number" autofocus>
                        </div>
                        <div class="col-12 mt-2">
                            <span id="valid-msg" class="d-none text-danger"></span>
                            <span id="error-msg" class="d-none text-danger"></span>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-12 input-group">
                            <select class="form-select input-text" name="where" aria-label="Default select example">
                                <option selected disabled>Where do you find this event ?</option>
                                <option value="Facebook">Facebook</option>
                                <option value="TikTok">TikTok</option>
                                <option value="Instagram">Instagram</option>
                                <option value="XiaoHongShu (小红书)">XiaoHongShu (小红书)</option>
                                <option value="Friend Referral">Friend Referral</option>
                                <option value="Walk-in  ">Walk-in  </option>

                            </select>
                        </div>
                    </div>
                    <div class="row mb-2 mt-4">
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="privacy_policy" value="1"
                                    id="privacyPolicy" required>
                                <label class="form-check-label" for="privacyPolicy">
                                    I agree to the <a href="/privacy-policy">Privacy Policy</a>.
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="marketing" value="1"
                                    id="marketing">
                                <label class="form-check-label" for="marketing">

                                    I agree to receive marketing and promotional communications from Hadalabo via e-mail and
                                    text messages (including SMS/WhatsApp).
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-0 mt-5">
                        <div class="col-12">
                            <button id="submitButton" type="submit" class="main-btn btn btn-primary">
                                {{ __('SUBMIT') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>

<script>

 document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector("#form");
   const input = document.querySelector("#number");

   const errorMsg = document.querySelector("#error-msg");
    const validMsg = document.querySelector("#valid-msg");

// here, the index maps to the error code returned from getValidationError - see readme
    const errorMap = ["Invalid number", "Invalid country code", "Too short", "Too long", "Invalid number"];
    const submitButton = document.querySelector('#submitButton')
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

        input.addEventListener('keyup', function() {
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
