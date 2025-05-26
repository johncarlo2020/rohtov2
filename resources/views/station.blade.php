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
    <div id="stationPage" class="station-page home content-box main-background d-flex flex-column min-vh-100 pt-5">
        <div class="modal fade" id="scanCompleteModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="text-center content">
                            <i class="fa-regular fa-circle-check text-yellow"></i>
                            <div class="text-content mt-0">
                                <p class="station-text mb-2 text-dark">{{ $station->name }}</p>
                                <p class="message text-dark">
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
        <div class="modal fade" id="scanFailedModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="text-center content">
                            <div class="text-content mt-0">
                                <p class="heading-text text-dark">
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

        <!-- Staff Selection Modal -->
        <div class="modal fade" id="staffSelectionModal" tabindex="-1" role="dialog" aria-labelledby="staffSelectionModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <form id="staffForm">
                            <p class="fw-bold">Kindly have your Beauty Advisor to include their ID</p>
                            <div class="form-group">
                                <select class="form-select" id="floatingSelectStaff" name="staff_id" aria-label="Floating label select example">
                                    <option selected disabled value="">Select advisor ID</option>
                                    @if (isset($stafs))
                                        @foreach ($stafs as $staf)
                                            <option value="{{ $staf->id }}">{{ $staf->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <button type="submit" class="button button-primary w-100" data-dismiss="modal" id="confirmStaffButton" disabled>Confirm</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Selection Modal (New) -->
        <div class="modal fade" id="productSelectionModal" tabindex="-1" role="dialog" aria-labelledby="productSelectionModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <form id="productForm">
                            <p class="fw-bold">Sample Selection</p>
                            <div class="form-group">
                                <select class="form-select" id="floatingSelectProduct" name="product_id" aria-label="Floating label select example">
                                    <option selected disabled value="">Select product</option>
                                    @if (isset($products))
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option> {{-- Assuming product has a 'name' attribute --}}
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <button type="submit" class="button button-primary w-100"  id="confirmProductButton" disabled>Confirm</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <div class="mb-3 branding-container">
            @include('components.branding')
        </div>
        <h2 class="station-subheading mt-2"># {{ $station->id }} {{ $station->name }}</h2>
        <p class="pharagraph-text px-4">{{ $selectedStationDescription }}</p>
        <div id="mainContent" class="p-0">
            <div id="{{ $user ? '' : 'forceQr' }}" class="mt-4 icon-container">
            </div>
            <img class="mt-2 station-image station-img-{{ $station->id }}"
                src="{{ asset('files/station/' . $station->id . '.webp') }}" alt="Station Image">

            {{-- Display Selected Staff --}}
            @if ($station->id == 3 && $selectedStaff !== null)
                <div class="selected-staff p-3 rounded bg-white w-75 mx-auto mt-3">
                      <img class="small-logo mb-2" src="{{ asset('files/main/logo.webp') }}" alt="" />
                      <p class="mb-1 fw-bold">Your Beauty Advisor:</p>
                      <p class="selected-id">{{ $selectedStaff->name }}</p>
                </div>
            @endif

            {{-- Display Selected Product (New) --}}
            {{-- This can be shown for a specific station or globally if a product is selected --}}
            @if ($station->id == 5 && $selectedProduct !== null) {{-- Or add a specific station condition e.g., $station->id == X && ... --}}

                    <div class="selected-product p-3 rounded bg-light w-75 mx-auto mt-3 border">
                        <p class="mb-1 fw-bold">Your Selected Product:</p>
                        @foreach ($selectedProduct as $product)
                        <p class="selected-id">{{ $product->name }}</p>
                @endforeach

                    </div>
            @endif

            @if ($station->id == 6 && $selectedProduct !== null)
                <div class="selected-staff p-3 rounded bg-white w-75 mx-auto  mb-3">
                      <img class="small-logo mb-2" src="{{ asset('files/main/logo.webp') }}" alt="" />
                      <p class="mb-1 fw-bold">Personalised Hair Sample</p>
                      @foreach ($selectedProduct as $product)
                    <p class="selected-id">{{ $product->name }}</p>
                    @endforeach
                </div>
            @endif



            @if ($user != true && $station->id == 3) {{-- For Station 3, trigger staff modal --}}
                <button id="start-scanner"  class="btn btn-info mx-auto mt-2 camera-btn" >
                    <i class="fa-solid fa-camera"></i>
                </button>
            @elseif ($user != true && $station->id == 5) {{-- For Station 5, trigger product modal --}}
                <button id="start-scanner" type="button" class="btn btn-info mx-auto mt-2 camera-btn">
                    <i class="fa-solid fa-camera"></i>
                </button>
            @elseif ($user != true) {{-- For other stations when user is not logged in (and station is not 3 or 5) --}}
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
        @if ($user != true)
            <p class="px-4 mt-4 bottom-text main-color font-medium small-width">Scan the QR code at the station to
                check in</p>
        @else
            <p class="mt-4 bottom-text main-color font-medium">Checked In</p>

            <div class="scanner-button d-flex justify-content-center mb-4">
                <a href="{{ route('map') }}" class="button button-white w-50 text-center">
                    BACK
                </a>
            </div>
        @endif
        <div class="footer-container p-0 mt-auto">
            @include('components.footer')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script> <!-- Ensure Bootstrap JS is included -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.4.0/dist/confetti.browser.min.js"></script>

    <script>
        @if ($user != true && $station -> id == 3 && $selectedStaff == null)
        document.addEventListener('DOMContentLoaded', function () {
                $('#staffSelectionModal').modal('show');
            });
        @endif

        @if ($user != true && $station -> id == 5 && count($selectedProduct) < 1)
                document.addEventListener('DOMContentLoaded', function () {
                    $('#productSelectionModal').modal('show');
                });
        @endif

        const mainContent = document.getElementById('mainContent');
        const scannerContainer = document.getElementById('scannerContainer');
        var message = '';
        var count = 0;
        var lastClick = 0;
        let selectedStaffId = null; // Added to store selected staff ID
        let selectedStaffName = null; // Added to store selected staff name
        let selectedProductId = null; // Added to store selected product ID
        let selectedProductName = null; // Added to store selected product name

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

                    // show the selected staff container with staff id
                    if ({{ $station->id }} == 3) { // Check if it's station 3
                        if (selectedStaffId) { // Check if a staff ID was selected and stored
                            const staffDisplayElement = $('.selected-staff');
                            const staffIdElement = $('.selected-staff .selected-id');
                            // Ensure the elements exist before trying to modify them
                            if (staffDisplayElement.length && staffIdElement.length) {
                                staffIdElement.text(selectedStaffName ? selectedStaffName + ' (ID: ' + selectedStaffId + ')' : 'ID: ' + selectedStaffId);
                                staffDisplayElement.removeClass('d-none');
                                // Also update the static display if present
                                if ($('.selected-staff').length && !$('.selected-staff').hasClass('d-none')){
                                    $('.selected-staff .selected-id').text(selectedStaffName ? selectedStaffName : 'ID: ' + selectedStaffId);
                                }
                            } else {
                                console.warn('.selected-staff or .selected-id element not found for station 3 display.');
                            }
                        } else {
                            // This case might occur if QR scan happens for station 3 without prior staff selection.
                            // A potential flow improvement could be to ensure scanner for station 3 only starts after staff selection.
                            console.warn('Station 3 QR success, but selectedStaffId is not set globally.');
                        }
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

        // Add this script for handling staff form submission
        $(document).ready(function() {
            $('#staffForm').on('submit', function(event) {
                event.preventDefault(); // Prevent default form submission

                var staffIdValue = $('#floatingSelectStaff').val(); // Get selected staff ID
                var staffNameValue = $('#floatingSelectStaff option:selected').text(); // Get selected staff name
                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: '{{ route('saveStaff') }}', // Make sure this route is defined in your web.php
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: {
                        staff_id: staffIdValue // Use the obtained staff ID
                    },
                    success: function(response) {
                        console.log('Staff ID saved successfully:', response);
                        $('#staffSelectionModal').modal('hide');
                        selectedStaffId = staffIdValue; // Store staff ID globally
                        selectedStaffName = staffNameValue; // Store staff name globally
                        // Update static display for staff if it exists
                        if ($('.selected-staff').length) {
                            $('.selected-staff .selected-id').text(selectedStaffName);
                            $('.selected-staff').removeClass('d-none'); // Make it visible if it was hidden
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error saving staff ID:', xhr.responseText);
                        alert('Failed to save Staff ID. Please try again.'); // Or handle error
                    }
                });
            });

            // Add this to handle enabling/disabling the staff confirm button
            $('#floatingSelectStaff').on('change', function() {
                if ($(this).val() && $(this).val() !== "") {
                    $('#confirmStaffButton').prop('disabled', false);
                } else {
                    $('#confirmStaffButton').prop('disabled', true);
                }
            });

            // --- Product Selection Modal Logic (New) ---
            $('#productForm').on('submit', function(event) {
                console.log('Product form submission triggered.'); // New debug line
                event.preventDefault();

                var productIdValue = $('#floatingSelectProduct').val();
                var productNameValue = $('#floatingSelectProduct option:selected').text();
                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: '{{ route("saveProduct") }}', // Ensure this route is defined in web.php
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: {
                        product_id: productIdValue
                    },
                    success: function(response) {
                        location.reload(); // Reload the page to reflect changes
                        console.log('Product ID saved successfully:', response);

                        selectedProductId = productIdValue;
                        selectedProductName = productNameValue;
                        // Update static display for product if it exists
                        if ($('.selected-product').length) {
                            $('.selected-product .selected-id').text(selectedProductName);
                            $('.selected-product').removeClass('d-none'); // Make it visible
                        } else {
                            // If the static display area doesn't exist, you might want to create it dynamically
                            // or ensure it's pre-rendered but hidden.
                            // For now, just log if it's not found.
                        }

                        var $productModal = $('#productSelectionModal');
                        if ($productModal.length === 0) {
                            console.error('#productSelectionModal not found in DOM.');
                            return;
                        }

                        console.log('Attempting to hide #productSelectionModal.');

                        var hiddenEventFired = false;
                        $productModal.one('hidden.bs.modal', function () {
                            hiddenEventFired = true;
                            console.log('#productSelectionModal hidden.bs.modal event fired.');
                            // Bootstrap should handle backdrop and body class, but double check
                            if ($('.modal-backdrop').length) {
                                console.warn('Backdrop still present after hidden.bs.modal, removing.');
                                $('.modal-backdrop').remove(); // Ensure backdrop is removed
                            }
                            if ($('body').hasClass('modal-open')) {
                                console.warn('body still has modal-open class after hidden.bs.modal, removing.');
                                $('body').removeClass('modal-open'); // Ensure body class is removed
                            }
                        });

                        $productModal.modal('hide');

                        setTimeout(function() {
                            if (!hiddenEventFired) {
                                console.warn('#productSelectionModal hidden.bs.modal event did NOT fire. Forcing cleanup.');
                                if ($productModal.hasClass('show') || $productModal.is(':visible')) {
                                    console.log('Modal still visible, applying manual hide steps.');
                                    $productModal.removeClass('show');
                                    $productModal.css('display', 'none');
                                    $productModal.attr('aria-hidden', 'true');
                                }
                                if ($('.modal-backdrop').length) {
                                    console.log('Removing modal-backdrop manually due to timeout.');
                                    $('.modal-backdrop').remove();
                                }
                                if ($('body').hasClass('modal-open')) {
                                    console.log('Removing modal-open from body manually due to timeout.');
                                    $('body').removeClass('modal-open');
                                }
                            } else {
                                console.log('Modal hide process completed via hidden.bs.modal event.');
                            }
                        }, 750); // Wait for animations (Bootstrap default is 300ms) + buffer
                    },
                    error: function(xhr, status, error) {
                        console.error('Error saving product ID:', xhr.responseText);
                        alert('Failed to save Product ID. Please try again.');
                    }
                });
            });

            $('#floatingSelectProduct').on('change', function() {
                console.log('Product selection changed. Value: "' + $(this).val() + '"'); // Debug line
                if ($(this).val() && $(this).val() !== "") {
                    $('#confirmProductButton').prop('disabled', false);
                    console.log('Confirm product button ENabled.'); // Debug line
                } else {
                    $('#confirmProductButton').prop('disabled', true);
                    console.log('Confirm product button DISabled.'); // Debug line
                }
            });
        });


        // document.getElementById('close').addEventListener('click', function(event) {
        //     event.preventDefault();
        //     mainContent.classList.remove('d-none');
        //     scannerContainer.classList.add('d-none');
        //     html5QrCode.stop();
        // });
    </script>
</x-app-layout>
