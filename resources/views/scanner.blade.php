@extends('layouts.admin')

@section('content')
<style>
    .scanner-card {
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.06);
        background: #ffffff;
        border: 1px solid #e2e8f0;
    }
    #reader {
        width: 100%;
        max-width: 480px;
        margin: 0 auto;
        border-radius: 12px;
        overflow: hidden;
        background: #0f172a;
    }
    #reader video {
        border-radius: 12px;
        object-fit: cover;
    }
    .status-badge-attended {
        background-color: #10b981 !important;
        color: #ffffff !important;
    }
    .status-badge-pending {
        background-color: #f59e0b !important;
        color: #ffffff !important;
    }
    .status-badge-missed {
        background-color: #ef4444 !important;
        color: #ffffff !important;
    }
</style>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card scanner-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="font-weight-bold mb-0 text-dark">
                        <i class="fa-solid fa-qrcode text-danger me-2"></i>Admin Ticket QR Scanner & Lookup
                    </h5>
                    <span class="badge bg-danger">LIVE VERIFICATION</span>
                </div>
                <p class="text-muted text-sm mb-4">
                    Point camera at customer's ticket QR code or type customer email / booking reference manually to inspect details and mark attendance.
                </p>

                <!-- Search Input Form -->
                <div class="row mb-4 justify-content-center">
                    <div class="col-md-9 col-12">
                        <div class="input-group input-group-lg shadow-sm">
                            <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-envelope text-muted"></i></span>
                            <input type="text" id="manual-input" class="form-control border-start-0" placeholder="Type customer email or reference no..." />
                            <button id="btn-manual-search" class="btn btn-primary px-4 mb-0 font-weight-bold" type="button">
                                <i class="fa-solid fa-magnifying-glass me-1"></i>LOOKUP
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Camera Container -->
                <div id="reader" class="mb-3"></div>
            </div>
        </div>
    </div>
</div>

