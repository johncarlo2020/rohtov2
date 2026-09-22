const mapPage = document.getElementById('map-page');

if (mapPage) {
    const toggle = mapPage.querySelector('.map-view-toggle');
    const locations = mapPage.querySelectorAll('[data-location]');

    function selectLocation(id) {
        locations.forEach(location => {
            const selected = location.dataset.location === id;
            location.classList.toggle('is-selected', selected);
            if (location.tagName === 'BUTTON' && !location.dataset.stationUrl) {
                location.setAttribute('aria-pressed', String(selected));
            }
        });
    }

    locations.forEach(location => {
        location.addEventListener('click', () => {
            if (location.disabled) return;
            if (location.dataset.stationUrl) {
                window.location.assign(location.dataset.stationUrl);
                return;
            }
            selectLocation(location.dataset.location);
        });
        if (location.tagName === 'A') {
            location.addEventListener('focus', () => selectLocation(location.dataset.location));
        }
    });

    function showView(view) {
        mapPage.dataset.view = view;
        sessionStorage.setItem('map-view', view);
        mapPage.querySelectorAll('[data-map-layer]').forEach(layer => {
            layer.hidden = layer.dataset.mapLayer !== view;
        });
        mapPage.querySelectorAll('[data-map-panel]').forEach(panel => {
            panel.hidden = panel.dataset.mapPanel !== view;
        });
        toggle.setAttribute('aria-label', view === 'journey' ? 'Show more rewards' : 'Show map legend');
        selectLocation(null);
    }

    const savedView = sessionStorage.getItem('map-view');
    const returningFromApplication = sessionStorage.getItem('card-apply-show-rewards');
    sessionStorage.removeItem('card-apply-show-rewards');
    showView(returningFromApplication || savedView === 'rewards' ? 'rewards' : 'journey');

    toggle.addEventListener('click', () => {
        showView(mapPage.dataset.view === 'journey' ? 'rewards' : 'journey');
    });

    const legendBooth = mapPage.querySelector('.legend-booth');
    if (legendBooth) {
        legendBooth.addEventListener('click', () => {
            showView(mapPage.dataset.view === 'journey' ? 'rewards' : 'journey');
        });
    }

    mapPage.querySelector('[data-show-rewards]').addEventListener('click', () => {
        showView('rewards');
        const destination = mapPage.querySelector('#card-apply-open') ||
            mapPage.querySelector('[data-map-panel="rewards"] .legend-location:not(:disabled)') || toggle;
        destination.focus({ preventScroll: true });
    });
}

const cardDialog = document.getElementById('card-apply-dialog');
const applyButton = document.getElementById('card-apply-open');

if (cardDialog && applyButton) {
    const message = document.getElementById('card-apply-message');
    const reader = document.getElementById('card-apply-reader');
    const result = document.getElementById('card-apply-result');
    const close = document.getElementById('card-apply-close');
    let scanner;
    let starting;
    let processing = false;
    let activated = false;

    async function stopCamera() {
        if (starting) await starting;
        if (scanner?.isScanning) await scanner.stop();
    }

    function showResult(text, success = false) {
        cardDialog.classList.remove('is-scanning');
        reader.hidden = true;
        result.hidden = false;
        result.textContent = success ? '✓' : 'i';
        result.classList.toggle('is-success', success);
        message.textContent = text;
        close.textContent = 'DONE';
    }

    applyButton.addEventListener('click', async () => {
        processing = false;
        reader.hidden = false;
        result.hidden = true;
        message.textContent = 'Head over to Card Sales Booth to scan QR and continue your journey';
        close.textContent = 'CLOSE';
        cardDialog.classList.add('is-scanning');
        cardDialog.showModal();
        try {
            await new Promise(resolve => requestAnimationFrame(resolve));
            scanner = new window.Html5Qrcode('card-apply-reader');
            starting = scanner.start({ facingMode: 'environment' }, {
                fps: 10,
                qrbox: (width, height) => {
                    const size = Math.floor(Math.min(width, height) * 0.75);
                    return { width: size, height: size };
                },
            }, async decodedText => {
                if (processing || !cardDialog.open) return;
                processing = true;
                close.disabled = true;
                try {
                    await stopCamera();
                    const response = await fetch(cardDialog.dataset.scanUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({ qrCodeMessage: decodedText }),
                    });
                    if (response.ok) {
                        activated = true;
                        showResult('Check-in Successful', true);
                    } else {
                        showResult(response.status === 422 ? 'Invalid QR Code' : 'Unable to check in. Please try again.');
                    }
                } catch {
                    showResult('Unable to check in. Please try again.');
                } finally {
                    close.disabled = false;
                }
            }, () => {});
            await starting;
        } catch {
            showResult('Camera unavailable. Allow camera access and try again.');
        } finally {
            starting = null;
        }
    });

    async function closeDialog() {
        if (close.disabled) return;
        close.disabled = true;
        try {
            await stopCamera();
        } catch {
            // A failed camera start has no active stream to stop.
        } finally {
            cardDialog.close();
            close.disabled = false;
            if (activated) {
                sessionStorage.setItem('card-apply-show-rewards', 'true');
                window.location.reload();
            } else {
                applyButton.focus();
            }
        }
    }

    close.addEventListener('click', closeDialog);
    cardDialog.addEventListener('cancel', event => {
        event.preventDefault();
        closeDialog();
    });
}
