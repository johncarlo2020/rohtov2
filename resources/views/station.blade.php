<x-app-layout>
    <style>
        .icon-badge {
            width: 150px;
            height: auto;
            margin-bottom: 25px;
        }

        .iconNew {
            width: 60px;
        }
    </style>
    <div class="modal fade " id="scanCompleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center content">
                        <div class="image-check">
                            <i class="fa-regular check"></i>
                        </div>
                        <div class="text-content">
                            <img class="icon-badge" id="badge" src="">
                            <img class="check" id="badge" src="">

                            <p class="station-text">Station <span class="station_id"></span></p>
                            <p class="message">
                                Check-in Successful
                            </p>
                        </div>
                        <div class="">
                            <a href="{{ route('dashboard') }}" class="button">
                                Close
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="stationPage" class="station-page home ">
        <div class="mb-3 branding-container">
            @include('components.branding')
        </div>
        <div id="mainContent" class="mt-3 text-center col-12 text-content">
            <div id="{{ $user ? '' : 'forceQr' }}" class="mt-4 icon-container">
            </div>
            @if ($station->id == 3)
                <button class="mx-auto mt-4 logo-btn"><img src="{{ asset('images/brand5.png') }}"
                        alt=""></button>
            @elseif($station->id == 4)
                <button class="mx-auto mt-4 logo-btn"><img src="{{ asset('images/brand1.png') }}"
                        alt=""></button>
            @elseif($station->id == 6)
                <button class="mx-auto mt-4 logo-btn"><img src="{{ asset('images/brand2.png') }}"
                        alt=""></button>
            @elseif($station->id == 7)
                <button class="mx-auto mt-4 logo-btn"><img src="{{ asset('images/brand4.png') }}"
                        alt=""></button>
            @elseif($station->id == 8)
                <button class="mx-auto mt-4 logo-btn"><img src="{{ asset('images/brand7.png') }}"
                        alt=""></button>
            @endif
            <h1 class="mt-4 station-heading">
                Station {{ $station->id }}
            </h1>
            <h2 class="station-subheading">{{ $station->name }}</h2>
            <img class="mt-5 station-image" src="{{ asset('images/n_S' . $station->id . '.png') }}" alt="Station Image">
            @if ($user != true)
                @if ($user == false && $station->id == 1)
                    <div class="scanner-button">
                        <a href="{{ route('station.extension', ['station' => $station->id]) }}" class="button">
                            BEGIN
                        </a>
                    @elseif($user == false && $station->id == 2)
                        <div class="scanner-button">
                            <a href="{{ route('station.brand', ['station' => $station->id]) }}" class="button">
                                BEGIN
                            </a>
                        </div>
                    @else
                        <button id="start-scanner" class="mx-auto mt-4 camera-btn"><img
                                src="{{ asset('images/camera.svg') }}" alt=""></button>
                        <p class="px-4 mt-3 bottom-text">Scan the QR code at the station to proceed</p>
                @endif
            @else
                <p class="mt-3 bottom-text">Checked-in Succesful</p>

                <div class="scanner-button">

                    <a href="{{ route('dashboard') }}" class="button">
                        BACK
                    </a>
                </div>
            @endif

        </div>
        <div id="scannerContainer" class="scanner-container d-none">
            <!-- <button id="close" class="mx-auto mt-4 camera-btn">x</button> -->
            <div id="reader"></div>
            <div class="p-3 mt-3">
                <p class="px-4 text-center bottom-text">Find the QR code & Scan to check in the station</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script> <!-- Ensure Bootstrap JS is included -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.4.0/dist/confetti.browser.min.js"></script>

    <script>
        const mainContent = document.getElementById('mainContent');
        const scannerContainer = document.getElementById('scannerContainer');
        var message = '';
        var count = 0;
        var lastClick = 0;
        document.getElementById('start-scanner').addEventListener('click', function(event) {
            event.preventDefault();

            mainContent.classList.add('d-none');
            scannerContainer.classList.remove('d-none');

            //get permission to use camera dont start qr scanner until permission is granted

            const html5QrCode = new Html5Qrcode("reader");

            html5QrCode.start({
                        facingMode: "environment"
                    }, {
                        fps: 10,
                        qrbox: 150,
                        aspectRatio: 9 / 16 // Set the aspect ratio to 16:9
                    },
                    qrCodeMessage => {
                        sendMessage(`${qrCodeMessage}`);
                        html5QrCode.stop();

                    },
                    errorMessage => {
                        console.log(`QR Code no longer in front of camera.`);
                    })
                .catch(err => {
                    console.log(`Unable to start scanning, error: ${err}`);
                });

        });

        function sendMessage(message) {
            // Fetch the CSRF token from the meta tag
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            console.log(message);

            $.ajax({
                url: '{{ route('process_qr_code') }}', // Using Laravel's route() helper function
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken, // Include the CSRF token in the headers
                },
                data: {
                    qrCodeMessage: message,
                    station: {{ $station->id }}
                },
                success: function(response) {
                    // Create a new canvas element for confetti
                    const confettiCanvas = document.createElement('canvas');
                    confettiCanvas.style.position = 'fixed';
                    confettiCanvas.style.top = 0;
                    confettiCanvas.style.left = 0;
                    confettiCanvas.style.width = '100%';
                    confettiCanvas.style.height = '100%';
                    confettiCanvas.style.pointerEvents = 'none';
                    confettiCanvas.style.zIndex = 9999;
                    document.body.appendChild(confettiCanvas);

                    // Trigger confetti using the new canvas
                    const myConfetti = confetti.create(confettiCanvas, {
                        resize: true,
                        useWorker: true
                    });

                    myConfetti({
                        particleCount: 100,
                        spread: 70,
                        origin: {
                            y: 0.6
                        }
                    });

                    // Optional: Remove the canvas after a short delay
                    setTimeout(() => {
                        document.body.removeChild(confettiCanvas);
                    }, 5000);
                    console.log('QR Code message sent successfully:', response);
                    // Handle success response if needed
                    const trimmedMessage = message.trim();
                    // Get the last character of the QR code message
                    const lastCharacter = trimmedMessage.charAt(trimmedMessage.length - 1);

                    $('.station_id').html(lastCharacter);
                    const dynamicImage = `{{ asset('images/badge') }}${lastCharacter}.png`;
                    $('#badge').attr('src', dynamicImage);

                    $(scanCompleteModal).modal('show');

                },
                error: function(xhr, status, error) {
                    console.error('Error sending QR Code message:', error);
                    $('.station-text').html('Failed');
                    $('.message').html('Invalid QR code. Please try again.');
                    $('.check').attr('src', '{{ asset('images/error.svg') }}');
                    $(scanCompleteModal).modal('show');
                }
            });
        }

        document.getElementById('btn_manual').addEventListener('click', function() {
            var password = $('#password').val();

            if (password == 8888) {
                sendMessage({{ $station->id }});
                $('#manualQR').modal('hide');
            } else {
                $('#manualQR').modal('hide');
                $('#password').val('');
                alert('wrong password');
            }
            console.log(password);
        });

        document.getElementById('forceQr').addEventListener('click', function() {
            console.log('clicked');
            var now = new Date().getTime();
            if (now - lastClick < 500) {
                count++;
                if (count === 3) {
                    console.log('asdad');
                    $('#manualQR').modal('show');

                    // Use Bootstrap's modal method to show the modal
                    count = 0; // Reset the count after showing the modal
                }
            } else {
                count = 0;
            }
            lastClick = now;
        });


        // document.getElementById('close').addEventListener('click', function(event) {
        //     event.preventDefault();
        //     mainContent.classList.remove('d-none');
        //     scannerContainer.classList.add('d-none');
        //     html5QrCode.stop();
        // });
    </script>
</x-app-layout>
