<x-guest-layout>
       <div class="otp-success">
        <div class="justify-content-center w-100 px-3 main-content with-scroll">
            <div class="my-5 col-12 d-flex justify-content-center animate-entry ">
                @include('components.branding')
            </div>
            <div class="card px-4 py-4 rounded animate-entry delay-3">
                <div class="text-center mb-4 px-1">
                    <h2 class="heading text-dark text-center mb-2">Hey,
                      <span class="fw-bold text-dark">{{ auth()->check() && isset(auth()->user()->fname) ? auth()->user()->fname : 'Guest' }}!</span></h2>
                   <p>
                        PLEASE CHOOSE YOUR PREFERRED DATE AND TIME SLOT,<br>
                        KEEPING IN MIND THAT <strong>YOU CAN ONLY RESCHEDULE ONCE,<br>
                        AT LEAST ONE WEEK BEFORE YOUR SLOT.</strong>
                    </p>

                    <p class="text-danger">
                        SUBJECT TO AVAILABILITY*
                    </p>
                </div>
                 <form method="POST" action="#">
                        @csrf

                        {{-- Date --}}
                        <div class="mb-4">
                            <label for="date" class="form-label">
                                DATE AVAILABLE:
                            </label>

                            <input
                                type="date"
                                id="date"
                                name="date"
                                value="{{ old('date') }}"
                                class="form-control input-text @error('date') is-invalid @enderror"
                                required
                            >

                            @error('date')
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Time Slot --}}
                        <div class="mb-2">
                            <label for="time_slot" class="form-label">
                                TIME SLOTS:
                            </label>

                            <select
                                id="time_slot"
                                name="time_slot"
                                class="form-control input-text @error('time_slot') is-invalid @enderror"
                                required
                            >
                                <option value="">SELECT YOUR TIME SLOT</option>

                                <option value="09:00 - 10:00">
                                    09:00 AM - 10:00 AM
                                </option>

                                <option value="10:00 - 11:00">
                                    10:00 AM - 11:00 AM
                                </option>

                                <option value="11:00 - 12:00">
                                    11:00 AM - 12:00 PM
                                </option>

                                <option value="12:00 - 13:00">
                                    12:00 PM - 01:00 PM
                                </option>
                            </select>

                            @error('time_slot')
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Hour session note --}}
                        <small class="text-danger">
                            * 1 HOUR SESSION
                        </small>

                        {{-- Next --}}
                        <div class="mb-0 text-center mt-5">
                            <button
                                type="submit"
                                class="mt-4 custom-btn custom-btn-primary pulse-slow"
                            >
                                NEXT
                            </button>
                        </div>

                        {{-- Terms --}}
                        <div class="text-center mt-2">
                            <a
                                href="{{ url('/terms-and-conditions') }}"
                                class="terms-link"
                            >
                                TERMS & CONDITIONS
                            </a>
                        </div>
                    </form>
                </div>
            </div>
       </div>
</x-guest-layout>
