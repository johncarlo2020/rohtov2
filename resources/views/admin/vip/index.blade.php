@extends('layouts.admin')

@section('content')
<style>
    .brand-orange-text { color: #e86034 !important; }
    .brand-orange-bg { background-color: #e86034 !important; color: #ffffff !important; border: none; }

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
        max-height: 230px;
        overflow-y: auto;
    }
    .custom-scroll::-webkit-scrollbar { width: 5px; }
    .custom-scroll::-webkit-scrollbar-track { background: #f1f5f9; }
    .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; }

    .cursor-pointer { cursor: pointer; }
    .cursor-not-allowed { cursor: not-allowed; }

    .date-row:hover:not(.opacity-50),
    .slot-row:hover:not(.opacity-50) {
        background-color: #f8fafc;
    }
</style>

<div class="container-fluid py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show text-white" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="font-weight-bolder text-dark mb-1">
                <i class="fa-solid fa-crown text-warning me-2"></i>VIP Management & Session Capacity
            </h4>
            <p class="text-sm text-muted mb-0">Create VIP bookings, adjust pax count, and view session attendance breakdown.</p>
        </div>
    </div>

    <div class="row">
        {{-- Left Column: VIP Reservation Form --}}
        <div class="col-lg-5 mb-4">
            <div class="card shadow-sm border-0" style="border-radius: 14px;">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="font-weight-bold text-dark mb-0">
                        <i class="fa-solid fa-user-plus text-primary me-2"></i>Add VIP Reservation
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.vip.store') }}" method="POST">
                        @csrf

                        {{-- VIP Group Schedule Preset --}}
                        <div class="mb-3">
                            <label for="vip_group_preset" class="form-label font-weight-bold text-xs text-uppercase text-primary">
                                <i class="fa-solid fa-crown me-1"></i>Select VIP Group / Schedule Preset
                            </label>
                            <select id="vip_group_preset" class="form-select font-weight-bold border-primary text-dark" style="border-width: 2px;">
                                <option value="">-- All Dates & Time Slots --</option>
                                <option value="KOL AND MEDIA INFLUENCER">KOL AND MEDIA INFLUENCER (80 Pax: Sep 30 & Oct 1)</option>
                                <option value="LONGCHAMP VIC">LONGCHAMP VIC (20 Pax: Sep 30)</option>
                                <option value="THE GARDENS EMERALD MEMBER">THE GARDENS EMERALD MEMBER (24 Pax: Sep 30 & Oct 1)</option>
                                <option value="MAYBANK PREMIUM CUSTOMER">MAYBANK PREMIUM CUSTOMER (30 Pax: Sep 30)</option>
                                <option value="PIN PRESTIGE">PIN PRESTIGE (12 Pax: Oct 1)</option>
                            </select>
                            <div class="form-text text-xxs text-muted">Selecting a VIP group filters available dates, slots & pre-fills pax count automatically.</div>
                        </div>

                        {{-- VIP User Dropdown or Custom Name --}}
                        <div class="mb-3">
                            <label for="user_id" class="form-label font-weight-bold text-xs text-uppercase text-muted">Select VIP User (Optional)</label>
                            <select id="user_id" name="user_id" class="form-select @error('user_id') is-invalid @enderror">
                                <option value="">-- Select Existing User --</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}">
                                        {{ trim(($u->fname ?? '') . ' ' . ($u->lname ?? '')) ?: ($u->name ?? 'User #' . $u->id) }} ({{ $u->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="vip_name" class="form-label font-weight-bold text-xs text-uppercase text-muted">Or Enter / Edit VIP Name *</label>
                            <input type="text" id="vip_name" name="vip_name" class="form-control font-weight-bold" placeholder="e.g. KOL AND MEDIA INFLUENCER / VIP Guest" />
                        </div>

                        {{-- Hidden Form Controls for Submission --}}
                        <input type="hidden" name="booking_date_id" id="vip_booking_date_id" required>
                        <input type="hidden" name="booking_slot_id" id="vip_booking_slot_id" required>

                        {{-- SECTION 1: DATE SELECTION (Matches Customer Side UI) --}}
                        <div class="mb-3">
                            <label class="form-label font-weight-bold text-xs text-uppercase text-muted mb-1">
                                DATE AVAILABLE: *
                            </label>

                            <div class="position-relative">
                                <div id="vip-date-trigger-box" class="form-control d-flex justify-content-between align-items-center py-2 px-3 bg-white border cursor-pointer rounded-1">
                                    <span id="vip-date-box-text" class="text-muted font-weight-bold text-xs text-uppercase">DATE SELECTION</span>
                                    <i class="fa-solid fa-chevron-down text-muted" id="vip-date-chevron" style="transition: transform 0.2s;"></i>
                                </div>

                                <div id="vip-date-dropdown-box" class="d-none dropdown-overlay p-3">
                                    <div class="small font-weight-bold text-dark text-uppercase pb-2 mb-2 border-bottom d-flex justify-content-between align-items-center text-xs">
                                        <span>DATE SELECTION</span>
                                        <span>30 SEP – 17 OCT 2026</span>
                                    </div>
                                    <div id="vip-date-items-list" class="custom-scroll">
                                        <!-- Dynamically populated -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- SECTION 2: TIME SLOTS SELECTION (Matches Customer Side UI) --}}
                        <div class="mb-3">
                            <label class="form-label font-weight-bold text-xs text-uppercase text-muted mb-1">
                                SELECT YOUR TIME SLOT: *
                            </label>

                            <div class="position-relative">
                                <div id="vip-time-trigger-box" class="form-control d-flex justify-content-between align-items-center py-2 px-3 bg-white border cursor-pointer rounded-1 opacity-60 cursor-not-allowed">
                                    <span id="vip-time-box-text" class="text-muted font-weight-bold text-xs text-uppercase">SELECT YOUR TIME SLOT</span>
                                    <i class="fa-solid fa-chevron-down text-muted" id="vip-time-chevron" style="transition: transform 0.2s;"></i>
                                </div>

                                <div id="vip-time-dropdown-box" class="d-none dropdown-overlay p-3">
                                    <div class="small font-weight-bold text-muted text-uppercase pb-2 mb-2 border-bottom text-xs">
                                        <span>TIME SLOTS</span>
                                    </div>
                                    <div id="vip-time-items-list">
                                        <!-- Dynamically populated -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Pax --}}
                        <div class="mb-4">
                            <label for="pax" class="form-label font-weight-bold text-xs text-uppercase text-muted">Pax Count (Number of Guests) *</label>
                            <input type="number" id="pax" name="pax" class="form-control @error('pax') is-invalid @enderror" value="1" min="1" max="50" required />
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 font-weight-bold shadow-sm" style="border-radius: 8px;">
                            <i class="fa-solid fa-crown me-2"></i>CREATE VIP RESERVATION
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Right Column: VIP Group Schedule Breakdown & Session Capacity --}}
        <div class="col-lg-7 mb-4">
            {{-- VIP Group Schedule Breakdown --}}
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 14px;">
                <div class="card-header bg-white border-0 pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="font-weight-bold text-dark mb-0">
                        <i class="fa-solid fa-layer-group text-warning me-2"></i>VIP Group Schedule & Allocation Breakdown
                    </h5>
                    <span class="badge bg-warning text-dark text-xxs font-weight-bold">5 VIP Groups</span>
                </div>
                <div class="card-body px-0 pt-2 pb-2">
                    <div class="table-responsive p-0" style="max-height: 250px; overflow-y: auto;">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3">VIP Group Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Active Dates</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Time Slots & Allocated Pax</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="ps-3 align-middle">
                                        <span class="text-xs font-weight-bold text-dark"><i class="fa-solid fa-crown text-warning me-1"></i>KOL AND MEDIA INFLUENCER</span>
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge bg-light text-dark text-xxs">Sep 30 & Oct 1</span>
                                    </td>
                                    <td class="align-middle text-xs">
                                        <div><strong class="text-primary">30 Sep:</strong> 11am-3pm (4 x 20 pax = 80 pax)</div>
                                        <div><strong class="text-primary">1 Oct:</strong> 11am-1pm (2 x 10 pax = 20 pax)</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-3 align-middle">
                                        <span class="text-xs font-weight-bold text-dark"><i class="fa-solid fa-crown text-warning me-1"></i>LONGCHAMP VIC</span>
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge bg-light text-dark text-xxs">Sep 30</span>
                                    </td>
                                    <td class="align-middle text-xs">
                                        <div><strong class="text-primary">30 Sep:</strong> 3pm-5pm (2 x 10 pax = 20 pax)</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-3 align-middle">
                                        <span class="text-xs font-weight-bold text-dark"><i class="fa-solid fa-crown text-warning me-1"></i>THE GARDENS EMERALD MEMBER</span>
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge bg-light text-dark text-xxs">Sep 30 & Oct 1</span>
                                    </td>
                                    <td class="align-middle text-xs">
                                        <div><strong class="text-primary">30 Sep:</strong> 5pm-7pm (2 x 6 pax = 12 pax)</div>
                                        <div><strong class="text-primary">1 Oct:</strong> 3pm-5pm (2 x 6 pax = 12 pax)</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-3 align-middle">
                                        <span class="text-xs font-weight-bold text-dark"><i class="fa-solid fa-crown text-warning me-1"></i>MAYBANK PREMIUM CUSTOMER</span>
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge bg-light text-dark text-xxs">Sep 30</span>
                                    </td>
                                    <td class="align-middle text-xs">
                                        <div><strong class="text-primary">30 Sep:</strong> 7pm-10pm (3 x 10 pax = 30 pax)</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-3 align-middle">
                                        <span class="text-xs font-weight-bold text-dark"><i class="fa-solid fa-crown text-warning me-1"></i>PIN PRESTIGE</span>
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge bg-light text-dark text-xxs">Oct 1</span>
                                    </td>
                                    <td class="align-middle text-xs">
                                        <div><strong class="text-primary">1 Oct:</strong> 1pm-3pm (2 x 6 pax = 12 pax)</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Session Capacity Breakdown --}}
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 14px;">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="font-weight-bold text-dark mb-0">
                        <i class="fa-solid fa-chart-pie text-info me-2"></i>Public & VIP Session Breakdown
                    </h5>
                </div>
                <div class="card-body px-0 pt-2 pb-2">
                    <div class="table-responsive p-0" style="max-height: 380px; overflow-y: auto;">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date & Time</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Capacity</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Public Pax</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">VIP Pax</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total Pax</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Remaining</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($publicCounts as $pc)
                                    <tr>
                                        <td class="ps-3">
                                            <div class="d-flex flex-column">
                                                <span class="text-xs font-weight-bold text-dark">{{ $pc['date'] }}</span>
                                                <span class="text-xs text-primary font-weight-bold">{{ $pc['time'] }}</span>
                                            </div>
                                        </td>
                                        <td class="align-middle text-center text-xs font-weight-bold">{{ $pc['capacity'] }}</td>
                                        <td class="align-middle text-center text-xs font-weight-bold text-dark">{{ $pc['public_pax'] }}</td>
                                        <td class="align-middle text-center text-xs font-weight-bold text-warning">{{ $pc['vip_pax'] }}</td>
                                        <td class="align-middle text-center text-xs font-weight-bold text-info">{{ $pc['total_pax'] }}</td>
                                        <td class="align-middle text-center">
                                            <span class="badge bg-{{ $pc['remaining'] > 0 ? 'success' : 'danger' }}">
                                                {{ $pc['remaining'] }} Left
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted text-xs">No active sessions found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- VIP Reservations List Table --}}
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0" style="border-radius: 14px;">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="font-weight-bold text-dark mb-0">
                        <i class="fa-solid fa-list-check text-warning me-2"></i>VIP Reservations List
                    </h5>
                    <span class="badge bg-warning text-dark font-weight-bold">{{ $vipBookings->count() }} VIP Entries</span>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ref No</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">VIP Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date & Time</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Pax</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($vipBookings as $vip)
                                    @php
                                        $cStatus = $vip->computed_status;
                                        $badgeClass = match($cStatus) {
                                            'Attended' => 'bg-success',
                                            'Missed' => 'bg-danger',
                                            default => 'bg-warning text-dark',
                                        };
                                    @endphp
                                    <tr>
                                        <td class="ps-3">
                                            <span class="badge bg-dark font-weight-bold">{{ $vip->reference_no }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="text-xs font-weight-bold text-dark">{{ $vip->customer_name }}</span>
                                                <span class="text-xs text-muted">{{ $vip->customer_email }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="text-xs font-weight-bold text-dark">{{ $vip->bookingDate->display_date ?? 'N/A' }}</span>
                                                <span class="text-xs text-primary font-weight-bold">{{ $vip->bookingSlot->display_time ?? 'N/A' }}</span>
                                            </div>
                                        </td>
                                        <td class="align-middle text-center font-weight-bold text-xs">{{ $vip->pax }}</td>
                                        <td class="align-middle text-center">
                                            <span class="badge {{ $badgeClass }} text-uppercase">
                                                {{ $cStatus }}
                                            </span>
                                        </td>
                                        <td class="align-middle text-center">
                                            @if($cStatus !== 'Attended')
                                                <form action="{{ route('admin.vip.attend', $vip->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-xs btn-success mb-0 me-1">
                                                        <i class="fa-solid fa-check me-1"></i>Attending Now
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('admin.vip.destroy', $vip->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this VIP booking?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-xs btn-outline-danger mb-0">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted text-xs">No VIP reservations created yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const vipDbDates = @json($bookingDates);

        const vipGroupScheduleMap = {
            'KOL AND MEDIA INFLUENCER': {
                '2026-09-30': [
                    { start_time: '11:00', pax: 20 },
                    { start_time: '12:00', pax: 20 },
                    { start_time: '13:00', pax: 20 },
                    { start_time: '14:00', pax: 20 }
                ],
                '2026-10-01': [
                    { start_time: '11:00', pax: 10 },
                    { start_time: '12:00', pax: 10 }
                ]
            },
            'LONGCHAMP VIC': {
                '2026-09-30': [
                    { start_time: '15:00', pax: 10 },
                    { start_time: '16:00', pax: 10 }
                ]
            },
            'THE GARDENS EMERALD MEMBER': {
                '2026-09-30': [
                    { start_time: '17:00', pax: 6 },
                    { start_time: '18:00', pax: 6 }
                ],
                '2026-10-01': [
                    { start_time: '15:00', pax: 6 },
                    { start_time: '16:00', pax: 6 }
                ]
            },
            'MAYBANK PREMIUM CUSTOMER': {
                '2026-09-30': [
                    { start_time: '19:00', pax: 10 },
                    { start_time: '20:00', pax: 10 },
                    { start_time: '21:00', pax: 10 }
                ]
            },
            'PIN PRESTIGE': {
                '2026-10-01': [
                    { start_time: '13:00', pax: 6 },
                    { start_time: '14:00', pax: 6 }
                ]
            }
        };

        let vipState = {
            dateAvailabilities: [],
            slots: [],
            selectedGroup: null,
            selectedDateRaw: null,
            selectedSlotId: null,
            dateDropdownOpen: false,
            timeDropdownOpen: false
        };

        const presetSelect = document.getElementById('vip_group_preset');
        const vipNameInput = document.getElementById('vip_name');
        const paxInput = document.getElementById('pax');

        if (presetSelect) {
            presetSelect.addEventListener('change', () => {
                const val = presetSelect.value;
                if (val) {
                    if (vipNameInput) vipNameInput.value = val;
                    vipState.selectedGroup = val;
                } else {
                    vipState.selectedGroup = null;
                }

                // Reset selections
                vipState.selectedDateRaw = null;
                vipState.selectedSlotId = null;
                const dateInput = document.getElementById('vip_booking_date_id');
                const slotInput = document.getElementById('vip_booking_slot_id');
                if (dateInput) dateInput.value = '';
                if (slotInput) slotInput.value = '';

                const dBoxText = document.getElementById('vip-date-box-text');
                const tBoxText = document.getElementById('vip-time-box-text');
                if (dBoxText) {
                    dBoxText.textContent = 'DATE SELECTION';
                    dBoxText.className = 'text-muted font-weight-bold text-xs text-uppercase';
                }
                if (tBoxText) {
                    tBoxText.textContent = 'SELECT YOUR TIME SLOT';
                    tBoxText.className = 'text-muted font-weight-bold text-xs text-uppercase';
                }

                const tTrigger = document.getElementById('vip-time-trigger-box');
                if (tTrigger) tTrigger.classList.add('opacity-60', 'cursor-not-allowed');

                renderVipDateDropdown(vipState.dateAvailabilities);
            });
        }

        async function fetchVipDateAvailabilities() {
            try {
                const res = await fetch('/api/booking/dates?start_date=2026-09-30&end_date=2026-10-17');
                const data = await res.json();
                vipState.dateAvailabilities = data;
                renderVipDateDropdown(data);
            } catch (err) {
                console.error('Error fetching VIP date availability:', err);
            }
        }

        function formatOrdinalDate(dateStr) {
            const parts = dateStr.split('-');
            const year = parseInt(parts[0]);
            const month = parseInt(parts[1]) - 1;
            const day = parseInt(parts[2]);
            const d = new Date(year, month, day);
            const monthName = d.toLocaleString('en-US', { month: 'long' }).toUpperCase();
            let suffix = 'TH';
            if (![11, 12, 13].includes(day)) {
                if (day % 10 === 1) suffix = 'ST';
                else if (day % 10 === 2) suffix = 'ND';
                else if (day % 10 === 3) suffix = 'RD';
            }
            return `${day}${suffix} ${monthName} ${year}`;
        }

        function getDayOfWeek(dateStr) {
            const parts = dateStr.split('-');
            const d = new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, parseInt(parts[2]));
            return d.toLocaleString('en-US', { weekday: 'long' }).toUpperCase();
        }

        function renderVipDateDropdown(items) {
            const container = document.getElementById('vip-date-items-list');
            if (!container) return;
            container.innerHTML = '';

            let filteredItems = items;
            if (vipState.selectedGroup && vipGroupScheduleMap[vipState.selectedGroup]) {
                const allowedDates = Object.keys(vipGroupScheduleMap[vipState.selectedGroup]);
                filteredItems = items.filter(item => allowedDates.includes(item.date));
            }

            if (!filteredItems || filteredItems.length === 0) {
                container.innerHTML = '<div class="py-2 text-center text-xs text-muted font-weight-bold">NO DATES AVAILABLE FOR THIS VIP GROUP</div>';
                return;
            }

            filteredItems.forEach(item => {
                const dateRow = document.createElement('div');
                const isSelected = vipState.selectedDateRaw === item.date;
                const isAvailable = item.status === 'available';
                const formattedLabel = formatOrdinalDate(item.date);
                const dowStr = getDayOfWeek(item.date);

                let rowClasses = 'date-row d-flex flex-column px-3 py-2 border mb-1 cursor-pointer transition text-uppercase rounded-1 ';
                if (isSelected) {
                    rowClasses += 'selected-pill text-dark';
                } else if (isAvailable) {
                    rowClasses += 'text-dark bg-white';
                } else {
                    rowClasses += 'opacity-50 cursor-not-allowed text-muted bg-light';
                }
                dateRow.className = rowClasses;

                let statusSpan = '';
                if (isSelected) {
                    statusSpan = `<svg style="width: 18px; height: 18px;" class="brand-orange-text" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>`;
                } else if (item.status === 'available') {
                    statusSpan = `<span class="text-xs font-weight-bold text-success">AVAILABLE</span>`;
                } else if (item.status === 'full') {
                    statusSpan = `<span class="text-xs font-weight-bold text-muted">FULLY BOOKED</span>`;
                } else {
                    statusSpan = `<span class="text-xs font-weight-bold text-muted">CLOSED</span>`;
                }

                dateRow.innerHTML = `
                    <div class="text-xxs text-muted font-weight-bold tracking-wider">${dowStr}</div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="font-weight-bold text-xs text-dark">${formattedLabel}</span>
                        ${statusSpan}
                    </div>
                `;

                if (isAvailable) {
                    dateRow.addEventListener('click', (e) => {
                        e.stopPropagation();
                        vipState.selectedDateRaw = item.date;

                        const match = vipDbDates.find(d => d.date === item.date || (d.date && d.date.startsWith(item.date)));
                        const dateId = match ? match.id : item.date;
                        const dateInput = document.getElementById('vip_booking_date_id');
                        if (dateInput) dateInput.value = dateId;

                        const dateBoxText = document.getElementById('vip-date-box-text');
                        if (dateBoxText) {
                            dateBoxText.textContent = `${dowStr}, ${formattedLabel}`;
                            dateBoxText.className = 'text-dark font-weight-bold text-xs text-uppercase';
                        }

                        toggleVipDateDropdown(false);
                        renderVipDateDropdown(vipState.dateAvailabilities);

                        const timeTrigger = document.getElementById('vip-time-trigger-box');
                        if (timeTrigger) {
                            timeTrigger.classList.remove('opacity-60', 'cursor-not-allowed');
                        }

                        loadVipSlotsForDate(item.date);
                    });
                }

                container.appendChild(dateRow);
            });
        }

        async function loadVipSlotsForDate(dateRaw) {
            const timeBoxText = document.getElementById('vip-time-box-text');
            if (timeBoxText) {
                timeBoxText.textContent = 'LOADING TIME SLOTS...';
                timeBoxText.className = 'text-muted font-weight-bold text-xs text-uppercase';
            }
            vipState.selectedSlotId = null;
            const slotInput = document.getElementById('vip_booking_slot_id');
            if (slotInput) slotInput.value = '';

            try {
                const res = await fetch(`/api/booking/dates/${dateRaw}/slots`);
                const slots = await res.json();

                let filteredSlots = slots;
                if (vipState.selectedGroup && vipGroupScheduleMap[vipState.selectedGroup] && vipGroupScheduleMap[vipState.selectedGroup][dateRaw]) {
                    const allowedGroupSlots = vipGroupScheduleMap[vipState.selectedGroup][dateRaw];
                    const allowedTimes = allowedGroupSlots.map(x => x.start_time);
                    filteredSlots = slots.filter(s => allowedTimes.includes(s.start_time));
                }

                vipState.slots = filteredSlots;

                if (timeBoxText) timeBoxText.textContent = 'SELECT YOUR TIME SLOT';
                renderVipSlotDropdown(filteredSlots, dateRaw);
                toggleVipTimeDropdown(true);
            } catch (err) {
                console.error('Error fetching VIP slots:', err);
                if (timeBoxText) timeBoxText.textContent = 'ERROR LOADING TIME SLOTS';
            }
        }

        function renderVipSlotDropdown(slots, dateRaw) {
            const container = document.getElementById('vip-time-items-list');
            if (!container) return;
            container.innerHTML = '';

            if (!slots || slots.length === 0) {
                container.innerHTML = `<div class="py-2 text-center text-xs font-weight-bold text-muted">NO SLOTS ALLOCATED FOR THIS VIP GROUP ON THIS DATE.</div>`;
                return;
            }

            slots.forEach(slot => {
                const slotRow = document.createElement('div');
                const isSelected = vipState.selectedSlotId === slot.id;
                const isAvailable = slot.available;

                let rowClasses = 'slot-row d-flex align-items-center justify-content-between px-3 py-2 border mb-1 cursor-pointer transition text-uppercase rounded-1 ';
                if (isSelected) {
                    rowClasses += 'selected-pill text-dark';
                } else if (isAvailable) {
                    rowClasses += 'text-dark bg-white';
                } else {
                    rowClasses += 'opacity-50 cursor-not-allowed text-muted bg-light';
                }
                slotRow.className = rowClasses;

                let rightSpan = '';
                if (isSelected) {
                    rightSpan = `<svg style="width: 18px; height: 18px;" class="brand-orange-text" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>`;
                } else if (!isAvailable) {
                    rightSpan = `<span class="text-xs font-weight-bold text-danger">SLOT FULL</span>`;
                } else {
                    rightSpan = `<span class="text-xs font-weight-bold text-success">AVAILABLE</span>`;
                }

                slotRow.innerHTML = `
                    <span class="font-weight-bold text-xs text-dark">${slot.label}</span>
                    ${rightSpan}
                `;

                if (isAvailable) {
                    slotRow.addEventListener('click', (e) => {
                        e.stopPropagation();
                        vipState.selectedSlotId = slot.id;
                        const slotInput = document.getElementById('vip_booking_slot_id');
                        if (slotInput) slotInput.value = slot.id;

                        const timeBoxText = document.getElementById('vip-time-box-text');
                        if (timeBoxText) {
                            timeBoxText.textContent = slot.label;
                            timeBoxText.className = 'text-dark font-weight-bold text-xs text-uppercase';
                        }

                        // Auto pre-fill pax if defined in schedule map
                        if (vipState.selectedGroup && vipGroupScheduleMap[vipState.selectedGroup] && dateRaw) {
                            const groupDateSlots = vipGroupScheduleMap[vipState.selectedGroup][dateRaw];
                            if (groupDateSlots) {
                                const matched = groupDateSlots.find(x => x.start_time === slot.start_time);
                                if (matched && paxInput) {
                                    paxInput.value = matched.pax;
                                }
                            }
                        }

                        toggleVipTimeDropdown(false);
                        renderVipSlotDropdown(vipState.slots, dateRaw);
                    });
                }

                container.appendChild(slotRow);
            });
        }

        function toggleVipDateDropdown(open = null) {
            vipState.dateDropdownOpen = open !== null ? open : !vipState.dateDropdownOpen;
            const box = document.getElementById('vip-date-dropdown-box');
            const chevron = document.getElementById('vip-date-chevron');
            if (vipState.dateDropdownOpen) {
                if (vipState.timeDropdownOpen) toggleVipTimeDropdown(false);
                if (box) box.classList.remove('d-none');
                if (chevron) chevron.style.transform = 'rotate(180deg)';
            } else {
                if (box) box.classList.add('d-none');
                if (chevron) chevron.style.transform = 'rotate(0deg)';
            }
        }

        function toggleVipTimeDropdown(open = null) {
            const timeTrigger = document.getElementById('vip-time-trigger-box');
            if (timeTrigger && timeTrigger.classList.contains('cursor-not-allowed')) return;

            vipState.timeDropdownOpen = open !== null ? open : !vipState.timeDropdownOpen;
            const box = document.getElementById('vip-time-dropdown-box');
            const chevron = document.getElementById('vip-time-chevron');
            if (vipState.timeDropdownOpen) {
                if (vipState.dateDropdownOpen) toggleVipDateDropdown(false);
                if (box) box.classList.remove('d-none');
                if (chevron) chevron.style.transform = 'rotate(180deg)';
            } else {
                if (box) box.classList.add('d-none');
                if (chevron) chevron.style.transform = 'rotate(0deg)';
            }
        }

        const dateTrig = document.getElementById('vip-date-trigger-box');
        if (dateTrig) {
            dateTrig.addEventListener('click', (e) => {
                e.stopPropagation();
                toggleVipDateDropdown();
            });
        }

        const timeTrig = document.getElementById('vip-time-trigger-box');
        if (timeTrig) {
            timeTrig.addEventListener('click', (e) => {
                e.stopPropagation();
                toggleVipTimeDropdown();
            });
        }

        document.addEventListener('click', (e) => {
            const dTrig = document.getElementById('vip-date-trigger-box');
            const dBox = document.getElementById('vip-date-dropdown-box');
            if (dTrig && dBox && !dTrig.contains(e.target) && !dBox.contains(e.target) && vipState.dateDropdownOpen) {
                toggleVipDateDropdown(false);
            }

            const tTrig = document.getElementById('vip-time-trigger-box');
            const tBox = document.getElementById('vip-time-dropdown-box');
            if (tTrig && tBox && !tTrig.contains(e.target) && !tBox.contains(e.target) && vipState.timeDropdownOpen) {
                toggleVipTimeDropdown(false);
            }
        });

        // Initialize loading VIP dates
        fetchVipDateAvailabilities();
    });
</script>
@endsection
