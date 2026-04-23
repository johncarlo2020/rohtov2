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
                                <label for="">Full Name</label>
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
                            <label for="preferredLocation">Preferred Property Location</label>
                            <div class="col-12 input-group">
                                
                                <select 
                                    id="preferredLocation" 
                                    class="form-select input-text" 
                                    multiple 
                                    name="locations[]" 
                                    required
                                >
                                    <option disabled>Select up to 3 locations</option>

                                    @foreach($locations as $location)
                                        <option value="{{ $location }}"
                                            {{ collect(old('locations'))->contains($location) ? 'selected' : '' }}>
                                            {{ $location }}
                                        </option>
                                    @endforeach

                                </select>
                            <small id="errorMsg" class="text-danger"></small>
                        </div>

                        <div class="mb-2 row">
                             <label for="">Property Budget</label>
                            <div class="col-12 input-group">
                              
                                @php
                                $budgets = [
                                    'RM1 million and above',
                                    'RM700K - RM999K',
                                    'RM500K - RM699K',
                                    'Below RM500K'
                                ];
                                @endphp

                                <select class="form-select input-text" name="property_budget" required>
                                    <option value="" disabled {{ old('property_budget') ? '' : 'selected' }}>
                                        Select 1 property budget
                                    </option>

                                    @foreach($budgets as $budget)
                                        <option value="{{ $budget }}" 
                                            {{ old('property_budget') == $budget ? 'selected' : '' }}>
                                            {{ $budget }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <hr>
                        <div class="mt-4 mb-2 row">
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privacy_policy" value="1"
                                        id="privacyPolicy" required />
                                    <label class="form-check-label text-dark" for="privacyPolicy">
                                        I have read and agree to the <a href="https://www.iproperty.com.my/privacy-policy/" class="text-primary">Terms and
                                            Conditions</a>. and <a href="https://www.iproperty.com.my/terms-and-conditions/" class="text-primary">Privacy Policy</a>.
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-0 row">
                            <div class="col-12 text-center">
                                <button id="submitButton" type="submit"
                                    class="w-auto main-btn button-dutch button-dutch-primary">
                                    {{ __('SUBMIT') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
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
    </div>
</x-guest-layout>

<script>
    document.addEventListener("DOMContentLoaded", function () {
    //     const form = document.querySelector("#form");
    //     const input = document.querySelector("#number");

    //     const errorMsg = document.querySelector("#error-msg");
    //     const validMsg = document.querySelector("#valid-msg");

    //     // here, the index maps to the error code returned from getValidationError - see readme
    //     const errorMap = [
    //         "Invalid number",
    //         "Invalid country code",
    //         "Too short",
    //         "Too long",
    //         "Invalid number",
    //     ];
    //     const submitButton = document.querySelector("#submitButton");
    //     const iti = window.intlTelInput(input, {
    //         initialCountry: "my",
    //         hiddenInput: "country",
    //         utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js", // just for formatting/placeholders etc
    //     });

    //     const reset = () => {
    //         input.classList.remove("error");
    //         errorMsg.innerHTML = "";
    //         errorMsg.classList.add("d-none");
    //         validMsg.classList.add("d-none");
    //     };

    //     const showError = (msg) => {
    //         input.classList.add("error");
    //         errorMsg.innerHTML = msg;
    //         errorMsg.classList.remove("d-none");
    //     };

    //     input.addEventListener("keyup", function () {
    //         reset();
    //         if (!input.value.trim()) {
    //             showError("Required");
    //             submitButton.disabled = true;
    //         } else if (iti.isValidNumber()) {
    //             validMsg.classList.remove("d-none");
    //             submitButton.disabled = false;
    //         } else {
    //             const errorCode = iti.getValidationError();
    //             const msg = errorMap[errorCode] || "Invalid number";
    //             showError(msg);
    //             submitButton.disabled = true;
    //         }
    //     });

    const select = document.getElementById('preferredLocation');
    const max = 3;
    const errorMsg = document.getElementById('errorMsg');

    select.addEventListener('change', function () {
        const selected = Array.from(this.selectedOptions);

        if (selected.length > max) {
            // remove last selected
            selected[selected.length - 1].selected = false;
            errorMsg.textContent = "You can only select up to 3 options.";
        } else {
            errorMsg.textContent = "";
        }
    });

    });

    
</script>