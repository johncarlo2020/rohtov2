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
    <div class="home content-box main-background d-flex flex-column min-vh-100 pt-5 ">
        <div class="mb-3 branding-container">
            @include('components.branding')
        </div>
        <div class="content p-4">
            <div class="w-100 d-flex justify-content-center">
                <img class="heart-with-hand mx-auto" src="{{ asset('files/main/hand-heart.webp') }}" alt="">
            </div>

            <p class="text-center heading-text">
                Thanks for participating! <br>
                We appreciate your support. However, we've noticed you already registered for and completed the journey
                in a previous event.
            </p>

            <p class="text-center heading-text fw-normal">Feel free to browse our products in the meantime, and we hope
                you have a great day.
            </p>
        </div>

        @if ($is2000 == true)
            <div class="redeem mb-3 mx-auto">
                <img onclick="showDateModal()" src="{{ asset('files/main/Loccitane Gift.webp') }}" alt="" />
            </div>
        @endif

        <div class="footer-container p-0 mt-auto">
            @include('components.footer')
        </div>

        <div class="modal fade" id="date" tabindex="-1" aria-labelledby="dateLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="modal-close" data-bs-dismiss="modal" aria-label="Close"
                            style="background: none; border: none;">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                        <div class="container mb-3">
                            <div>
                                @include('components.branding')
                            </div>
                        </div>
                        <p class="text-center">Flash your QR code at the Redemption Counter upon completing the Ocean
                            or
                            Plastic Roadshow Journey (5 stations) for verification.</p>
                        <div class="qr d-flex justify-content-center"></div>
                        @if ($selectedAppointment && $selectedAppointment->appointment)
                            <p class="text-center mt-4"><span
                                    id="selected-date">{{ $selectedAppointment->appointment->name ?? '' }}</span>,
                                {{ $convertedDate }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/gh/davidshimjs/qrcodejs/qrcode.min.js"></script>
    <script>
        let dateModalInstance;

        function showDateModal() {
            const modalElement = document.getElementById('date');
            if (!modalElement) return;

            const qrDiv = modalElement.querySelector('.qr');
            if (qrDiv) {
                qrDiv.innerHTML = ''; // Clear previous QR code if any

                // Ensure the $user variable (with an 'id' property) is passed to this Blade view from your controller.
                // If this QR code is for the currently authenticated user, you could use Auth::id().
                // Example: const userSpecificUrl = "{{ rtrim(env('APP_URL', 'http://localhost'), '/') }}/user?id={{ Auth::id() }}";
                const userSpecificUrl = "https://oceanorplastic.experienceloccitane.com/user?id={{ $user->id }}";

                new QRCode(qrDiv, {
                    text: userSpecificUrl,
                    width: 128, // You can adjust the size as needed
                    height: 128,
                    correctLevel: QRCode.CorrectLevel.H // Error correction level
                });
            }

            if (!dateModalInstance) { // Initialize if not already done for the date modal
                dateModalInstance = new bootstrap.Modal(modalElement);
            }
            dateModalInstance.show();
        }
    </script>
</x-app-layout>
