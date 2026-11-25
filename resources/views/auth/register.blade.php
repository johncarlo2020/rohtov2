<x-guest-layout>
    <div class="rwg-page-container">
        <!-- Top Section: Resorts World Genting Logo -->
        <div class="d-flex justify-content-center mb-4 pt-2">
            @include('components.branding')
        </div>

        <!-- Page Title -->
        <h1 class="rwg-title">SIGN UP</h1>

        <!-- Registration Form with Alpine State -->
        <div class="w-100"
             x-data="{
                 dob: '{{ old('dob', '') }}',
                 isGrcMember: {{ old('grc_id') ? 'true' : 'false' }},
                 agreedPrivacy: {{ old('privacy_policy') ? 'true' : 'false' }},
                 agreedBiometric: {{ old('biometric_consent') ? 'true' : 'false' }},
                 agreedTerms: {{ old('terms_conditions') ? 'true' : 'false' }},
                 guardianConsent: {{ old('guardian_consent') ? 'true' : 'false' }},
                 showAgeModal: false,
                 underGuidanceInModal: false,
                 isUnder18() {
                     if (!this.dob) return false;
                     const birthDate = new Date(this.dob);
                     const today = new Date();
                     let age = today.getFullYear() - birthDate.getFullYear();
                     const m = today.getMonth() - birthDate.getMonth();
                     if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                         age--;
                     }
                     return age < 18;
                 },
                 checkAge() {
                     if (this.isUnder18() && !this.guardianConsent) {
                         this.showAgeModal = true;
                     }
                 },
                 confirmModalGuidance() {
                     if (this.underGuidanceInModal) {
                         this.guardianConsent = true;
                         this.showAgeModal = false;
                     }
                 },
                 canSubmit() {
                     const basic = this.agreedPrivacy && this.agreedBiometric && this.agreedTerms;
                     return this.isUnder18() ? (basic && this.guardianConsent) : basic;
                 }
             }">

            <form id="registerForm" method="POST" action="{{ route('register') }}" class="w-100">
                @csrf

                <!-- Hidden input for guardian consent -->
                <input type="hidden" name="guardian_consent" :value="guardianConsent ? '1' : ''">

                <!-- Full Name -->
                <div class="mb-3">
                    <label for="fname" class="rwg-label">Full Name</label>
                    <input id="fname"
                           name="fname"
                           type="text"
                           class="rwg-input @error('fname') is-invalid @enderror"
                           placeholder="Enter your full name"
                           value="{{ old('fname') }}"
                           required
                           autocomplete="name"
                           autofocus />
                    @error('fname')
                        <span class="text-danger small d-block mt-1"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <!-- Email Address -->
                <div class="mb-3">
                    <label for="email" class="rwg-label">Email Address</label>
                    <input id="email"
                           name="email"
                           type="email"
                           class="rwg-input @error('email') is-invalid @enderror"
                           placeholder="Enter your email"
                           value="{{ old('email') }}"
                           required
                           autocomplete="email" />
                    @error('email')
                        <span class="text-danger small d-block mt-1"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <!-- Date of Birth -->
                <div class="mb-3">
                    <label for="dob" class="rwg-label">Date of Birth</label>
                    <input id="dob"
                           name="dob"
                           type="date"
                           class="rwg-input @error('dob') is-invalid @enderror"
                           value="{{ old('dob') }}"
                           x-model="dob"
                           @change="checkAge()"
                           max="{{ date('Y-m-d') }}"
                           required />
                    @error('dob')
                        <span class="text-danger small d-block mt-1"><strong>{{ $message }}</strong></span>
                    @enderror

                    <!-- Under 18 Verification Status Indicator -->
                    <template x-if="isUnder18()">
                        <div class="mt-2 p-2 rounded d-flex align-items-center justify-content-between"
                             style="background: rgba(255, 255, 255, 0.12); border: 1px solid rgba(255, 255, 255, 0.25);">
                            <span class="small text-white" x-text="guardianConsent ? '✓ Guardian Guidance Confirmed' : '⚠️ Guardian guidance required for under 18'"></span>
                            <button type="button" class="btn btn-sm btn-outline-light py-0 px-2 small" @click="showAgeModal = true">
                                <span x-text="guardianConsent ? 'Review' : 'Verify'"></span>
                            </button>
                        </div>
                    </template>
                </div>

                <!-- GRC Member Checkbox & Conditional Input -->
                <div class="mb-3">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <input type="checkbox" id="is_grc_member" class="rwg-checkbox" x-model="isGrcMember" />
                        <label for="is_grc_member" class="text-white fw-bold small m-0 cursor-pointer" style="text-shadow: 0 1px 4px rgba(0,0,0,0.5); font-size: 0.95rem;">
                            Are you a GRC Member ID
                        </label>
                    </div>
                    <div x-show="isGrcMember" x-cloak x-transition class="mt-2">
                        <label for="grc_id" class="rwg-label">Key-in your GRC Member ID</label>
                        <input id="grc_id"
                               name="grc_id"
                               type="text"
                               class="rwg-input @error('grc_id') is-invalid @enderror"
                               placeholder="Your GRC Member ID"
                               value="{{ old('grc_id') }}" />
                        @error('grc_id')
                            <span class="text-danger small d-block mt-1"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                </div>

                <hr class="rwg-divider" />

                <!-- Declaration Header -->
                <p class="text-white fw-bold mb-3" style="font-size: 0.92rem; text-shadow: 0 1px 4px rgba(0,0,0,0.5);">
                    By signing up, I hereby expressly declare that:
                </p>

                <!-- Declaration Checkbox 1 -->
                <div class="d-flex align-items-start gap-2 mb-3">
                    <input type="checkbox" id="declaration_pdps" name="privacy_policy" value="1" class="rwg-checkbox mt-1" x-model="agreedPrivacy" required />
                    <label for="declaration_pdps" class="rwg-declaration-text">
                        I hereby expressly declare that I have read, understood and voluntarily and unconditionally agreed to the terms and conditions stipulated in the Personal Data Processing Statement and hereby consent to the use and processing of my Personal Data in the manner described in the Personal Data Processing Statement.
                    </label>
                </div>
                @error('privacy_policy')
                    <span class="text-danger small d-block mb-2"><strong>{{ $message }}</strong></span>
                @enderror

                <!-- Declaration Checkbox 2 -->
                <div class="d-flex align-items-start gap-2 mb-3">
                    <input type="checkbox" id="declaration_biometric" name="biometric_consent" value="1" class="rwg-checkbox mt-1" x-model="agreedBiometric" required />
                    <label for="declaration_biometric" class="rwg-declaration-text">
                        I hereby give my explicit consent to the collection, usage, processing, and storage of my Personal Data, including sensitive personal data such as biometric information (including but not limited to fingerprints, facial recognition data, live detection data, and any other biometric features that may be implemented in the future), in the manner and for the purposes set out in the Personal Data Protection Statement (PDPS) that was shown to me or accessed by me via <a href="https://www.rwgenting.com/PrivacyPolicy" target="_blank" rel="noopener noreferrer" class="text-white text-decoration-underline fw-bold">https://www.rwgenting.com/PrivacyPolicy</a>
                    </label>
                </div>
                @error('biometric_consent')
                    <span class="text-danger small d-block mb-2"><strong>{{ $message }}</strong></span>
                @enderror

                <!-- Declaration Checkbox 3 -->
                <div class="d-flex align-items-start gap-2 mb-4">
                    <input type="checkbox" id="declaration_loyalty" name="terms_conditions" value="1" class="rwg-checkbox mt-1" x-model="agreedTerms" required />
                    <label for="declaration_loyalty" class="rwg-declaration-text">
                        I have read, understood, voluntarily and unconditionally agreed and accepted the <a href="https://www.rwgenting.com" target="_blank" rel="noopener noreferrer" class="text-white text-decoration-underline fw-bold">Terms and Conditions of the Genting Rewards Loyalty Programme</a>
                    </label>
                </div>
                @error('terms_conditions')
                    <span class="text-danger small d-block mb-2"><strong>{{ $message }}</strong></span>
                @enderror

                <!-- Submit Button -->
                <div class="mb-4 text-center">
                    <button id="submitButton"
                            type="submit"
                            class="rwg-btn-gold"
                            :disabled="!canSubmit()"
                            :class="{ 'opacity-50': !canSubmit() }">
                        SUBMIT
                    </button>
                </div>
            </form>

            <!-- Bottom Link -->
            <div class="rwg-footer-link mb-2">
                ALREADY REGISTERED? <a href="{{ route('login') }}">LOGIN HERE</a>
            </div>

            <!-- Age Verification Modal for Under 18 -->
            <div x-show="showAgeModal"
                 x-cloak
                 class="rwg-age-modal-backdrop"
                 style="position: fixed; inset: 0; background: rgba(10, 5, 20, 0.75); backdrop-filter: blur(6px); z-index: 10500; display: flex; align-items: center; justify-content: center; padding: 1.25rem;">
                
                <div class="rwg-age-modal-card animate-entry"
                     @click.outside="if (underGuidanceInModal) showAgeModal = false"
                     style="background: #ffffff; border-radius: 24px; padding: 2rem 1.5rem; max-width: 320px; width: 100%; box-shadow: 0 15px 35px rgba(0, 0, 0, 0.45); text-align: center;">

                    <!-- Red Exclamation Circle -->
                    <div class="d-flex align-items-center justify-content-center mx-auto mb-3"
                         style="width: 52px; height: 52px; border-radius: 50%; border: 2.5px solid #E03546; color: #E03546; font-size: 1.85rem; font-weight: 700; line-height: 1;">
                        !
                    </div>

                    <!-- Title -->
                    <h5 class="fw-bold mb-2 text-dark" style="font-size: 1.05rem; letter-spacing: -0.2px;">
                        Age Verification:
                    </h5>

                    <!-- Description -->
                    <p class="text-secondary small mb-3" style="font-size: 0.85rem; line-height: 1.45; color: #4A4A4A !important;">
                        As you are under 18, please ensure you are accessing this website under the supervision of a parent or legal guardian.
                    </p>

                    <!-- Checkbox -->
                    <div class="d-flex align-items-center justify-content-center gap-2 mb-3">
                        <input type="checkbox" id="modalUnderGuidance" class="rwg-checkbox" x-model="underGuidanceInModal" />
                        <label for="modalUnderGuidance" class="text-dark small fw-semibold cursor-pointer m-0" style="font-size: 0.88rem;">
                            I am under guidance
                        </label>
                    </div>

                    <!-- Confirm Button -->
                    <div>
                        <button type="button"
                                class="rwg-btn-gold py-2"
                                :disabled="!underGuidanceInModal"
                                :class="{ 'opacity-50': !underGuidanceInModal }"
                                @click="confirmModalGuidance()">
                            CONFIRM
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
