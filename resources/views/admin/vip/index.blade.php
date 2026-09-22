@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show text-white" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show text-white" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>
            <ul class="mb-0 ps-3 text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="font-weight-bolder text-dark mb-1">
                <i class="fa-solid fa-crown text-warning me-2"></i>VIP Schedule & Group Allocation Breakdown
            </h4>
            <p class="text-sm text-muted mb-0">Overview of VIP group schedule presets and allocated pax capacity.</p>
        </div>
        <div>
            @if(auth()->check() && !auth()->user()->hasRole('staff'))
                <button type="button" class="btn btn-warning text-dark font-weight-bold shadow-sm rounded-3 px-3 py-2 text-xs text-uppercase" id="btn-add-vip-group">
                    <i class="fa-solid fa-plus me-1"></i>Add VIP Group Preset
                </button>
            @endif
        </div>
    </div>

    {{-- VIP Group Schedule Breakdown --}}
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow-sm border-0" style="border-radius: 14px;">
                <div class="card-header bg-white border-0 pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="font-weight-bold text-dark mb-0">
                        <i class="fa-solid fa-layer-group text-warning me-2"></i>VIP Group Schedule & Allocation Breakdown
                    </h5>
                    <span class="badge bg-warning text-dark text-xxs font-weight-bold">{{ count($vipGroups) }} VIP Groups</span>
                </div>
                <div class="card-body px-0 pt-2 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3">VIP Group Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Active Dates</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Time Slots & Allocated Pax</th>
                                    @if(auth()->check() && !auth()->user()->hasRole('staff'))
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end pe-3">Actions</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vipGroups as $key => $group)
                                    <tr>
                                        <td class="ps-3 align-middle">
                                            <span class="text-xs font-weight-bold text-dark"><i class="fa-solid fa-crown text-warning me-1"></i>{{ $group['name'] }}</span>
                                        </td>
                                        <td class="align-middle">
                                            <span class="badge bg-light text-dark text-xxs">{{ $group['badge'] }}</span>
                                        </td>
                                        <td class="align-middle text-xs">
                                            @foreach($group['breakdown_details'] as $detail)
                                                <div>{{ $detail }}</div>
                                            @endforeach
                                        </td>
                                        @if(auth()->check() && !auth()->user()->hasRole('staff'))
                                            <td class="align-middle text-end pe-3">
                                                <button type="button" 
                                                    class="btn btn-xs btn-outline-warning text-dark font-weight-bold mb-0 me-1 btn-edit-vip-group"
                                                    data-key="{{ $key }}"
                                                    data-name="{{ $group['name'] }}"
                                                    data-schedules='@json($group['schedules'])'>
                                                    <i class="fa-solid fa-pen-to-square me-1"></i>Modify
                                                </button>
                                                <button type="button" 
                                                    class="btn btn-xs btn-outline-danger font-weight-bold mb-0 btn-delete-vip-group"
                                                    data-key="{{ $key }}"
                                                    data-name="{{ $group['name'] }}">
                                                    <i class="fa-solid fa-trash me-1"></i>Delete
                                                </button>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Date Availability & Public Schedule Slots Management --}}
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow-sm border-0" style="border-radius: 14px;">
                <div class="card-header bg-white border-0 pb-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h5 class="font-weight-bold text-dark mb-0">
                            <i class="fa-solid fa-calendar-days text-success me-2"></i>Date Availability & Public Schedule Management
                        </h5>
                        <p class="text-xs text-muted mb-0">Block/unblock event dates, add new dates (e.g. Oct 19), and manage public workshop slots & Pax capacity.</p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        @if(auth()->check() && !auth()->user()->hasRole('staff'))
                            <button type="button" class="btn btn-xs btn-success font-weight-bold mb-0 shadow-sm" id="btn-add-new-event-date">
                                <i class="fa-solid fa-calendar-plus me-1"></i>Add New Event Date
                            </button>
                        @endif
                        <span class="badge bg-success text-white text-xxs font-weight-bold">{{ count($eventDates ?? []) }} Event Dates</span>
                    </div>
                </div>
                <div class="card-body px-0 pt-2 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3">Event Date</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Availability Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Public Time Slots & Pax Capacity</th>
                                    @if(auth()->check() && !auth()->user()->hasRole('staff'))
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end pe-3">Actions</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($eventDates as $eDate)
                                    <tr>
                                        <td class="ps-3 align-middle">
                                            <span class="text-xs font-weight-bold text-dark"><i class="fa-solid fa-calendar-day me-1 text-primary"></i>{{ $eDate['display_date'] }}</span>
                                        </td>
                                        <td class="align-middle">
                                            @if($eDate['is_available'])
                                                <span class="badge bg-success text-white text-xxs"><i class="fa-solid fa-check-circle me-1"></i>OPEN / AVAILABLE</span>
                                            @else
                                                <span class="badge bg-danger text-white text-xxs"><i class="fa-solid fa-ban me-1"></i>BLOCKED / CLOSED</span>
                                            @endif
                                        </td>
                                        <td class="align-middle text-xs">
                                            @if(empty($eDate['slots']))
                                                <span class="text-muted text-xxs italic">No public slots configured</span>
                                            @else
                                                <div class="d-flex flex-wrap gap-1">
                                                    @foreach($eDate['slots'] as $s)
                                                        <span class="badge bg-light text-dark border me-1 mb-1">
                                                            <i class="fa-solid fa-clock me-1 text-muted"></i>{{ $s['display_time'] }} ({{ $s['capacity'] }} Pax)
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </td>
                                        @if(auth()->check() && !auth()->user()->hasRole('staff'))
                                            <td class="align-middle text-end pe-3">
                                                <button type="button" 
                                                    class="btn btn-xs btn-outline-primary font-weight-bold mb-0 me-1 btn-edit-public-slots"
                                                    data-date="{{ $eDate['date'] }}"
                                                    data-display-date="{{ $eDate['display_date'] }}"
                                                    data-is-available="{{ $eDate['is_available'] ? '1' : '0' }}"
                                                    data-slots='@json($eDate['slots'])'>
                                                    <i class="fa-solid fa-pen-to-square me-1"></i>Modify Slots & Pax
                                                </button>
                                                
                                                <form action="{{ route('admin.schedule.toggle-date') }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="date" value="{{ $eDate['date'] }}">
                                                    <input type="hidden" name="is_available" value="{{ $eDate['is_available'] ? '0' : '1' }}">
                                                    @if($eDate['is_available'])
                                                        <button type="submit" class="btn btn-xs btn-outline-danger font-weight-bold mb-0" onclick="return confirm('Block date {{ $eDate['display_date'] }} for public bookings?')">
                                                            <i class="fa-solid fa-ban me-1"></i>Block Date
                                                        </button>
                                                    @else
                                                        <button type="submit" class="btn btn-xs btn-outline-success font-weight-bold mb-0">
                                                            <i class="fa-solid fa-lock-open me-1"></i>Unblock Date
                                                        </button>
                                                    @endif
                                                </form>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL 1: EDIT / CREATE VIP GROUP PRESET --}}
