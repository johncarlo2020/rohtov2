const page = document.getElementById('station-journey');

if (page) {
    const details = page.querySelector('.station-journey-details');
    const camera = page.querySelector('.station-camera');
    const reader = document.getElementById('station-reader');
    const start = document.getElementById('station-start');
    const hint = document.getElementById('station-camera-hint');
    const completed = document.getElementById('station-completed');
    const dialog = page.querySelector('.station-result');
    const done = document.getElementById('station-result-done');
    const scanButton = page.querySelector('.station-scan-button');
    let scanner;
    let starting;
    let busy = false;
    let success = page.dataset.completed === 'true';
    let redirectUrl = null;

    async function stopCamera() {
        if (starting) await starting.catch(() => {});
        if (scanner?.isScanning) await scanner.stop();
    }

    function showResult(message, ok = false) {
        dialog.querySelector('.station-result-icon').textContent = ok ? '✓' : 'i';
        dialog.classList.toggle('is-success', ok);
        document.getElementById('station-result-message').textContent = message;
        dialog.showModal();
        done.focus();
    }

    function showDetails() {
        camera.hidden = true;
        details.hidden = false;
        hint.hidden = true;
        start.hidden = success;
        completed.hidden = !success;
        page.querySelector('.station-journey-instructions').hidden = success;
        page.classList.remove('is-scanning');
    }

    async function onScan(decodedText) {
        if (busy || success) return;
        busy = true;
        try {
            await stopCamera();
            const response = await fetch(page.dataset.scanUrl, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ station: Number(page.dataset.stationId), qrCodeMessage: decodedText }),
            });
            if (!response.ok) {
                showResult([401, 422].includes(response.status) ? 'Invalid QR Code' : 'Unable to check in. Please try again.');
                return;
            }
            const result = await response.json();
            success = true;
            redirectUrl = result.redirect_url;
            hint.hidden = true;
            completed.hidden = false;
            showResult('Check-in Successful', true);
        } catch {
            showResult('Unable to check in. Check your connection and try again.');
        } finally {
            busy = false;
        }
    }

    scanButton.addEventListener('click', async () => {
        if (busy || success) return;
        busy = true;
        details.hidden = true;
        start.hidden = true;
        camera.hidden = false;
        hint.hidden = false;
        page.classList.add('is-scanning');
        try {
            await new Promise(resolve => requestAnimationFrame(resolve));
            scanner = new window.Html5Qrcode('station-reader');
            starting = scanner.start({ facingMode: 'environment' }, {
                fps: 10,
                qrbox: (width, height) => {
                    const size = Math.floor(Math.min(width, height) * 0.7);
                    return { width: size, height: size };
                },
            }, onScan, () => {});
            await starting;
        } catch {
            showResult('Camera unavailable. Allow camera access and try again.');
        } finally {
            starting = null;
            busy = false;
        }
    });

    function finishResult() {
        dialog.close();
        if (success && redirectUrl) {
            window.location.assign(redirectUrl);
            return;
        }
        showDetails();
        (success ? completed.querySelector('a') : scanButton).focus();
    }
    done.addEventListener('click', finishResult);
    dialog.addEventListener('cancel', event => {
        event.preventDefault();
        finishResult();
    });
    page.querySelector('.station-back').addEventListener('click', async event => {
        if (!page.classList.contains('is-scanning')) return;
        event.preventDefault();
        await stopCamera().catch(() => {});
        showDetails();
        scanButton.focus();
    });
    window.addEventListener('pagehide', () => { stopCamera().catch(() => {}); });
}
