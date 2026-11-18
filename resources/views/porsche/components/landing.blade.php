<section class="porsche-screen porsche-landing-screen active" id="screen-landing" style="background-image: url('{{ asset(config('porsche.campaign.landing_bg')) }}');">
    <div class="porsche-overlay"></div>
    <div class="porsche-content-wrapper porsche-content-landing">
        @include('porsche.components.header')

        <div class="porsche-landing-box">
            <div class="porsche-landing-title-container">
                <img src="{{ asset(config('porsche.campaign.landing_header')) }}" 
                     alt="DESTINATION Cayenne - YOUR NEXT JOURNEY STARTS HERE." 
                     class="porsche-landing-header-img">
            </div>

            <div class="porsche-landing-actions">
                <button type="button" class="porsche-btn porsche-btn-outline" id="btn-begin">
                    Begin
                </button>
            </div>
        </div>
    </div>
</section>
