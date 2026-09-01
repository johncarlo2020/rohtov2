<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Permissions-Policy" content="unload=()">
    <title>Reservation - Preferred Date & Time Slot</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Great+Vibes&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        cursive: ['Great Vibes', 'cursive'],
                    },
                    colors: {
                        brand: {
                            orange: '#e86034',
                            orangeHover: '#d44f25',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body {
            background-color: #f8fafc;
            color: #1e293b;
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
        }
        .main-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
        }
        /* Orange Pill Border Selection */
        .selected-pill {
            border: 2px solid #e86034 !important;
            border-radius: 0.75rem !important;
            background-color: rgba(232, 96, 52, 0.03) !important;
        }
        /* Custom scrollbars */
        .custom-scroll::-webkit-scrollbar {
            width: 5px;
        }
        .custom-scroll::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        .custom-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        .step-fade {
            animation: fadeIn 0.25s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(4px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="py-10 px-4 sm:px-6">

    <div class="max-w-md mx-auto">
        <!-- Main Card -->
        <main class="main-card rounded-2xl p-6 sm:p-8">
            
            <!-- BOOKING FLOW FORM -->
            <form id="reservation-form" onsubmit="event.preventDefault();">
                <!-- Header Text -->
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-black tracking-tight text-slate-900 mb-2 uppercase">Hey, <span id="user-greeting-name">{{ auth()->check() && isset(auth()->user()->fname) ? auth()->user()->fname : 'Guest' }}!</span></h2>
                    <p class="text-xs sm:text-sm font-bold text-slate-700 leading-relaxed uppercase">
                        PLEASE CHOOSE YOUR PREFERRED DATE AND TIME SLOT,<br>
                        KEEPING IN MIND THAT <span class="font-extrabold text-slate-900">YOU CAN ONLY RESCHEDULE ONCE,<br>
                        AT LEAST ONE WEEK BEFORE YOUR SLOT.</span>
                    </p>
                    <p class="text-xs font-bold text-rose-500 tracking-wider uppercase mt-3">
                        SUBJECT TO AVAILABILITY*
                    </p>
                </div>

                <!-- Global Alert Banner -->
                <div id="alert-banner" class="hidden mb-6 p-4 rounded-xl border text-xs font-semibold flex items-start space-x-2.5">
                    <i id="alert-icon" data-lucide="alert-circle" class="w-4 h-4 flex-shrink-0 mt-0.5"></i>
                    <div id="alert-message" class="flex-1"></div>
                </div>

                <!-- SECTION 1: DATE SELECTION -->
                <div class="mb-6">
                    <label class="block text-xs font-extrabold tracking-widest text-slate-800 uppercase mb-2">
                        DATE AVAILABLE:
                    </label>

                    <!-- Date Selection Box (Collapsed / Selected Display) -->
                    <div id="date-trigger-box" class="border border-slate-300 rounded-lg px-4 py-3.5 bg-white text-sm font-bold tracking-wider text-slate-700 uppercase cursor-pointer flex justify-between items-center shadow-sm hover:border-slate-400 transition">
                        <span id="date-box-text" class="text-slate-400 font-semibold">DATE SELECTION</span>
                        <i data-lucide="chevron-down" id="date-chevron" class="w-4 h-4 text-slate-400 transition-transform"></i>
                    </div>

                    <!-- Date Selection Expanded Dropdown Box -->
                    <div id="date-dropdown-box" class="mt-1.5 border border-slate-300 rounded-lg p-3 bg-white shadow-lg space-y-3">
                        <div class="text-[10px] font-bold tracking-widest text-slate-400 uppercase pb-2 border-b border-slate-100 flex justify-between items-center">
                            <span>DATE SELECTION</span>
                            <span class="text-slate-400 font-semibold">30 SEP – 18 OCT 2026</span>
                        </div>

                        <!-- Date Items List -->
                        <div id="date-items-list" class="space-y-3 max-h-72 overflow-y-auto pr-1 custom-scroll">
                            <!-- Dynamically populated -->
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: TIME SLOTS SELECTION (Appears once Date is Selected) -->
                <div id="time-slots-section" class="hidden mb-6 step-fade">
                    <label class="block text-xs font-extrabold tracking-widest text-slate-800 uppercase mb-2">
                        TIME SLOTS:
                    </label>

                    <!-- Time Slot Trigger Box (Collapsed / Selected Display) -->
                    <div id="time-trigger-box" class="border border-slate-300 rounded-lg px-4 py-3.5 bg-white text-sm font-bold tracking-wider text-slate-700 uppercase cursor-pointer flex justify-between items-center shadow-sm hover:border-slate-400 transition">
                        <span id="time-box-text" class="text-slate-400 font-semibold">SELECT YOUR TIME SLOT</span>
                        <i data-lucide="chevron-down" id="time-chevron" class="w-4 h-4 text-slate-400 transition-transform"></i>
                    </div>

                    <!-- Time Slot Expanded Dropdown Box -->
                    <div id="time-dropdown-box" class="mt-1.5 border border-slate-300 rounded-lg p-3 bg-white shadow-lg space-y-3">
                        <div class="text-[10px] font-bold tracking-widest text-slate-400 uppercase pb-2 border-b border-slate-100">
                            <span id="sessions-per-day-header">2 SESSIONS PER DAY</span>
                        </div>

                        <!-- Time Slot Items List -->
                        <div id="time-items-list" class="space-y-2">
                            <!-- Dynamically populated -->
                        </div>
                    </div>

                    <!-- 1 Hour Session Note -->
                    <div id="session-note" class="mt-2 text-[11px] font-bold text-rose-500 uppercase tracking-wider">
                        * 1 HOUR SESSION
                    </div>
                </div>

                <!-- NEXT BUTTON -->
                <div class="mt-8 text-center">
                    <button id="next-btn" disabled type="button" class="w-full py-4 px-6 rounded-lg bg-slate-300 text-slate-500 font-black text-sm tracking-widest uppercase transition cursor-not-allowed shadow-none">
                        NEXT
                    </button>
                </div>

                <!-- Terms Link -->
                <div class="text-center mt-4">
                    <a href="{{ url('/terms-and-conditions') }}" class="text-xs font-bold text-slate-400 hover:text-slate-600 underline uppercase tracking-wider">
                        TERMS & CONDITIONS
                    </a>
                </div>

            </form>

            <!-- BOOKING CONFIRMED SUCCESS SCREEN - EXACT MATCH TO PROVIDED SCREENSHOT -->
            <div id="confirmation-success-screen" class="hidden text-center py-2 step-fade">
                
                <!-- Branding Header -->
                <div class="mb-4 text-center">
                    <div class="text-lg font-serif italic text-slate-900 tracking-wide">Caroline Hélain</div>
                    <div class="text-xs font-bold text-slate-500 my-0.5">x</div>
                    <div class="text-sm font-extrabold tracking-widest text-slate-900 uppercase">LONGCHAMP</div>
                </div>

                <!-- Title -->
                <h2 class="text-2xl font-black text-[#e86034] uppercase tracking-wider mb-2">
                    BOOKING CONFIRMED!
                </h2>

                <!-- Subtitle -->
                <p class="text-xs sm:text-sm font-black text-slate-800 uppercase tracking-wide leading-relaxed max-w-xs mx-auto mb-5">
                    HI <span id="confirmed-greeting-name">CUSTOMER</span>,<br>
                    YOUR SLOT IS OFFICIALLY LOCKED IN. SEE YOU THERE!
                </p>

                <!-- Red Dashed Ticket Container -->
                <div id="ticket-container" class="border-2 border-dashed border-red-500 rounded-2xl p-6 bg-white inline-block max-w-xs w-full shadow-sm mb-6 text-center">
                    
                    <!-- Dynamic QR Code Image -->
                    <img id="qr-code-img" src="" alt="Booking QR Code" class="w-44 h-44 mx-auto mb-3 object-contain" crossorigin="anonymous">

                    <!-- Customer Name -->
                    <div id="confirmed-ticket-name" class="text-sm font-black text-slate-900 uppercase mb-2 tracking-wide">
                        JOSHUA
                    </div>

                    <!-- Details List -->
                    <div class="space-y-1 text-[10px] font-extrabold text-slate-900 uppercase tracking-wider">
                        <div><span class="text-slate-400">DATE:</span> <span id="confirmed-ticket-date">7TH OCTOBER</span></div>
                        <div><span class="text-slate-400">TIME:</span> <span id="confirmed-ticket-time">6:00PM</span></div>
                        <div class="mt-1 leading-snug px-2">
                            <span class="text-slate-400">VENUE:</span> LONGCHAMP POP UP STORE THE GARDENS MALL
                        </div>
                    </div>
                </div>

                <!-- Action Buttons (CHANGE YOUR SLOT / DOWNLOAD) -->
                <div class="space-y-3 max-w-xs mx-auto">
                    <button id="modify-btn" type="button" class="w-full py-3.5 px-6 rounded-lg bg-[#e86034] hover:bg-[#d44f25] text-white font-black text-sm tracking-widest uppercase transition shadow-md shadow-orange-500/20">
                        CHANGE YOUR SLOT
                    </button>
                    <button id="download-btn" type="button" class="w-full py-3.5 px-6 rounded-lg bg-[#e86034] hover:bg-[#d44f25] text-white font-black text-sm tracking-widest uppercase transition shadow-md shadow-orange-500/20">
                        DOWNLOAD
                    </button>
                </div>

            </div>

        </main>
    </div>

    <!-- REVIEW MODAL POPUP ("ALMOST THERE!") -->
    <div id="review-modal-overlay" class="hidden fixed inset-0 bg-slate-900/70 backdrop-blur-sm z-50 flex items-center justify-center p-4 step-fade">
        <div class="border-4 border-[#e86034] rounded-2xl bg-white shadow-2xl p-6 sm:p-8 max-w-sm w-full font-sans text-center relative">
            
            <!-- Modal Title -->
            <h3 class="text-2xl font-black text-[#e86034] uppercase tracking-wider mb-2">
                ALMOST THERE!
            </h3>

            <!-- Subtitle -->
            <p class="text-[11px] font-extrabold text-slate-600 tracking-wide uppercase leading-tight mb-6">
                PLEASE REVIEW YOUR BOOKING DETAILS BEFORE CONFIRMING.
            </p>

            <!-- Review Items -->
            <div class="space-y-4 mb-8">
                <div>
                    <div class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">DATE:</div>
                    <div id="modal-review-date" class="text-sm font-black text-slate-900 uppercase">7TH OCTOBER</div>
                </div>

                <div>
                    <div class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">TIME:</div>
                    <div id="modal-review-time" class="text-sm font-black text-slate-900 uppercase">6:00PM</div>
                </div>

                <div>
                    <div class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">VENUE:</div>
                    <div class="text-xs font-extrabold text-slate-900 uppercase leading-snug px-4">
                        LONGCHAMP POP UP STORE THE GARDENS MALL
                    </div>
                </div>
            </div>

            <!-- Modal Action Buttons -->
            <div class="space-y-3">
                <button id="modal-back-btn" type="button" class="text-xs font-black text-slate-900 hover:text-slate-700 uppercase tracking-wider block mx-auto py-1">
                    BACK
                </button>
                <button id="modal-confirm-btn" type="button" class="w-full py-3.5 px-6 rounded-lg bg-[#e86034] hover:bg-[#d44f25] text-white font-black text-sm tracking-widest uppercase transition shadow-lg shadow-orange-500/20">
                    CONFIRM
                </button>
            </div>

        </div>
    </div>

    <!-- UNAVAILABLE SLOT ERROR MODAL ("OH, NO!") -->
    <div id="slot-error-modal-overlay" class="hidden fixed inset-0 bg-slate-900/70 backdrop-blur-sm z-50 flex items-center justify-center p-4 step-fade">
        <div class="border-4 border-[#e86034] rounded-2xl bg-white shadow-2xl p-6 sm:p-8 max-w-sm w-full font-sans text-center relative">
            
            <!-- Modal Title -->
            <h3 class="text-2xl font-black text-[#e86034] uppercase tracking-wider mb-4">
                OH, NO!
            </h3>

            <!-- Message -->
            <p id="slot-error-modal-message" class="text-xs sm:text-sm font-black text-slate-800 tracking-wide uppercase leading-relaxed max-w-xs mx-auto mb-8">
                LOOKS LIKE THIS SLOT HAS JUST BEEN TAKEN. PLEASE CHOOSE ANOTHER PREFERRED DATE.
            </p>

            <!-- Action Button -->
            <button id="slot-error-back-btn" type="button" class="w-full py-3.5 px-6 rounded-lg bg-[#e86034] hover:bg-[#d44f25] text-white font-black text-sm tracking-widest uppercase transition shadow-lg shadow-orange-500/20">
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
                dateDropdownOpen: true,
                timeDropdownOpen: true
            };

            const alertBanner = document.getElementById('alert-banner');
            const alertMessage = document.getElementById('alert-message');

            function showAlert(msg, type = 'error') {
                alertBanner.classList.remove('hidden', 'bg-rose-50', 'border-rose-200', 'text-rose-700', 'bg-amber-50', 'border-amber-200', 'text-amber-700');
                if (type === 'error') {
                    alertBanner.classList.add('bg-rose-50', 'border-rose-200', 'text-rose-700');
                } else {
                    alertBanner.classList.add('bg-amber-50', 'border-amber-200', 'text-amber-700');
                }
                alertMessage.textContent = msg;
            }

            function clearAlert() {
                alertBanner.classList.add('hidden');
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
                    monthGroup.className = 'mb-3';

                    const monthHeader = document.createElement('div');
                    monthHeader.className = 'text-[10px] font-bold tracking-widest text-slate-400 uppercase mb-1.5 px-1';
                    monthHeader.textContent = monthName;
                    monthGroup.appendChild(monthHeader);

                    const rowsContainer = document.createElement('div');
                    rowsContainer.className = 'space-y-1.5';

                    grouped[monthName].forEach(item => {
                        const dateRow = document.createElement('div');
                        const isSelected = state.selectedDate === item.date;
                        const isAvailable = item.status === 'available';
                        const formattedLabel = formatDateOrdinal(item.date);

                        let rowClasses = 'flex items-center justify-between px-4 py-3 transition text-sm font-extrabold uppercase tracking-wide cursor-pointer ';

                        if (isSelected) {
                            rowClasses += 'selected-pill text-slate-900';
                        } else if (isAvailable) {
                            rowClasses += 'hover:bg-slate-50 text-slate-800';
                        } else {
                            rowClasses += 'opacity-40 cursor-not-allowed text-slate-400';
                        }

                        dateRow.className = rowClasses;

                        let statusSpan = '';
                        if (isSelected) {
                            statusSpan = `<svg class="w-5 h-5 text-brand-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>`;
                        } else if (item.status === 'available') {
                            statusSpan = `<span class="text-[11px] font-extrabold text-emerald-500 uppercase tracking-wider">AVAILABLE</span>`;
                        } else if (item.status === 'full') {
                            statusSpan = `<span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">FULLY BOOKED</span>`;
                        } else {
                            statusSpan = `<span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">CLOSED</span>`;
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
                                document.getElementById('date-box-text').className = 'text-slate-900 font-extrabold';
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
                    dateDropdownBox.classList.remove('hidden');
                    dateChevron.style.transform = 'rotate(180deg)';
                } else {
                    dateDropdownBox.classList.add('hidden');
                    dateChevron.style.transform = 'rotate(0deg)';
                }
            }

            dateTriggerBox.addEventListener('click', () => toggleDateDropdown());

            // Load Slots for Step 2
            async function loadSlotsForSelectedDate(dateStr) {
                clearAlert();

                document.getElementById('time-slots-section').classList.remove('hidden');

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
                    document.getElementById('time-box-text').className = 'text-slate-400 font-semibold';
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
                    container.innerHTML = `<div class="py-4 text-center text-xs font-bold text-slate-400">NO AVAILABLE SESSIONS FOR THIS DATE.</div>`;
                    return;
                }

                slots.forEach(slot => {
                    const slotRow = document.createElement('div');
                    const isSelected = state.selectedSlotId === slot.id;
                    const isAvailable = slot.available;

                    let rowClasses = 'flex items-center justify-between px-4 py-3 transition text-sm font-extrabold uppercase tracking-wide cursor-pointer ';

                    if (isSelected) {
                        rowClasses += 'selected-pill text-slate-900';
                    } else if (isAvailable) {
                        rowClasses += 'hover:bg-slate-50 text-slate-800';
                    } else {
                        rowClasses += 'opacity-40 cursor-not-allowed text-slate-400';
                    }

                    slotRow.className = rowClasses;

                    let rightSpan = '';
                    if (isSelected) {
                        rightSpan = `<svg class="w-5 h-5 text-brand-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>`;
                    } else if (!isAvailable) {
                        rightSpan = `<span class="text-[11px] font-extrabold text-rose-500 uppercase tracking-wider">SLOT FULL</span>`;
                    } else {
                        rightSpan = `<span class="text-[11px] font-extrabold text-emerald-500 uppercase tracking-wider">AVAILABLE</span>`;
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
                            document.getElementById('time-box-text').className = 'text-slate-900 font-extrabold';
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
                    timeDropdownBox.classList.remove('hidden');
                    timeChevron.style.transform = 'rotate(180deg)';
                } else {
                    timeDropdownBox.classList.add('hidden');
                    timeChevron.style.transform = 'rotate(0deg)';
                }
            }

            timeTriggerBox.addEventListener('click', () => toggleTimeDropdown());

            // Enable NEXT button when date and slot are selected
            const nextBtn = document.getElementById('next-btn');

            function enableNextButton() {
                nextBtn.disabled = false;
                nextBtn.className = 'w-full py-4 px-6 rounded-lg bg-brand-orange hover:bg-brand-orangeHover text-white font-black text-sm tracking-widest uppercase transition cursor-pointer shadow-lg shadow-orange-500/20';
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
                modalOverlay.classList.add('hidden');
                slotErrorOverlay.classList.remove('hidden');
            }

            slotErrorBackBtn.addEventListener('click', () => {
                slotErrorOverlay.classList.add('hidden');
                state.selectedDate = null;
                state.selectedSlotId = null;
                document.getElementById('date-box-text').textContent = 'DATE SELECTION';
                document.getElementById('date-box-text').className = 'text-slate-400 font-semibold';
                document.getElementById('time-slots-section').classList.add('hidden');
                nextBtn.disabled = true;
                nextBtn.className = 'w-full py-4 px-6 rounded-lg bg-slate-300 text-slate-500 font-black text-sm tracking-widest uppercase transition cursor-not-allowed shadow-none';

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

                modalOverlay.classList.remove('hidden');
            });

            // Modal BACK button closes popup
            modalBackBtn.addEventListener('click', () => {
                modalOverlay.classList.add('hidden');
            });

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
                        state.isModifying = false;
                        state.modifyingRefNo = null;

                        modalOverlay.classList.add('hidden');
                        document.getElementById('reservation-form').classList.add('hidden');
                        
                        const customerName = (data.data.customer && data.data.customer.name) ? data.data.customer.name : 'CUSTOMER';
                        const firstFirstName = customerName.split(' ')[0].toUpperCase();

                        document.getElementById('confirmed-greeting-name').textContent = firstFirstName;
                        document.getElementById('confirmed-ticket-name').textContent = customerName.toUpperCase();
                        
                        document.getElementById('confirmed-ticket-date').textContent = state.selectedDateFormatted;
                        
                        let formattedTimeSlot = state.selectedSlotLabel.split('-')[0].trim().replace(/\s+/g, '');
                        document.getElementById('confirmed-ticket-time').textContent = formattedTimeSlot;

                        // Dynamic QR Code Image
                        const qrData = encodeURIComponent(data.data.reference_no);
                        document.getElementById('qr-code-img').src = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${qrData}`;

                        document.getElementById('confirmation-success-screen').classList.remove('hidden');
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
                }
                
                document.getElementById('confirmation-success-screen').classList.add('hidden');
                document.getElementById('reservation-form').classList.remove('hidden');
                
                // Reset selected values for modification
                state.selectedDate = null;
                state.selectedSlotId = null;
                document.getElementById('date-box-text').textContent = 'DATE SELECTION';
                document.getElementById('date-box-text').className = 'text-slate-400 font-semibold';
                document.getElementById('time-slots-section').classList.add('hidden');
                nextBtn.disabled = true;
                nextBtn.className = 'w-full py-4 px-6 rounded-lg bg-slate-300 text-slate-500 font-black text-sm tracking-widest uppercase transition cursor-not-allowed shadow-none';

                toggleDateDropdown(true);
                fetchDateAvailabilities();
            });

            // DOWNLOAD button saves ONLY the red dashed ticket container area as JPEG & redirects to Dashboard
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

                // Create offscreen high-res canvas (600x700)
                const canvas = document.createElement('canvas');
                canvas.width = 600;
                canvas.height = 700;
                const ctx = canvas.getContext('2d');

                // 1. Fill outer background white
                ctx.fillStyle = '#ffffff';
                ctx.fillRect(0, 0, 600, 700);

                // 2. Draw Red Dashed Border Box
                ctx.save();
                ctx.strokeStyle = '#ef4444';
                ctx.lineWidth = 4;
                ctx.setLineDash([10, 8]);
                
                const r = 24;
                const x = 30, y = 30, w = 540, h = 640;
                ctx.beginPath();
                ctx.moveTo(x + r, y);
                ctx.lineTo(x + w - r, y);
                ctx.quadraticCurveTo(x + w, y, x + w, y + r);
                ctx.lineTo(x + w, y + h - r);
                ctx.quadraticCurveTo(x + w, y + h, x + w - r, y + h);
                ctx.lineTo(x + r, y + h);
                ctx.quadraticCurveTo(x, y + h, x, y + h - r);
                ctx.lineTo(x, y + r);
                ctx.quadraticCurveTo(x, y, x + r, y);
                ctx.closePath();
                ctx.stroke();
                ctx.restore();

                const triggerDownload = (imgSource) => {
                    if (imgSource) {
                        ctx.drawImage(imgSource, 160, 60, 280, 280);
                    }

                    // Customer Name
                    ctx.fillStyle = '#0f172a';
                    ctx.font = '900 28px "Plus Jakarta Sans", sans-serif';
                    ctx.textAlign = 'center';
                    ctx.fillText(customerName.toUpperCase(), 300, 390);

                    // Details (DATE, TIME, VENUE)
                    ctx.font = '800 18px "Plus Jakarta Sans", sans-serif';
                    
                    // Date
                    ctx.fillStyle = '#94a3b8';
                    ctx.fillText('DATE: ', 240, 440);
                    ctx.fillStyle = '#0f172a';
                    ctx.fillText(dateText, 320, 440);

                    // Time
                    ctx.fillStyle = '#94a3b8';
                    ctx.fillText('TIME: ', 240, 480);
                    ctx.fillStyle = '#0f172a';
                    ctx.fillText(timeText, 320, 480);

                    // Venue Label
                    ctx.fillStyle = '#94a3b8';
                    ctx.fillText('VENUE: ', 220, 520);
                    ctx.fillStyle = '#0f172a';
                    ctx.fillText('LONGCHAMP POP UP STORE', 340, 520);
                    ctx.fillText('THE GARDENS MALL', 300, 555);

                    // Trigger JPEG download
                    const imgData = canvas.toDataURL('image/jpeg', 0.95);
                    const link = document.createElement('a');
                    link.download = `booking-ticket-${refNo}.jpeg`;
                    link.href = imgData;
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

            // Initial Load: Check if modify parameter is present in URL
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('modify') === '1' || urlParams.has('modify')) {
                state.isModifying = true;
                const storedRef = localStorage.getItem('latest_booking_ref');
                if (storedRef) {
                    state.modifyingRefNo = storedRef;
                }
            }

            fetchDateAvailabilities();
        });
    </script>
</body>
</html>
