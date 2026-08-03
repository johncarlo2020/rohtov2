<x-app-layout>
    <div class="promotion-main with-scroll">
        <div class="justify-content-center px-3">
            <div class="col-12 d-flex justify-content-center mt-5">
                @include('components.branding')
            </div>

            <div class="col-12 d-flex justify-content-center align-items-center my-3">
                <img class="welcome_img w-50" src="{{ asset('images/dutchlady/DL Station Map (5) Registered.webp') }}"
                    alt="" />
            </div>

            <div class="content congrats-workshop text-center">
                <h1 class="heading">{{ $appointment->workshop->title ?? 'Workshop' }}</h1>
                <div id="qrCode" class="qr mb-3">

                </div>


                <p class="name">{{ $appointment->guardian }}</p>
                <p class="date">{{ \Carbon\Carbon::parse($appointment->appointmentDate->date)->format('jS F Y (l)') }}
                </p>
                <p class="time">{{ $appointment->workshop->title }} <br> {{ $appointment->workshop->time }}</p>
                <p class="count">{{ $appointment->attendee }} person{{ $appointment->attendee > 1 ? 's' : '' }}</p>
            </div>

            <div class="d-flex justify-content-center mt-4">
                <a href="{{ route('dashboard') }}" class="button-dutch button-dutch-primary text-center">
                    Done
                </a>
            </div>
        </div>
    </div>
<script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
<script>
    function generateQRCode(url) {
            var qrCodeContainer = document.getElementById("qrCode");
            qrCodeContainer.innerHTML = ""; // Clear previous QR code
            var qrCode = new QRCode(qrCodeContainer, {
                text: url,
                width: 256,
                height: 256,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H,
            });
        }
        const url = "{{ env('APP_URL') }}user?id={{ auth()->id() }}";

            generateQRCode(url);
</script>

</x-app-layout>
