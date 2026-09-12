<x-guest-layout>
    <style>
    @media (max-width: 430px) {
        .bottom-text
        {
            margin-bottom:5%;
        }
    }
    @media (min-width: 992px) {
        .register-fields-scroll {
            max-height: 42vh;
            overflow-y: auto;
            overflow-x: hidden;
            padding-right: 8px;
            margin-bottom: 0.5rem;
        }
        .register-fields-scroll::-webkit-scrollbar {
            width: 5px;
        }
        .register-fields-scroll::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        .register-fields-scroll::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 4px;
        }
        .register-fields-scroll::-webkit-scrollbar-thumb:hover {
            background: #999;
        }
    }
    </style>
    <div class="register-main with-scroll row">
        <div class="col-lg-8 desktop-image-main d-flex align-items-center">
            <img src="{{ asset('images/brand/main_img.webp') }}" alt="Hero Image" srcset="">
        </div>
        <div class="flex-parent col-lg-4 d-flex flex-column justify-content-between gap-5">
                <div class="top">
                    <div class="d-flex justify-content-center col-12">
                        @include('components.branding')
                    </div>
                </div>
                <div class="mid-top">
                    <div class="col-lg-8 mobile-image-main">
                        <img src="{{ asset('images/brand/main_img.webp') }}" class="img-fluid hero-image" alt="Hero Image" srcset="">
                    </div>
                </div>
                <div class="mid">
                    <div class="mt-2 w-100">
                        <h1 class="mt-3 mb-5 text-center fw-bold text-dark text-uppercase" style="letter-spacing: 1px;">WORKSHOP REGISTRATION</h1> 
                        <div class="register-form-parent">
                            <form id="form" method="POST" action="{{ route('register') }}">
                                @csrf

                                <div x-data="{ agreed: false }">
                                    <div class="register-fields-scroll">
                                        {{-- Title --}}
                                        <div class="mb-3">
                                            <label for="title" class="fw-bold text-dark text-uppercase mb-1">TITLE:</label>
                                            <select id="title" name="title" class="form-select input-text @error('title') is-invalid @enderror" required>
                                                <option value="" disabled {{ old('title') ? '' : 'selected' }} hidden>TITLE SELECTION</option>
                                                <option value="MR." {{ old('title') == 'MR.' ? 'selected' : '' }}>MR.</option>
                                                <option value="MS." {{ old('title') == 'MS.' ? 'selected' : '' }}>MS.</option>
                                                <option value="MRS." {{ old('title') == 'MRS.' ? 'selected' : '' }}>MRS.</option>
                                                <option value="MISS" {{ old('title') == 'MISS' ? 'selected' : '' }}>MISS</option>
                                                <option value="OTHER" {{ old('title') == 'OTHER' ? 'selected' : '' }}>OTHER</option>
                                            </select>
                                            @error('title')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        {{-- Last Name --}}
                                        <div class="mb-3">
                                            <label for="lname" class="fw-bold text-dark text-uppercase mb-1">LAST NAME:</label>
                                            <input id="lname" placeholder="ENTER YOUR NAME" type="text"
                                                class="form-control input-text @error('lname') is-invalid @enderror" name="lname"
                                                value="{{ old('lname') }}" required autocomplete="lname" />
                                            @error('lname')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        {{-- First Name --}}
                                        <div class="mb-3">
                                            <label for="fname" class="fw-bold text-dark text-uppercase mb-1">FIRST NAME:</label>
                                            <input id="fname" placeholder="ENTER YOUR NAME" type="text"
                                                class="form-control input-text @error('fname') is-invalid @enderror" name="fname"
                                                value="{{ old('fname') }}" required autocomplete="fname" />
                                            @error('fname')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        {{-- Email --}}
                                        <div class="mb-3">
                                            <label for="email" class="fw-bold text-dark text-uppercase mb-1">EMAIL:</label>
                                            <input id="email" placeholder="ENTER YOUR EMAIL" type="email"
                                                class="form-control input-text @error('email') is-invalid @enderror" name="email"
                                                value="{{ old('email') }}" required autocomplete="email" />
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        {{-- Preferred Contact --}}
                                        <div class="mb-3">
                                            <label for="preferred_contact" class="fw-bold text-dark text-uppercase mb-1">PREFERRED CONTACT:</label>
                                            <select id="preferred_contact" name="preferred_contact" class="form-select input-text @error('preferred_contact') is-invalid @enderror" required>
                                                <option value="" disabled {{ old('preferred_contact') ? '' : 'selected' }} hidden>CONTACT SELECTION</option>
                                                <option value="EMAIL" {{ old('preferred_contact') == 'EMAIL' ? 'selected' : '' }}>EMAIL</option>
                                                <option value="WHATSAPP" {{ old('preferred_contact') == 'WHATSAPP' ? 'selected' : '' }}>WHATSAPP</option>
                                            </select>
                                            @error('preferred_contact')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        {{-- Communication Consent & Terms/Conditions --}}
                                        <div class="mt-4 mb-3">
                                            <label class="fw-bold text-dark text-uppercase mb-1">COMMUNICATION CONSENT*</label>
                                            <div class="form-check d-flex align-items-start gap-2 mb-3">
                                                <input class="form-check-input mt-1 me-2" type="checkbox" name="communication_consent"
                                                    value="1" id="communicationConsent" {{ old('communication_consent') ? 'checked' : '' }} />
                                                <label class="text-dark form-check-label" for="communicationConsent" style="text-transform: none; font-size: 0.78rem; line-height: 1.35; letter-spacing: 0; font-weight:400;">
                                                    I agree to receive communications from Longchamp via my selected contact method regarding its products, services, events, offers and experiences.
                                                </label>
                                            </div>

                                            <label class="fw-bold text-dark text-uppercase mb-1">TERMS & CONDITIONS*</label>
                                            <div class="form-check d-flex align-items-start gap-2 mb-2">
                                                <input class="form-check-input mt-1 me-2" type="checkbox" name="privacy_policy"
                                                    value="1" id="privacyPolicy" x-model="agreed" required />
                                                <label class="text-dark form-check-label" for="privacyPolicy" style="text-transform: none; font-size: 0.78rem; line-height: 1.35; letter-spacing: 0; font-weight:400;">
                                                    I have read and agree to the Terms & Conditions and acknowledge the Privacy Policy.
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-0 text-center">
                                        <button id="submitButton" type="submit"
                                            class="mt-3 mb-2 w-50 custom-btn custom-btn-primary pulse-slow" :disabled="!agreed"
                                            :class="{ 'opacity-50': !agreed }">
                                            {{ __('REGISTER') }}
                                        </button>
                                        <br>
                                        <small class="already-register text-dark" style="margin-top:1rem;">ALREADY REGISTERED? <a href="{{ route('login') }}" class="text-dark fw-bold">LOGIN HERE</a></small>
                                    </div>
                                    <div class="logo-bot d-flex justify-content-center mt-3">
                                        <img src="{{ asset('images/brand/bot_logo.webp') }}" class="img-fluid" alt="Footer Image" srcset="" style="width:4rem;">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- <div class="col-12 bot">
                    <div class="logo-bot d-flex justify-content-center">
                        <img src="{{ asset('images/brand/bot_logo.webp') }}" class="img-fluid w-25" alt="Login Image" srcset="">
                    </div>
                </div> -->
        </div>
    </div>
</x-guest-layout>
