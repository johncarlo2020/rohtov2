<x-guest-layout>
    <div class="register-main with-scroll">
        <div class="justify-content-center w-100">
            <div class="d-flex justify-content-center mt-3 col-12">
                @include('components.branding')
            </div>
            <div class="mt-3 px-2 w-100">
                <h1 class="mt-5 mb-3 text-center fw-bold heading-dutch">SIGN UP</h1>
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

                        {{-- Preferred Location (multi-select, max 3) --}}
                        <div class="mb-3">
                            <label>Preferred Property Location</label>
                            <div x-data="{
                                open: false,
                                selected: {{ json_encode(old('locations', [])) }},
                                max: 3,
                                options: {{ json_encode($locations) }},
                                toggle(option) {
                                    if (this.selected.includes(option)) {
                                        this.selected = this.selected.filter(i => i !== option);
                                    } else if (this.selected.length < this.max) {
                                        this.selected.push(option);
                                    }
                                },
                                get label() {
                                    return this.selected.length ? this.selected.join(', ') : null;
                                }
                            }" @click.outside="open = false" class="custom-select-wrapper">

                                {{-- Hidden inputs for form submission --}}
                                <template x-for="loc in selected" :key="loc">
                                    <input type="hidden" name="locations[]" :value="loc">
                                </template>

                                <button type="button" @click="open = !open" class="custom-select-trigger"
                                    :class="{ 'active': open }">
                                    <span x-show="!label" class="select-placeholder">Select up to 3 locations</span>
                                    <span x-show="label" class="select-value" x-text="label"></span>
                                    <svg class="chevron" :class="{ 'rotate': open }" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>

                                <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="opacity-100 scale-100"
                                    x-transition:leave-end="opacity-0 scale-95" class="custom-select-dropdown">
                                    <template x-for="option in options" :key="option">
                                        <label class="custom-select-option"
                                            :class="{
                                                'is-selected': selected.includes(option),
                                                'is-disabled': !selected.includes(option) && selected.length >= max
                                            }"
                                            @click.prevent="toggle(option)">
                                            <input type="checkbox" :checked="selected.includes(option)"
                                                :disabled="!selected.includes(option) && selected.length >= max"
                                                tabindex="-1">
                                            <span x-text="option"></span>
                                        </label>
                                    </template>
                                </div>

                                <span class="select-hint" x-text="selected.length + ' / ' + max + ' selected'"></span>
                            </div>
                            @error('locations')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Property Budget --}}
                        <div class="mb-3">
                            <label>Property Budget</label>
                            @php
                                $budgets = [
                                    'RM1 million and above',
                                    'RM700K - RM999K',
                                    'RM500K - RM699K',
                                    'Below RM500K',
                                ];
                            @endphp
                            <div x-data="{
                                open: false,
                                selected: '{{ old('property_budget', '') }}',
                                options: {{ json_encode($budgets) }}
                            }" @click.outside="open = false" class="custom-select-wrapper">

                                <input type="hidden" name="property_budget" :value="selected" required>

                                <button type="button" @click="open = !open" class="custom-select-trigger"
                                    :class="{ 'active': open }">
                                    <span x-show="!selected" class="select-placeholder">Select 1 property budget</span>
                                    <span x-show="selected" class="select-value" x-text="selected"></span>
                                    <svg class="chevron" :class="{ 'rotate': open }" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>

                                <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="opacity-100 scale-100"
                                    x-transition:leave-end="opacity-0 scale-95" class="custom-select-dropdown">
                                    <template x-for="option in options" :key="option">
                                        <div class="custom-select-option"
                                            :class="{ 'is-selected': selected === option }"
                                            @click="selected = option; open = false">
                                            <span x-text="option"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            @error('property_budget')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <hr>

                        {{-- Privacy Policy --}}
                        <div x-data="{ agreed: false }">
                            <div class="mt-4 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="privacy_policy"
                                        value="1" id="privacyPolicy" x-model="agreed" required />
                                    <small class="text-dark form-check-label" for="privacyPolicy">
                                        I have read and agree to the
                                        <a href="https://www.iproperty.com.my/privacy-policy/"
                                            class="text-primary">Terms
                                            and Conditions</a>
                                        and
                                        <a href="https://www.iproperty.com.my/terms-and-conditions/"
                                            class="text-primary">Privacy Policy</a>.
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
