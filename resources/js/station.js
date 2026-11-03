document.addEventListener('DOMContentLoaded', function () {
    const mainContent = document.getElementById('mainContent');
    const scannerContainer = document.getElementById('scannerContainer');
    const startScannerBtn = document.getElementById('start-scanner');
    const forceQrElement = document.getElementById('forceQr');

    // Access config passed from Blade
    const stationConfig = window.stationConfig || {};
    const processQrCodeUrl = stationConfig.urls.process_qr_code;
    const congratsUrl = stationConfig.urls.congrats;
    const dashboardUrl = stationConfig.urls.dashboard;

    const stationId = parseInt(stationConfig.station_id);
    const stationName = stationConfig.station_name;
    const checkImageUrl = stationConfig.assets.check_image;
    const errorImageUrl = stationConfig.assets.error_image;

    if (startScannerBtn) {
        startScannerBtn.addEventListener('click', function (event) {
            event.preventDefault();

            mainContent.classList.add('d-none');
            scannerContainer.classList.remove('d-none');

            const html5QrCode = new Html5Qrcode("reader");

            html5QrCode.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: 200, aspectRatio: 1.0 },
                (qrCodeMessage) => {
                    sendMessage(qrCodeMessage);
                    html5QrCode.stop();
                },
                (errorMessage) => {
                    // Scanning active
                }
            ).catch((err) => {
                console.log(`Unable to start scanning, error: ${err}`);
            });
        });
    }

    function sendMessage(message) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        $.ajax({
            url: processQrCodeUrl,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
            data: {
                qrCodeMessage: message,
                station: stationId,
            },
            success: function (response) {
                $('#badge').attr('src', checkImageUrl);
                $('#scanCompleteModal').modal('show');

                $('.station_id').html(stationId);
                $('.station_name').html(stationName);
                $('.status-text').html('Check-in Successful');

                // Confetti animation
                if (typeof confetti === 'function') {
                    confetti({
                        particleCount: 80,
                        spread: 60,
                        origin: { y: 0.6 }
                    });
                }

                // If station 4 (gift station) or all complete -> auto redirect to thank you page
                const isFinalStation = (stationId === 4 || response.all_completed);
                const nextUrl = isFinalStation ? congratsUrl : dashboardUrl;

                $('#routeBtn').text(isFinalStation ? 'FINISH' : 'BACK');
                $('#routeBtn').attr('href', nextUrl).removeAttr('onclick');

                if (isFinalStation) {
                    setTimeout(function () {
                        window.location.href = congratsUrl;
                    }, 1500);
                }
            },
            error: function (xhr, status, error) {
                console.error('Error sending QR Code message:', error);
                $('.modal-icon').addClass('d-none');
                $('.station_name_container').addClass('d-none');
                $('.message').html('Invalid QR Code');
                $('.check').attr('src', errorImageUrl);
                $('#scanCompleteModal').modal('show');
                $('#routeBtn')
                    .removeAttr('href')
                    .attr('onclick', `gotoStation(${stationId})`)
                    .text('BACK');
            }
        });
    }

    let count = 0;
    let lastClick = 0;
    if (forceQrElement) {
        forceQrElement.addEventListener('click', function () {
            const now = new Date().getTime();
            if (now - lastClick < 500) {
                count++;
                if (count === 3) {
                    $('#manualQR').modal('show');
                    count = 0;
                }
            } else {
                count = 0;
            }
            lastClick = now;
        });
    }
});
