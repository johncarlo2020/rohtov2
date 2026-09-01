@extends('layouts.admin')

@section('content')
<style>
    .scanner-card {
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
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
    .badge-status {
        font-size: 0.85rem;
        padding: 6px 14px;
        border-radius: 20px;
    }
</style>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card scanner-card p-4 text-center">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="font-weight-bold mb-0 text-dark">
                        <i class="fa-solid fa-qrcode text-danger me-2"></i>Event Ticket QR Scanner
                    </h5>
                    <span class="badge bg-danger">LIVE VERIFICATION</span>
                </div>
                <p class="text-muted text-sm mb-4">
                    Point camera at customer's reservation ticket QR code or enter booking reference manually to mark attendance.
                </p>

                <!-- Camera Container -->
                <div id="reader" class="mb-4"></div>
            </div>
        </div>
    </div>
</div>

<!-- Scan Response Modal -->
<div class="modal fade" id="responseModal" tabindex="-1" aria-labelledby="responseModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title font-weight-bold" id="responseModalLabel">Verification Result</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <h4 id="responseStatusTitle" class="font-weight-bold mb-2"></h4>
                <p id="responseMessage" class="text-muted mb-4"></p>

                <!-- Booking Details Container -->
                <div id="booking-details-box" class="d-none text-start p-3 bg-light rounded-3 border">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-xs text-uppercase font-weight-bold text-muted">Customer Name</span>
                        <span id="detail-name" class="font-weight-bold text-dark"></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-xs text-uppercase font-weight-bold text-muted">Reference No</span>
                        <span id="detail-ref" class="badge bg-dark"></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-xs text-uppercase font-weight-bold text-muted">Date & Time</span>
                        <span id="detail-datetime" class="font-weight-bold text-primary"></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-xs text-uppercase font-weight-bold text-muted">Venue</span>
                        <span id="detail-venue" class="text-xs text-end font-weight-bold text-dark" style="max-width: 220px;"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0 justify-content-center">
                <button type="button" class="btn btn-primary px-5 py-2" data-bs-dismiss="modal" id="closeResponseModal">
                    Scan Next Ticket
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
    let html5QrCode = null;
    let isScanning = false;

    document.addEventListener("DOMContentLoaded", function () {
        startScanner();

        document.getElementById('btn-manual-verify').addEventListener('click', function() {
            const manualCode = document.getElementById('manual-code').value.trim();
            if (manualCode) {
                sendVerification(manualCode);
            }
        });

        document.getElementById('manual-code').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const manualCode = this.value.trim();
                if (manualCode) {
                    sendVerification(manualCode);
                }
            }
        });

        document.getElementById('closeResponseModal').addEventListener('click', function() {
            const modalElem = document.getElementById('responseModal');
            if (typeof $ !== 'undefined' && typeof $.fn.modal === 'function') {
                $(modalElem).modal('hide');
            } else {
                modalElem.classList.remove('show', 'd-block');
                modalElem.style.display = 'none';
            }
            if (!isScanning) {
                startScanner();
            }
        });

        const modalElem = document.getElementById('responseModal');
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
                    sendVerification(qrCodeMessage);
                }).catch(err => {
                    sendVerification(qrCodeMessage);
                });
            },
            (errorMessage) => {
                // ignore scanning frame errors
            }
        ).then(() => {
            isScanning = true;
        }).catch(err => {
            console.warn('Camera initiation failed:', err);
        });
    }

    function sendVerification(code) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch('{{ route("workshop.scan") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ qrCodeMessage: code })
        })
        .then(res => res.json())
        .then(data => {
            const modalIcon = document.getElementById('modal-icon');
            const titleElem = document.getElementById('responseStatusTitle');
            const msgElem = document.getElementById('responseMessage');
            const detailsBox = document.getElementById('booking-details-box');

            if (data.status === 'success') {
                titleElem.textContent = 'ATTENDANCE VERIFIED';
                titleElem.className = 'font-weight-bold mb-2 text-success';
                msgElem.textContent = data.message;
            } else if (data.status === 'already_redeemed') {
                titleElem.textContent = 'ALREADY ATTENDED';
                titleElem.className = 'font-weight-bold mb-2 text-warning';
                msgElem.textContent = data.message;
            } else {
                titleElem.textContent = 'INVALID TICKET';
                titleElem.className = 'font-weight-bold mb-2 text-danger';
                msgElem.textContent = data.message || 'Invalid QR code or booking not found.';
            }

            if (data.booking) {
                detailsBox.classList.remove('d-none');
                document.getElementById('detail-name').textContent = data.booking.name || 'N/A';
                document.getElementById('detail-ref').textContent = data.booking.ref || code;
                document.getElementById('detail-datetime').textContent = `${data.booking.date} @ ${data.booking.time}`;
                document.getElementById('detail-venue').textContent = data.booking.venue || 'LONGCHAMP POP UP STORE';
            } else {
                detailsBox.classList.add('d-none');
            }

            showModal('responseModal');
        })
        .catch(err => {
            console.error('Scan Error:', err);
            document.getElementById('responseStatusTitle').textContent = 'ERROR';
            document.getElementById('responseStatusTitle').className = 'font-weight-bold mb-2 text-danger';
            document.getElementById('responseMessage').textContent = 'An error occurred while connecting to the server.';
            document.getElementById('booking-details-box').classList.add('d-none');
            showModal('responseModal');
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