<!-- Booking Details Verification Pop-up Modal -->
<div class="modal fade" id="verificationModal" tabindex="-1" aria-labelledby="verificationModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title font-weight-bold text-dark" id="verificationModalLabel">
                    <i class="fa-solid fa-address-card text-primary me-2"></i>User Booking Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-start">
                
                <!-- Status Badge Banner -->
                <div class="text-center mb-3">
                    <span id="modal-status-badge" class="badge px-4 py-2 font-weight-bold text-uppercase" style="font-size: 0.9rem;">
                        Checking Status...
                    </span>
                </div>

                <!-- Booking Info Box -->
                <div class="p-3 bg-light rounded-3 border">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-xs text-uppercase font-weight-bold text-muted">Customer Name</span>
                        <span id="modal-customer-name" class="font-weight-bold text-dark"></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-xs text-uppercase font-weight-bold text-muted">Email</span>
                        <span id="modal-customer-email" class="text-xs font-weight-bold text-dark"></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-xs text-uppercase font-weight-bold text-muted">Reference No</span>
                        <span id="modal-reference-no" class="badge bg-dark font-weight-bold"></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-xs text-uppercase font-weight-bold text-muted">Date & Time Slot</span>
                        <span id="modal-datetime" class="font-weight-bold text-primary text-xs"></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-xs text-uppercase font-weight-bold text-muted">Pax</span>
                        <span id="modal-pax" class="font-weight-bold text-dark text-xs">1</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-xs text-uppercase font-weight-bold text-muted">Venue</span>
                        <span id="modal-venue" class="text-xs text-end font-weight-bold text-dark" style="max-width: 210px;"></span>
                    </div>
                </div>

                <div id="modal-alert-msg" class="alert alert-info py-2 px-3 mt-3 mb-0 text-xs font-weight-bold text-center d-none"></div>
            </div>

            <!-- 2 Buttons: Close & Attending Now -->
            <div class="modal-footer border-0 pt-0 pb-3 justify-content-center gap-2">
                <button type="button" class="btn btn-secondary px-4 py-2 font-weight-bold" data-bs-dismiss="modal" id="btn-modal-close">
                    Close
                </button>
                <button type="button" class="btn btn-success px-4 py-2 font-weight-bold shadow-sm" id="btn-modal-attending-now">
                    <i class="fa-solid fa-user-check me-1"></i>Attending Now
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
    let html5QrCode = null;
    let isScanning = false;
    let currentBookingId = null;
    let currentRefNo = null;

    document.addEventListener("DOMContentLoaded", function () {
        startScanner();

        // Search Button click
        document.getElementById('btn-manual-search').addEventListener('click', function() {
            const query = document.getElementById('manual-input').value.trim();
            if (query) {
                lookupBooking(query);
            }
        });

        // Enter key in input
        document.getElementById('manual-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const query = this.value.trim();
                if (query) {
                    lookupBooking(query);
                }
            }
        });

        // Attending Now Button click
        document.getElementById('btn-modal-attending-now').addEventListener('click', function() {
            if (currentBookingId || currentRefNo) {
                markAttendingNow(currentBookingId, currentRefNo);
            }
        });

        // Close button & Modal hide listener to restart scanner
        const modalElem = document.getElementById('verificationModal');
        modalElem.addEventListener('hidden.bs.modal', function () {
            if (!isScanning) {
                startScanner();
            }
        });
    });

    function startScanner() {
        if (isScanning) return;
        
        html5QrCode = new Html5Qrcode("reader");
        html5QrCode.start(
            { facingMode: "environment" },
            { fps: 10, qrbox: 240 },
            (qrCodeMessage) => {
                isScanning = false;
                html5QrCode.stop().then(() => {
                    lookupBooking(qrCodeMessage);
                }).catch(() => {
                    lookupBooking(qrCodeMessage);
                });
            },
            (errorMessage) => {}
        ).then(() => {
            isScanning = true;
        }).catch(err => {
            console.warn('Camera scanner fallback:', err);
        });
    }

    function lookupBooking(queryStr) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch('{{ route("admin.scanner.lookup") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ query: queryStr, qrCodeMessage: queryStr })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success' && data.booking) {
                populateAndShowModal(data.booking);
            } else {
                alert(data.message || 'No booking record found.');
                if (!isScanning) startScanner();
            }
        })
        .catch(err => {
            console.error('Lookup Error:', err);
            alert('Booking record not found or server error.');
            if (!isScanning) startScanner();
        });
    }

    function populateAndShowModal(booking) {
        currentBookingId = booking.id;
        currentRefNo = booking.ref;

        document.getElementById('modal-customer-name').textContent = booking.name || 'N/A';
        document.getElementById('modal-customer-email').textContent = booking.email || 'N/A';
        document.getElementById('modal-reference-no').textContent = booking.ref || 'N/A';
        document.getElementById('modal-datetime').textContent = `${booking.date} @ ${booking.time}`;
        document.getElementById('modal-pax').textContent = booking.pax || 1;
        document.getElementById('modal-venue').textContent = booking.venue || 'LONGCHAMP POP UP STORE';

        const badgeElem = document.getElementById('modal-status-badge');
        const attendingBtn = document.getElementById('btn-modal-attending-now');
        const alertMsg = document.getElementById('modal-alert-msg');
        alertMsg.classList.add('d-none');

        const status = booking.status; // 'Attended', 'Missed', or 'Not Yet Attended'

        if (status === 'Attended') {
            badgeElem.textContent = 'STATUS: ATTENDED';
            badgeElem.className = 'badge px-4 py-2 font-weight-bold text-uppercase status-badge-attended';
            attendingBtn.disabled = true;
            attendingBtn.classList.add('opacity-50');
            alertMsg.textContent = 'Customer verified on ' + (booking.attended_at || 'earlier');
            alertMsg.classList.remove('d-none', 'alert-warning', 'alert-danger');
            alertMsg.classList.add('alert-success');
        } else if (status === 'Missed') {
            badgeElem.textContent = 'STATUS: MISSED';
            badgeElem.className = 'badge px-4 py-2 font-weight-bold text-uppercase status-badge-missed';
            attendingBtn.disabled = false;
            attendingBtn.classList.remove('opacity-50');
            alertMsg.textContent = 'Time slot passed! Click "Attending Now" if customer is present late.';
            alertMsg.classList.remove('d-none', 'alert-success', 'alert-warning');
            alertMsg.classList.add('alert-danger');
        } else {
            badgeElem.textContent = 'STATUS: NOT YET ATTENDED';
            badgeElem.className = 'badge px-4 py-2 font-weight-bold text-uppercase status-badge-pending';
            attendingBtn.disabled = false;
            attendingBtn.classList.remove('opacity-50');
        }

        showModal('verificationModal');
    }

    function markAttendingNow(bookingId, refNo) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const attendingBtn = document.getElementById('btn-modal-attending-now');
        attendingBtn.disabled = true;
        attendingBtn.textContent = 'Updating...';

        fetch('{{ route("admin.scanner.attend") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ booking_id: bookingId, reference_no: refNo })
        })
        .then(res => res.json())
        .then(data => {
            attendingBtn.textContent = 'Attending Now';
            if (data.status === 'success') {
                const badgeElem = document.getElementById('modal-status-badge');
                badgeElem.textContent = 'STATUS: ATTENDED';
                badgeElem.className = 'badge px-4 py-2 font-weight-bold text-uppercase status-badge-attended';
                attendingBtn.disabled = true;
                attendingBtn.classList.add('opacity-50');

                const alertMsg = document.getElementById('modal-alert-msg');
                alertMsg.textContent = data.message;
                alertMsg.classList.remove('d-none', 'alert-warning', 'alert-danger');
                alertMsg.classList.add('alert-success');
            } else {
                alert(data.message || 'Failed to update attendance.');
                attendingBtn.disabled = false;
            }
        })
        .catch(err => {
            console.error('Attendance Error:', err);
            alert('Server error confirming attendance.');
            attendingBtn.disabled = false;
            attendingBtn.textContent = 'Attending Now';
        });
    }

    function showModal(modalId) {
        const modalElem = document.getElementById(modalId);
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            const instance = bootstrap.Modal.getOrCreateInstance(modalElem);
            instance.show();
        } else if (typeof $ !== 'undefined' && typeof $.fn.modal === 'function') {
            $(modalElem).modal('show');
        } else {
            modalElem.classList.add('show', 'd-block');
        }
    }
</script>
@endsection
