<x-guest-layout>
    <div class="content-box main-background px-3 d-flex flex-column min-vh-100">
        <div class="container">
            <div>
                @include('components.branding')
            </div>
        </div>
        <div class="form-container p-4 mt-5 bg-white rounded">
            <h1 class="heading-text mb-4 text-center mb">
                LOG IN
            </h1>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-2 row">
                    <div class="col-12 input-group w-100 phone-number-input">
                        <label class="form-label" for="">Phone Number</label>

                        <input id="number" type="number"
                            class="input-text form-control w-100 @error('number') is-invalid @enderror d-block"
                            name="number" value="{{ old('number') }}" required autocomplete="number" autofocus />
                    </div>
                    <div class="mt-2 col-12">
                        <span id="valid-msg" class="d-none text-danger"></span>
                        <span id="error-msg" class="d-none text-danger"></span>
                    </div>
                </div>

                <!-- Password -->
                <x-text-input id="password" class="block w-full mt-1" type="hidden" name="password" value="password"
                    required autocomplete="current-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />

                <div class="flex items-center justify-end">
                    <x-primary-button class="button button-secondary w-100 mb-2">
                        Submit
                    </x-primary-button>
                </div>
            </form>
        </div>
        <div class="footer-container p-4 mt-auto">
            @include('components.footer')
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
