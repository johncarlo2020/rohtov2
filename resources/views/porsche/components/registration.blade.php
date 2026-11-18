<section class="porsche-screen porsche-registration-screen" id="screen-registration" style="background-image: url('{{ asset(config('porsche.campaign.form_bg')) }}');">
    <div class="porsche-overlay"></div>
    <div class="porsche-content-wrapper porsche-content-registration">
        <form id="porsche-registration-form" novalidate class="porsche-registration-form-layout">
            @csrf

            <!-- Top Zone: Logo and Input Fields above the car -->
            <div class="porsche-reg-top-zone">
                @include('porsche.components.header')

                <div class="porsche-form-fields-container">
                    <div class="porsche-form-group">
                        <label for="reg-name" class="porsche-label">Name</label>
                        <div class="porsche-input-wrapper">
                            <span class="porsche-input-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </span>
                            <input type="text" id="reg-name" name="full_name" class="porsche-input porsche-input-with-icon" placeholder="Enter your full name" required autocomplete="name">
                        </div>
                        <span class="porsche-error-text" id="error-full_name"></span>
                    </div>

                    <div class="porsche-form-group">
                        <label for="reg-phone" class="porsche-label">Contact Number</label>
                        <div class="porsche-input-wrapper">
                            <span class="porsche-input-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                </svg>
                            </span>
                            <input type="tel" id="reg-phone" name="contact_number" class="porsche-input porsche-input-with-icon" placeholder="Enter your phone number" required autocomplete="tel">
                        </div>
                        <span class="porsche-error-text" id="error-contact_number"></span>
                    </div>

                    <div class="porsche-form-group">
                        <label for="reg-email" class="porsche-label">Email address</label>
                        <div class="porsche-input-wrapper">
                            <span class="porsche-input-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                                    <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                                </svg>
                            </span>
                            <input type="email" id="reg-email" name="email" class="porsche-input porsche-input-with-icon" placeholder="Enter your email address" required autocomplete="email">
                        </div>
                        <span class="porsche-error-text" id="error-email"></span>
                    </div>
                </div>
            </div>

            <!-- Bottom Zone: Consent & Continue button below the car -->
            <div class="porsche-reg-bottom-zone">
                <div class="porsche-form-group porsche-consent-group">
                    <label class="porsche-checkbox-label">
                        <input type="checkbox" id="reg-consent" name="consent" value="1" required>
                        <span class="porsche-custom-checkbox"></span>
                        <span class="porsche-consent-text">
                            I consent to the collection, use and/or disclosure of my personal data by Sime Darby Auto Performance Sdn Bhd, its related corporations and affiliates, and their authorised service providers and relevant third parties in accordance with the 
                            <a href="{{ config('porsche.campaign.privacy_policy_url') }}" target="_blank" rel="noopener noreferrer" class="porsche-link">Privacy Policy</a>.
                        </span>
                    </label>
                    <span class="porsche-error-text" id="error-consent"></span>
                </div>

                <div class="porsche-form-actions">
                    <button type="submit" class="porsche-btn porsche-btn-outline" id="btn-continue">
                        <span class="btn-text">Continue</span>
                        <span class="btn-loader d-none">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>
