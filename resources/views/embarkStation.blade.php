<x-app-layout>
    <div class="content-box main-background d-flex flex-column min-vh-100 px-3">
        <div class="container mb-5">
            <div>
                @include('components.branding')
            </div>
        </div>
        @if ($station->id == 1)
            <div class="success h-100 d-flex flex-column justify-content-center fade-in">
                <div class="congrats-container mt-5 px-4">
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
                        part of our Big Little Things community! Start recycling your beauty empties with us—every small
                        action creates a
                        big impact for the planet.
                    </h1>
                    <p class="heading-text text-center px-5 fw-bold">Thank you!</p>

                </div>
                <div class="button-container mt-auto px-2">
                    <a id="homeButton" href="{{ route('embarckJourney') }}" class="button button-primary w-100">
                        Home
                    </a>
                </div>
            </div>
        @elseif($station->id == 2 || $station->id == 3)
            <div class="photo-container fade-in">
                <div class="info-container bg-white p-3 rounded mb-5">
                    <h1 class="heading-text text-center mb-4  px-4">Task {{ $station->id }}: {{ $station->name }}</h1>
                    <p class="pharagraph-text text-center">{{ $station->description }}</p>
                    <p class="pharagraph-text text-center">Snap and upload a photo of you using a recycle bag while
                        shopping.
                    </p>
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
                            Choose eco-refills for your favorite L'Occitane products and reduce waste with every purchase. <br/> <br/> Purchase any of L’Occitane’s Jumbo or Eco-Refills at the Ocean or Plastic Roadshow to complete this task.
                        </p>
                    </div>
                    <div id="reader" class="qr-container bg-white rounded h-75 mb-4">

                    </div>
                    <p class="pharagrap-text text-center px-2 mb-3">
                        Notify our Beauty Advisor or Cashier at the point of purchase for QR code verification.
                        </p>
                    <div class="button-container mt-auto d-flex justify-content-center">
                        <a id="homeButton" href="{{ route('embarckJourney') }}" class="button button-white w-50 text-center">
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

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
</x-app-layout>


<script>
    document.addEventListener('DOMContentLoaded', function() {

        @if ($station->id == 4)
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
                                success: function (response) {
                                    document.querySelector('.photo-container').classList.add('d-none');
                                    document.querySelector('.success').classList.remove('d-none');
                                },
                                error: function (xhr, status, error) {

                                }
                            });
                        }
        @endif

        @if($station->id == 2 || $station->id == 3)
            const fileUpload = document.getElementById('file-upload');

        fileUpload.addEventListener('change', function (event) {
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();

                reader.onload = function (e) {
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

        fileUpload.addEventListener('change', function (event) {
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();

                reader.onload = function (e) {
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

        submitBtn.addEventListener('click', function (e) {
            e.preventDefault();

            const file = fileUpload.files[0];
            if (!file) {
                alert('Please select an image before submitting.');
                return;
            }

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
