<x-guest-layout>
    <style>
        .login-page h4,
        .login-page label,
        .login-page input,
        .login-page p,
        .login-page a,
        .login-page span {
            font-family: 'Singulier' !important;
        }
    </style>
    <div class="login-page vh-100">
        <div class="main-content main-background with-scroll">
            <div class="col-12 animate-entry mb-4">
                @include('components.branding')
            </div>
            <div class="col-12 animate-entry delay-2 bg-white p-3 mt-4 card-parent" style="margin-bottom:20vh;">
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />
                <form id="form" method="POST" action="{{ route('login') }}" >
                    @csrf
                    <input type="hidden" name="code" value="{{ old('code') }}" id="code"></input>
                    <input type="hidden" name="dialCode" id="dialCode" ></input>
                    <input type="hidden" name="countryIso" id="countryIso">
                    <p class="text-center text-dark">. . . . . . Code</p>
                    <!-- Password -->
                    <x-text-input id="password" class="block w-full mt-1" type="hidden" name="password"
                        value="password" required autocomplete="current-password" />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/intlTelInput.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const urlParams = new URLSearchParams(window.location.search);

            // Extract the 'id' parameter from the URL
            const id = urlParams.get("id");

            // Log the 'id' value or use it as needed
            console.log(id);
            $("#code").val(id);
            $("form").submit();
        });
    </script>
</x-guest-layout>
