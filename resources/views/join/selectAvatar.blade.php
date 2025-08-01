<x-guest-layout>
    <style>

         .avatar-wrapper {
            overflow: hidden;
            position: relative;
            width: 140px;
            height: 140px;
            border-radius: 50%;
            border: 3px solid transparent;
            overflow: hidden;
            transition: border 0.3s ease;
            cursor: pointer;
            flex-shrink: 0;
        }

        .avatar {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            display: block;
        }

        .avatar-wrapper.selected {
            border-color: red;
        }

        /* .avatar-wrapper.dimmed {
            opacity: 0.2;
        } */

         .avatar-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0);
            border-radius: 50%;
            transition: background-color 0.3s ease;
        }

        .avatar-wrapper.dimmed .avatar-overlay {
            background-color: rgba(0, 0, 0, 0.8); /* dark overlay */
        }

        .avatar-grid {
            max-height: 440px;
            overflow-y: auto;
        }

        /* Optional scrollbar customization */
        .avatar-grid::-webkit-scrollbar {
            width: 6px;
        }

        .avatar-grid::-webkit-scrollbar-thumb {
            background-color: red;
            border-radius: 3px;
        }

        .avatar-grid::-webkit-scrollbar-track {
            background-color: #ccc;
        }

        .continue-btn {
            background-color: #f26666;
            border: none;
            color: white;
            border-radius: 30px;
            padding: 10px 30px;
            font-weight: bold;
        }

        .continue-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>
    <div class="select-avatar-main main-content with-scroll">
        <div class="d-flex flex-column justify-content-between h-100">
            <div class="col-12 d-flex justify-content-center animate-entry">
                @include('components.branding')
            </div>
                 <div class="mb-4 w-100 animate-entry delay-3 px-2">
                    <img  class="select-avatar-text my-3" src="{{ asset('images/brand/select-avatar-text.webp') }}" alt="Brand Logo" />
                    <div class="avatar-grid d-flex flex-wrap gap-2 justify-content-center pt-2">
                        @for ($i = 1; $i <= 9; $i++)
                            <div class="avatar-wrapper" data-avatar-id="{{ $i }}">
                                <img src="{{ asset('images/avatarCats/02_cat0' . $i . '.webp') }}"
                                    class="avatar"
                                    alt="Cat {{ $i }}"
                                    data-avatar-id="{{ $i }}">
                                <div class="avatar-overlay"></div>
                            </div>
                        @endfor
                    </div>
                </div>

                {{-- Continue Button --}}
                <div class="w-100 animate-entry delay-3 pb-3">
                    <div class="row">
                        <div class="col-12 text-center">
                            <button id="continueBtn" type="button"
                                class="w-50 custom-btn custom-btn-primary"
                                disabled>
                                {{ __('CONTINUE') }}
                            </button>
                        </div>
                    </div>
                </div>
        </div>
        <img class="bottom-img-paw" src="{{ asset('images/brand/bottom-img.webp') }}" alt="">
    </div>
</x-guest-layout>
<script>
    const avatars = document.querySelectorAll('.avatar-wrapper');
    const continueBtn = document.getElementById('continueBtn');
    let selectedAvatarId = null;

    avatars.forEach(avatar => {
        avatar.addEventListener('click', () => {
            // Clear all classes
            avatars.forEach(a => {
                a.classList.remove('selected');
                a.classList.remove('dimmed');
            });

            // Set selected class
            avatar.classList.add('selected');
            selectedAvatarId = avatar.getAttribute('data-avatar-id');

            // Dim others
            avatars.forEach(a => {
                if (!a.classList.contains('selected')) {
                    a.classList.add('dimmed');
                }
            });

            continueBtn.disabled = false;
        });
    });

    continueBtn.addEventListener('click', () => {
        if (selectedAvatarId) {
            window.location.href = "{{ route('avatar.register') }}?avatar=" + selectedAvatarId;
        }
    });
</script>

