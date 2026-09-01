@extends('layouts.admin')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">

<style>
    .fc-header-toolbar {
        padding: 12px 16px;
        background: #f8fafc;
        border-radius: 12px;
        margin-bottom: 1rem !important;
        border: 1px solid #e2e8f0;
    }
    .fc-button-primary {
        background-color: #0f172a !important;
        border-color: #0f172a !important;
        font-weight: 600 !important;
        font-size: 0.82rem !important;
        text-transform: capitalize !important;
        border-radius: 8px !important;
    }
    .fc-button-active {
        background-color: #e86034 !important;
        border-color: #e86034 !important;
    }
    .fc-event {
        cursor: pointer;
        border-radius: 6px;
        font-size: 0.8rem;
        padding: 2px 4px;
        border: none !important;
    }
    .fc-toolbar-title {
        font-size: 1.15rem !important;
        font-weight: 700 !important;
        color: #0f172a;
    }
    .card-dashboard {
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
        background: #ffffff;
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

    <!-- Main Calendar & Control Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-dashboard p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="font-weight-bold text-dark mb-0">
                            <i class="fa-solid fa-calendar-days text-danger me-2"></i>Event Reservations Calendar
                        </h5>
                        <small class="text-muted">Interactive visual schedule of all guest bookings & attendance statuses</small>
                    </div>
                    <div>
                        <a href="{{ route('scanner') }}" class="btn btn-dark btn-sm px-3">
                            <i class="fa-solid fa-qrcode me-1"></i> Open Ticket QR Scanner
                        </a>
                    </div>
                </div>

                <!-- Calendar Mounting Point -->
                <div id="calendar" style="min-height: 580px;"></div>
            </div>
        </div>
    </div>

    <!-- Data Table List of Bookings -->
    <div class="row">
        <div class="col-12">
            <div class="card card-dashboard p-4">
                <h5 class="font-weight-bold text-dark mb-3">All Customer Bookings</h5>
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
                                $isAttended = ($b->status === 'attended' || $b->status === 'completed' || !is_null($b->attended_at));
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
                                    @else
                                        <span class="badge bg-secondary"><i class="fa-solid fa-clock me-1"></i>NOT ATTENDED</span>
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
</div>

<!-- Booking Event Details Modal -->
<div class="modal fade" id="eventDetailModal" tabindex="-1" aria-labelledby="eventDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title font-weight-bold" id="eventDetailModalLabel">Booking Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-3">
                    <span id="modal-ref-badge" class="badge bg-dark fs-6 px-3 py-2"></span>
                </div>
                <div class="bg-light p-3 rounded-3 border mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-xs text-uppercase font-weight-bold text-muted">Customer Name</span>
                        <span id="modal-name" class="font-weight-bold text-dark"></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-xs text-uppercase font-weight-bold text-muted">Email</span>
                        <span id="modal-email" class="text-dark text-sm"></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-xs text-uppercase font-weight-bold text-muted">Phone</span>
                        <span id="modal-phone" class="text-dark text-sm"></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-xs text-uppercase font-weight-bold text-muted">Date & Time</span>
                        <span id="modal-datetime" class="font-weight-bold text-primary"></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-xs text-uppercase font-weight-bold text-muted">Venue</span>
                        <span id="modal-venue" class="text-xs text-dark font-weight-bold text-end" style="max-width: 220px;"></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center pt-2 border-top mt-2">
                        <span class="text-xs text-uppercase font-weight-bold text-muted">Status</span>
                        <span id="modal-status-badge"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0 justify-content-between">
                <button type="button" id="modal-btn-delete" class="btn btn-outline-danger btn-sm px-3">
                    <i class="fa-solid fa-trash me-1"></i> Delete
                </button>
                <button type="button" id="modal-btn-toggle" class="btn btn-success btn-sm px-4">
                    Mark as Attended
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

<script>
    let calendar = null;
    let selectedBookingId = null;

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
            },
            eventClick: function (info) {
                const props = info.event.extendedProps;
                selectedBookingId = info.event.id;

                document.getElementById('modal-ref-badge').textContent = props.ref || 'BK-TICKET';
                document.getElementById('modal-name').textContent = props.name;
                document.getElementById('modal-email').textContent = props.email;
                document.getElementById('modal-phone').textContent = props.phone;
                document.getElementById('modal-datetime').textContent = `${props.date} @ ${props.time}`;
                document.getElementById('modal-venue').textContent = props.venue;

                const statusBadge = document.getElementById('modal-status-badge');
                const toggleBtn = document.getElementById('modal-btn-toggle');

                if (props.is_attended) {
                    statusBadge.className = 'badge bg-success';
                    statusBadge.textContent = 'ATTENDED (' + props.attended_at + ')';
                    toggleBtn.className = 'btn btn-outline-secondary btn-sm px-4';
                    toggleBtn.textContent = 'Mark Unattended';
                } else {
                    statusBadge.className = 'badge bg-warning text-dark';
                    statusBadge.textContent = 'CONFIRMED (NOT ATTENDED)';
                    toggleBtn.className = 'btn btn-success btn-sm px-4';
                    toggleBtn.textContent = 'Mark as Attended';
                }

                if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    const modalInstance = bootstrap.Modal.getOrCreateInstance(document.getElementById('eventDetailModal'));
                    modalInstance.show();
                } else if (typeof $ !== 'undefined') {
                    $('#eventDetailModal').modal('show');
                } else {
                    alert(`Booking: ${props.name}\nRef: ${props.ref}\nStatus: ${props.is_attended ? 'ATTENDED' : 'NOT ATTENDED'}`);
                }
            }
        });

        calendar.render();

        document.getElementById('modal-btn-toggle').addEventListener('click', function() {
            if (selectedBookingId) {
                toggleAttendance(selectedBookingId);
            }
        });

        document.getElementById('modal-btn-delete').addEventListener('click', function() {
            if (selectedBookingId) {
                deleteBooking(selectedBookingId);
            }
        });
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
