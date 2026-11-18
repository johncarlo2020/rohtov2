<section class="porsche-screen porsche-instructions-screen" id="screen-instructions" style="background-image: url('{{ asset(config('porsche.campaign.instruction_bg')) }}');">
    <div class="porsche-overlay"></div>
    <div class="porsche-content-wrapper porsche-content-instructions">
        @include('porsche.components.header')

        <div class="porsche-instructions-box">
            <h3 class="porsche-instructions-heading">Instructions:</h3>
            <p class="porsche-instructions-text">Select the answer<br>that best suits you.</p>
            <div class="porsche-instructions-actions">
                <button type="button" class="porsche-btn porsche-btn-outline" id="btn-instructions-next">
                    Next
                </button>
            </div>
        </div>
    </div>
</section>
