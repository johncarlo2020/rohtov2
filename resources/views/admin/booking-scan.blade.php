@extends('layouts.admin')
@section('content')
<div class="container py-4" style="max-width:700px;">
    <div class="card p-4">
        <h1 class="h4">Scan booking QR</h1>
        <p>Scan the booking reference to mark the guest as attended.</p>
        <div id="booking-reader"></div>
        <form id="scan-form" class="mt-4">
            <label for="booking-reference">Booking reference</label>
            <div class="d-flex gap-2">
                <input id="booking-reference" class="form-control" required maxlength="255" placeholder="RSVP-…" autocomplete="off">
                <button class="btn btn-dark mb-0" id="scan-submit">Mark attended</button>
            </div>
        </form>
        <div id="scan-result" class="mt-3" role="status" aria-live="polite"></div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
<script>
(() => {
    const result = document.getElementById('scan-result');
    const input = document.getElementById('booking-reference');
    const button = document.getElementById('scan-submit');
    let busy = false;
    let lastReference = '';
    let lastScan = 0;
    async function attend(reference) {
        reference = reference.trim();
        if (!reference || busy) return;
        busy = true;
        button.disabled = true;
        result.textContent = 'Recording attendance…';
        try {
            const response = await fetch(@json(route('admin.scanner.scan')), {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': @json(csrf_token()) },
                body: JSON.stringify({ reference_no: reference })
            });
            const data = await response.json();
            result.className = 'mt-3 alert ' + (response.ok ? 'alert-success' : 'alert-danger');
            result.textContent = response.ok ? `${data.message} ${data.name} (${data.reference})` : (data.message || 'Unable to record attendance.');
        } catch (error) {
            result.className = 'mt-3 alert alert-danger';
            result.textContent = 'Unable to record attendance. Check your connection and try again.';
        } finally {
            busy = false;
            button.disabled = false;
        }
    }
    document.getElementById('scan-form').addEventListener('submit', event => {
        event.preventDefault();
        attend(input.value);
    });
    const scanner = new Html5QrcodeScanner('booking-reader', { fps: 10, qrbox: { width: 250, height: 250 } }, false);
    scanner.render(reference => {
        if (busy || (reference === lastReference && Date.now() - lastScan < 5000)) return;
        lastReference = reference;
        lastScan = Date.now();
        input.value = reference;
        attend(reference);
    });
})();
</script>
@endsection
