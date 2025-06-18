<x-guest-layout>
    <div class="content-box main-background px-3 d-flex flex-column min-vh-100">
        <div class="container">
            <div>
                @include('components.branding')
            </div>
        </div>
        <div class="form-container p-4 mt-5 bg-white rounded fade-in">
            <h1 class="heading-text mb-1 text-center">
                LOG IN
            </h1>
            <p class="sub-heading-text-small text-center mb-3">If you've signed up for a previous event or pre-registered, just enter your registered email to log in.</p>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-2 row">
                    <div class="col-12 w-100 phone-number-input">
                        <label class="form-label" for="">Email</label>

                        <input id="email" type="email"
                            class="input-text form-control w-100 @error('email') is-invalid @enderror d-block" placeholder="example@email.com"
                            name="email" value="{{ old('email') }}" required autocomplete="email" autofocus />
                    </div>
                    <div class="mt-2 col-12">
                        <span id="valid-msg" class="d-none text-danger"></span>
                        <span id="error-msg" class="d-none text-danger"></span>
                    </div>
                </div>

                <!-- Password -->
                <x-text-input id="password" class="block w-full mt-1" type="hidden" name="password" value="password"
                    required autocomplete="current-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />

                <div class="flex items-center justify-end">
                    <x-primary-button class="button button-secondary w-100">
                        Submit
                    </x-primary-button>
                </div>
            </form>
        </div>
        <div class="footer-container p-4 mt-auto">
            @include('components.footer')
        </div>
    </div>
</x-guest-layout>

