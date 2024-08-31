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
    <div id="stationPage" class="station-page main main-bg">
        <div class="mb-3 branding-container">
            @include('components.branding')
        </div>
    <div id="mainContent" class="text-center text-content">

        <div class="content">
          <h1 class="station-number">0{{$station->id}}</h1>
          <h2 class="station-name">{{$station->name}}</h2>
          <p class="tag-line">{{$station->description}}</p>
          @if($station->id == 5)
          <p class="tag-line">
            And experience revolutionary Personalised RoboSkin to get tailor-made skin solutions for your specific skin needs.</p>
          @endif
        </div>
        <div class="station-img">
          <img src="{{ asset('images/station' . $station->id . 'main.jpg') }}" alt="">

        </div>

        @if( $user == false && $station->id != 1)
        <div class="scanner-button">
          <button id="scan-btn" class="scan-btn">
              <img src="{{ asset('images/camera.png') }}">
          </button>
          <p>Scan the QR Code at the station to proceed</p>
        </div>
        @else
        <div class="scanner-button">
          <a  href="{{ route('station.extension', ['station' => $station->id]) }}" class="button">
              BEGIN
          </a>
        @endif
      </div>
      <div id="scannerContainer" class="scanner-container d-none">
                <!-- <button id="close" class="mx-auto mt-4 camera-btn">x</button> -->
                <div id="reader"></div>
                <div class="p-3 mt-3">
                    <p class="px-4 text-center bottom-text">Find the QR code & Scan to check in the station</p>
                </div>

                <div class="button" id="btn-back">Back</div>
    </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script>

        const mainContent = document.getElementById('mainContent');
        const scannerContainer = document.getElementById('scannerContainer');
        document.getElementById('btn-back').addEventListener('click', function(event) {
            event.preventDefault();
            mainContent.classList.remove('d-none');
            scannerContainer.classList.add('d-none');
        });

      document.getElementById('scan-btn').addEventListener('click', function(event) {
            event.preventDefault();

            mainContent.classList.add('d-none');
            scannerContainer.classList.remove('d-none');
            const isLandscape = window.innerWidth > window.innerHeight;
            //get permission to use camera dont start qr scanner until permission is granted

            const html5QrCode = new Html5Qrcode("reader");

            html5QrCode.start({
            facingMode: "environment",
        }, {
            fps: 10,
            qrbox: { width: 200, height: 250 },
            aspectRatio: isLandscape ? 3 / 4 : 4 / 3

        },
                    qrCodeMessage => {
                        console.log(`${qrCodeMessage}`);
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
                    $('.station_id').html(lastCharacter);


                    $('.check').addClass('fa-circle-check text-success');

                    $('#scanCompleteModal').modal('show');
                },
                error: function(xhr, status, error) {
                    console.error('Error sending QR Code message:', error);
                    $('.station-text').html('Failed');
                    $('.message').html('Invalid QR code. Please try again.');
                    $('.check').addClass('fa-circle-xmark text-danger');

                    $('#scanCompleteModal').modal('show');


                }
            });
        }
        function isSafari() {
            const userAgent = window.navigator.userAgent;
            const isChrome = userAgent.indexOf('Chrome') > -1;
            const isChromium = userAgent.indexOf('Chromium') > -1;
            const isSafari = userAgent.indexOf('Safari') > -1;

            return isSafari && !isChrome && !isChromium;
        }

        if(isSafari()){
            const scannerContainer = document.getElementById('stationPage');
            scannerContainer.classList.add('safari-padding');
            console.log(isSafari());
        }
    </script>
  </x-app-layout>
