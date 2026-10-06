<x-guest-layout>
    <div class="mm-mobile-container justify-content-between">
        <div>
            {{-- Header --}}
            @include('components.branding')

            {{-- Title --}}
            <div class="mm-page-title mt-2">
                REGISTER
            </div>

            {{-- Registration Form --}}
            <form id="registerForm" method="POST" action="{{ route('register') }}">
                @csrf

                {{-- Full Name --}}
                <div class="mb-4">
                    <label for="name" class="mm-label">FULL NAME</label>
                    <input id="name" type="text" name="fname"
                           class="mm-input @error('fname') is-invalid @enderror"
                           placeholder="Enter your full name"
                           value="{{ old('fname', old('name')) }}" required autocomplete="name">
                    @error('fname')
                        <div class="mm-error-text">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Email Address --}}
                <div class="mb-4">
                    <label for="email" class="mm-label">*EMAIL ADDRESS</label>
                    <input id="email" type="email" name="email"
                           class="mm-input @error('email') is-invalid @enderror"
                           placeholder="Enter your email"
                           value="{{ old('email') }}" required autocomplete="email">

                    @error('email')
                        <div class="mm-error-text">
                            *Looks like you already have an account! Please <a href="{{ route('login') }}" style="color: #D93838; text-decoration: underline; font-weight: 700;">log in</a> to continue.
                        </div>
                    @else
                        <div class="mm-helper-text">
                            *A one-time password (OTP) will be sent to this email address.
                        </div>
                    @enderror
                </div>

                {{-- Terms & Privacy Checkbox --}}
                <div class="mb-4 mt-4">
                    <label class="mm-checkbox-label">
                        <input type="checkbox" name="terms" value="1" required checked>
                        <span>
                            I have read and agree to the <a href="#" onclick="event.preventDefault();">Terms and Conditions</a> and <a href="#" onclick="event.preventDefault();">Privacy Policy</a>.
                        </span>
                    </label>
                    @error('terms')
                        <div class="mm-error-text">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Submit Button --}}
                <div class="mt-4">
                    <button type="submit" class="mm-btn-black">
                        SUBMIT
                    </button>
                </div>
            </form>
        </div>

        {{-- Footer Link --}}
        <div class="mm-link-sub mb-3">
            ALREADY REGISTERED? <a href="{{ route('login') }}">LOGIN HERE</a>
        </div>
    </div>
</x-guest-layout>
