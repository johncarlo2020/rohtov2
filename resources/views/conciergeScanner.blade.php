<x-app-layout>
    <div class="container-fluid start completed-screen main-content main-background with-scroll pt-4">
        <div class="animate-entry">
            @include('components.branding')
        </div>
         <h2 class="mx-4 text-center sub-heading-text animate-entry mt-4" >SCANNER</h2>
        <div class="">
            <div class="mt-4 row justify-content-center">
                <div class="mb-4 col-lg-8 mb-lg-0">
                    <div class="card scanner-container text-center mb-5">
                        <div id="reader">
                        </div>
                    </div>
                    <div class="form-container text-center">
                        <form action="">
                            <label for="email">Key-In Customer Email</label>
                            <input type="text" name="email" id="email" class="form-control mb-2" placeholder="Enter customer email">
                            <button type="submit" class="custom-btn custom-btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Response Message Modal -->
            <div class="modal fade" id="responseModal" tabindex="-1" aria-labelledby="responseModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="responseModalLabel">QR Scan Result</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <span id="responseMessage" class="fs-5"></span>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="closeResponseModal">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



<!-- QuaggaJS Library -->
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
<script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Intercept manual email key-in form submission
        const formElem = document.querySelector('.form-container form');
        if (formElem) {
            formElem.addEventListener('submit', function (e) {
                e.preventDefault();
                const emailInput = document.getElementById('email');
                if (emailInput && emailInput.value.trim()) {
                    sendMessage(emailInput.value.trim());
                }
            });
        }

        // Initialize QuaggaJS / Html5Qrcode
        const html5QrCode = new Html5Qrcode("reader");

        html5QrCode.start({
            facingMode: "environment"
        }, {
            fps: 10,
            qrbox: 200,
            aspectRatio: 2 / 2
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
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: '{{ route('workshop.scan') }}',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
            data: {
                qrCodeMessage: message,
                email: message,
            },
            success: function (response) {
                console.log(response);
                let message = '';

                if (response.message) {
                    message = response.message;
                } else if (response.status === 'success') {
                    message = '✅ Scanned Successfully';
                } else if (response.status === 'already_redeemed') {
                    message = '⚠️ Already Attended';
                } else if (response.status === 'invalid') {
                    message = '❌ Invalid QR';
                } else {
                    message = 'ℹ️ Unknown response';
                }

                $("#responseMessage").text(message);
                $("#responseModal").modal('show');
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
                let errText = '❌ An error occurred while processing.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errText = xhr.responseJSON.message;
                }
                $("#responseMessage").text(errText);
                $("#responseModal").modal('show');
            }
        });
    }

    $("#closeResponseModal").on("click", function () {
        location.reload(); // Refresh the page
    });
</script>
</x-app-layout>
