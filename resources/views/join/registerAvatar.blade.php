<x-guest-layout>
    <style>
        .cat-loader img {
            animation: bounce 1s infinite ease-in-out;
        }

        .cat-loader img:nth-child(2) { animation-delay: 0.1s; }
        .cat-loader img:nth-child(3) { animation-delay: 0.2s; }
        .cat-loader img:nth-child(4) { animation-delay: 0.3s; }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50%      { transform: translateY(-6px); }
        }
    </style>
    <div class="register-main main-content with-scroll">
        <div class="d-flex flex-column justify-content-between h-100">
            <div class="col-12 d-flex flex-column justify-content-center align-items-center animate-entry" style="z-index: 99;">
                @include('components.branding')
                <div id="loading_container" class="fw-bold text-dark mb-3 d-none mt-5"  style="font-size: 20px;z-index: 999;">LOADING</div>
            </div>

            <div class="mt-4 w-100 animate-entry delay-3">
                <div class="mt-5">
                    @php
                        $avatarId = request()->get('avatar'); // e.g. "3"
                        $avatarPath = asset('images/avatarCats/02_cat0' . $avatarId . '.webp'); // dynamic image
                    @endphp

                    {{-- Selected Avatar --}}
                    <div class="d-flex justify-content-center">
                        <img src="{{ $avatarPath }}" class="rounded-circle" style="width: 160px; height: 160px; object-fit: cover; filter: grayscale(100%);">
                    </div>

                    {{-- Form --}}
                    <div class="mb-0 row">
                        <div class="col-12 text-center">
                            <form method="POST" action="{{ route('register') }}" id="register-avatar-form">
                                @csrf
                                <input type="hidden" name="avatar_id" value="{{ $avatarId }}">

                                <div class="mb-3 mt-4">
                                    <div class="text-start">
                                        <label class="form-label text-danger fw-bold">Your Name</label>
                                    </div>
                                    <input type="text" name="fname" maxlength="10" class="form-control" placeholder="Enter your name" required>
                                    <div class="form-text text-muted">*MAX 10 characters</div>
                                </div>

                                <button type="submit" class="w-100 btn btn-danger rounded-pill">SUBMIT</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <x-footer />
        </div>
    </div>
</x-guest-layout>

{{-- AJAX Script --}}
<!-- <script>
    document.getElementById('register-avatar-form').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);
        const overlay = document.getElementById('loading-overlay');
        const loading_container = document.getElementById('loading_container');

        // Show the custom loader
        overlay.classList.remove('d-none');
        loading_container.classList.remove('d-none');

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) throw response;
            return response.json();
        })
        .then(data => {
            // Redirect on success
            console.log('submit');
            // window.location.href = data.redirect || '/';
        })
        .catch(async error => {
            overlay.classList.add('d-none');
            loading_container.classList.add('d-none');
            alert('Something went wrong. Please try again.');
        });
    });
</script> -->

<script>
$('#register-avatar-form').on('submit', function(e) {
    e.preventDefault();

    const $form = $(this);
    const formData = new FormData(this);
    const loadingContainer = $('#loading_container');

    $.ajax({
        url: $form.attr('action'),
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        },

        // 🔄 Show loader before request is sent
        beforeSend: function() {
            loadingContainer.removeClass('d-none');
        },

        // ✅ Handle successful response
        success: function(data) {
            // go to game.index
            window.location.href = '{{ route('game.index') }}';
        },

        // ❌ Handle error
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
            alert('Something went wrong. Please try again.\n' + xhr.responseText);
        },

        // ✅ Always hide loader (whether success or error)
        complete: function() {
            loadingContainer.addClass('d-none');
        }
    });
});
</script>
