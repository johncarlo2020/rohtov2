@props(['show' => false])

<div x-data="{
    showModal: {{ $show ? 'true' : 'false' }},
    underGuidance: false,
    init() {
        if (sessionStorage.getItem('rwg_age_verified') === '1' && !{{ $show ? 'true' : 'false' }}) {
            this.showModal = false;
        }
    },
    confirmGuidance() {
        if (this.underGuidance) {
            this.showModal = false;
            sessionStorage.setItem('rwg_age_verified', '1');
            $dispatch('age-verified');
        }
    }
}"
@show-age-modal.window="showModal = true"
x-show="showModal"
x-cloak
class="rwg-age-modal-backdrop"
style="position: fixed; inset: 0; background: rgba(10, 5, 20, 0.75); backdrop-filter: blur(6px); z-index: 10500; display: flex; align-items: center; justify-content: center; padding: 1.25rem;">
    
    <div class="rwg-age-modal-card animate-entry"
         @click.outside="if (underGuidance) showModal = false"
         style="background: #ffffff; border-radius: 24px; padding: 2rem 1.5rem; max-width: 320px; width: 100%; box-shadow: 0 15px 35px rgba(0, 0, 0, 0.45); text-align: center;">

        <!-- Red Exclamation Circle -->
        <div class="d-flex align-items-center justify-content-center mx-auto mb-3"
             style="width: 52px; height: 52px; border-radius: 50%; border: 2.5px solid #E03546; color: #E03546; font-size: 1.85rem; font-weight: 700; line-height: 1;">
            !
        </div>

        <!-- Title -->
        <h5 class="fw-bold mb-2 text-dark" style="font-size: 1.05rem; letter-spacing: -0.2px;">
            Age Verification:
        </h5>

        <!-- Description -->
        <p class="text-secondary small mb-3" style="font-size: 0.85rem; line-height: 1.45; color: #4A4A4A !important;">
            As you are under 18, please ensure you are accessing this website under the supervision of a parent or legal guardian.
        </p>

        <!-- Checkbox -->
        <div class="d-flex align-items-center justify-content-center gap-2 mb-3">
            <input type="checkbox" id="modalUnderGuidance" class="rwg-checkbox" x-model="underGuidance" />
            <label for="modalUnderGuidance" class="text-dark small fw-semibold cursor-pointer m-0" style="font-size: 0.88rem;">
                I am under guidance
            </label>
        </div>

        <!-- Confirm Button -->
        <div>
            <button type="button"
                    class="rwg-btn-gold py-2"
                    :disabled="!underGuidance"
                    :class="{ 'opacity-50': !underGuidance }"
                    @click="confirmGuidance()">
                CONFIRM
            </button>
        </div>
    </div>
</div>