<div class="modal fade" id="editVipGroupModal" tabindex="-1" aria-labelledby="editVipGroupModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <form action="{{ route('admin.vip.group.update') }}" method="POST" id="vip-group-form">
                @csrf
                <input type="hidden" name="original_key" id="vip_modal_original_key" value="">
                
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title font-weight-bold text-dark" id="editVipGroupModalLabel">
                        <i class="fa-solid fa-pen-to-square text-warning me-2"></i>Modify VIP Group & Schedule Allocation
                    </h5>
                    <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body p-4">
                    {{-- VIP Group Name --}}
                    <div class="mb-3">
                        <label for="vip_modal_name" class="form-label font-weight-bold text-xs text-uppercase text-dark mb-1">
                            VIP Group Name *
                        </label>
                        <input type="text" class="form-control font-weight-bold border-warning text-dark" id="vip_modal_name" name="name" placeholder="e.g. PIN PRESTIGE" required>
                        <div class="form-text text-xxs text-muted">Updating this name updates the VIP group selection preset across admin booking creation.</div>
                    </div>

                    {{-- Schedule Entries Header --}}
                    <div class="d-flex justify-content-between align-items-center mt-4 mb-2">
                        <label class="form-label font-weight-bold text-xs text-uppercase text-dark mb-0">
                            <i class="fa-solid fa-clock me-1 text-warning"></i>Schedule Slots & Allocated Pax *
                        </label>
                        <button type="button" class="btn btn-xs btn-outline-warning text-dark font-weight-bold" id="btn-add-schedule-row">
                            <i class="fa-solid fa-plus me-1"></i>Add Schedule Slot
                        </button>
                    </div>

                    {{-- Dynamic Schedule Slot Container --}}
                    <div id="schedule-entries-container" class="border rounded-2 p-3 bg-light">
                        {{-- Rows injected dynamically by JS --}}
                    </div>
                </div>

                <div class="modal-footer border-top-0 pt-0 pe-4 pb-4">
                    <button type="button" class="btn btn-sm btn-light text-secondary font-weight-bold rounded-2 px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-warning text-dark font-weight-bold rounded-2 px-4 shadow-sm">
                        <i class="fa-solid fa-floppy-disk me-1"></i>Save VIP Preset Updates
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL 2: DELETE CONFIRMATION --}}
<div class="modal fade" id="deleteVipGroupModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 14px;">
            <form action="{{ route('admin.vip.group.delete') }}" method="POST">
                @csrf
                <input type="hidden" name="key" id="delete_modal_key" value="">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title font-weight-bold text-dark"><i class="fa-solid fa-triangle-exclamation text-danger me-2"></i>Delete VIP Group Preset</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 text-sm text-secondary">
                    Are you sure you want to delete VIP Group Preset <strong id="delete_modal_name_text" class="text-dark"></strong>?
                    <div class="alert alert-warning text-white text-xs mt-3 mb-0">
                        <i class="fa-solid fa-info-circle me-1"></i>This will remove the group preset option from VIP creation dropdowns.
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-sm btn-light font-weight-bold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-danger font-weight-bold">Confirm Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL 3: MODIFY PUBLIC SCHEDULE SLOTS & PAX CAPACITY --}}
<div class="modal fade" id="editPublicSlotsModal" tabindex="-1" aria-labelledby="editPublicSlotsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <form action="{{ route('admin.schedule.update-public-slots') }}" method="POST" id="public-schedule-form">
                @csrf
                <input type="hidden" name="date" id="public_modal_date" value="">
                <input type="hidden" name="is_create" id="public_modal_is_create" value="0">
                
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title font-weight-bold text-dark" id="editPublicSlotsModalLabel">
                        <i class="fa-solid fa-calendar-pen text-primary me-2"></i>Modify Public Schedule Slots & Pax Capacity
                    </h5>
                    <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body p-4">
                    {{-- Date Picker for New Date --}}
                    <div class="mb-3 d-none" id="public_modal_date_input_container">
                        <label for="public_modal_date_input" class="form-label font-weight-bold text-xs text-uppercase text-dark mb-1">
                            <i class="fa-solid fa-calendar-plus text-success me-1"></i>Event Date *
                        </label>
                        <input type="date" class="form-control form-control-sm text-xs font-weight-bold text-dark border-success mb-2" id="public_modal_date_input" min="2026-09-30" max="2026-12-31">
                        
                        <div class="alert alert-danger text-white text-xs mb-0 font-weight-bold d-none shadow-sm" id="public_modal_duplicate_alert" style="border-radius: 8px;">
                            <i class="fa-solid fa-triangle-exclamation me-1"></i> Cannot create event date: Event date <span id="duplicate_date_text"></span> already exists in the schedule! Duplicate creation is not allowed. Please modify the existing date instead.
                        </div>
                    </div>

                    <div class="alert alert-info text-white text-xs mb-3 font-weight-bold">
                        <i class="fa-solid fa-info-circle me-1"></i>Configure public workshop time slots and set Pax capacity for <span id="public_modal_date_display"></span>.
                    </div>

                    {{-- Availability Switch --}}
                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" name="is_available" value="1" id="public_modal_is_available" checked>
                        <label class="form-check-label font-weight-bold text-xs text-uppercase text-dark" for="public_modal_is_available">
                            Date Open / Available for Bookings
                        </label>
                    </div>

                    {{-- Schedule Entries Header --}}
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label font-weight-bold text-xs text-uppercase text-dark mb-0">
                            <i class="fa-solid fa-clock me-1 text-primary"></i>Time Slots & Pax Capacity *
                        </label>
                        <button type="button" class="btn btn-xs btn-outline-primary font-weight-bold" id="btn-add-public-slot-row">
                            <i class="fa-solid fa-plus me-1"></i>Add Schedule Slot
                        </button>
                    </div>

                    {{-- Dynamic Schedule Slot Container --}}
                    <div id="public-schedule-entries-container" class="border rounded-2 p-3 bg-light">
                        {{-- Rows injected dynamically by JS --}}
                    </div>
                </div>

                <div class="modal-footer border-top-0 pt-0 pe-4 pb-4">
                    <button type="button" class="btn btn-sm btn-light text-secondary font-weight-bold rounded-2 px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary font-weight-bold rounded-2 px-4 shadow-sm" id="public_modal_submit_btn">
                        <i class="fa-solid fa-floppy-disk me-1"></i>Save Public Schedule Updates
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const editModalEl = document.getElementById('editVipGroupModal');
    const editModal = new bootstrap.Modal(editModalEl);

    const deleteModalEl = document.getElementById('deleteVipGroupModal');
    const deleteModal = new bootstrap.Modal(deleteModalEl);

    const publicModalEl = document.getElementById('editPublicSlotsModal');
    const publicModal = new bootstrap.Modal(publicModalEl);

    const container = document.getElementById('schedule-entries-container');
    const publicContainer = document.getElementById('public-schedule-entries-container');

    const btnAddRow = document.getElementById('btn-add-schedule-row');
    const btnAddPublicRow = document.getElementById('btn-add-public-slot-row');
    const btnAddVipGroup = document.getElementById('btn-add-vip-group');

    const fullSlots = @json($fullSlots ?? []);

    function isSlotFull(dateStr, timeStr) {
        if (!dateStr || !timeStr) return false;
        const cleanTime = timeStr.substr(0, 5);
        return fullSlots.some(s => s.date === dateStr && s.start_time === cleanTime);
    }

    const availableTimes = [
        '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30',
        '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30',
        '19:00', '19:30', '20:00', '20:30', '21:00', '21:30', '22:00'
    ];

    function renderTimeOptions(selectedTime, dateStr = '') {
        let html = '';
        availableTimes.forEach(t => {
            const isSel = selectedTime && selectedTime.startsWith(t) ? 'selected' : '';
            const dStr = formatTimeLabel(t);
            const fullTag = (dateStr && isSlotFull(dateStr, t)) ? ' [FULL - Modification Locked]' : '';
            html += `<option value="${t}" ${isSel}>${dStr} (${t})${fullTag}</option>`;
        });
        return html;
    }

    function formatTimeLabel(timeStr) {
        const parts = timeStr.split(':');
        let h = parseInt(parts[0]);
        const m = parts[1];
        const ampm = h >= 12 ? 'PM' : 'AM';
        h = h % 12;
        h = h ? h : 12;
        return `${h}:${m} ${ampm}`;
    }

    function addScheduleRow(date = '2026-10-01', startTime = '11:00', endTime = '12:00', pax = 6) {
        const rowIdx = container.children.length;
        const isFull = isSlotFull(date, startTime);
        const rowDiv = document.createElement('div');
        rowDiv.className = `row g-2 align-items-center mb-2 schedule-row p-2 rounded border ${isFull ? 'bg-light border-danger' : 'bg-white'}`;
        rowDiv.innerHTML = `
            <div class="col-md-3">
                <label class="form-label text-xxs font-weight-bold text-uppercase text-muted mb-1">Date</label>
                <input type="date" class="form-control form-control-sm text-xs font-weight-bold text-dark schedule-date-input" name="entries[${rowIdx}][date]" value="${date}" min="2026-09-30" max="2026-12-31" required>
            </div>
            <div class="col-md-3">
                <label class="form-label text-xxs font-weight-bold text-uppercase text-muted mb-1">From (Start Time)</label>
                <select class="form-select form-select-sm text-xs font-weight-bold text-dark schedule-start-select" name="entries[${rowIdx}][start_time]" required>
                    ${renderTimeOptions(startTime, date)}
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label text-xxs font-weight-bold text-uppercase text-muted mb-1">To (End Time)</label>
                <select class="form-select form-select-sm text-xs font-weight-bold text-dark" name="entries[${rowIdx}][end_time]" required>
                    ${renderTimeOptions(endTime, date)}
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label text-xxs font-weight-bold text-uppercase text-muted mb-1">Pax Count</label>
                <input type="number" class="form-control form-control-sm text-xs font-weight-bold text-dark" name="entries[${rowIdx}][pax]" value="${pax}" min="1" max="200" required>
            </div>
            <div class="col-md-1 text-center pt-3">
                <button type="button" class="btn btn-sm btn-link text-danger p-0 m-0 btn-remove-row" title="Remove Slot">
                    <i class="fa-solid fa-trash-can fa-lg"></i>
                </button>
            </div>
            <div class="col-12 row-full-warning ${isFull ? '' : 'd-none'}">
                <div class="alert alert-danger py-1 px-2 mb-0 text-xxs font-weight-bold text-white">
                    <i class="fa-solid fa-ban me-1"></i>This slot is fully booked. Modification is not allowed for this date and time.
                </div>
            </div>
        `;

        const dateInput = rowDiv.querySelector('.schedule-date-input');
        const startSelect = rowDiv.querySelector('.schedule-start-select');
        const warningDiv = rowDiv.querySelector('.row-full-warning');

        function checkRowFullness() {
            const currentFull = isSlotFull(dateInput.value, startSelect.value);
            if (currentFull) {
                rowDiv.classList.add('bg-light', 'border-danger');
                rowDiv.classList.remove('bg-white');
                warningDiv.classList.remove('d-none');
            } else {
                rowDiv.classList.remove('bg-light', 'border-danger');
                rowDiv.classList.add('bg-white');
                warningDiv.classList.add('d-none');
            }
        }

        dateInput.addEventListener('change', checkRowFullness);
        startSelect.addEventListener('change', checkRowFullness);

        rowDiv.querySelector('.btn-remove-row').addEventListener('click', function() {
            if (container.children.length > 1) {
                rowDiv.remove();
            } else {
                alert('A VIP Group must have at least one schedule slot.');
            }
        });

        container.appendChild(rowDiv);
    }

    function addPublicScheduleRow(startTime = '11:00', endTime = '12:00', pax = 6) {
        const rowIdx = publicContainer.children.length;
        const rowDiv = document.createElement('div');
        rowDiv.className = 'row g-2 align-items-center mb-2 schedule-row p-2 rounded border bg-white';
        rowDiv.innerHTML = `
            <div class="col-md-4">
                <label class="form-label text-xxs font-weight-bold text-uppercase text-muted mb-1">From (Start Time)</label>
                <select class="form-select form-select-sm text-xs font-weight-bold text-dark" name="entries[${rowIdx}][start_time]" required>
                    ${renderTimeOptions(startTime)}
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label text-xxs font-weight-bold text-uppercase text-muted mb-1">To (End Time)</label>
                <select class="form-select form-select-sm text-xs font-weight-bold text-dark" name="entries[${rowIdx}][end_time]" required>
                    ${renderTimeOptions(endTime)}
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label text-xxs font-weight-bold text-uppercase text-muted mb-1">Pax Capacity</label>
                <input type="number" class="form-control form-control-sm text-xs font-weight-bold text-dark" name="entries[${rowIdx}][pax]" value="${pax}" min="1" max="200" required>
            </div>
            <div class="col-md-1 text-center pt-3">
                <button type="button" class="btn btn-sm btn-link text-danger p-0 m-0 btn-remove-public-row" title="Remove Slot">
                    <i class="fa-solid fa-trash-can fa-lg"></i>
                </button>
            </div>
        `;

        rowDiv.querySelector('.btn-remove-public-row').addEventListener('click', function() {
            rowDiv.remove();
        });

        publicContainer.appendChild(rowDiv);
    }

    if (btnAddRow) {
        btnAddRow.addEventListener('click', function() {
            addScheduleRow('2026-10-01', '11:00', '12:00', 6);
        });
    }

    if (btnAddPublicRow) {
        btnAddPublicRow.addEventListener('click', function() {
            addPublicScheduleRow('11:00', '12:00', 6);
        });
    }

    if (btnAddVipGroup) {
        btnAddVipGroup.addEventListener('click', function() {
            document.getElementById('editVipGroupModalLabel').innerHTML = '<i class="fa-solid fa-plus text-warning me-2"></i>Add New VIP Group Preset';
            document.getElementById('vip_modal_original_key').value = '';
            document.getElementById('vip_modal_name').value = '';
            container.innerHTML = '';
            addScheduleRow('2026-10-01', '11:00', '12:00', 6);
            editModal.show();
        });
    }

    document.querySelectorAll('.btn-edit-vip-group').forEach(btn => {
        btn.addEventListener('click', function() {
            const key = this.getAttribute('data-key');
            const name = this.getAttribute('data-name');
            const schedules = JSON.parse(this.getAttribute('data-schedules') || '{}');

            document.getElementById('editVipGroupModalLabel').innerHTML = `<i class="fa-solid fa-pen-to-square text-warning me-2"></i>Modify VIP Group: ${name}`;
            document.getElementById('vip_modal_original_key').value = key;
            document.getElementById('vip_modal_name').value = name;

            container.innerHTML = '';

            let hasSlots = false;
            for (const dateStr in schedules) {
                const slots = schedules[dateStr];
                slots.forEach(s => {
                    hasSlots = true;
                    let endTime = s.end_time;
                    if (!endTime && s.start_time) {
                        const parts = s.start_time.split(':');
                        let h = parseInt(parts[0]) + 1;
                        endTime = (h < 10 ? '0' + h : h) + ':' + (parts[1] || '00');
                    }
                    addScheduleRow(dateStr, s.start_time, endTime || '12:00', s.pax);
                });
            }

            if (!hasSlots) {
                addScheduleRow('2026-10-01', '11:00', '12:00', 6);
            }

            editModal.show();
        });
    });

    const existingConfiguredDates = @json(collect($eventDates)->filter(fn($d) => !empty($d['slots']))->pluck('date')->values());

    function checkDuplicateEventDate(dateVal) {
        const isCreate = document.getElementById('public_modal_is_create').value === '1';
        const dupAlert = document.getElementById('public_modal_duplicate_alert');
        const submitBtn = document.getElementById('public_modal_submit_btn');

        if (isCreate && existingConfiguredDates.includes(dateVal)) {
            let dFmt = dateVal;
            const parts = dateVal.split('-');
            if (parts.length === 3) {
                const dObj = new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, parseInt(parts[2]));
                dFmt = dObj.toLocaleDateString('en-US', { day: 'numeric', month: 'short', year: 'numeric' });
            }
            if (document.getElementById('duplicate_date_text')) {
                document.getElementById('duplicate_date_text').textContent = dFmt;
            }
            if (dupAlert) dupAlert.classList.remove('d-none');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.classList.add('disabled');
            }
        } else {
            if (dupAlert) dupAlert.classList.add('d-none');
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.classList.remove('disabled');
            }
        }
    }

    const btnAddNewEventDate = document.getElementById('btn-add-new-event-date');
    const publicDateInput = document.getElementById('public_modal_date_input');
    const publicDateInputContainer = document.getElementById('public_modal_date_input_container');

    if (btnAddNewEventDate) {
        btnAddNewEventDate.addEventListener('click', function() {
            document.getElementById('editPublicSlotsModalLabel').innerHTML = '<i class="fa-solid fa-calendar-plus text-success me-2"></i>Add New Event Date & Slots';
            if (publicDateInputContainer) publicDateInputContainer.classList.remove('d-none');
            document.getElementById('public_modal_is_create').value = '1';

            const defaultDate = '2026-10-19';
            if (publicDateInput) publicDateInput.value = defaultDate;
            document.getElementById('public_modal_date').value = defaultDate;
            document.getElementById('public_modal_date_display').textContent = '19 Oct 2026';
            document.getElementById('public_modal_is_available').checked = true;

            publicContainer.innerHTML = '';
            addPublicScheduleRow('11:00', '12:00', 6);
            addPublicScheduleRow('12:00', '13:00', 6);

            checkDuplicateEventDate(defaultDate);
            publicModal.show();
        });
    }

    if (publicDateInput) {
        publicDateInput.addEventListener('change', function() {
            const val = this.value;
            document.getElementById('public_modal_date').value = val;
            if (val) {
                const parts = val.split('-');
                if (parts.length === 3) {
                    const dObj = new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, parseInt(parts[2]));
                    const dFmt = dObj.toLocaleDateString('en-US', { day: 'numeric', month: 'short', year: 'numeric' });
                    document.getElementById('public_modal_date_display').textContent = dFmt;
                }
            }
            checkDuplicateEventDate(val);
        });
    }

    document.querySelectorAll('.btn-edit-public-slots').forEach(btn => {
        btn.addEventListener('click', function() {
            const dateStr = this.getAttribute('data-date');
            const displayDate = this.getAttribute('data-display-date');
            const isAvailable = this.getAttribute('data-is-available') === '1';
            const slots = JSON.parse(this.getAttribute('data-slots') || '[]');

            document.getElementById('editPublicSlotsModalLabel').innerHTML = `<i class="fa-solid fa-calendar-pen text-primary me-2"></i>Modify Public Schedule Slots & Pax Capacity`;
            if (publicDateInputContainer) publicDateInputContainer.classList.add('d-none');
            document.getElementById('public_modal_is_create').value = '0';

            document.getElementById('public_modal_date').value = dateStr;
            document.getElementById('public_modal_date_display').textContent = displayDate;
            document.getElementById('public_modal_is_available').checked = isAvailable;

            publicContainer.innerHTML = '';

            if (slots.length > 0) {
                slots.forEach(s => {
                    addPublicScheduleRow(s.start_time, s.end_time, s.capacity);
                });
            } else {
                addPublicScheduleRow('11:00', '12:00', 6);
            }

            checkDuplicateEventDate(dateStr);
            publicModal.show();
        });
    });

    document.querySelectorAll('.btn-delete-vip-group').forEach(btn => {
        btn.addEventListener('click', function() {
            const key = this.getAttribute('data-key');
            const name = this.getAttribute('data-name');
            document.getElementById('delete_modal_key').value = key;
            document.getElementById('delete_modal_name_text').textContent = name;
            deleteModal.show();
        });
    });
});
</script>
@endsection
