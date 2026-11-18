<section class="porsche-screen porsche-quiz-screen" id="screen-quiz" style="background-image: url('{{ asset(config('porsche.campaign.quiz_bg')) }}');">
    <div class="porsche-overlay"></div>
    <div class="porsche-content-wrapper porsche-content-quiz">
        <div class="porsche-quiz-top-container">
            <!-- Dynamic Question Container -->
            <div class="porsche-question-box" id="porsche-question-box">
                <h3 class="porsche-question-text" id="quiz-question-title">
                    <!-- Populated dynamically via config -->
                </h3>

                <!-- Options A, B, C -->
                <div class="porsche-options-list" id="quiz-options-list">
                    <!-- Populated dynamically via config -->
                </div>
            </div>
        </div>

        <!-- Quiz Navigation Action below the car -->
        <div class="porsche-quiz-actions">
            <button type="button" class="porsche-btn porsche-btn-outline" id="btn-quiz-next" disabled>
                Continue
            </button>
        </div>
    </div>
</section>
