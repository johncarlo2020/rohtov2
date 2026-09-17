<x-guest-layout>
    <div class="register-main with-scroll">
        <!-- Desktop Left Hero Image -->
        <div class="desktop-image-main">
            <img src="{{ asset('images/brand/main_img.webp') }}" alt="Longchamp Workshop">
        </div>

        <!-- Mobile Top Hero Image -->
        <div class="mobile-image-main">
            <img src="{{ asset('images/brand/main_img.webp') }}" alt="Longchamp Workshop">
        </div>

        <!-- Right / Bottom Content Area -->
        <div class="flex-parent">
            <!-- Top Logo (Desktop Only) -->
            <div class="top">
                @include('components.branding')
            </div>

            <!-- Middle Content -->
            <div class="mid">
                <h1 class="mb-3 text-center fw-bold text-dark text-uppercase" style="font-size: 1.35rem; letter-spacing: 1px;">
                    WORKSHOP <br> REGISTRATION
                </h1> 

                <div class="register-form-parent">
                    <form id="form" method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="pe-2">
                            {{-- Title --}}
                            <div class="mb-3">
                                <label class="field-label">Title:</label>
                                <div class="d-flex flex-column gap-2 mt-1">
                                    <label class="option-item">
                                        <input type="radio" name="title" value="Mr." id="title_mr" class="custom-square-radio" required
                                            {{ old('title') == 'Mr.' ? 'checked' : '' }}>
                                        <span class="option-label">Mr.</span>
                                    </label>
                                    <label class="option-item">
                                        <input type="radio" name="title" value="Ms." id="title_ms" class="custom-square-radio" required
                                            {{ old('title') == 'Ms.' ? 'checked' : '' }}>
                                        <span class="option-label">Ms.</span>
                                    </label>
                                    <label class="option-item">
                                        <input type="radio" name="title" value="Mrs." id="title_mrs" class="custom-square-radio" required
                                            {{ old('title') == 'Mrs.' ? 'checked' : '' }}>
                                        <span class="option-label">Mrs.</span>
                                    </label>
                                </div>
                                @error('title')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            {{-- First Name --}}
                            <div class="mb-3">
                                <label for="fname" class="field-label">FIRST Name:</label>
                                <input id="fname" placeholder="Enter Your Name" type="text"
                                    class="form-control input-text @error('fname') is-invalid @enderror" name="fname"
                                    value="{{ old('fname') }}" required autocomplete="given-name" />
                                @error('fname')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            {{-- Last Name --}}
                            <div class="mb-3">
                                <label for="lname" class="field-label">LAST Name:</label>
                                <input id="lname" placeholder="Enter Your Name" type="text"
                                    class="form-control input-text @error('lname') is-invalid @enderror" name="lname"
                                    value="{{ old('lname') }}" required autocomplete="family-name" />
                                @error('lname')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            {{-- Email address --}}
                            <div class="mb-3">
                                <label for="email" class="field-label">E-mail address:</label>
                                <input id="email" placeholder="Enter Your Email" type="email"
                                    class="form-control input-text @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" required autocomplete="email" />
                                @error('email')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            {{-- Phone number --}}
                            <div class="mb-3">
                                <label for="number" class="field-label">Phone number:</label>
                                <input id="number" placeholder="+60" type="tel"
                                    class="form-control input-text @error('number') is-invalid @enderror" name="number"
                                    value="{{ old('number') }}" autocomplete="tel" />
                                @error('number')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            {{-- Consent --}}
                            <div class="mt-4 mb-3">
                                <label class="consent-title">Consent</label>
                                <p class="consent-description">
                                    I agree that my information may be used to send me promotional offers, event invitations, or any other news from Maison Longchamp — <strong>several options available.</strong>
                                </p>
                                <div class="d-flex flex-column gap-2">
                                    <label class="option-item">
                                        <input type="checkbox" name="consent_channels[]" value="email" class="custom-square-check consent-channel-item"
                                            {{ is_array(old('consent_channels')) && in_array('email', old('consent_channels')) ? 'checked' : '' }}>
                                        <span class="option-label">By e-mail</span>
                                    </label>
                                    <label class="option-item">
                                        <input type="checkbox" name="consent_channels[]" value="sms" class="custom-square-check consent-channel-item"
                                            {{ is_array(old('consent_channels')) && in_array('sms', old('consent_channels')) ? 'checked' : '' }}>
                                        <span class="option-label">By SMS</span>
                                    </label>
                                    <label class="option-item">
                                        <input type="checkbox" name="consent_channels[]" value="whatsapp" class="custom-square-check consent-channel-item"
                                            {{ is_array(old('consent_channels')) && in_array('whatsapp', old('consent_channels')) ? 'checked' : '' }}>
                                        <span class="option-label">By WhatsApp (META)</span>
                                    </label>
                                    <label class="option-item">
                                        <input type="checkbox" name="consent_channels[]" value="phone" class="custom-square-check consent-channel-item"
                                            {{ is_array(old('consent_channels')) && in_array('phone', old('consent_channels')) ? 'checked' : '' }}>
                                        <span class="option-label">By phone</span>
                                    </label>
                                    <label class="option-item">
                                        <input type="checkbox" name="consent_channels[]" value="none" id="consent_none" class="custom-square-check consent-channel-none"
                                            {{ is_array(old('consent_channels')) && in_array('none', old('consent_channels')) ? 'checked' : '' }}>
                                        <span class="option-label">I prefer not to be contacted</span>
                                    </label>
                                </div>
                            </div>

                            {{-- SUBSCRIBE TO THE LONGCHAMP E-NEWSLETTER* --}}
                            <div class="mt-4 mb-3">
                                <label class="section-header-title">SUBSCRIBE TO THE LONGCHAMP E-NEWSLETTER*</label>
                                <div class="d-flex flex-column gap-2 mt-1">
                                    <label class="option-item">
                                        <input type="radio" name="newsletter_consent" value="1" class="custom-square-radio" required
                                            {{ old('newsletter_consent') === '1' ? 'checked' : '' }}>
                                        <span class="option-label">Yes, I would like to subscribe to receive the latest news, collections, events and exclusive experiences from Longchamp.</span>
                                    </label>
                                    <label class="option-item">
                                        <input type="radio" name="newsletter_consent" value="0" class="custom-square-radio" required
                                            {{ old('newsletter_consent') === '0' ? 'checked' : '' }}>
                                        <span class="option-label">No, I would not like to subscribe to receive the latest news, collections, events and exclusive experiences from Longchamp.</span>
                                    </label>
                                </div>
                                @error('newsletter_consent')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            {{-- COMMUNICATION CONSENT* --}}
                            <div class="mt-4 mb-3">
                                <label class="section-header-title">COMMUNICATION CONSENT*</label>
                                <div class="d-flex flex-column gap-2 mt-1">
                                    <label class="option-item">
                                        <input type="radio" name="communication_consent" value="1" class="custom-square-radio" required
                                            {{ old('communication_consent') === '1' ? 'checked' : '' }}>
                                        <span class="option-label">I agree to receive communications from Longchamp via my selected contact method regarding its products, services, events, and experiences.</span>
                                    </label>
                                    <label class="option-item">
                                        <input type="radio" name="communication_consent" value="0" class="custom-square-radio" required
                                            {{ old('communication_consent') === '0' ? 'checked' : '' }}>
                                        <span class="option-label">I do not agree to receive communications from Longchamp via my selected contact method regarding its products, services, events, and experiences.</span>
                                    </label>
                                </div>
                                @error('communication_consent')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-0 text-center mt-3">
                            <button id="submitButton" type="submit"
                                class="custom-btn custom-btn-primary mb-2" style="max-width: 220px; width: 100%;">
                                {{ __('REGISTER') }}
                            </button>
                            <br>
                            <div class="text-center mt-2">
                                <small class="text-dark text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">
                                    ALREADY REGISTERED? <a href="{{ route('login') }}" class="text-dark fw-bold text-decoration-none">LOGIN HERE</a>
                                </small>
                            </div>
                        </div>
                    </form>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const channelItems = document.querySelectorAll('.consent-channel-item');
                            const channelNone = document.querySelector('.consent-channel-none');

                            if (channelNone) {
                                channelNone.addEventListener('change', function() {
                                    if (this.checked) {
                                        channelItems.forEach(item => item.checked = false);
                                    }
                                });
                            }

                            channelItems.forEach(item => {
                                item.addEventListener('change', function() {
                                    if (this.checked && channelNone) {
                                        channelNone.checked = false;
                                    }
                                });
                            });

                            const phoneInput = document.getElementById('number');
                            if (phoneInput) {
                                phoneInput.addEventListener('blur', function() {
                                    let val = this.value.trim();
                                    if (val && !val.startsWith('+')) {
                                        let clean = val.replace(/[^\d+]/g, '');
                                        if (clean.startsWith('60')) {
                                            this.value = '+' + clean;
                                        } else if (clean.startsWith('0')) {
                                            this.value = '+60' + clean.substring(1);
                                        } else {
                                            this.value = '+60' + clean;
                                        }
                                    }
                                });
                            }
                        });
                    </script>
                </div>
            </div>

            <!-- Bottom Horse Logo -->
            <div class="col-12 bot">
                <div class="logo-bot d-flex justify-content-center mt-3">
                    <img src="{{ asset('images/brand/bot_logo.webp') }}" class="img-fluid" alt="Footer Image" srcset="" style="width: 4rem;">
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
