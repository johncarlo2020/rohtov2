<x-app-layout>
    <div class="modal fade" id="scanCompleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center content">
                        <img class="check mx-auto mb-4" id="badge" src="">
                        <div class="text-content mt-0">
                            <p class="station-text mb-2 text-dark">Station <span class="station_id"></span></p>
                            <p class="message text-dark">
                                Check-in Successful
                            </p>
                        </div>
                        <div class="">
                            <a href="{{ route('dashboard') }}" id="routeBtn" class="button">
                                okay
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="stationPage" class="station-page home content-box main-background">
        <div class="mb-3 branding-container">
            @include('components.branding')
        </div>
        <div id="mainContent" class="mt-1 mb-2 text-center col-12 text-content d-none">
            <div id="{{ $user ? '' : 'forceQr' }}" class="mt-4 icon-container">
            </div>

            <h1 class=" station-heading mt-2 mb-4">
                {{ $station->name }}
            </h1>
            <div class="stationn-image-container mb-3">
                <img class="station-image" src="{{ asset('files/station/' . $station->id . '.webp') }}" alt="">
            </div>
            @if ($user != true)
                <button id="start-scanner" class="mx-auto mt-2 button button-primary px-4 py-1">
                    <i class="fa-solid fa-camera"></i>
                </button>
                <p class="px-4 mt-4 bottom-text main-color font-medium small-width">Scan the QR code at the station to
                    proceed</p>
            @else
                <p class="mt-2 bottom-text main-color font-medium">Checked-in Succesful</p>
            @endif

            <div class="scanner-button">
                <a href="{{ route('dashboard') }}" class="button button-primary w-50 mx-auto">
                    BACK
                </a>
            </div>

        </div>

        <div id="scannerContainer" class="scanner-container d-none mt-5">
            <!-- <button id="close" class="mx-auto mt-4 camera-btn">x</button> -->
            <div id="reader"></div>
            {{-- <div>
                <a href="{{ route('dashboard') }}" class="button">
                    BACK
                </a>
            </div> --}}
            <p class="px-4 mt-4 bottom-text main-color font-medium small-width text-center">Scan the QR code at the station to
                proceed</p>
        </div>

        <div class="check-in-successful mt-5">
              <div class="check-in-successful-img">
                <img src="{{ asset('files/main/successful_img.webp') }}" alt="">
            </div>
            <div class="main-img">
                <img class="station-image" src="{{ asset('files/congrats/c'. $station->id . '.webp') }}" alt="">
            </div>
            <div class="complete-progress p-3 mx-auto">
                <div class="info-progress d-flex gap-3">
                    <div class="station-progress border-right px-4">
                        <div class="circular-progress-container">
                            <div class="circular-progress" style="--progress-percent: {{ ($station->id / 4) * 100 }}%;">
                                <div class="progress-value-center">
                                    <span class="current-step-display">{{ $station->id }}</span><span class="separator">/</span><span class="total-steps-display">4</span>
                                </div>
                            </div>
                        </div>
                        <div class="progress-label-below">
                            {{ $station->id }}/4 Check-In Completed
                        </div>
                    </div>
                    <div class="info-text px-2 mt-3">
                        <h2 class="mb-0">Well Done!</h2>
                        <h1 class="mb-0">You've just checked in!</h1>
                        <p class="mb-0">Complete all checkpoints to redeem an exclusive gift.</p>
                    </div>
                </div>
                <a href="" class="button button-black w-100 uppercase">back to main journey</a>
            </div>
        </div>

        <div class="footer-container p-4 mt-auto">
            @include('components.footer')
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
                        qrbox: 200,
                        aspectRatio: 2 / 2 // Set the aspect ratio to 16:9
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
                    $('#badge').attr('src', '{{ asset('images/check.png') }}');

                    $('#scanCompleteModal').modal('show');

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


                    if (lastCharacter == 9) {
                        document.getElementById('routeBtn').setAttribute('href', '{{ route('congrats') }}');
                    }

                },
                error: function(xhr, status, error) {
                    console.error('Error sending QR Code message:', error);
                    $('.modal-icon').addClass('d-none');
                    $('.station-text').html('Failed');
                    $('.message').html('Invalid QR code. Please try again.');
                    $('.check').attr('src', '{{ asset('images/error.webp') }}');
                    $('#scanCompleteModal').modal('show');
                }
            });
        }

        // document.getElementById('btn_manual').addEventListener('click', function() {
        //     var password = $('#password').val();

        //     if (password == 8888) {
        //         sendMessage({{ $station->id }});
        //         $('#manualQR').modal('hide');
        //     } else {
        //         $('#manualQR').modal('hide');
        //         $('#password').val('');
        //         alert('wrong password');
        //     }
        //     console.log(password);
        // });

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
