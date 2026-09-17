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

        .logo-img {
            width: 100px;
        }
    </style>
    @include('components.back-button')
     <div class="mb-3 branding-container">
            @include('components.branding')
        </div>
    <div id="stationPage" class="d-flex flex-column pt-4 content-box station-page home main-background min-vh-100">
        <div class="modal fade" data-bs-backdrop="static" id="scanCompleteModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                    <div class="modal-body">
                        <div class="text-center content">
                            <i class="text-yellow fa-regular fa-circle-check"></i>
                            <div class="mt-0 text-content">
                                <p class="mb-2 text-dark station-text">{{ $station->name }}</p>
                                <p class="text-dark message">
                                    Check-in Successful
                                </p>
                            </div>
                            <div class="">
                                <a href="{{ route('map') }}" id="routeBtn" class="button button-primary">
                                    Close
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" data-bs-backdrop="static" id="scanFailedModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                    <div class="modal-body">
                        <div class="text-center content">
                            <div class="mt-0 text-content">
                                <p class="text-dark heading-text">
                                    Invalid QR Code
                                </p>
                            </div>
                            <div class="">
                                <a href="{{ route('map') }}" id="routeBtn" class="button button-primary">
                                    Back
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="station-number">
              <p class="station-id">{{ $station->id }}</p>
        </div>
        <h2 class="mt-2 station-subheading">{{ $station->name }}</h2>
        <div id="mainContent" class="p-0">
            <div id="{{ $stationDone ? '' : 'forceQr' }}" class="mt-4 icon-container">
            </div>
            <img class="mt-2 station-image station-img-{{ $station->id }}"
                src="{{ asset('files/station/' . $station->id . '.webp') }}" alt="Station Image">

            <div class="station-not-finish">
                <p class="">Proceed to</p>
                <h2 class="station-subheading">{{ $station->name }}</h2>
                 <p class="">to begin your journey.</p>
            </div>



            {{-- Display Selected Product (New) --}}
            {{-- This can be shown for a specific station or globally if a product is selected --}}
            @if ($station->id == 5 && count($selectedProduct) === 0)
                <div class="p-3 sample-selection-container">
                    <div class="bg-light shadow-sm p-3 rounded">
                        <form id="productForm">
                            <p class="fw-bold">Sample Selection</p>
                            <div class="form-group">
                                <select class="form-select" id="floatingSelectProduct" name="product_id"
                                    aria-label="Floating label select example">
                                    <option selected disabled value="">Select product</option>
                                    @if (isset($products))
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                            {{-- Assuming product has a 'name' attribute --}}
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <button type="button" class="my-2 w-100 button button-primary"
                                id="confirmProductButton">Confirm</button>
                        </form>
                    </div>
                </div>
            @elseif ($station->id == 5 && count($selectedProduct) > 0)
                <div class="p-3 sample-selection-container">
                    <div class="bg-light shadow-sm p-3 rounded selected-product">
                        <p class="mb-1 fw-bold">Your Selected Product:</p>
                        @foreach ($selectedProduct as $product)
                            <p class="selected-id">{{ $product->name }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($station->id == 6 && $selectedProduct !== null)
                <div class="p-3">
                    <div class="bg-white shadow-sm p-3 rounded sample-selection-container">
                        <img class="mb-2 small-logo" src="{{ asset('files/main/logo.webp') }}" alt="" />
                        <p class="mb-1 fw-bold">Personalised Hair Sample</p>
                        @foreach ($selectedProduct as $product)
                            <p class="selected-id">{{ $product->name }}</p>
                        @endforeach
                    </div>
                </div>
            @endif


        @if ($stationDone != true && $station->id == 5)
            @if (count($selectedProduct) > 0)
                {{-- For Station 5, trigger product modal --}}
                <button id="start-scanner" type="button" class="mx-auto mt-2 btn btn-info camera-btn">
                    <i class="fa-solid fa-camera"></i>
                </button>
            @else
                <button id="start-scanner" type="button" class="mx-auto mt-2 btn btn-info camera-btn d-none">
                    <i class="fa-solid fa-camera"></i>
                </button>
            @endif
        @elseif ($stationDone != true)
            {{-- For other stations when user is not logged in (and station is not 3 or 5) --}}
            <button id="start-scanner" class="mx-auto mt-2 camera-btn">
                <i class="fa-solid fa-camera"></i>
            </button>
        @endif

    </div>



    <div id="scannerContainer" class="scanner-container d-none">
        <!-- <button id="close" class="mx-auto mt-4 camera-btn">x</button> -->
        <div id="reader"></div>
        {{-- <div>
                <a href="{{ route('dashboard') }}" class="button">
                    BACK
                </a>
            </div> --}}
    </div>
    @if ($stationDone != true)
        <p class="bottom-text mt-4 px-4 font-medium main-color small-width">Scan the QR code to proceed</p>
    @else
        <p class="bottom-text mt-4 font-medium main-color">Checked In</p>

        <div class="d-flex justify-content-center mb-4 scanner-button">
            <a href="{{ route('map') }}" class="w-50 text-center button button-white">
                BACK
            </a>
        </div>
    @endif
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
        let selectedProductId = null; // Added to store selected product ID
        let selectedProductName = null; // Added to store selected product name

        document.getElementById('start-scanner').addEventListener('click', function(event) {
            event.preventDefault();


            console.log('Scanner started');

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


                    if (lastCharacter == 6) {
                        document.getElementById('routeBtn').setAttribute('href', '{{ route('station', $station) }}');
                    }

                },
                error: function(xhr, status, error) {
                    $('#scanFailedModal').modal('show');
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

        $(document).ready(function() {
            // --- Product Selection Modal Logic (New) ---
            $('#confirmProductButton').on('click', function() {
                console.log('Product form submission triggered.'); // New debug line
                var productIdValue = $('#floatingSelectProduct').val();
                var productNameValue = $('#floatingSelectProduct option:selected').text();
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                console.log('Selected Product ID:', productIdValue); // Debug line

                $.ajax({
                    url: '{{ route('saveProduct') }}', // Ensure this route is defined in web.php
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: {
                        product_id: productIdValue
                    },
                    success: function(response) {
                        // location.reload(); // Reload the page to reflect changes
                        console.log('Product ID saved successfully:', response);
                        location.reload(); // Reload the page to reflect changes

                    },
                    error: function(xhr, status, error) {
                        console.error('Error saving product ID:', xhr.responseText);
                        alert('Failed to save Product ID. Please try again.');
                    }
                });
            });

            // $('#floatingSelectProduct').on('change', function() {
            //     console.log('Product selection changed. Value: "' + $(this).val() + '"'); // Debug line
            //     if ($(this).val() && $(this).val() !== "") {
            //         $('#confirmProductButton').prop('disabled', false);
            //         console.log('Confirm product button ENabled.'); // Debug line
            //     } else {
            //         $('#confirmProductButton').prop('disabled', true);
            //         console.log('Confirm product button DISabled.'); // Debug line
            //     }
            // });
        });


        // document.getElementById('close').addEventListener('click', function(event) {
        //     event.preventDefault();
        //     mainContent.classList.remove('d-none');
        //     scannerContainer.classList.add('d-none');
        //     html5QrCode.stop();
        // });
    </script>
</x-app-layout>
