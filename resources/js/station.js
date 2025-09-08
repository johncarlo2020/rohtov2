document.addEventListener('DOMContentLoaded', function () {
    const mainContent = document.getElementById('mainContent');
    const scannerContainer = document.getElementById('scannerContainer');
    const startScannerBtn = document.getElementById('start-scanner');
    const forceQrElement = document.getElementById('forceQr');

    // $('#scanCompleteModal').modal('show'); for testing purposes don't remove

    if (!startScannerBtn) return; // Guard against running on pages without the button

    let count = 0;
    let lastClick = 0;

    // Access config passed from Blade
    const stationConfig = window.stationConfig || {};
    const processQrCodeUrl = stationConfig.urls.process_qr_code;
    const congratsUrl = stationConfig.urls.congrats;
    const stationId = stationConfig.station_id;
    const stationName = stationConfig.station_name;
    const checkImageUrl = stationConfig.assets.check_image;
    const errorImageUrl = stationConfig.assets.error_image;

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
                // console.log(`QR Code no longer in front of camera.`);
            }
        ).catch((err) => {
            console.log(`Unable to start scanning, error: ${err}`);
        });
    });

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
                station: stationId
            },
            success: function (response) {
                const confettiCanvas = document.createElement('canvas');
                // ... (confetti logic from original file)
                $('#badge').attr('src', checkImageUrl);
                $('#scanCompleteModal').modal('show');

                

                const trimmedMessage = message.trim();
                const lastCharacter = trimmedMessage.charAt(trimmedMessage.length - 1);
                console.log(lastCharacter);
                $('.station_id').html(lastCharacter);
                $('.station_name').html(stationName);
                $('#routeBtn').text('Next');

                if (lastCharacter == 5) {
                    document.getElementById('routeBtn').setAttribute('href', congratsUrl);
                }
                else 
                {
                    const stationParsed = parseInt(lastCharacter);
                    const nextStation = stationParsed + 1;
                $('#routeBtn')
                .removeAttr('href') // remove href if it exists
                .attr('onclick', `gotoStation(${nextStation})`);
                    }
            },
            error: function (xhr, status, error) {
                console.error('Error sending QR Code message:', error);
                $('.modal-icon').addClass('d-none');
                $('.station-text').html('Failed');
                $('.message').html('Invalid QR code');
                $('.check').attr('src', errorImageUrl);
                $('#scanCompleteModal').modal('show');
            }
        });
    }

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
