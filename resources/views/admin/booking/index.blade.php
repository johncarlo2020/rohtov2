@extends('layouts.admin')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">

<style>
    .matrix-container {
        overflow-x: auto;
        border: 2px solid #1b5e20;
        border-radius: 4px;
        background: #ffffff;
    }
    .matrix-table {
        width: 100%;
        border-collapse: collapse;
        font-family: 'Gill Sans', 'Gill Sans MT', sans-serif;
        font-size: 13px;
        white-space: nowrap;
    }
    .matrix-table th, .matrix-table td {
        border: 1.5px solid #1b5e20;
        padding: 6px 10px;
        text-align: center;
        vertical-align: middle;
    }
    .matrix-header-green {
        background-color: #1b5e20 !important;
        color: #ffffff !important;
        font-weight: 700;
    }
    .matrix-side-header {
        background-color: #f1f5f9;
        font-weight: 700;
        color: #0f172a;
        width: 110px;
    }
    .cell-public {
        background-color: #c8e6c9 !important;
        color: #1b5e20 !important;
        font-weight: 700;
        cursor: pointer;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .cell-public:hover {
        transform: scale(1.03);
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }
    .cell-vip {
        background-color: #ffe0b2 !important;
        color: #e65100 !important;
        font-weight: 700;
        cursor: pointer;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .cell-vip-blue {
        background-color: #bbdefb !important;
        color: #0d47a1 !important;
        font-weight: 700;
        cursor: pointer;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .cell-vip:hover, .cell-vip-blue:hover {
        transform: scale(1.03);
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }
    .cell-empty {
        background-color: #ffffff;
    }
    .brand-title-longchamp {
        font-family: 'Gill Sans', 'Gill Sans MT', sans-serif;
        font-size: 1.8rem;
        letter-spacing: 6px;
        font-weight: 900;
        color: #000000;
    }
    .card-dashboard {
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
        background: #ffffff;
    }

    /* Customer Side UI Components */
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

<div class="container-fluid py-3">

    <!-- Stat Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-sm-6">
            <div class="card card-dashboard p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-xs text-uppercase font-weight-bold text-muted mb-1">Total Reservations</p>
                        <h4 class="font-weight-bolder text-dark mb-0">{{ $totalBookings }}</h4>
                    </div>
                    <div class="icon icon-shape bg-gradient-dark text-white rounded-3 p-3">
                        <i class="fa-solid fa-ticket fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card card-dashboard p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-xs text-uppercase font-weight-bold text-muted mb-1">Attended Customers</p>
                        <h4 class="font-weight-bolder text-success mb-0">{{ $attendedCount }}</h4>
                    </div>
                    <div class="icon icon-shape bg-gradient-success text-white rounded-3 p-3">
                        <i class="fa-solid fa-user-check fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card card-dashboard p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-xs text-uppercase font-weight-bold text-muted mb-1">Confirmed / Active</p>
                        <h4 class="font-weight-bolder text-warning mb-0">{{ $confirmedCount }}</h4>
                    </div>
                    <div class="icon icon-shape bg-gradient-warning text-white rounded-3 p-3">
                        <i class="fa-solid fa-clock fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card card-dashboard p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-xs text-uppercase font-weight-bold text-muted mb-1">Attendance Rate</p>
                        <h4 class="font-weight-bolder text-info mb-0">{{ $attendanceRate }}%</h4>
                    </div>
                    <div class="icon icon-shape bg-gradient-info text-white rounded-3 p-3">
                        <i class="fa-solid fa-chart-line fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Controls & View Mode Tabs -->
    <div class="card card-dashboard p-4 mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
            <div>
                <h4 class="font-weight-bold text-dark mb-1">
                    <i class="fa-solid fa-table-cells text-success me-2"></i>Master Workshop Schedule
                </h4>
                <p class="text-xs text-muted mb-0">Overview of public bookings, VIP sessions, and capacity per date & time slot</p>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <div class="brand-title-longchamp d-none d-md-block me-3">L O N G C H A M P</div>

                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-success btn-sm font-weight-bold shadow-sm mb-0" data-bs-toggle="modal" data-bs-target="#walkinBookingModal" onclick="openWalkinModal()">
                        <i class="fa-solid fa-person-walking me-1"></i> + Walk-In Customer
                    </button>

                    <div class="btn-group shadow-sm mb-0" role="group" aria-label="View Switcher">
                        <button type="button" class="btn btn-primary active btn-sm font-weight-bold" id="btn-view-matrix" onclick="switchView('matrix')">
                            <i class="fa-solid fa-table-cells me-1"></i> Matrix Schedule
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm font-weight-bold" id="btn-view-calendar" onclick="switchView('calendar')">
                            <i class="fa-solid fa-calendar-days me-1"></i> FullCalendar
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm font-weight-bold" id="btn-view-table" onclick="switchView('table')">
                            <i class="fa-solid fa-list me-1"></i> Table List
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- 1. MATRIX SCHEDULE GRID VIEW (Reference Screenshot) --}}
        <div id="matrix-schedule-view" class="view-section">
            <div class="matrix-container shadow-sm mb-2">
                <table class="matrix-table">
                    <thead>
                        {{-- Row 1: Header Green Bar --}}
                        <tr>
                            <th class="matrix-header-green" style="min-width: 150px;">Workshop Session & Time</th>
                            @foreach($matrixDates as $d)
                                <th class="matrix-header-green text-center">
                                    {{ $d['display_day'] }}<br>{{ $d['display_dow'] }}
                                </th>
                            @endforeach
                        </tr>
                        {{-- Row 2: Date Codes (D1, D2, D3...) --}}
                        <tr>
                            <th class="matrix-header-green text-center">By Session</th>
                            @foreach($matrixDates as $d)
                                <th class="matrix-header-green text-center" style="font-size: 11px;">
                                    {{ $d['code'] }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($standardTimeSlots as $label => $timeRange)
                            <tr>
                                {{-- Leftmost Time Slot Column --}}
                                <td class="matrix-side-header text-center">{{ $label }}</td>

                                {{-- Date Grid Cells --}}
                                @foreach($matrixDates as $d)
                                    @php
                                        $cellData = $matrixCells[$label][$d['id']] ?? null;
                                        $pubCount = $cellData['public_count'] ?? 0;
                                        $vipCount = $cellData['vip_count'] ?? 0;
                                        $vipName = $cellData['vip_name'] ?? '';
                                    @endphp

                                    @if($cellData && ($pubCount > 0 || $vipCount > 0))
                                        @if($vipCount > 0 && $pubCount == 0)
                                            {{-- VIP Cell --}}
                                            <td class="cell-vip text-center" onclick="openSessionModal('{{ $label }}', '{{ $d['display_day'] }}', {{ json_encode($cellData['bookings']) }})">
                                                {{ $vipName }} x{{ $vipCount }}
                                            </td>
                                        @elseif($pubCount > 0 && $vipCount > 0)
                                            {{-- Combined Cell --}}
                                            <td class="cell-vip-blue text-center" onclick="openSessionModal('{{ $label }}', '{{ $d['display_day'] }}', {{ json_encode($cellData['bookings']) }})">
                                                Public x{{ $pubCount }}<br><small>({{ $vipName }} x{{ $vipCount }})</small>
                                            </td>
                                        @else
                                            {{-- Public Cell --}}
                                            <td class="cell-public text-center" onclick="openSessionModal('{{ $label }}', '{{ $d['display_day'] }}', {{ json_encode($cellData['bookings']) }})">
                                                Public x{{ $pubCount }}
                                            </td>
                                        @endif
                                    @else
                                        {{-- Empty Cell --}}
                                        <td class="cell-empty"></td>
                                    @endif
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Legend Footer --}}
            <div class="d-flex align-items-center gap-4 mt-3 text-xs font-weight-bold">
                <div class="d-flex align-items-center gap-2">
                    <span class="d-inline-block px-3 py-1 rounded" style="background:#c8e6c9; border: 1px solid #1b5e20; color:#1b5e20;">Public Session</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="d-inline-block px-3 py-1 rounded" style="background:#ffe0b2; border: 1px solid #e65100; color:#e65100;">VIP Session</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="d-inline-block px-3 py-1 rounded" style="background:#bbdefb; border: 1px solid #0d47a1; color:#0d47a1;">Combined (Public + VIP)</span>
                </div>
            </div>
        </div>

        {{-- 2. FULLCALENDAR VIEW --}}
        <div id="fullcalendar-view" class="view-section d-none">
            <div id="calendar" style="min-height: 580px;"></div>
        </div>

        {{-- 3. TABLE LIST VIEW --}}
        <div id="table-list-view" class="view-section d-none">
            <div class="table-responsive">
                <table class="table table-hover align-items-center mb-0" id="booking-list-table">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Reference No</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder ps-2">Customer</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Booking Date</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Time Slot</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Attendance</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bookings as $b)
                        @php
                            $isAttended = ($b->computed_status === 'Attended');
                        @endphp
                        <tr>
                            <td>
                                <span class="badge bg-dark">{{ $b->reference_no }}</span>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <h6 class="mb-0 text-sm font-weight-bold">{{ $b->customer_name }}</h6>
                                    <span class="text-xs text-muted">{{ $b->customer_email }} | {{ $b->customer_phone }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="text-sm font-weight-bold text-dark">
                                    {{ $b->bookingDate->display_date ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ $b->bookingSlot->display_time ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                @if($isAttended)
                                    <span class="badge bg-success"><i class="fa-solid fa-check me-1"></i>ATTENDED</span>
                                    @if($b->attended_at)
                                        <br><small class="text-muted text-xxs">{{ \Carbon\Carbon::parse($b->attended_at)->format('M d, h:i A') }}</small>
                                    @endif
                                @elseif($b->computed_status === 'Missed')
                                    <span class="badge bg-danger"><i class="fa-solid fa-xmark me-1"></i>MISSED</span>
                                @else
                                    <span class="badge bg-secondary"><i class="fa-solid fa-clock me-1"></i>NOT YET ATTENDED</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <button class="btn btn-xs {{ $isAttended ? 'btn-outline-secondary' : 'btn-success' }} me-1" 
                                        onclick="toggleAttendance({{ $b->id }})">
                                    {{ $isAttended ? 'Mark Unattended' : 'Mark Attended' }}
                                </button>
                                <button class="btn btn-xs btn-outline-danger" onclick="deleteBooking({{ $b->id }})">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- Session Details Modal -->
<div class="modal fade" id="sessionModal" tabindex="-1" aria-labelledby="sessionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title font-weight-bold text-dark" id="sessionModalLabel">
                    <i class="fa-solid fa-users text-primary me-2"></i>Session Guest Bookings
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <h6 id="session-modal-subtitle" class="font-weight-bold text-primary mb-3"></h6>
                <div class="table-responsive">
                    <table class="table align-items-center mb-0" id="session-modal-table">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Ref No</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Guest Name</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Email / Phone</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">Pax</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">Status</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">Action</th>
                            </tr>
                        </thead>
                        <tbody id="session-modal-tbody"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary btn-sm px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Walk-In Customer Booking Modal -->
<div class="modal fade" id="walkinBookingModal" tabindex="-1" aria-labelledby="walkinBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title font-weight-bold text-dark" id="walkinBookingModalLabel">
                    <i class="fa-solid fa-person-walking text-success me-2"></i>Walk-In Customer Registration
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.walkin.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 text-start">
                    <div class="alert alert-success py-2 px-3 text-xs mb-3 font-weight-bold text-white">
                        <i class="fa-solid fa-bolt me-1"></i>Direct Admin Check-in (No OTP Verification Needed)
                    </div>

                    {{-- Title --}}
                    <div class="mb-3">
                        <label class="form-label font-weight-bold text-xs text-uppercase text-muted">Title *</label>
                        <select name="title" class="form-select" required>
                            <option value="MR.">MR.</option>
                            <option value="MS.">MS.</option>
                            <option value="MRS.">MRS.</option>
                            <option value="MISS">MISS</option>
                            <option value="OTHER">OTHER</option>
                        </select>
                    </div>

                    {{-- First & Last Name --}}
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label font-weight-bold text-xs text-uppercase text-muted">First Name *</label>
                            <input type="text" name="fname" class="form-control" placeholder="FIRST NAME" required />
                        </div>
                        <div class="col-6">
                            <label class="form-label font-weight-bold text-xs text-uppercase text-muted">Last Name *</label>
                            <input type="text" name="lname" class="form-control" placeholder="LAST NAME" required />
                        </div>
                    </div>

                    {{-- Email & Phone --}}
                    <div class="mb-3">
                        <label class="form-label font-weight-bold text-xs text-uppercase text-muted">Email Address *</label>
                        <input type="email" name="email" class="form-control" placeholder="customer@example.com" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold text-xs text-uppercase text-muted">Phone Number</label>
                        <input type="text" name="phone" class="form-control" placeholder="+60123456789" />
                    </div>

                    {{-- Hidden Form Controls for Submission --}}
                    <input type="hidden" name="booking_date_id" id="walkin_booking_date_id" required>
                    <input type="hidden" name="booking_slot_id" id="walkin_booking_slot_id" required>

                    {{-- SECTION 1: DATE SELECTION (Matches Customer Side UI) --}}
                    <div class="mb-3">
                        <label class="form-label font-weight-bold text-xs text-uppercase text-muted mb-1">
                            DATE AVAILABLE: *
                        </label>

                        <div class="position-relative">
                            <!-- Date Selection Box (Collapsed / Selected Display) -->
                            <div id="walkin-date-trigger-box" class="form-control d-flex justify-content-between align-items-center py-2 px-3 bg-white border cursor-pointer rounded-1">
                                <span id="walkin-date-box-text" class="text-muted font-weight-bold text-xs text-uppercase">DATE SELECTION</span>
                                <i class="fa-solid fa-chevron-down text-muted" id="walkin-date-chevron" style="transition: transform 0.2s;"></i>
                            </div>

                            <!-- Date Selection Expanded Dropdown Box (Overlay) -->
                            <div id="walkin-date-dropdown-box" class="d-none dropdown-overlay p-3">
                                <div class="small font-weight-bold text-dark text-uppercase pb-2 mb-2 border-bottom d-flex justify-content-between align-items-center text-xs">
                                    <span>DATE SELECTION</span>
                                    <span>30 SEP – 17 OCT 2026</span>
                                </div>

                                <!-- Date Items List -->
                                <div id="walkin-date-items-list" class="custom-scroll">
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
                            <!-- Time Slot Trigger Box (Collapsed / Selected Display) -->
                            <div id="walkin-time-trigger-box" class="form-control d-flex justify-content-between align-items-center py-2 px-3 bg-white border cursor-pointer rounded-1 opacity-60 cursor-not-allowed">
                                <span id="walkin-time-box-text" class="text-muted font-weight-bold text-xs text-uppercase">SELECT YOUR TIME SLOT</span>
                                <i class="fa-solid fa-chevron-down text-muted" id="walkin-time-chevron" style="transition: transform 0.2s;"></i>
                            </div>

                            <!-- Time Slot Expanded Dropdown Box (Overlay) -->
                            <div id="walkin-time-dropdown-box" class="d-none dropdown-overlay p-3">
                                <div class="small font-weight-bold text-muted text-uppercase pb-2 mb-2 border-bottom text-xs">
                                    <span id="walkin-sessions-header">TIME SLOTS</span>
                                </div>

                                <!-- Time Slot Items List -->
                                <div id="walkin-time-items-list">
                                    <!-- Dynamically populated -->
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Pax & Mark Attended Checkbox --}}
                    <div class="row g-2 align-items-center mb-2">
                        <div class="col-6">
                            <label class="form-label font-weight-bold text-xs text-uppercase text-muted">Pax Count *</label>
                            <input type="number" name="pax" class="form-control" value="1" min="1" max="20" required />
                        </div>
                        <div class="col-6 pt-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="mark_attended" value="1" id="markAttendedWalkin" checked />
                                <label class="form-check-label text-xs font-weight-bold text-dark" for="markAttendedWalkin">
                                    Mark as Attended Now
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success btn-sm px-4 font-weight-bold shadow-sm">
                        <i class="fa-solid fa-check me-1"></i>Confirm Walk-In Booking
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

<script>
    let calendar = null;

    function switchView(mode) {
        document.querySelectorAll('.view-section').forEach(el => el.classList.add('d-none'));
        document.querySelectorAll('.btn-group .btn').forEach(btn => {
            btn.classList.remove('btn-primary', 'active');
            btn.classList.add('btn-outline-primary');
        });

        if (mode === 'matrix') {
            document.getElementById('matrix-schedule-view').classList.remove('d-none');
            document.getElementById('btn-view-matrix').classList.add('btn-primary', 'active');
        } else if (mode === 'calendar') {
            document.getElementById('fullcalendar-view').classList.remove('d-none');
            document.getElementById('btn-view-calendar').classList.add('btn-primary', 'active');
            if (calendar) {
                setTimeout(() => calendar.render(), 100);
            }
        } else if (mode === 'table') {
            document.getElementById('table-list-view').classList.remove('d-none');
            document.getElementById('btn-view-table').classList.add('btn-primary', 'active');
        }
    }

    function openSessionModal(timeLabel, dateLabel, bookings) {
        document.getElementById('session-modal-subtitle').textContent = `${dateLabel} @ ${timeLabel} (${bookings.length} Guest Entries)`;
        const tbody = document.getElementById('session-modal-tbody');
        tbody.innerHTML = '';

        if (!bookings || bookings.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center py-3 text-muted text-xs">No bookings recorded for this session.</td></tr>';
        } else {
            bookings.forEach(b => {
                const tr = document.createElement('tr');
                const badgeClass = b.status === 'Attended' ? 'bg-success' : (b.status === 'Missed' ? 'bg-danger' : 'bg-warning text-dark');
                const isVipBadge = b.is_vip ? '<span class="badge bg-warning text-dark me-1">VIP</span>' : '';

                tr.innerHTML = `
                    <td class="ps-3"><span class="badge bg-dark">${b.ref}</span></td>
                    <td><span class="font-weight-bold text-dark text-xs">${isVipBadge}${b.name}</span></td>
                    <td><span class="text-xs text-muted">${b.email} | ${b.phone}</span></td>
                    <td class="text-center font-weight-bold text-xs">${b.pax || 1}</td>
                    <td class="text-center"><span class="badge ${badgeClass}">${b.status}</span></td>
                    <td class="text-center">
                        <button class="btn btn-xs ${b.status === 'Attended' ? 'btn-outline-secondary' : 'btn-success'}" onclick="toggleAttendance(${b.id})">
                            ${b.status === 'Attended' ? 'Mark Unattended' : 'Mark Attended'}
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            const instance = bootstrap.Modal.getOrCreateInstance(document.getElementById('sessionModal'));
            instance.show();
        } else if (typeof $ !== 'undefined') {
            $('#sessionModal').modal('show');
        }
    }

    function openWalkinModal() {
        const modalElem = document.getElementById('walkinBookingModal');
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            const instance = bootstrap.Modal.getOrCreateInstance(modalElem);
            instance.show();
        } else if (typeof $ !== 'undefined' && typeof $.fn.modal === 'function') {
            $(modalElem).modal('show');
        } else {
            modalElem.classList.add('show', 'd-block');
            modalElem.style.display = 'block';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');
        const eventsData = @json($calendarEvents);

        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            initialDate: '{{ $initialCalendarDate }}',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
            },
            events: eventsData,
            eventTimeFormat: {
                hour: 'numeric',
                minute: '2-digit',
                meridiem: 'short'
            }
        });

        // Walk-in Date & Slot Custom Customer-Side UI Dropdown Logic
        const walkinDbDates = @json($walkinDates);

        let walkinState = {
            dateAvailabilities: [],
            slots: [],
            selectedDateRaw: null,
            selectedSlotId: null,
            dateDropdownOpen: false,
            timeDropdownOpen: false
        };

        async function fetchWalkinDateAvailabilities() {
            try {
                const res = await fetch('/api/booking/dates?start_date=2026-09-30&end_date=2026-10-17');
                const data = await res.json();
                walkinState.dateAvailabilities = data;
                renderWalkinDateDropdown(data);
            } catch (err) {
                console.error('Error fetching date availability:', err);
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

        function renderWalkinDateDropdown(items) {
            const container = document.getElementById('walkin-date-items-list');
            if (!container) return;
            container.innerHTML = '';

            if (!items || items.length === 0) {
                container.innerHTML = '<div class="py-2 text-center text-xs text-muted font-weight-bold">NO DATES AVAILABLE</div>';
                return;
            }

            items.forEach(item => {
                const dateRow = document.createElement('div');
                const isSelected = walkinState.selectedDateRaw === item.date;
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
                        walkinState.selectedDateRaw = item.date;

                        // Find matching DB ID
                        const match = walkinDbDates.find(d => d.date === item.date || (d.date && d.date.startsWith(item.date)));
                        const dateId = match ? match.id : item.date;
                        document.getElementById('walkin_booking_date_id').value = dateId;

                        document.getElementById('walkin-date-box-text').textContent = `${dowStr}, ${formattedLabel}`;
                        document.getElementById('walkin-date-box-text').className = 'text-dark font-weight-bold text-xs text-uppercase';

                        toggleWalkinDateDropdown(false);
                        renderWalkinDateDropdown(walkinState.dateAvailabilities);

                        // Unlock time slot selection
                        const timeTrigger = document.getElementById('walkin-time-trigger-box');
                        if (timeTrigger) {
                            timeTrigger.classList.remove('opacity-60', 'cursor-not-allowed');
                        }

                        loadWalkinSlotsForDate(item.date);
                    });
                }

                container.appendChild(dateRow);
            });
        }

        async function loadWalkinSlotsForDate(dateRaw) {
            const timeBoxText = document.getElementById('walkin-time-box-text');
            if (timeBoxText) {
                timeBoxText.textContent = 'LOADING TIME SLOTS...';
                timeBoxText.className = 'text-muted font-weight-bold text-xs text-uppercase';
            }
            walkinState.selectedSlotId = null;
            const slotInput = document.getElementById('walkin_booking_slot_id');
            if (slotInput) slotInput.value = '';

            try {
                const res = await fetch(`/api/booking/dates/${dateRaw}/slots`);
                const slots = await res.json();
                walkinState.slots = slots;

                if (timeBoxText) timeBoxText.textContent = 'SELECT YOUR TIME SLOT';
                renderWalkinSlotDropdown(slots);
                toggleWalkinTimeDropdown(true);
            } catch (err) {
                console.error('Error fetching slots:', err);
                if (timeBoxText) timeBoxText.textContent = 'ERROR LOADING TIME SLOTS';
            }
        }

        function renderWalkinSlotDropdown(slots) {
            const container = document.getElementById('walkin-time-items-list');
            if (!container) return;
            container.innerHTML = '';

            if (!slots || slots.length === 0) {
                container.innerHTML = `<div class="py-2 text-center text-xs font-weight-bold text-muted">NO AVAILABLE SESSIONS FOR THIS DATE.</div>`;
                return;
            }

            slots.forEach(slot => {
                const slotRow = document.createElement('div');
                const isSelected = walkinState.selectedSlotId === slot.id;
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
                        walkinState.selectedSlotId = slot.id;
                        const slotInput = document.getElementById('walkin_booking_slot_id');
                        if (slotInput) slotInput.value = slot.id;

                        const timeBoxText = document.getElementById('walkin-time-box-text');
                        if (timeBoxText) {
                            timeBoxText.textContent = slot.label;
                            timeBoxText.className = 'text-dark font-weight-bold text-xs text-uppercase';
                        }

                        toggleWalkinTimeDropdown(false);
                        renderWalkinSlotDropdown(walkinState.slots);
                    });
                }

                container.appendChild(slotRow);
            });
        }

        function toggleWalkinDateDropdown(open = null) {
            walkinState.dateDropdownOpen = open !== null ? open : !walkinState.dateDropdownOpen;
            const box = document.getElementById('walkin-date-dropdown-box');
            const chevron = document.getElementById('walkin-date-chevron');
            if (walkinState.dateDropdownOpen) {
                if (walkinState.timeDropdownOpen) toggleWalkinTimeDropdown(false);
                if (box) box.classList.remove('d-none');
                if (chevron) chevron.style.transform = 'rotate(180deg)';
            } else {
                if (box) box.classList.add('d-none');
                if (chevron) chevron.style.transform = 'rotate(0deg)';
            }
        }

        function toggleWalkinTimeDropdown(open = null) {
            const timeTrigger = document.getElementById('walkin-time-trigger-box');
            if (timeTrigger && timeTrigger.classList.contains('cursor-not-allowed')) return;

            walkinState.timeDropdownOpen = open !== null ? open : !walkinState.timeDropdownOpen;
            const box = document.getElementById('walkin-time-dropdown-box');
            const chevron = document.getElementById('walkin-time-chevron');
            if (walkinState.timeDropdownOpen) {
                if (walkinState.dateDropdownOpen) toggleWalkinDateDropdown(false);
                if (box) box.classList.remove('d-none');
                if (chevron) chevron.style.transform = 'rotate(180deg)';
            } else {
                if (box) box.classList.add('d-none');
                if (chevron) chevron.style.transform = 'rotate(0deg)';
            }
        }

        const dateTrig = document.getElementById('walkin-date-trigger-box');
        if (dateTrig) {
            dateTrig.addEventListener('click', (e) => {
                e.stopPropagation();
                toggleWalkinDateDropdown();
            });
        }

        const timeTrig = document.getElementById('walkin-time-trigger-box');
        if (timeTrig) {
            timeTrig.addEventListener('click', (e) => {
                e.stopPropagation();
                toggleWalkinTimeDropdown();
            });
        }

        document.addEventListener('click', (e) => {
            const dTrig = document.getElementById('walkin-date-trigger-box');
            const dBox = document.getElementById('walkin-date-dropdown-box');
            if (dTrig && dBox && !dTrig.contains(e.target) && !dBox.contains(e.target) && walkinState.dateDropdownOpen) {
                toggleWalkinDateDropdown(false);
            }

            const tTrig = document.getElementById('walkin-time-trigger-box');
            const tBox = document.getElementById('walkin-time-dropdown-box');
            if (tTrig && tBox && !tTrig.contains(e.target) && !tBox.contains(e.target) && walkinState.timeDropdownOpen) {
                toggleWalkinTimeDropdown(false);
            }
        });

        // Initialize loading dates for Walk-in modal
        fetchWalkinDateAvailabilities();
    });

    function toggleAttendance(id) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(`/admin/bookings/${id}/attend`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            }
        })
        .catch(err => console.error(err));
    }

    function deleteBooking(id) {
        if (!confirm('Are you sure you want to delete this booking?')) return;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(`/admin/bookings/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            }
        })
        .catch(err => console.error(err));
    }
</script>
@endsection
