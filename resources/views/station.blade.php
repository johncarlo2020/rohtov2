<x-app-layout>
    <div class="modal fade " id="scanCompleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center content">
                    <div class="image-check">
                        <i class="fa-regular check"></i>
                        </div>
                        <div class="text-content">
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
        <div id="mainContent" class="col-12 mt-3 text-content text-center">
                <div id="{{ $user ? '' : 'forceQr' }}" class="icon-container mt-4">
                    <img class="icon-bg" src="{{ asset('images/icon-bg.svg') }}" alt="Lock Image">
                    <img class="icon" src="{{ asset('images/station3icon.svg') }}" alt="Lock Image">
                </div>
                <h1 class="station-heading mt-4">
                    @if ($station->id == 6)
                        Gift House
                    @else
                        Station {{ $station->id }}
                    @endif
                </h1>
                <h2 class="station-subheading">{{ $station->name }}</h2>
                <img class="mt-5 station-image" src="{{ asset('images/step/step-img-' . $station->id . '.png') }}"
                    alt="Station Image">
                @if ($user != true)
                    <button id="start-scanner" class="camera-btn mx-auto mt-4"><img src="{{ asset('images/camera.svg') }}"
                            alt=""></button>
                    <p class="bottom-text px-4 mt-3">Scan the QR code at the station to proceed</p>
                @else
                    <p class="bottom-text px-4 mt-3">Already Completed</p>
                @endif

        </div>
        <div id="scannerContainer" class="scanner-container d-none">
                <!-- <button id="close" class="camera-btn mx-auto mt-4">x</button> -->
                <div id="reader"></div>
                <div class="mt-3 p-3">
                    <p class="bottom-text px-4 text-center">Find the QR code & Scan to check in the station</p>
                </div>
            </div>
    </div>

        
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

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
                    console.log('QR Code message sent successfully:', response);
                    // Handle success response if needed
                    const trimmedMessage = message.trim();
                    // Get the last character of the QR code message
                    const lastCharacter = trimmedMessage.charAt(trimmedMessage.length - 1);


                    if (lastCharacter != 6) {
                        $('.station_id').html(lastCharacter);
                    } else {
                        // $('.station_id').html('Gift House');
                        $('.station-text').html('Gift House');

                    }
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
