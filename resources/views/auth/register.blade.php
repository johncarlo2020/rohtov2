<x-guest-layout>
    <div class="register-main with-scroll">
        <div class="justify-content-center w-100">
            <div class="d-flex justify-content-center mt-3 col-12">
                @include('components.branding')
            </div>
            <div class="mt-3 px-2 w-100">
                <h1 class="mt-5 mb-3 text-center fw-bold heading-dutch">REGISTRATION</h1>
                <div class="px-4 py-5 pt-1 register-form-parent">
                    <form id="form" method="POST" action="{{ route('register') }}">
                        @csrf

                        {{-- Full Name --}}
                        <div class="mb-3">
                            <label for="fname">Full Name</label>
                            <input id="fname" placeholder="Enter your full name" type="text"
                                class="form-control input-text @error('fname') is-invalid @enderror" name="fname"
                                value="{{ old('fname') }}" required autocomplete="fname" autofocus />
                            @error('fname')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label for="email">Email Address</label>
                            <input id="email" placeholder="example@email.com" type="email"
                                class="form-control input-text @error('email') is-invalid @enderror" name="email"
                                value="{{ old('email') }}" required autocomplete="email" />
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Privacy Policy --}}
                        <div x-data="{ agreed: false }">
                            <div class="mt-4 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privacy_policy"
                                        value="1" id="privacyPolicy" x-model="agreed" required />
                                    <small class="text-dark form-check-label" for="privacyPolicy">
                                        I have read and agree to the
                                        <a href="https://www.iproperty.com.my/privacy-policy/"
                                            class="text-primary">Privacy Policy</a>.
                                        and
                                        <a href="https://www.iproperty.com.my/terms-and-conditions/"
                                            class="text-primary">Terms
                                            and Conditions</a>
                                    </small>
                                </div>
                            </div>

                            <div class="mb-0 text-center">
                                <button id="submitButton" type="submit"
                                    class="mt-4 custom-btn custom-btn-primary pulse-slow" :disabled="!agreed"
                                    :class="{ 'opacity-50': !agreed }">
                                    {{ __('SUBMIT') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="bottom-text">
                    <p class="already-register">Already Registered</p>
                    <p class="already-register">
                        Please Login <a href="{{ route('login') }}">here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
