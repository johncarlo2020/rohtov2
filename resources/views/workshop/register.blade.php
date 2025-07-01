<x-guest-layout>
    <div class="register-workflow with-scroll">
        <div class="justify-content-center w-100">
            <div class="mt-5 col-12 d-flex justify-content-center">
                @include('components.branding')
            </div>
            <div class="mt-3 w-100 px-2">
                <h1 class="mb-4 text-center heading-dutch text-center">Register for the workshop</h1>
                <div class="card register-info-box mb-3 p-3">
                    <h2 class="text-center mb-3 register-info-title">Workshop Info!</h2>
                    <ul class="register-info-list">
                        <li><strong>DIY Bento</strong> Kids only (Let the little chefs shine!)</li>
                        <li><strong>Sip & Paint</strong> For everyone, adults and kids welcome!</li>
                        <li>One session per customer, everyone gets a turn!</li>
                        <li>Please arrive 15 minutes early so you don't miss the fun!</li>
                    </ul>
                </div>
                <div class="card py-5 px-4 register-workflow-form-parent">
                    <form id="form" method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="mb-2 row">
                            <div class="col-12">
                                <label for="" class="text-blue"><strong>Guardian Name</strong></label>
                                <input id="fname" placeholder="Enter your name" type="text"
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
                                <label for="" class="text-blue"><strong>Date</strong></label>

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
                                <label for="" class="text-blue"><strong>Workshop Session</strong></label>
                                <span class="text-danger">*</span><small>Limited to 20 pax only</small>
                                <select class="form-select input-text" name="find" aria-label="Default select example"
                                    required>
                                    <option value="" selected disabled>Select your preferred workshop </option>

                                    <option value="">DIY Bento Workshop (2:00 PM - 3:00 PM) Slot 0/20</option>
                                    <option value="">Sip & Paint ( 4:00pm - 5:00pm ) Slot 0/20</option>
                                    <option value="">DIY Bento Workshop ( 7:00pm - 8:00pm) Slot 0/20</option>
                                </select>
                                @error('lname')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                <span class="text-danger"><strong>Slot 0/20</strong></span>
                            </div>
                        </div>

                        <div class="mb-2 row">
                            <div class="col-12 mb-3">
                                <label for="" class="text-blue"><strong>No. of Attendee</strong></label>
                                <span class="text-danger">*</span><small>No. of joining attendee</small>
                                <div class="quantity-selector">
                                    <button class="qty-btn minus">−</button>
                                        <input type="text" class="qty-input" value="1" readonly>
                                    <button class="qty-btn plus">+</button>
                                </div>
                                @error('lname')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
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
            </div>
        </div>
    </div>
</x-guest-layout>

<script>

    const minusBtn = document.querySelector('.minus');
    const plusBtn = document.querySelector('.plus');
    const qtyInput = document.querySelector('.qty-input');

    minusBtn.addEventListener('click', () => {
    let value = parseInt(qtyInput.value);
    if (value > 1) qtyInput.value = value - 1;
  });

  plusBtn.addEventListener('click', () => {
    let value = parseInt(qtyInput.value);
    qtyInput.value = value + 1;
  });

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
