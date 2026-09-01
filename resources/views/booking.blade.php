<x-guest-layout>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        .brand-orange-text { color: #e86034 !important; }
        .brand-orange-bg { background-color: #e86034 !important; color: #ffffff !important; border: none; }
        .brand-orange-bg:hover, .brand-orange-bg:focus { background-color: #d44f25 !important; color: #ffffff !important; }

        .main-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
        }

        .selected-pill {
            border: 2px solid #e86034 !important;
            background-color: rgba(232, 96, 52, 0.03) !important;
        }

        .dropdown-overlay {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            z-index: 1050;
            margin-top: 0.375rem;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .custom-scroll {
            max-height: 250px;
            overflow-y: auto;
        }
        .custom-scroll::-webkit-scrollbar { width: 5px; }
        .custom-scroll::-webkit-scrollbar-track { background: #f1f5f9; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; }

        .step-fade { animation: fadeIn 0.25s ease-in-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(4px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .ticket-box {
            border: 2px dashed #ef4444;
            padding: 1.5rem;
            background: #ffffff;
        }

        .modal-backdrop-custom {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(4px);
            z-index: 2000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .modal-dialog-custom {
            border: 4px solid #e86034;
            background: #ffffff;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
            padding: 1.5rem;
            max-width: 380px;
            width: 100%;
        }

        .cursor-pointer { cursor: pointer; }
        .cursor-not-allowed { cursor: not-allowed; }

        .date-row:hover:not(.opacity-50),
        .slot-row:hover:not(.opacity-50) {
            background-color: #f8fafc;
        }

        button:disabled,
        .custom-btn:disabled,
        .custom-btn.disabled {
            background-color: #cbd5e1 !important;
            color: #94a3b8 !important;
            border-color: #cbd5e1 !important;
            cursor: not-allowed !important;
            box-shadow: none !important;
            animation: none !important;
            opacity: 0.7;
        }
    </style>

    <div class="register-main with-scroll row">
        <div class="col-lg-8 desktop-image-main">
            <img src="{{ asset('images/brand/main_img.webp') }}" alt="Login Image" srcset="">
        </div>
        <div class="flex-parent col-lg-4 d-flex flex-column justify-content-between">
            <div class="top">
                <div class="d-flex justify-content-center col-12">
                    @include('components.branding')
                </div>
            </div>
            <!-- <div class="mid-top">
                <div class="col-lg-8 mobile-image-main">
                    <img src="{{ asset('images/brand/main_img.webp') }}" alt="Login Image" srcset="">
                </div>
            </div> -->
            <div class="mid">
                <div class="px-2 w-100 m-auto">
                    <!-- Main Card -->
                    <main>
                        
                        <!-- BOOKING FLOW FORM -->
                        <form id="reservation-form" onsubmit="event.preventDefault();">
                            <!-- Header Text -->
                            <div class="text-center my-4">
                                @if(isset($formattedBooking) && $formattedBooking && $formattedBooking['reschedule_count'] < 1)
                                    <!-- MODIFY RESERVATION HEADER (MATCHES USER SCREENSHOT) -->
                                    <h2 class="h4 fw-bold text-dark mb-3 text-uppercase">HEY {{ $formattedBooking['first_name'] }},</h2>
                                    <p class="small text-dark fw-bold text-uppercase mb-2 leading-snug">
                                        WE CAN ONLY CHANGE YOUR BOOKING <span class="fw-black text-dark">ONCE</span>.<br>
                                        YOUR NEW SELECTION IS FINAL AND DEPENDS ENTIRELY ON SLOT AVAILABILITY FOR THAT SPECIFIC DAY.
                                    </p>
                                    <div class="p-2.5 bg-light border text-dark small fw-bold text-uppercase rounded-0 my-3">
                                        CURRENT BOOKING: <span class="fw-black">{{ $formattedBooking['display_text'] }}</span>
                                    </div>
                                @else
                                    <!-- NEW RESERVATION HEADER -->
                                    <h2 class="h4 fw-bold text-dark mb-4 text-uppercase">Hey {{ auth()->check() && isset(auth()->user()->fname) ? auth()->user()->fname : 'Guest' }}!</h2>
                                    <p class="small text-secondary text-uppercase mb-0 leading-snug text-dark">
                                        PLEASE CHOOSE YOUR PREFERRED DATE AND TIME SLOT,<br>
                                        KEEPING IN MIND THAT <br>
                                        <span class="fw-bold text-dark">YOU CAN ONLY RESCHEDULE ONCE,<br>
                                        AT LEAST ONE WEEK BEFORE YOUR SLOT.</span>
                                    </p>
                                    <p class="small fw-bold text-danger text-uppercase mt-2 mb-0">
                                        SUBJECT TO AVAILABILITY*
                                    </p>
                                @endif
                            </div>

                            <!-- Global Alert Banner -->
                            <div id="alert-banner" class="d-none mb-3 p-3 rounded-0 alert alert-danger small fw-bold d-flex align-items-center gap-2">
                                <i id="alert-icon" data-lucide="alert-circle" style="width: 16px; height: 16px;"></i>
                                <div id="alert-message" class="flex-grow-1"></div>
                            </div>

                            <!-- SECTION 1: DATE SELECTION -->
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-uppercase text-dark mb-1">
                                    DATE AVAILABLE:
                                </label>

                                <div class="position-relative">
                                    <!-- Date Selection Box (Collapsed / Selected Display) -->
                                    <div id="date-trigger-box" class="form-control d-flex justify-content-between align-items-center py-3 px-3 bg-white border cursor-pointer rounded-0">
                                        <span id="date-box-text" class="text-muted fw-bold">DATE SELECTION</span>
                                        <i data-lucide="chevron-down" id="date-chevron" class="text-muted" style="width: 18px; height: 18px; transition: transform 0.2s;"></i>
                                    </div>

                                    <!-- Date Selection Expanded Dropdown Box (Overlay) -->
                                    <div id="date-dropdown-box" class="d-none dropdown-overlay p-3">
                                        <div class="small fw-bold text-dark text-uppercase pb-2 mb-2 border-bottom d-flex justify-content-between align-items-center">
                                            <span>DATE SELECTION</span>
                                            <span>30 SEP – 18 OCT 2026</span>
                                        </div>

                                        <!-- Date Items List -->
                                        <div id="date-items-list" class="custom-scroll">
                                            <!-- Dynamically populated -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- SECTION 2: TIME SLOTS SELECTION (Appears once Date is Selected) -->
                            <div id="time-slots-section" class="d-none mb-4 step-fade">
                                <label class="form-label small fw-bold text-uppercase text-dark mb-1">
                                    TIME SLOTS:
                                </label>

                                <div class="position-relative">
                                    <!-- Time Slot Trigger Box (Collapsed / Selected Display) -->
                                    <div id="time-trigger-box" class="form-control d-flex justify-content-between align-items-center py-3 px-3 bg-white border cursor-pointer rounded-0">
                                        <span id="time-box-text" class="text-muted fw-bold">SELECT YOUR TIME SLOT</span>
                                        <i data-lucide="chevron-down" id="time-chevron" class="text-muted" style="width: 18px; height: 18px; transition: transform 0.2s;"></i>
                                    </div>

                                    <!-- Time Slot Expanded Dropdown Box (Overlay) -->
                                    <div id="time-dropdown-box" class="d-none dropdown-overlay p-3">
                                        <div class="small fw-bold text-muted text-uppercase pb-2 mb-2 border-bottom">
                                            <span id="sessions-per-day-header">2 SESSIONS PER DAY</span>
                                        </div>

                                        <!-- Time Slot Items List -->
                                        <div id="time-items-list">
                                            <!-- Dynamically populated -->
                                        </div>
                                    </div>
                                </div>

                                <!-- 1 Hour Session Note -->
                                <div id="session-note" class="mt-2 small fw-bold text-danger text-uppercase">
                                    * 1 HOUR SESSION
                                </div>
                            </div>

                            <!-- NEXT BUTTON -->
                            <div class="mt-4 text-center">
                                <button id="next-btn" disabled type="button" class="custom-btn custom-btn-primary mb-2 pulse-slow w-50">
                                    NEXT
                                </button>
                            </div>

                            <!-- Terms Link -->
                            <div class="text-center mt-3">
                                <a href="{{ url('/terms-and-conditions') }}" class="small fw-bold text-muted text-decoration-underline text-uppercase">
                                    TERMS & CONDITIONS
                                </a>
                            </div>

                        </form>

                        <!-- BOOKING CONFIRMED SUCCESS SCREEN -->
                        <div id="confirmation-success-screen" class="d-none text-center py-2 step-fade">

                            <!-- Title -->
                            <h2 class="h4 fw-bold brand-orange-text text-uppercase mb-2">
                                BOOKING CONFIRMED!
                            </h2>

                            <!-- Subtitle -->
                            <p class="small fw-bold text-dark text-uppercase mb-4">
                                HI <span id="confirmed-greeting-name">CUSTOMER</span>,
                                YOUR IS CONFIRMED
                            </p>
                            <p class="small text-dark mb-4">
                              PLEASE CHECK YOUR EMAIL FOR YOUR<br>CONFIRMATION DETAILS AND PRESENT THIS <br> QR CODE UPON ARRIVAL  
                            </p>
                            <!-- Red Dashed Ticket Container -->
                            <div id="ticket-container" class="ticket-box d-inline-block w-100 mb-4 text-center" style="max-width: 320px;">
                                
                                <!-- Dynamic QR Code Image -->
                                <img id="qr-code-img" src="" alt="Booking QR Code" class="img-fluid mb-3" style="width: 170px; height: 170px; margin:auto; object-fit: contain;" crossorigin="anonymous">

                                <!-- Customer Name -->
                                <div id="confirmed-ticket-name" class="fw-bold text-dark text-uppercase mb-2">
                                    JOSHUA
                                </div>

                                <!-- Details List -->
                                <div class="small fw-bold text-dark text-uppercase">
                                    <div><span class="text-muted">DATE:</span> <span id="confirmed-ticket-date">7TH OCTOBER</span></div>
                                    <div class="my-1"><span class="text-muted">TIME:</span> <span id="confirmed-ticket-time">6:00PM</span></div>
                                    <div class="mt-2 text-muted">
                                        <span class="text-muted">VENUE:</span> LONGCHAMP POP UP STORE THE GARDENS MALL
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons (CHANGE YOUR SLOT / DOWNLOAD) -->
                            <div class="d-flex flex-column gap-2 mx-auto" style="max-width: 320px;">
                                <button id="modify-btn" type="button" class="custom-btn custom-btn-primary pulse-slow w-50 m-auto">
                                    MODIFY
                                </button>
                                <button id="download-btn" type="button" class="custom-btn custom-btn-primary pulse-slow w-50 m-auto">
                                    DOWNLOAD
                                </button>
                            </div>

                        </div>
                    </main>
                </div>
            </div>
            <div class="col-12 bot">
                <div class="logo-bot d-flex justify-content-center mt-4">
                    <img src="{{ asset('images/brand/bot_logo.webp') }}" class="img-fluid w-25" alt="Login Image" srcset="">
                </div>
            </div>
        </div>
    </div>

    <!-- REVIEW MODAL POPUP ("ALMOST THERE!") -->
    <div id="review-modal-overlay" class="d-none modal-backdrop-custom step-fade">
        <div class="modal-dialog-custom text-center">
            
            <!-- Modal Title -->
            <h3 class="h4 fw-bold brand-orange-text text-uppercase mb-2">
                ALMOST THERE!
            </h3>

            <!-- Subtitle -->
            <p class="small fw-bold text-dark text-uppercase mb-4">
                PLEASE REVIEW YOUR BOOKING DETAILS BEFORE CONFIRMING.
            </p>

            <!-- Review Items -->
            <div class="mb-4 text-center">
                <div class="mb-3">
                    <div class="small text-muted fw-bold text-uppercase">DATE:</div>
                    <div id="modal-review-date" class="fw-bold text-dark text-uppercase">7TH OCTOBER</div>
                </div>

                <div class="mb-3">
                    <div class="small text-muted fw-bold text-uppercase">TIME:</div>
                    <div id="modal-review-time" class="fw-bold text-dark text-uppercase">6:00PM</div>
                </div>

                <div>
                    <div class="small text-muted fw-bold text-uppercase">VENUE:</div>
                    <div class="small fw-bold text-dark text-uppercase">
                        LONGCHAMP POP UP STORE THE GARDENS MALL
                    </div>
                </div>
            </div>

            <!-- Modal Action Buttons -->
            <div class="d-flex flex-column gap-2">
                <button id="modal-back-btn" type="button" class="btn btn-link text-dark fw-bold text-uppercase p-0 text-decoration-none mb-1">
                    BACK
                </button>
                <button id="modal-confirm-btn" type="button" class="custom-btn custom-btn-primary pulse-slow w-50 m-auto">
                    CONFIRM
                </button>
            </div>

        </div>
    </div>

    <!-- UNAVAILABLE SLOT ERROR MODAL ("OH, NO!") -->
    <div id="slot-error-modal-overlay" class="d-none modal-backdrop-custom step-fade">
        <div class="modal-dialog-custom text-center">
            
            <!-- Modal Title -->
            <h3 class="h4 fw-bold brand-orange-text text-uppercase mb-3">
                OH, NO!
            </h3>

            <!-- Message -->
            <p id="slot-error-modal-message" class="small fw-bold text-dark text-uppercase mb-4">
                LOOKS LIKE THIS SLOT HAS JUST BEEN TAKEN. PLEASE CHOOSE ANOTHER PREFERRED DATE.
            </p>

            <!-- Action Button -->
            <button id="slot-error-back-btn" type="button" class="custom-btn custom-btn-primary pulse-slow w-50 m-auto">
                BACK
            </button>
        </div>
    </div>

    <!-- Application Logic Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();

            const START_DATE = '2026-09-30';
            const END_DATE = '2026-10-18';

            let state = {
                selectedDate: null,
                selectedDateFormatted: null,
                selectedSlotId: null,
                selectedSlotLabel: null,
                dateAvailabilities: [],
                slots: [],
                bookingResult: null,
                isModifying: false,
                modifyingRefNo: null,
                dateDropdownOpen: false,
                timeDropdownOpen: false
            };

            const alertBanner = document.getElementById('alert-banner');
            const alertMessage = document.getElementById('alert-message');

            function showAlert(msg, type = 'error') {
                alertBanner.classList.remove('d-none', 'alert-danger', 'alert-warning');
                if (type === 'error') {
                    alertBanner.classList.add('alert-danger');
                } else {
                    alertBanner.classList.add('alert-warning');
                }
                alertMessage.textContent = msg;
            }

            function clearAlert() {
                alertBanner.classList.add('d-none');
                alertMessage.textContent = '';
            }

            // Ordinal Date Helper
            function getOrdinalSuffix(day) {
                if (day > 3 && day < 21) return 'TH';
                switch (day % 10) {
                    case 1:  return "ST";
                    case 2:  return "ND";
                    case 3:  return "RD";
                    default: return "TH";
                }
            }

            function formatDateOrdinal(dateStr) {
                const parts = dateStr.split('-');
                const year = parseInt(parts[0]);
                const month = parseInt(parts[1]) - 1;
                const day = parseInt(parts[2]);
                const d = new Date(year, month, day);
                const monthName = d.toLocaleString('default', { month: 'long' }).toUpperCase();
                return `${day}${getOrdinalSuffix(day)} ${monthName}`;
            }

            // Fetch Date Availabilities (30 Sep - 18 Oct 2026)
            async function fetchDateAvailabilities() {
                try {
                    const res = await fetch(`/api/booking/dates?start_date=${START_DATE}&end_date=${END_DATE}`);
                    const data = await res.json();
                    state.dateAvailabilities = data;

                    renderDateDropdown(data);
                } catch (err) {
                    console.error('Error fetching date availability:', err);
                    showAlert('Failed to load date availability.');
                }
            }

            // Render Date Dropdown List
            function renderDateDropdown(items) {
                const container = document.getElementById('date-items-list');
                container.innerHTML = '';

                // Group by Month
                const grouped = {};
                items.forEach(item => {
                    const parts = item.date.split('-');
                    const year = parts[0];
                    const monthIdx = parseInt(parts[1]) - 1;
                    const d = new Date(year, monthIdx, 1);
                    const monthKey = d.toLocaleString('default', { month: 'long' }).toUpperCase();

                    if (!grouped[monthKey]) grouped[monthKey] = [];
                    grouped[monthKey].push(item);
                });

                Object.keys(grouped).forEach(monthName => {
                    const monthGroup = document.createElement('div');
                    monthGroup.className = 'mb-2';

                    const monthHeader = document.createElement('div');
                    monthHeader.className = 'small fw-bold text-muted text-uppercase mb-1 px-1';
                    monthHeader.textContent = monthName;
                    monthGroup.appendChild(monthHeader);

                    const rowsContainer = document.createElement('div');
                    rowsContainer.className = 'd-flex flex-column gap-1';

                    grouped[monthName].forEach(item => {
                        const dateRow = document.createElement('div');
                        const isSelected = state.selectedDate === item.date;
                        const isAvailable = item.status === 'available';
                        const formattedLabel = formatDateOrdinal(item.date);

                        let rowClasses = 'date-row d-flex align-items-center justify-between px-3 py-2 rounded-0 transition small fw-bold text-uppercase cursor-pointer ';

                        if (isSelected) {
                            rowClasses += 'selected-pill text-dark';
                        } else if (isAvailable) {
                            rowClasses += 'text-dark';
                        } else {
                            rowClasses += 'opacity-50 cursor-not-allowed text-muted';
                        }

                        dateRow.className = rowClasses;

                        let statusSpan = '';
                        if (isSelected) {
                            statusSpan = `<svg style="width: 18px; height: 18px;" class="brand-orange-text" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>`;
                        } else if (item.status === 'available') {
                            statusSpan = `<span class="small fw-bold text-success text-uppercase">AVAILABLE</span>`;
                        } else if (item.status === 'full') {
                            statusSpan = `<span class="small fw-bold text-muted text-uppercase">FULLY BOOKED</span>`;
                        } else {
                            statusSpan = `<span class="small fw-bold text-muted text-uppercase">CLOSED</span>`;
                        }

                        dateRow.innerHTML = `
                            <span>${formattedLabel}</span>
                            ${statusSpan}
                        `;

                        if (isAvailable) {
                            dateRow.addEventListener('click', () => {
                                state.selectedDate = item.date;
                                state.selectedDateFormatted = formattedLabel;

                                document.getElementById('date-box-text').textContent = formattedLabel;
                                document.getElementById('date-box-text').className = 'text-dark fw-bold';
                                toggleDateDropdown(false);

                                renderDateDropdown(state.dateAvailabilities);
                                loadSlotsForSelectedDate(item.date);
                            });
                        }

                        rowsContainer.appendChild(dateRow);
                    });

                    monthGroup.appendChild(rowsContainer);
                    container.appendChild(monthGroup);
                });
            }

            // Toggle Date Dropdown Open/Close
            const dateTriggerBox = document.getElementById('date-trigger-box');
            const dateDropdownBox = document.getElementById('date-dropdown-box');
            const dateChevron = document.getElementById('date-chevron');

            function toggleDateDropdown(open = null) {
                state.dateDropdownOpen = open !== null ? open : !state.dateDropdownOpen;
                if (state.dateDropdownOpen) {
                    if (state.timeDropdownOpen) toggleTimeDropdown(false);
                    dateDropdownBox.classList.remove('d-none');
                    dateChevron.style.transform = 'rotate(180deg)';
                } else {
                    dateDropdownBox.classList.add('d-none');
                    dateChevron.style.transform = 'rotate(0deg)';
                }
            }

            dateTriggerBox.addEventListener('click', (e) => {
                e.stopPropagation();
                toggleDateDropdown();
            });

            // Load Slots for Step 2
            async function loadSlotsForSelectedDate(dateStr) {
                clearAlert();

                document.getElementById('time-slots-section').classList.remove('d-none');

                const d = new Date(dateStr + 'T00:00:00');
                const dayOfWeek = d.getDay(); // 6 = Saturday
                const headerText = dayOfWeek === 6 ? '4 SESSIONS PER DAY' : '2 SESSIONS PER DAY';
                document.getElementById('sessions-per-day-header').textContent = headerText;

                try {
                    const res = await fetch(`/api/booking/dates/${dateStr}/slots`);
                    const data = await res.json();
                    state.slots = data;
                    state.selectedSlotId = null;
                    state.selectedSlotLabel = null;

                    document.getElementById('time-box-text').textContent = 'SELECT YOUR TIME SLOT';
                    document.getElementById('time-box-text').className = 'text-muted fw-bold';
                    toggleTimeDropdown(true);

                    renderTimeDropdown(data);
                } catch (err) {
                    console.error('Error loading slots:', err);
                    showAlert('Failed to load session time slots.');
                }
            }

            // Render Time Slot Dropdown
            function renderTimeDropdown(slots) {
                const container = document.getElementById('time-items-list');
                container.innerHTML = '';

                if (!slots || slots.length === 0) {
                    container.innerHTML = `<div class="py-3 text-center small fw-bold text-muted">NO AVAILABLE SESSIONS FOR THIS DATE.</div>`;
                    return;
                }

                slots.forEach(slot => {
                    const slotRow = document.createElement('div');
                    const isSelected = state.selectedSlotId === slot.id;
                    const isAvailable = slot.available;

                    let rowClasses = 'slot-row d-flex align-items-center justify-content-between px-3 py-2 rounded-0 transition small fw-bold text-uppercase cursor-pointer ';

                    if (isSelected) {
                        rowClasses += 'selected-pill text-dark';
                    } else if (isAvailable) {
                        rowClasses += 'text-dark';
                    } else {
                        rowClasses += 'opacity-50 cursor-not-allowed text-muted';
                    }

                    slotRow.className = rowClasses;

                    let rightSpan = '';
                    if (isSelected) {
                        rightSpan = `<svg style="width: 18px; height: 18px;" class="brand-orange-text" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>`;
                    } else if (!isAvailable) {
                        rightSpan = `<span class="small fw-bold text-danger text-uppercase">SLOT FULL</span>`;
                    } else {
                        rightSpan = `<span class="small fw-bold text-success text-uppercase">AVAILABLE</span>`;
                    }

                    slotRow.innerHTML = `
                        <span>${slot.label}</span>
                        ${rightSpan}
                    `;

                    if (isAvailable) {
                        slotRow.addEventListener('click', () => {
                            state.selectedSlotId = slot.id;
                            state.selectedSlotLabel = slot.label;

                            document.getElementById('time-box-text').textContent = slot.label;
                            document.getElementById('time-box-text').className = 'text-dark fw-bold';
                            toggleTimeDropdown(false);

                            renderTimeDropdown(state.slots);
                            enableNextButton();
                        });
                    }

                    container.appendChild(slotRow);
                });
            }

            // Toggle Time Dropdown Open/Close
            const timeTriggerBox = document.getElementById('time-trigger-box');
            const timeDropdownBox = document.getElementById('time-dropdown-box');
            const timeChevron = document.getElementById('time-chevron');

            function toggleTimeDropdown(open = null) {
                state.timeDropdownOpen = open !== null ? open : !state.timeDropdownOpen;
                if (state.timeDropdownOpen) {
                    if (state.dateDropdownOpen) toggleDateDropdown(false);
                    timeDropdownBox.classList.remove('d-none');
                    timeChevron.style.transform = 'rotate(180deg)';
                } else {
                    timeDropdownBox.classList.add('d-none');
                    timeChevron.style.transform = 'rotate(0deg)';
                }
            }

            timeTriggerBox.addEventListener('click', (e) => {
                e.stopPropagation();
                toggleTimeDropdown();
            });

            // Close dropdown overlays when clicking outside
            document.addEventListener('click', (e) => {
                const dateContainer = dateTriggerBox?.parentElement;
                const timeContainer = timeTriggerBox?.parentElement;
                if (dateContainer && !dateContainer.contains(e.target) && state.dateDropdownOpen) {
                    toggleDateDropdown(false);
                }
                if (timeContainer && !timeContainer.contains(e.target) && state.timeDropdownOpen) {
                    toggleTimeDropdown(false);
                }
            });

            // Enable NEXT button when date and slot are selected
            const nextBtn = document.getElementById('next-btn');

            function enableNextButton() {
                nextBtn.disabled = false;
                nextBtn.className = 'custom-btn custom-btn-primary mb-2 pulse-slow w-50';
            }

            // Review Modal Dom Elements
            const modalOverlay = document.getElementById('review-modal-overlay');
            const modalBackBtn = document.getElementById('modal-back-btn');
            const modalConfirmBtn = document.getElementById('modal-confirm-btn');

            // Slot Error Modal Dom Elements ("OH, NO!")
            const slotErrorOverlay = document.getElementById('slot-error-modal-overlay');
            const slotErrorBackBtn = document.getElementById('slot-error-back-btn');
            const slotErrorMessage = document.getElementById('slot-error-modal-message');

            function showSlotErrorModal(msg) {
                if (msg) {
                    slotErrorMessage.textContent = msg.toUpperCase();
                } else {
                    slotErrorMessage.textContent = 'LOOKS LIKE THIS SLOT HAS JUST BEEN TAKEN. PLEASE CHOOSE ANOTHER PREFERRED DATE.';
                }
                modalOverlay.classList.add('d-none');
                slotErrorOverlay.classList.remove('d-none');
            }

            slotErrorBackBtn.addEventListener('click', () => {
                slotErrorOverlay.classList.add('d-none');
                state.selectedDate = null;
                state.selectedSlotId = null;
                document.getElementById('date-box-text').textContent = 'DATE SELECTION';
                document.getElementById('date-box-text').className = 'text-muted fw-bold';
                document.getElementById('time-slots-section').classList.add('d-none');
                nextBtn.disabled = true;
                nextBtn.className = 'custom-btn custom-btn-primary mb-2 pulse-slow w-50';

                toggleDateDropdown(true);
                fetchDateAvailabilities();
            });

            // Clicking NEXT opens the "ALMOST THERE!" popup modal
            nextBtn.addEventListener('click', () => {
                if (!state.selectedDate || !state.selectedSlotId) {
                    showAlert('Please select your preferred date and time slot.');
                    return;
                }

                let timeFormatted = state.selectedSlotLabel.split('-')[0].trim().replace(/\s+/g, '');

                document.getElementById('modal-review-date').textContent = state.selectedDateFormatted;
                document.getElementById('modal-review-time').textContent = timeFormatted;

                modalOverlay.classList.remove('d-none');
            });

            // Modal BACK button closes popup
            modalBackBtn.addEventListener('click', () => {
                modalOverlay.classList.add('d-none');
            });

            function showConfirmationScreen(data, rescheduleCount = 0) {
                document.getElementById('reservation-form').classList.add('d-none');
                
                const customerName = (data.customer && data.customer.name) ? data.customer.name : (data.customer_name || 'CUSTOMER');
                const firstFirstName = customerName.split(' ')[0].toUpperCase();

                document.getElementById('confirmed-greeting-name').textContent = firstFirstName;
                document.getElementById('confirmed-ticket-name').textContent = customerName.toUpperCase();
                document.getElementById('confirmed-ticket-date').textContent = data.date || data.date_formatted;
                document.getElementById('confirmed-ticket-time').textContent = data.time || data.time_formatted;

                // Dynamic QR Code Image
                const qrData = encodeURIComponent(data.reference_no);
                document.getElementById('qr-code-img').src = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${qrData}`;

                const modifyBtn = document.getElementById('modify-btn');
                if (rescheduleCount >= 1) {
                    modifyBtn.classList.add('d-none'); // Hide "CHANGE YOUR SLOT" because customer can only modify once!
                } else {
                    modifyBtn.classList.remove('d-none');
                }

                document.getElementById('confirmation-success-screen').classList.remove('d-none');
            }

            // Modal CONFIRM button submits reservation or modification
            modalConfirmBtn.addEventListener('click', async () => {
                clearAlert();

                modalConfirmBtn.disabled = true;
                modalConfirmBtn.textContent = 'CONFIRMING...';

                const endpoint = state.isModifying ? '/reservation-create/modify' : '/reservation-create';
                const refNoToUse = state.modifyingRefNo || (state.bookingResult ? state.bookingResult.reference_no : localStorage.getItem('latest_booking_ref'));

                const payload = state.isModifying 
                    ? { reference_no: refNoToUse, date: state.selectedDate, slot_id: state.selectedSlotId }
                    : { date: state.selectedDate, slot_id: state.selectedSlotId };

                try {
                    const response = await fetch(endpoint, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(payload)
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        state.bookingResult = data.data;
                        if (data.data.reference_no) {
                            localStorage.setItem('latest_booking_ref', data.data.reference_no);
                        }

                        const newRescheduleCount = data.data.reschedule_count ?? (state.isModifying ? 1 : 0);

                        state.isModifying = false;
                        state.modifyingRefNo = null;

                        modalOverlay.classList.add('d-none');
                        
                        let formattedTimeSlot = state.selectedSlotLabel ? state.selectedSlotLabel.split('-')[0].trim().replace(/\s+/g, '') : data.data.time;

                        showConfirmationScreen({
                            reference_no: data.data.reference_no,
                            date: state.selectedDateFormatted || data.data.date,
                            time: formattedTimeSlot,
                            customer: data.data.customer
                        }, newRescheduleCount);
                    } else {
                        const errorMsg = data.errors?.slot?.[0] || data.errors?.date?.[0] || data.errors?.booking?.[0] || data.message || 'LOOKS LIKE THIS SLOT HAS JUST BEEN TAKEN. PLEASE CHOOSE ANOTHER PREFERRED DATE.';
                        showSlotErrorModal(errorMsg);
                    }
                } catch (err) {
                    console.error('Confirmation error:', err);
                    showSlotErrorModal('A network error occurred while submitting your booking.');
                } finally {
                    modalConfirmBtn.disabled = false;
                    modalConfirmBtn.textContent = 'CONFIRM';
                }
            });

            // CHANGE YOUR SLOT button re-opens date/slot selection view for modification
            document.getElementById('modify-btn').addEventListener('click', () => {
                const storedRef = localStorage.getItem('latest_booking_ref');
                if (state.bookingResult && state.bookingResult.reference_no) {
                    state.isModifying = true;
                    state.modifyingRefNo = state.bookingResult.reference_no;
                } else if (storedRef) {
                    state.isModifying = true;
                    state.modifyingRefNo = storedRef;
                } else if (existingBooking) {
                    state.isModifying = true;
                    state.modifyingRefNo = existingBooking.reference_no;
                }
                
                document.getElementById('confirmation-success-screen').classList.add('d-none');
                document.getElementById('reservation-form').classList.remove('d-none');
                
                // Reset selected values for modification
                state.selectedDate = null;
                state.selectedSlotId = null;
                document.getElementById('date-box-text').textContent = 'DATE SELECTION';
                document.getElementById('date-box-text').className = 'text-muted fw-bold';
                document.getElementById('time-slots-section').classList.add('d-none');
                nextBtn.disabled = true;
                nextBtn.className = 'custom-btn custom-btn-primary mb-2 pulse-slow w-50';

                toggleDateDropdown(true);
                fetchDateAvailabilities();
            });

            // DOWNLOAD button saves ONLY the red dashed ticket box as JPEG
            document.getElementById('download-btn').addEventListener('click', () => {
                const downloadBtn = document.getElementById('download-btn');
                const refNo = state.bookingResult && state.bookingResult.reference_no 
                    ? state.bookingResult.reference_no 
                    : (localStorage.getItem('latest_booking_ref') || 'ticket');
                
                const customerName = document.getElementById('confirmed-ticket-name').textContent.trim();
                const dateText = document.getElementById('confirmed-ticket-date').textContent.trim();
                const timeText = document.getElementById('confirmed-ticket-time').textContent.trim();
                const qrImgElem = document.getElementById('qr-code-img');

                downloadBtn.disabled = true;
                downloadBtn.textContent = 'GENERATING JPEG...';

                // Create offscreen canvas matching red dashed ticket box aspect (450x540)
                const canvas = document.createElement('canvas');
                canvas.width = 450;
                canvas.height = 540;
                const ctx = canvas.getContext('2d');

                // Fill white background
                ctx.fillStyle = '#ffffff';
                ctx.fillRect(0, 0, 450, 540);

                // Outer Red Dashed Border Box (inset 15px)
                ctx.strokeStyle = '#ef4444';
                ctx.lineWidth = 3;
                ctx.setLineDash([8, 6]);
                ctx.strokeRect(15, 15, 420, 510);
                ctx.setLineDash([]); // reset dash

                // Helper to trigger image download
                const triggerDownload = (loadedQrImage) => {
                    // 1. QR Code (Centered 200x200)
                    if (loadedQrImage) {
                        ctx.drawImage(loadedQrImage, 125, 40, 200, 200);
                    } else {
                        ctx.fillStyle = '#f1f5f9';
                        ctx.fillRect(125, 40, 200, 200);
                        ctx.fillStyle = '#64748b';
                        ctx.font = 'bold 16px "Helvetica Neue", Helvetica, Arial, sans-serif';
                        ctx.textAlign = 'center';
                        ctx.fillText('QR CODE', 225, 145);
                    }

                    // 2. Customer Name (Bold, Centered)
                    ctx.fillStyle = '#0f172a';
                    ctx.font = 'bold 22px "Helvetica Neue", Helvetica, Arial, sans-serif';
                    ctx.textAlign = 'center';
                    ctx.fillText(customerName.toUpperCase(), 225, 280);

                    // 3. DATE Line (DATE: dark, value: brand orange)
                    ctx.font = 'bold 14px "Helvetica Neue", Helvetica, Arial, sans-serif';
                    const dateLabel = 'DATE: ';
                    const dateVal = dateText.toUpperCase();

                    ctx.fillStyle = '#0f172a';
                    const labelW = ctx.measureText(dateLabel).width;
                    ctx.fillStyle = '#e86034';
                    const valW = ctx.measureText(dateVal).width;
                    const dateStartX = (450 - (labelW + valW)) / 2;

                    ctx.textAlign = 'left';
                    ctx.fillStyle = '#0f172a';
                    ctx.fillText(dateLabel, dateStartX, 330);
                    ctx.fillStyle = '#e86034';
                    ctx.fillText(dateVal, dateStartX + labelW, 330);

                    // 4. TIME Line (Centered)
                    ctx.textAlign = 'center';
                    ctx.fillStyle = '#0f172a';
                    ctx.font = 'bold 14px "Helvetica Neue", Helvetica, Arial, sans-serif';
                    ctx.fillText(`TIME: ${timeText.toUpperCase()}`, 225, 360);

                    // 5. VENUE Lines (Centered)
                    ctx.fillText('VENUE: LONGCHAMP POP UP STORE', 225, 410);
                    ctx.fillText('THE GARDENS MALL', 225, 435);

                    // Convert Canvas to Data URL & Trigger Download
                    const dataUrl = canvas.toDataURL('image/jpeg', 0.95);
                    const link = document.createElement('a');
                    link.download = `Reservation_${refNo}.jpg`;
                    link.href = dataUrl;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);

                    downloadBtn.disabled = false;
                    downloadBtn.textContent = 'DOWNLOAD';

                    // Redirect to dashboard after download
                    setTimeout(() => {
                        window.location.href = "{{ route('dashboard') }}";
                    }, 1000);
                };

                if (qrImgElem && qrImgElem.src) {
                    const img = new Image();
                    img.crossOrigin = 'anonymous';
                    img.onload = () => triggerDownload(img);
                    img.onerror = () => triggerDownload(null);
                    img.src = qrImgElem.src;
                } else {
                    triggerDownload(null);
                }
            });

            // Initial Load: Check existing booking passed from server
            const existingBooking = @json($formattedBooking ?? null);

            if (existingBooking) {
                state.modifyingRefNo = existingBooking.reference_no;
                state.rescheduleCount = existingBooking.reschedule_count;

                if (existingBooking.reschedule_count >= 1) {
                    // Customer has ALREADY modified once -> directly show confirmation screen with ONLY Download button
                    showConfirmationScreen(existingBooking, existingBooking.reschedule_count);
                } else {
                    // Customer has an active booking that can be modified once -> show form with Modify Reservation Header
                    state.isModifying = true;
                }
            } else {
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.get('modify') === '1' || urlParams.has('modify')) {
                    state.isModifying = true;
                    const storedRef = localStorage.getItem('latest_booking_ref');
                    if (storedRef) {
                        state.modifyingRefNo = storedRef;
                    }
                }
            }

            fetchDateAvailabilities();
        });
    </script>
</x-guest-layout>
