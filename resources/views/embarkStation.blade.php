<x-app-layout>
    <div class="content-box main-background d-flex flex-column min-vh-100 px-3">
        <a href="{{ route('embarckJourney') }}" class="go-home"><i class="fa-solid fa-arrow-left"></i></a>
        <div class="container mb-5">
            <div>
                @include('components.branding')
            </div>
        </div>
        @if ($station->id == 1)
            <div class="success h-100 fade-in">
                {{-- <div class="congrats-container mt-5 px-4">
                    <div class="congrats-icon mb-3">
                        <img src="{{ asset('files/main/congratulations.webp') }}" alt="Congratulations" />
                    </div>
                    <h1 class="heading-text text-center mb-3">
                        You're
                        @if ($status == 'registered')
                            now
                        @else
                            already
                        @endif
                        part of our Big Little Things community!
                    </h1>
                    <p class="sub-heading-text text-center px-5 ">Start recycling your beauty empties with us—every small action creates a big impact for the planet.</p>

                </div> --}}
                <div id="choose" class="card pt-4 pb-2 px-3">
                    <div class="logo-small mb-2">
                        <img class="px-3" src="{{ asset('files/main/station_branding.webp') }}" alt="" />
                    </div>
                    <p class="modal-main-text mb-3 mt-4 text-center text-gray fw-bold">Will you pledge to protect our
                        oceans?</p>
                    <div class="radio-button-choice p-3 mb-3">
                        <div class="form-check form-check">
                            <input class="form-check-input" type="radio" name="pledgeOptions" id="pledgeYes"
                                value="yes">
                            <label class="form-check-label" for="pledgeYes">Yes</label>
                        </div>
                        <div class="form-check form-check">
                            <input class="form-check-input" type="radio" name="pledgeOptions" id="pledgeNo"
                                value="no">
                            <label class="form-check-label" for="pledgeNo">No</label>
                        </div>
                    </div>
                    <div class="">
                        <button id="setPledge" type="submit" class="button button-primary w-100 mb-2">
                            Submit
                        </button>
                    </div>
                </div>
                {{-- <div class="button-container mt-auto px-2">
                    <a id="homeButton" href="{{ route('embarckJourney') }}" class="button button-primary w-100">
                        Home
                    </a>
                </div> --}}
            </div>
        @elseif($station->id == 2 || $station->id == 3)
            <div class="photo-container fade-in">
                <div class="info-container bg-white p-3 rounded mb-5">
                    <h1 class="heading-text text-center mb-4  px-4">Task {{ $station->id }}: {{ $station->name }}</h1>
                    @if ($station->id == 2)
                        <p class="pharagraph-text text-center">Skip single-use plastic bags and bring your own reusable
                            bag when you shop.</p>
                        <p class="pharagraph-text text-center">Snap and upload a photo of you using a recycle bag
                    @endif
                    @if ($station->id == 3)
                        <p class="pharagraph-text text-center">Make a conscious choice to avoid plastic straws and
                            bottles – opt for reusable alternatives.</p>
                        <p class="pharagraph-text text-center">Snap and upload a photo of you using a reusable straw,
                            cup or bottle. </p>
                    @endif

                    <div class="upload">
                        <div class="image-upload-container text-center my-3">
                            <label for="file-upload" class="file-upload-label">
                                <div id="image-preview" class="image-preview">
                                    @php
                                        $imageColumn = 'task_' . $station->id . '_image';
                                    @endphp
                                    @if (auth()->user()->$imageColumn)
                                        <!-- Display the existing image if available -->
                                        <img src="{{ asset('storage/uploads/' . auth()->user()->$imageColumn) }}"
                                            alt="Uploaded Image" class="uploaded-image-preview">
                                    @else
                                        <!-- Show the upload icon and text if no image is uploaded -->
                                        <span class="upload-icon"><i class="fa-solid fa-cloud-arrow-up"></i></span>
                                        <span class="upload-text">Upload your image</span>
                                    @endif
                                </div>
                            </label>

                            <form id="imageUploadForm" enctype="multipart/form-data">
                                @csrf
                                <input type="file" class="form-control visually-hidden" id="file-upload"
                                    name="image" accept="image/*">
                                <input type="hidden" name="task_id" value="{{ $station->id }}">
                            </form>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="button button-primary submit-btn w-100">Submit</button>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-auto px-4 d-flex justify-content-center">

                    <a class="button button-white w-50" href="{{ route('embarckJourney') }}">Back</a>
                </div>
            </div>
            <div class="success h-100 d-flex flex-column justify-content-center d-none fade-in">
                <div class="congrats-container mt-5 px-4">
                    <div class="congrats-icon mb-3">
                        <img src="{{ asset('files/main/congratulations.webp') }}" alt="Congratulations" />
                    </div>
                    <h1 class="heading-text text-center">Your photo has been uploaded successfully.</h1>
                    <p class="pharagrap-text text-center px-5">Thank you for contributing to a greener future—every
                        action helps reduce waste and protect the planet.</p>
                </div>
                <div class="button-container mt-auto">
                    <a id="homeButton" href="{{ route('embarckJourney') }}" class="button button-primary w-100">
                        Home
                    </a>
                </div>
            </div>
        @elseif($station->id == 4)
            <div class="photo-container fade-in ">

                <div class=" h-100 d-flex flex-column justify-content-center">
                    <div class="congrats-container mt-3 px-4">
                        <h1 class="heading-text text-center">Task 4: Switch to Eco-Refills</h1>
                        <p class="pharagrap-text text-center px-2">
                            Choose eco-refills for your favorite L'Occitane products and reduce waste with every
                            purchase. <br /> <br /> Purchase any of L’Occitane’s Jumbo or Eco-Refills at the Ocean or
                            Plastic Roadshow to complete this task.
                        </p>
                    </div>
                    <div id="reader" class="qr-container bg-white rounded h-75 mb-4">

                    </div>
                    <p class="pharagrap-text text-center px-2 mb-3">
                        Notify our Beauty Advisor or Cashier at the point of purchase for QR code verification.
                    </p>
                    <div class="button-container mt-auto d-flex justify-content-center">
                        <a id="homeButton" href="{{ route('embarckJourney') }}"
                            class="button button-white w-50 text-center">
                            Home
                        </a>
                    </div>
                </div>
            </div>
            <div class="success h-100 d-flex flex-column justify-content-center d-none fade-in">
                <div class="congrats-container mt-5 px-4">
                    <div class="congrats-icon mb-3">
                        <img src="{{ asset('files/main/congratulations.webp') }}" alt="Congratulations" />
                    </div>
                    <h1 class="heading-text text-center">Your purchase has been recordedy.</h1>
                    <p class="pharagrap-text text-center px-5">Thank you for contributing to a greener future—every
                        action helps reduce waste and protect the planet.</p>
                </div>
                <div class="button-container mt-auto">
                    <a id="homeButton" href="{{ route('embarckJourney') }}" class="button button-primary w-100">
                        Home
                    </a>
                </div>
            </div>
        @else
            <div class="success h-100 d-flex flex-column justify-content-center fade-in ">
                <div class="congrats-container mt-5 px-4">
                    <div class="congrats-icon mb-3">
                        <img src="{{ asset('files/main/congratulations.webp') }}" alt="Congratulations" />
                    </div>
                    <h1 class="heading-text text-center">Your photo has been uploaded successfully.</h1>
                    <p class="pharagrap-text text-center px-5">Thank you for contributing to a greener future—every
                        action helps
                        reduce waste and protect the planet.</p>
                </div>
                <div class="scanner-container mt-auto">
                    <a id="homeButton" href="{{ route('embarckJourney') }}" class="button button-primary w-100">
                        Home
                    </a>
                </div>
            </div>
        @endif

        <div class="footer-container p-4">
            @include('components.footer')
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <a type="button" class="modal-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fa-solid fa-xmark"></i></a>
                    <div class="info-icon mb-3">
                        <img src="{{ asset('files/main/info.png') }}" alt="" />
                    </div>
                    <p class="modal-main-text mb-4 px-5">Do you want to resubmit you photo?</p>
                    {{-- <p class="warning-text text-center px-5">Note: You may reschedule your selected date
                        <strong>only once</strong>.
                    </p> --}}
                    <div class="">
                        <button id="confirmVisitButton" type="submit" class="button button-primary w-100 mb-2">
                            YES
                        </button>
                        <button id="cancelModalButton" type="button" class="button button-secondary w-100 mb-2"
                            data-bs-dismiss="modal">
                            NO
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="pledge" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <a type="button" class="modal-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fa-solid fa-xmark"></i></a>
                    <div id="selected">
                        <div class="yes d-none">
                            <div class="pledge-answer mb-3">
                                <img class="px-3 pledge-img" id="selected-answer-img"
                                    src="{{ asset('files/main/congratulations.webp') }}" alt="" />
                            </div>
                            <p class="heading-text fw-bold mb-3 text-center text-gray">Thank you for making a conscious
                                choice!</p>
                            <p class="pharagraph-text mb-3 text-center">Enjoy RM5 off any eco-refill
                                or jumbo, redeemable at our
                                Ocean or Plastic Roadshow.</p>
                        </div>
                        <div class="no d-none">
                            <div class="pledge-answer mb-3">
                                <img class="px-3 pledge-img" id="selected-answer-img"
                                    src="{{ asset('files/main/no.webp') }}" alt="" />
                            </div>
                            <p class="pharagraph-text mb-3 text-center">Thank you for your consideration — every step
                                toward awareness
                                helps us all move forward.</p>
                        </div>
                        <button id="close" data-bs-dismiss="modal" type="button"
                            class="button button-primary w-100 mb-2">
                            Close
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const setPledgeButton = document.getElementById('setPledge');
        // disable submit until user selects a pledge option
        setPledgeButton.disabled = true;
        const noContainer = document.querySelector('.no');
        const yesContainer = document.querySelector('.yes');

        setPledgeButton.addEventListener('click', function(event) {
            event.preventDefault();
            showModal();
        });

        function showModal() {
            const selected = document.querySelector('input[name="pledgeOptions"]:checked');
            const selectedAnswerImg = document.getElementById('selected-answer-img');

            if (selected) {
                if (selected.value === 'yes') {
                    noContainer.classList.add('d-none');
                    yesContainer.classList.remove('d-none');
                } else {
                   noContainer.classList.remove('d-none');
                    yesContainer.classList.add('d-none');
                }
            }

            const el = document.getElementById("pledge");
            const bsModal = new bootstrap.Modal(el);
            bsModal.show();
        }

        const pledgeRadios = document.querySelectorAll('input[name="pledgeOptions"]');
        pledgeRadios.forEach(radio => radio.addEventListener('change', function() {
            // enable submit once an option is picked
            setPledgeButton.disabled = false;
        }));

        @if ($station->id == 4)
            @if ($check == null)
                event.preventDefault();
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

                function sendMessage(message) {
                    // Fetch the CSRF token from the meta tag
                    var csrfToken = $('meta[name="csrf-token"]').attr('content');
                    console.log(message);

                    $.ajax({
                        url: '{{ route('receipt') }}', // Using Laravel's route() helper function
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken, // Include the CSRF token in the headers
                        },
                        data: {
                            qrCodeMessage: message,

                        },
                        success: function(response) {
                            document.querySelector('.photo-container').classList.add('d-none');
                            document.querySelector('.success').classList.remove('d-none');
                        },
                        error: function(xhr, status, error) {

                        }
                    });
                }
            @else
                document.querySelector('.photo-container').classList.add('d-none');
                document.querySelector('.success').classList.remove('d-none');
            @endif
        @endif

        @if ($station->id == 2 || $station->id == 3)
            const fileUpload = document.getElementById('file-upload');

            fileUpload.addEventListener('change', function(event) {
                const file = event.target.files[0];

                if (file) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        imagePreview.innerHTML = '';

                        const img = document.createElement('img');
                        img.src = e.target.result;

                        imagePreview.appendChild(img);
                    };

                    reader.readAsDataURL(file);
                } else {
                    imagePreview.innerHTML = `
                        <span class="upload-icon"><i class="fa-solid fa-cloud-arrow-up"></i></span>
                        <span class="upload-text">Upload your image</span>
                    `;
                }
            });
            const imagePreview = document.getElementById('image-preview');
            const submitBtn = document.querySelector('.submit-btn');
            const form = document.getElementById('imageUploadForm');

            fileUpload.addEventListener('change', function(event) {
                const file = event.target.files[0];

                if (file) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        imagePreview.innerHTML = '';

                        const img = document.createElement('img');
                        img.src = e.target.result;

                        imagePreview.appendChild(img);
                    };

                    reader.readAsDataURL(file);
                } else {
                    imagePreview.innerHTML = `
                    <span class="upload-icon"><i class="fa-solid fa-cloud-arrow-up"></i></span>
                    <span class="upload-text">Upload your image</span>
                `;
                }
            });

            submitBtn.addEventListener('click', function(e) {
                e.preventDefault();

                const file = fileUpload.files[0];

                const formData = new FormData(form);
                fetch("{{ route('upload.image') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        },
                        body: formData,
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.querySelector('.photo-container').classList.add('d-none');
                            document.querySelector('.success').classList.remove('d-none');
                        } else {
                            alert('Upload failed.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Something went wrong during upload.');
                    });
            });
        @endif

    });
</script>
