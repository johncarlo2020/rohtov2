<section class="porsche-screen porsche-result-screen" id="screen-result" style="background-image: url('{{ asset(config('porsche.results.A.background_image')) }}');">
    <div class="porsche-overlay"></div>
    <div class="porsche-content-wrapper porsche-content-result">
        <div class="porsche-result-container">
            <div class="porsche-result-header">
                <span class="porsche-result-tagline" id="result-tagline">YOUR NEXT JOURNEY IS:</span>
            </div>

            <!-- Vehicle inside Red Shield Frame -->
            <div class="porsche-shield-frame-wrapper">
                <img src="{{ asset(config('porsche.results.A.shield_image')) }}" 
                     alt="Porsche Cayenne Journey" 
                     class="porsche-shield-img" 
                     id="result-shield-img">
            </div>

            <div class="porsche-result-body">
                <h2 class="porsche-result-title" id="result-title">The one with endless possibilities</h2>

                <!-- Red vertical accent line containing subtitle & description -->
                <div class="porsche-result-accent-box">
                    <p class="porsche-result-subtitle" id="result-subtitle">You are driven by the roads less travelled.</p>
                    <p class="porsche-result-description" id="result-description">Hidden discoveries and unexpected tours are what make the journey worthwhile.</p>
                </div>
            </div>

            <div class="porsche-result-actions">
                <button type="button" class="porsche-btn porsche-btn-outline" id="btn-try-again">
                    DONE
                </button>
            </div>
        </div>
    </div>
</section>
