<x-guest-layout>
    <div class="rwg-page-container position-relative" style="max-width: 480px; padding-bottom: calc(env(safe-area-inset-bottom, 0px) + 4rem) !important;">
        <!-- Top Section: Back Link, Resorts World Genting Logo, Burger Menu -->
        <div class="d-flex align-items-center justify-content-between w-100 mb-4 pt-2">
            <a href="{{ route('dashboard') }}" class="text-white text-decoration-none p-2" aria-label="Back to Map">
                <i class="fa-solid fa-chevron-left fs-4"></i>
            </a>
            <div class="flex-grow-1 text-center">
                @include('components.branding')
            </div>
            <div class="dropdown rwg-menu-dropdown-wrapper">
                <button type="button"
                        class="rwg-menu-btn"
                        id="rwgBurgerMenuBtn"
                        data-bs-toggle="dropdown"
                        data-bs-display="static"
                        aria-expanded="false"
                        aria-label="Toggle Menu">
                    <i class="fa-solid fa-bars text-white fs-5"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end rwg-burger-dropdown" aria-labelledby="rwgBurgerMenuBtn">
                    <div class="rwg-dropdown-header d-flex justify-content-between align-items-center mb-3">
                        <span class="rwg-dropdown-title">Menu</span>
                        <button type="button" class="rwg-dropdown-close" data-bs-dismiss="dropdown" aria-label="Close">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <a href="{{ route('dashboard') }}" class="rwg-dropdown-item">
                            <span>MY MAP</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                        <a href="{{ route('profile.edit') }}" class="rwg-dropdown-item">
                            <span>MY PROFILE</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                        <a href="{{ route('faq') }}" class="rwg-dropdown-item active">
                            <span>FAQ</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ Content Card 1: Frequently Asked Questions (FAQ) -->
        <div class="w-100 p-4 rounded-4 shadow-lg mb-4"
             style="background: rgba(14, 5, 26, 0.85); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.15);"
             x-data="{ activeCategory: null }">

            <h2 class="text-center text-white fw-bold mb-4" style="font-size: 1.15rem; letter-spacing: 0.5px; color: #ffffff !important;">
                Frequently Asked Questions (FAQ)
            </h2>

            <div class="d-flex flex-column gap-3">
                <!-- 1. Getting Started & Access -->
                <div class="border-bottom border-white-50 pb-3">
                    <button type="button"
                            class="faq-accordion-header w-100 text-start bg-transparent border-0 d-flex align-items-center p-0"
                            style="font-size: 0.98rem;"
                            @click="activeCategory = activeCategory === 'start' ? null : 'start'">
                        <i class="fa-solid fa-chevron-right text-white me-2 small transition-transform"
                           :class="{ 'rotate-90': activeCategory === 'start' }"></i>
                        <span class="faq-title">1. Getting Started &amp; Access</span>
                    </button>
                    <div x-show="activeCategory === 'start'"
                         x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         class="mt-3 ps-2 pe-1 small">
                        <div class="mb-3">
                            <p class="faq-q-title mb-1">Q: What do I need to participate in the Treasure Hunt?</p>
                            <p class="faq-a-desc mb-0">A: All you need is a smartphone with an active internet connection and a working camera/QR scanner. No app download is required—everything is directly through your mobile web browser.</p>
                        </div>
                        <div class="mb-3">
                            <p class="faq-q-title mb-1">Q: Which web browsers are recommended?</p>
                            <p class="faq-a-desc mb-0">A: For the smoothest experience, we strongly recommend using Google Chrome (for Android users) or Safari (for iOS users). Please ensure your browser is updated to the latest version.</p>
                        </div>
                        <div>
                            <p class="faq-q-title mb-1">Q: Why am I seeing a "No Internet Connection" or loading error?</p>
                            <p class="faq-a-desc mb-0">A: Due to high visitor traffic or coverage areas around the resort, mobile data signals can occasionally fluctuate. Please check that your mobile data or Wi-Fi is enabled and stable, then refresh the page or re-scan the QR code.</p>
                        </div>
                    </div>
                </div>

                <!-- 2. Camera & QR Code Scanning -->
                <div class="border-bottom border-white-50 pb-3">
                    <button type="button"
                            class="faq-accordion-header w-100 text-start bg-transparent border-0 d-flex align-items-center p-0"
                            style="font-size: 0.98rem;"
                            @click="activeCategory = activeCategory === 'camera' ? null : 'camera'">
                        <i class="fa-solid fa-chevron-right text-white me-2 small transition-transform"
                           :class="{ 'rotate-90': activeCategory === 'camera' }"></i>
                        <span class="faq-title">2. Camera &amp; QR Code Scanning</span>
                    </button>
                    <div x-show="activeCategory === 'camera'"
                         x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         class="mt-3 ps-2 pe-1 small">
                        <div class="mb-3">
                            <p class="faq-q-title mb-1">Q: My camera is not opening or scanning the QR code. What should I do?</p>
                            <p class="faq-a-desc mb-0">A: Check your browser permissions and ensure you have allowed the browser access to your device's camera. If the in-browser scanner fails, try using your phone's default native camera app to scan the QR code directly, which will prompt you to open the link in your browser.<br>Also, avoid using in-app browsers of social apps (such as Instagram or Facebook browser) as they often block camera and device permissions.</p>
                        </div>
                        <div>
                            <p class="faq-q-title mb-1">Q: I scanned a station QR code, but it didn't register as completed.</p>
                            <p class="faq-a-desc mb-0">A: Make sure you are logged into your account. If the page does not automatically update to show the stamp, ensure you have an active internet connection and re-scan the station QR code.</p>
                        </div>
                    </div>
                </div>

                <!-- 3. Registration, Login & Entry Limits -->
                <div class="border-bottom border-white-50 pb-3">
                    <button type="button"
                            class="faq-accordion-header w-100 text-start bg-transparent border-0 d-flex align-items-center p-0"
                            style="font-size: 0.98rem;"
                            @click="activeCategory = activeCategory === 'reg' ? null : 'reg'">
                        <i class="fa-solid fa-chevron-right text-white me-2 small transition-transform"
                           :class="{ 'rotate-90': activeCategory === 'reg' }"></i>
                        <span class="faq-title">3. Registration, Login &amp; Entry Limits</span>
                    </button>
                    <div x-show="activeCategory === 'reg'"
                         x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         class="mt-3 ps-2 pe-1 small">
                        <div class="mb-3">
                            <p class="faq-q-title mb-1">Q: Can I play the treasure hunt multiple times?</p>
                            <p class="faq-a-desc mb-0">A: No. Participation is limited to one entry per single email address / GRC member ID account.</p>
                        </div>
                        <div class="mb-3">
                            <p class="faq-q-title mb-1">Q: I accidentally closed the browser window. Do I have to start the treasure hunt all over again?</p>
                            <p class="faq-a-desc mb-0">A: Not at all! If you leave the page and want to join back in, simply use the Login Page to sign back in using your registered details. Your existing progress and completed stations will be saved.</p>
                        </div>
                        <div>
                            <p class="faq-q-title mb-1">Q: Do I need an OTP or email verification to sign up?</p>
                            <p class="faq-a-desc mb-0">A: No OTP or complex verification is required. Registration is quick and straightforward—just enter your details to kickstart your journey.</p>
                        </div>
                    </div>
                </div>

                <!-- 4. Gameplay & Station Progress -->
                <div class="border-bottom border-white-50 pb-3">
                    <button type="button"
                            class="faq-accordion-header w-100 text-start bg-transparent border-0 d-flex align-items-center p-0"
                            style="font-size: 0.98rem;"
                            @click="activeCategory = activeCategory === 'progress' ? null : 'progress'">
                        <i class="fa-solid fa-chevron-right text-white me-2 small transition-transform"
                           :class="{ 'rotate-90': activeCategory === 'progress' }"></i>
                        <span class="faq-title">4. Gameplay &amp; Station Progress</span>
                    </button>
                    <div x-show="activeCategory === 'progress'"
                         x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         class="mt-3 ps-2 pe-1 small">
                        <div class="mb-3">
                            <p class="faq-q-title mb-1">Q: Can I visit the stations in any order?</p>
                            <p class="faq-a-desc mb-0">A: Yes! You are free to explore and choose any station from the Main Map in whichever order you prefer.</p>
                        </div>
                        <div>
                            <p class="faq-q-title mb-1">Q: How do I know if a station has been successfully completed?</p>
                            <p class="faq-a-desc mb-0">A: Once you find and scan the QR code at a designated location, the system will update your progress profile and unlock that station as "Completed" on your digital map.</p>
                        </div>
                    </div>
                </div>

                <!-- 5. Gift Redemption -->
                <div>
                    <button type="button"
                            class="faq-accordion-header w-100 text-start bg-transparent border-0 d-flex align-items-center p-0"
                            style="font-size: 0.98rem;"
                            @click="activeCategory = activeCategory === 'gift' ? null : 'gift'">
                        <i class="fa-solid fa-chevron-right text-white me-2 small transition-transform"
                           :class="{ 'rotate-90': activeCategory === 'gift' }"></i>
                        <span class="faq-title">5. Gift Redemption</span>
                    </button>
                    <div x-show="activeCategory === 'gift'"
                         x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         class="mt-3 ps-2 pe-1 small">
                        <div class="mb-3">
                            <p class="faq-q-title mb-1">Q: How do I redeem my gift or prize?</p>
                            <p class="faq-a-desc mb-0">A: You must successfully unlock all stations to trigger and access the final Gift Redemption Page.</p>
                        </div>
                        <div>
                            <p class="faq-q-title mb-1">Q: Why is the gift redemption button locked?</p>
                            <p class="faq-a-desc mb-0">A: The redemption button becomes active if and only if all stations on your checklist have been successfully completed. Check your main map to see which stations are still pending.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ Content Card 2: Treasure Hunt Frequent Asked Question (FAQ) -->
        <div class="w-100 p-4 rounded-4 shadow-lg mb-4"
             style="background: rgba(14, 5, 26, 0.85); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.15);"
             x-data="{ activeSection: null }">

            <h2 class="text-center text-white fw-bold mb-4" style="font-size: 1.15rem; letter-spacing: 0.5px; color: #ffffff !important;">
                Treasure Hunt Frequent Asked Question (FAQ)
            </h2>

            <div class="d-flex flex-column gap-3">
                <!-- General Participation -->
                <div class="border-bottom border-white-50 pb-3">
                    <button type="button"
                            class="faq-accordion-header w-100 text-start bg-transparent border-0 d-flex align-items-center p-0"
                            style="font-size: 0.98rem;"
                            @click="activeSection = activeSection === 'general' ? null : 'general'">
                        <i class="fa-solid fa-chevron-right text-white me-2 small transition-transform"
                           :class="{ 'rotate-90': activeSection === 'general' }"></i>
                        <span class="faq-title">General Participation</span>
                    </button>
                    <div x-show="activeSection === 'general'"
                         x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         class="mt-3 ps-2 pe-1 small">
                        <div class="mb-3">
                            <p class="faq-q-title mb-1">Q1: How do I participate in the Treasure Hunt?</p>
                            <p class="faq-a-desc mb-0">A: Scan the QR code displayed at participant locations and follow the instructions on the landing page to start the game.</p>
                        </div>
                        <div class="mb-3">
                            <p class="faq-q-title mb-1">Q2: Is registration required?</p>
                            <p class="faq-a-desc mb-0">A: Yes. Participants are required to provide basic information such as name, email address, date of birth, and GRC Member ID (if applicable) for participation and prize redemption purposes.</p>
                        </div>
                        <div class="mb-3">
                            <p class="faq-q-title mb-1">Q3: Is there any participation fee?</p>
                            <p class="faq-a-desc mb-0">A: No. Participation in the Treasure Hunt is free of charge.</p>
                        </div>
                        <div class="mb-3">
                            <p class="faq-q-title mb-1">Q4: What do I need to participate?</p>
                            <p class="faq-a-desc mb-0">A: All you need is a smartphone with an active internet connection and a functioning camera or QR scanner. No app download is required.</p>
                        </div>
                        <div class="mb-3">
                            <p class="faq-q-title mb-1">Q5: Can I participate if I am not a GRC member?</p>
                            <p class="faq-a-desc mb-0">A: Yes. Non-GRC members are welcome to participate unless otherwise specified in the campaign Terms &amp; Conditions.</p>
                        </div>
                        <div>
                            <p class="faq-q-title mb-1">Q6: Can I play the Treasure Hunt multiple times?</p>
                            <p class="faq-a-desc mb-0">A: No. Participation is limited to one entry per email address / Member ID.</p>
                        </div>
                    </div>
                </div>

                <!-- Game Mechanics -->
                <div class="border-bottom border-white-50 pb-3">
                    <button type="button"
                            class="faq-accordion-header w-100 text-start bg-transparent border-0 d-flex align-items-center p-0"
                            style="font-size: 0.98rem;"
                            @click="activeSection = activeSection === 'mechanics' ? null : 'mechanics'">
                        <i class="fa-solid fa-chevron-right text-white me-2 small transition-transform"
                           :class="{ 'rotate-90': activeSection === 'mechanics' }"></i>
                        <span class="faq-title">Game Mechanics</span>
                    </button>
                    <div x-show="activeSection === 'mechanics'"
                         x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         class="mt-3 ps-2 pe-1 small">
                        <div class="mb-3">
                            <p class="faq-q-title mb-1">Q1: Do I need to complete the missions on the same day I register?</p>
                            <p class="faq-a-desc mb-0">A: Yes. Participants must complete all required missions on the same day as their registration to be eligible for prize redemption.</p>
                        </div>
                        <div class="mb-3">
                            <p class="faq-q-title mb-1">Q2: What happens if I am unable to complete the missions on the same day?</p>
                            <p class="faq-a-desc mb-0">A: Participants who fail to complete all required missions on the day of registration will not be eligible for prize redemption.</p>
                        </div>
                        <div class="mb-3">
                            <p class="faq-q-title mb-1">Q3: Can I visit the stations in any order?</p>
                            <p class="faq-a-desc mb-0">A: Yes. Participants may visit and complete the stations in any order.</p>
                        </div>
                        <div class="mb-3">
                            <p class="faq-q-title mb-1">Q4: How do I know if a station has been successfully completed?</p>
                            <p class="faq-a-desc mb-0">A: Once a station QR code is successfully scanned, the system will update your progress dashboard and mark the station as completed.</p>
                        </div>
                        <div>
                            <p class="faq-q-title mb-1">Q5: I accidentally closed the browser. Do I need to start over?</p>
                            <p class="faq-a-desc mb-0">A: No. Simply log back in using your registered details. Your progress will be saved and you may continue where you left off.</p>
                        </div>
                    </div>
                </div>

                <!-- Prize Redemption -->
                <div class="border-bottom border-white-50 pb-3">
                    <button type="button"
                            class="faq-accordion-header w-100 text-start bg-transparent border-0 d-flex align-items-center p-0"
                            style="font-size: 0.98rem;"
                            @click="activeSection = activeSection === 'prizeredemption' ? null : 'prizeredemption'">
                        <i class="fa-solid fa-chevron-right text-white me-2 small transition-transform"
                           :class="{ 'rotate-90': activeSection === 'prizeredemption' }"></i>
                        <span class="faq-title">Prize Redemption</span>
                    </button>
                    <div x-show="activeSection === 'prizeredemption'"
                         x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         class="mt-3 ps-2 pe-1 small">
                        <div class="mb-3">
                            <p class="faq-q-title mb-1">Q1: How do I redeem my prize?</p>
                            <p class="faq-a-desc mb-0">A: Successfully complete all required stations to unlock the Gift Redemption Page and follow the instructions provided.</p>
                        </div>
                        <div class="mb-3">
                            <p class="faq-q-title mb-1">Q2: Why is my redemption button locked?</p>
                            <p class="faq-a-desc mb-0">A: The redemption button will only be activated after all required stations have been successfully completed.</p>
                        </div>
                        <div class="mb-3">
                            <p class="faq-q-title mb-1">Q3: Are prizes guaranteed upon completing the Treasure Hunt?</p>
                            <p class="faq-a-desc mb-0">A: No. Prizes are subject to daily allocation and availability.</p>
                        </div>
                        <div class="mb-3">
                            <p class="faq-q-title mb-1">Q4: Is there a limit to the number of prizes available?</p>
                            <p class="faq-a-desc mb-0">A: Yes. Daily prizes are distributed on a first-come, first-served basis while stocks last.</p>
                        </div>
                        <div>
                            <p class="faq-q-title mb-1">Q5: Can I redeem a prize on behalf of someone else?</p>
                            <p class="faq-a-desc mb-0">A: No. Prizes must be redeemed by the registered participant and verification may be required.</p>
                        </div>
                    </div>
                </div>

                <!-- Technical Support -->
                <div class="border-bottom border-white-50 pb-3">
                    <button type="button"
                            class="faq-accordion-header w-100 text-start bg-transparent border-0 d-flex align-items-center p-0"
                            style="font-size: 0.98rem;"
                            @click="activeSection = activeSection === 'technical' ? null : 'technical'">
                        <i class="fa-solid fa-chevron-right text-white me-2 small transition-transform"
                           :class="{ 'rotate-90': activeSection === 'technical' }"></i>
                        <span class="faq-title">Technical Support</span>
                    </button>
                    <div x-show="activeSection === 'technical'"
                         x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         class="mt-3 ps-2 pe-1 small">
                        <div class="mb-3">
                            <p class="faq-q-title mb-1">Q1: Which web browsers are recommended?</p>
                            <p class="faq-a-desc mb-0">A: We recommend using Google Chrome for Android devices and Safari for iOS devices. Please ensure your browser is updated to the latest version.</p>
                        </div>
                        <div class="mb-3">
                            <p class="faq-q-title mb-1">Q2: Why am I experiencing a "No Internet Connection" message?</p>
                            <p class="faq-a-desc mb-0">A: Mobile connectivity may fluctuate due to crowd density or location within the resort. Check your internet connection, try moving to an area with better reception, and refresh the page or re-scan the QR code.</p>
                        </div>
                        <div class="mb-3">
                            <p class="faq-q-title mb-1">Q3: The camera is not opening or scanning the QR code. What should I do?</p>
                            <p class="faq-a-desc mb-0">A: Check that you have allowed camera permissions for your browser. If issues persist, try restarting your browser or mobile device, or use your native camera app.</p>
                        </div>
                        <div class="mb-3">
                            <p class="faq-q-title mb-1">Q4: I scanned a QR code, but it does not register. What should I do?</p>
                            <p class="faq-a-desc mb-0">A: Ensure you are logged in to your account and have a stable internet connection. Re-scan the QR code or refresh the page.</p>
                        </div>
                        <div>
                            <p class="faq-q-title mb-1">Q5: Who should I contact if I encounter technical issues?</p>
                            <p class="faq-a-desc mb-0">A: Please approach our event team members at any station counter or reach out to our event support desk.</p>
                        </div>
                    </div>
                </div>

                <!-- Personal Data & Privacy -->
                <div class="border-bottom border-white-50 pb-3">
                    <button type="button"
                            class="faq-accordion-header w-100 text-start bg-transparent border-0 d-flex align-items-center p-0"
                            style="font-size: 0.98rem;"
                            @click="activeSection = activeSection === 'privacy' ? null : 'privacy'">
                        <i class="fa-solid fa-chevron-right text-white me-2 small transition-transform"
                           :class="{ 'rotate-90': activeSection === 'privacy' }"></i>
                        <span class="faq-title">Personal Data &amp; Privacy</span>
                    </button>
                    <div x-show="activeSection === 'privacy'"
                         x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         class="mt-3 ps-2 pe-1 small">
                        <div class="mb-3">
                            <p class="faq-q-title mb-1">Q1: What information will be collected?</p>
                            <p class="faq-a-desc mb-0">A: Information collected may include: Full Name, Email Address, Date of Birth, GRC Member ID (if applicable), and IP Address.</p>
                        </div>
                        <div class="mb-3">
                            <p class="faq-q-title mb-1">Q2: How will my personal information be used?</p>
                            <p class="faq-a-desc mb-0">A: Your information will only be used for: Participant registration and verification, Tracking game progress, Prize redemption and verification, Campaign analytics and reporting.</p>
                        </div>
                        <div class="mb-3">
                            <p class="faq-q-title mb-1">Q3: Is my personal information secure?</p>
                            <p class="faq-a-desc mb-0">A: Yes. We apply industry-standard security measures to safeguard your personal information and prevent unauthorized access or disclosure.</p>
                        </div>
                        <div class="mb-3">
                            <p class="faq-q-title mb-1">Q4: Who has access to my data?</p>
                            <p class="faq-a-desc mb-0">A: Only authorized personnel and service providers bound by strict confidentiality agreements will have access to your personal data.</p>
                        </div>
                        <div class="mb-3">
                            <p class="faq-q-title mb-1">Q5: Will my data be shared with third parties?</p>
                            <p class="faq-a-desc mb-0">A: No. Resorts World Genting does not sell or share personal data with external third parties without consent, except as required by law.</p>
                        </div>
                        <div class="mb-3">
                            <p class="faq-q-title mb-1">Q6: How long will my data be retained?</p>
                            <p class="faq-a-desc mb-0">A: Data will be retained in accordance with applicable data protection requirements and our current retention policies.</p>
                        </div>
                        <div>
                            <p class="faq-q-title mb-1">Q7: How can I access or update my personal data?</p>
                            <p class="faq-a-desc mb-0">A: You may update your profile directly within the My Profile section of this application.</p>
                        </div>
                    </div>
                </div>

                <!-- Terms & Conditions -->
                <div>
                    <button type="button"
                            class="faq-accordion-header w-100 text-start bg-transparent border-0 d-flex align-items-center p-0"
                            style="font-size: 0.98rem;"
                            @click="activeSection = activeSection === 'terms' ? null : 'terms'">
                        <i class="fa-solid fa-chevron-right text-white me-2 small transition-transform"
                           :class="{ 'rotate-90': activeSection === 'terms' }"></i>
                        <span class="faq-title">Terms &amp; Conditions</span>
                    </button>
                    <div x-show="activeSection === 'terms'"
                         x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         class="mt-3 ps-2 pe-1 small">
                        <div class="mb-3">
                            <p class="faq-q-title mb-1">Q1: Where can I find the Terms &amp; Conditions?</p>
                            <p class="faq-a-desc mb-0">A: Full Terms &amp; Conditions are available on the official Resorts World Genting website and event information page.</p>
                        </div>
                        <div class="mb-3">
                            <p class="faq-q-title mb-1">Q2: What happens if I provide incorrect information?</p>
                            <p class="faq-a-desc mb-0">A: Participants must ensure their profile information is accurate. Inaccurate details may result in disqualification or inability to redeem prizes.</p>
                        </div>
                        <div>
                            <p class="faq-q-title mb-1">Q3: Can the campaign rules change?</p>
                            <p class="faq-a-desc mb-0">A: Resorts World Genting reserves the right to amend the campaign mechanics, prize allocation, Terms &amp; Conditions, eligibility criteria, and other aspects of the Treasure Hunt without prior notice.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- General Disclaimer Box -->
            <div class="mt-4 pt-3 border-top border-white-50 text-center">
                <h3 class="text-white fw-bold mb-2" style="font-size: 0.95rem; color: #ffffff !important;">
                    General Disclaimer
                </h3>
                <p class="text-light small mb-0" style="font-size: 0.8rem; line-height: 1.5; color: rgba(255, 255, 255, 0.8) !important;">
                    Resorts World Genting reserves the right to amend the campaign mechanics, prize allocation, Terms &amp; Conditions, eligibility criteria, and other aspects of the Treasure Hunt without prior notice. In the event of any dispute, the decision of Resorts World Genting shall be final and binding.
                </p>
            </div>
        </div>

        <!-- Back Button -->
        <div class="text-center my-3 pb-4">
            <a href="{{ route('dashboard') }}" class="rwg-btn-gold text-decoration-none">
                BACK
            </a>
        </div>
    </div>

    <style>
        .rotate-90 {
            transform: rotate(90deg);
        }
        .transition-transform {
            transition: transform 0.2s ease;
        }
        .faq-accordion-header,
        .faq-accordion-header span,
        .faq-title,
        .faq-title * {
            color: #ffffff !important;
            font-weight: 700 !important;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.4);
        }
        .faq-accordion-header:hover,
        .faq-accordion-header:focus,
        .faq-accordion-header:hover .faq-title,
        .faq-accordion-header:focus .faq-title,
        .faq-accordion-header:hover i,
        .faq-accordion-header:focus i {
            color: #E8C88B !important;
        }
        .faq-q-title {
            color: #ffffff !important;
            font-weight: 700 !important;
            font-size: 0.92rem;
        }
        .faq-a-desc {
            color: #E2E8F0 !important;
            line-height: 1.5;
            font-size: 0.88rem;
        }
    </style>
</x-guest-layout>
