<x-guest-layout>
    <div class="rwg-page-container position-relative">
        <!-- Top Section: Back Link & Resorts World Genting Logo -->
        <div class="d-flex align-items-center justify-content-between w-100 mb-4 pt-2">
            <a href="{{ route('dashboard') }}" class="text-white text-decoration-none p-2" aria-label="Back to Map">
                <i class="fa-solid fa-chevron-left fs-4"></i>
            </a>
            <div class="flex-grow-1 text-center pe-4">
                @include('components.branding')
            </div>
        </div>

        <!-- Page Title -->
        <h1 class="rwg-title mb-4">MY PROFILE</h1>

        <!-- Profile Form & State -->
        <div class="w-100 flex-grow-1 d-flex flex-column justify-content-between"
             x-data="{
                 isEditing: false,
                 hasExistingGrcId: {{ !empty($user->grc_id) ? 'true' : 'false' }}
             }">

            <form method="POST" action="{{ route('profile.update') }}" class="w-100">
                @csrf
                @method('PATCH')

                <!-- Full Name -->
                <div class="mb-3">
                    <label for="fname" class="rwg-label">Full Name</label>
                    <input type="text"
                           id="fname"
                           name="fname"
                           value="{{ old('fname', $user->fname) }}"
                           :readonly="!isEditing"
                           :class="isEditing ? 'rwg-input-editable' : 'rwg-input-readonly'"
                           required>
                    @error('fname')
                        <div class="text-danger small mt-1 fw-bold">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email Address (Always read-only and greyed out) -->
                <div class="mb-3">
                    <label class="rwg-label">Email Address</label>
                    <input type="email"
                           value="{{ $user->email }}"
                           class="rwg-input-readonly"
                           readonly
                           disabled
                           tabindex="-1">
                </div>

                <!-- Date of Birth (Always read-only and greyed out) -->
                <div class="mb-3">
                    <label class="rwg-label">Date of Birth</label>
                    <div class="position-relative">
                        <input type="text"
                               value="{{ $user->dob ? \Carbon\Carbon::parse($user->dob)->format('d/m/Y') : '' }}"
                               class="rwg-input-readonly pe-5"
                               readonly
                               disabled
                               tabindex="-1">
                        <i class="fa-regular fa-calendar position-absolute top-50 end-0 translate-middle-y me-3 text-secondary fs-5 pointer-events-none"></i>
                    </div>
                </div>

                <!-- GRC Member ID -->
                <div class="mb-4">
                    <label for="grc_id" class="rwg-label">GRC Member ID</label>
                    <input type="text"
                           id="grc_id"
                           name="grc_id"
                           value="{{ old('grc_id', $user->grc_id) }}"
                           placeholder="{{ empty($user->grc_id) ? 'Key-in your GRC Member ID (optional)' : '' }}"
                           :readonly="!isEditing || hasExistingGrcId"
                           :class="(isEditing && !hasExistingGrcId) ? 'rwg-input-editable' : 'rwg-input-readonly'"
                           {{ !empty($user->grc_id) ? 'disabled tabindex=-1' : '' }}>
                    @error('grc_id')
                        <div class="text-danger small mt-1 fw-bold">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Action Button(s) -->
                <div class="mt-4">
                    <!-- View-only state: EDIT PROFILE button -->
                    <template x-if="!isEditing">
                        <button type="button"
                                @click="isEditing = true; $nextTick(() => document.getElementById('fname').focus())"
                                class="rwg-btn-gold">
                            EDIT PROFILE
                        </button>
                    </template>

                    <!-- Edit state: SAVE CHANGES button -->
                    <template x-if="isEditing">
                        <button type="submit" class="rwg-btn-gold">
                            SAVE CHANGES
                        </button>
                    </template>
                </div>
            </form>

            @if(session('status') === 'profile-updated')
                <!-- Success Notification Modal (Account Updated Successfully!) -->
                <div class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center p-3"
                     style="position: fixed; inset: 0; width: 100vw; height: 100vh; display: flex !important; align-items: center; justify-content: center; z-index: 10500; background: rgba(0, 0, 0, 0.72); backdrop-filter: blur(4px);">
                    <div class="bg-white text-dark p-4 rounded-4 text-center shadow-lg w-100"
                         style="max-width: 360px; border-radius: 24px !important; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
                        
                        <!-- Red exclamation circle icon -->
                        <div class="mb-3 d-flex justify-content-center">
                            <div style="width: 58px; height: 58px; border-radius: 50%; border: 3px solid #E03546; display: flex; align-items: center; justify-content: center; color: #E03546; font-size: 2.2rem; font-weight: 700; font-family: sans-serif; line-height: 1;">
                                !
                            </div>
                        </div>

                        <!-- Title -->
                        <h4 class="fw-bold mb-3" style="font-size: 1.15rem; color: #1E120A; letter-spacing: -0.2px;">
                            Account Updated Successfully!
                        </h4>

                        <!-- Message -->
                        <p class="text-secondary small mb-4" style="line-height: 1.5; font-size: 0.88rem; color: #555555 !important;">
                            For your security, you need to sign in again.<br>
                            Please click the verification link we just sent<br>
                            to your email to access your account.
                        </p>

                        <!-- GOT IT Button: logs out and redirects to login -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <input type="hidden" name="redirect" value="{{ route('login') }}">
                            <button type="submit" class="rwg-btn-gold py-2 w-100">
                                GOT IT
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-guest-layout>
