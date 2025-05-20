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
                        <input type="hidden" name="utm_source" value="{{ session('utm.source') }}">
                        <input type="hidden" name="utm_medium" value="{{ session('utm.medium') }}">
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

                        @error('dob')
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
                                id="privacyPolicy" />
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
                                id="privacyPolicy" required />
                            <label class="form-check-label" for="privacyPolicy">
                                I agree to receive marketing and promotional communications from Wardah via e-mail and text messages (including SMS/WhatsApp).
                            </label>
                        </div>
                    </div>
                </div>
                <div class="mb-3 d-flex justify-content-center">
                    <!-- Visible reCAPTCHA v2 widget -->
                    <div class="g-recaptcha" data-sitekey="6LfSnzorAAAAABAcoPooh89ujm8IKf5eyCsqm25y"
                        data-callback="onRecaptchaSuccess" data-expired-callback="onRecaptchaExpired"></div>
                </div>
                <div class="mb-0 row">
                    <div class="col-12">
                        <button id="submitButton" type="submit" class="button button-primary w-100 mb-4" disabled>
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
    ! function(w, d, t) {

        w.TiktokAnalyticsObject = t;
        var ttq = w[t] = w[t] || [];
        ttq.methods = ["page", "track", "identify", "instances", "debug", "on", "off", "once", "ready", "alias",
            "group", "enableCookie", "disableCookie"
        ], ttq.setAndDefer = function(t, e) {
            t[e] = function() {
                t.push([e].concat(Array.prototype.slice.call(arguments, 0)))
            }
        };
        for (var i = 0; i < ttq.methods.length; i++) ttq.setAndDefer(ttq, ttq.methods[i]);
        ttq.instance = function(t) {
            for (var e = ttq._i[t] || [], n = 0; n < ttq.methods.length; n++

            ) ttq.setAndDefer(e, ttq.methods[n]);
            return e
        }, ttq.load = function(e, n) {
            var i = "https://analytics.tiktok.com/i18n/pixel/events.js";
            ttq._i = ttq._i || {}, ttq._i[e] = [], ttq._i[e]._u = i, ttq._t = ttq._t || {}, ttq._t[e] = +new Date,
                ttq._o = ttq._o || {}, ttq._o[e] = n || {};
            n = document.createElement("script");
            n.type = "text/javascript", n.async = !0, n.src = i + "?sdkid=" + e + "&lib=" + t;
            e = document.getElementsByTagName("script")[0];
            e.parentNode.insertBefore(n, e)
        };

        ttq.track('PageView');

        ttq.load('CIHP63RC77U9G5MV8B0G');

        ttq.page();

    }(window, document, 'ttq');
</script>

<!-- Facebook Pixel Code -->
<!-- <script>
    ! function(f, b, e, v, n, t, s) {
        if (f.fbq) return;
        n = f.fbq = function() {
            n.callMethod ?
                n.callMethod.apply(n, arguments) : n.queue.push(arguments)
        };
        if (!f._fbq) f._fbq = n;
        n.push = n;
        n.loaded = !0;
        n.version = '2.0';
        n.queue = [];
        t = b.createElement(e);
        t.async = !0;
        t.src = v;
        s = b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t, s)
    }(window, document, 'script',
        'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '800121447587288');
    fbq('track', 'PageView');
</script> -->

<!-- Facebook Pixel Code -->
<script>
    ! function(f, b, e, v, n, t, s) {
        if (f.fbq) return;
        n = f.fbq = function() {
            n.callMethod ?
                n.callMethod.apply(n, arguments) : n.queue.push(arguments)
        };
        if (!f._fbq) f._fbq = n;
        n.push = n;
        n.loaded = !0;
        n.version = '2.0';
        n.queue = [];
        t = b.createElement(e);
        t.async = !0;
        t.src = v;
        s = b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t, s)
    }(window, document, 'script',
        'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '1857983581193229');
    fbq('track', 'PageView');
</script>
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
                // leave disabled until reCAPTCHA is completed
            } else {
                const errorCode = iti.getValidationError();
                const msg = errorMap[errorCode] || "Invalid number";
                showError(msg);
                submitButton.disabled = true;
            }
        });
    });

    // reCAPTCHA callback functions
    function onRecaptchaSuccess(token) {
        document.getElementById('submitButton').disabled = false;
    }

    function onRecaptchaExpired() {
        document.getElementById('submitButton').disabled = true;
    }
</script>
