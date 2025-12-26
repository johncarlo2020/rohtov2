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
        font-family: 'Brevia' !important;
    }
    </style>
    <div class="register-main main-content with-scroll">
        <div class="justify-content-center w-100">
            <div class="col-12 animate-entry mb-4">
                @include('components.branding')
            </div>
            <h2 class="mx-4 text-center sub-heading-text animate-entry">DAFTAR</h2>
            <div class=" mt-4 mb-3 w-100  animate-entry delay-3 bg-white p-3 card-parent">
                <div class="py-3 register-form-parent">
                    <form id="form" method="POST" action="{{ route('register') }}">
                        @csrf
                        <input type="hidden" name="dialCode" id="dialCode" ></input>
                        <input type="hidden" name="countryIso" id="countryIso">
                        <div class="mb-3 row">
                            <div class="col-12">
                                <label for="fname" class="text-white">Nama <span class="text-danger">*</span></label>
                                <input id="fname" placeholder="Masukkan nama anda" type="text"
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
                                <label for="lname" class="text-white">Nama Keluarga <span class="text-danger">*</span></label>
                                <input id="lname" placeholder="Masukkan nama keluarga anda" type="text"
                                    class="input-text form-control @error('lname') is-invalid @enderror" name="lname"
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
                                <label for="dob" class="text-white">Tarikh Lahir <span class="text-danger">*</span></label>
                                <input id="dob" placeholder="" type="date"
                                    class="input-text form-control @error('dob') is-invalid @enderror" name="dob"
                                    value="{{ old('dob') }}" required autocomplete="fname" autofocus />
                                @error('dob')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>


                        <div class="mb-3 row">
                            <div class="col-12">
                                <label for="email" class="text-white">Alamat e-mel <span class="text-danger">*</span></label>

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

                        <div class="mb-3 row">
                            <div class="col-12 input-group w-100">
                                <label for="number" class="text-white">Nombor telefon <span class="text-danger">*</span></label>

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

                         <div class="mb-2 row">
                            <div class="col-12 input-group">
                                <!-- <label for="">Where did you find this event?</label> -->

                                <select class="form-select input-text" name="find" aria-label="Default select example"
                                    required>
                                    <option value="" selected disabled>Dari mana anda mendapat tahu tentang acara ini? </option>

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
                                    <label class="form-check-label text-white" for="privacyPolicy">
                                        Saya telah membaca dan bersetuju dengan Terma dan Syarat serta Polisi Privasi.
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-2 row">
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="marketing" value="1"
                                        id="marketing" />
                                    <label class="form-check-label text-white" for="marketing">
                                        Saya bersetuju menerima maklumat pemasaran dan promosi daripada Dutch Lady melalui e-mel dan mesej teks (SMS/WhatsApp).
                                    </label>
                                </div>
                            </div>
                        </div>

                         @error('country')
                            <div class="text-danger text-center mb-2">{!! $message !!}</div> 
                        @enderror

                       
                        <div class="mb-0 row">
                            <div class="col-12 text-center">
                                <button id="submitButton" type="submit"
                                    class="custom-btn custom-btn-primary animate-entry delay-3">
                                    {{ __('HANTAR') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bottom-text text-center mb-3">
                    <span class="already-register text-white">
                        <strong>Sudah Daftar!</strong>
                    </span>
                    <br>
                    <span class="already-register text-white">
                        Log Masuk
                        <a href="{{ route('login') }}" class="text-white"><strong>disini</strong></a>
                    </span>
                </div>
        </div>
        <x-footer/>
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
