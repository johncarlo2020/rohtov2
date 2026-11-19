<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('porsche.campaign.name') }}</title>

    <!-- Critical Preloads for Instant First Paint -->
    <link rel="preload" href="{{ asset('porsche/fonts/PorscheNext-Regular.otf') }}" as="font" type="font/otf" crossorigin>
    <link rel="preload" href="{{ asset('porsche/fonts/PorscheNext-Bold.otf') }}" as="font" type="font/otf" crossorigin>
    <link rel="preload" href="{{ asset(config('porsche.campaign.porsche_logo')) }}" as="image">
    <link rel="preload" href="{{ asset(config('porsche.campaign.landing_header')) }}" as="image">
    <link rel="preload" href="{{ asset(config('porsche.campaign.landing_bg')) }}" as="image">

    <!-- Prefetch Next Screens in Background -->
    <link rel="prefetch" href="{{ asset(config('porsche.campaign.form_bg')) }}">
    <link rel="prefetch" href="{{ asset(config('porsche.campaign.instruction_bg')) }}">
    <link rel="prefetch" href="{{ asset(config('porsche.campaign.quiz_bg')) }}">

    <style>
        /* ==========================================================================
           PORSCHE DESIGN TOKENS & TYPOGRAPHY
           ========================================================================== */
        @font-face {
            font-family: 'Porsche Next';
            src: url('{{ asset('porsche/fonts/PorscheNext-Thin.otf') }}') format('opentype');
            font-weight: 100;
            font-style: normal;
        }
        @font-face {
            font-family: 'Porsche Next';
            src: url('{{ asset('porsche/fonts/PorscheNext-Regular.otf') }}') format('opentype');
            font-weight: 400;
            font-style: normal;
        }
        @font-face {
            font-family: 'Porsche Next';
            src: url('{{ asset('porsche/fonts/PorscheNext-Bold.otf') }}') format('opentype');
            font-weight: 700;
            font-style: normal;
        }

        :root {
            --porsche-black: #0b0c0e;
            --porsche-red: #d5001c;
            --porsche-white: #ffffff;
            --porsche-gray-100: #f4f4f4;
            --porsche-gray-300: #d1d5db;
            --porsche-gray-500: #9ca3af;
            --porsche-gray-800: #1f2937;
            --porsche-border-light: rgba(255, 255, 255, 0.45);
            --porsche-border-active: rgba(255, 255, 255, 0.95);
            --porsche-overlay: rgba(11, 12, 14, 0.48);
            --porsche-overlay-dark: rgba(11, 12, 14, 0.68);
            --porsche-font: 'Porsche Next', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            --porsche-transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }

        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html, body {
            width: 100%;
            height: 100%;
            overflow-x: hidden;
            background-color: var(--porsche-black);
            color: var(--porsche-white);
            font-family: var(--porsche-font);
            font-size: 16px;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Container for all screens: Full-Bleed Edge-to-Edge Adaptive (Option B) */
        .porsche-app-wrapper {
            position: relative;
            width: 100%;
            min-height: 100vh;
            overflow: hidden;
            background-color: var(--porsche-black);
        }

        /* Screen system */
        .porsche-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center bottom;
            background-repeat: no-repeat;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity 0.5s ease, visibility 0.5s ease;
            z-index: 1;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }

        .porsche-screen.active {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            z-index: 2;
        }

        .porsche-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(180deg, rgba(11, 12, 14, 0.6) 0%, rgba(11, 12, 14, 0.35) 50%, rgba(11, 12, 14, 0.75) 100%);
            z-index: 1;
            pointer-events: none;
        }

        .porsche-content-wrapper {
            position: relative;
            z-index: 2;
            width: 100%;
            min-height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            padding: 30px 24px 40px;
        }

        /* Header */
        .porsche-header {
            width: 100%;
            text-align: center;
            padding: 10px 0 20px;
        }

        .porsche-brand-logo {
            height: 16px;
            width: auto;
            max-width: 180px;
            object-fit: contain;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.5));
        }

        /* Common Buttons */
        .porsche-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-family: var(--porsche-font);
            font-size: 0.95rem;
            font-weight: 400;
            letter-spacing: 1.5px;
            text-transform: none;
            text-decoration: none;
            color: var(--porsche-white);
            background: transparent;
            border: 1px solid var(--porsche-border-light);
            border-radius: 2px;
            padding: 10px 36px;
            cursor: pointer;
            transition: var(--porsche-transition);
            user-select: none;
            touch-action: manipulation;
            min-width: 140px;
        }

        .porsche-btn:hover:not(:disabled) {
            border-color: var(--porsche-white);
            background-color: rgba(255, 255, 255, 0.12);
            transform: translateY(-1px);
        }

        .porsche-btn:active:not(:disabled) {
            transform: translateY(0);
            background-color: rgba(255, 255, 255, 0.2);
        }

        .porsche-btn:disabled {
            opacity: 0.35;
            cursor: not-allowed;
            border-color: rgba(255, 255, 255, 0.2);
        }

        .porsche-btn-sm {
            padding: 10px 26px;
            min-width: 130px;
            font-size: 0.85rem;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        /* ==========================================================================
           SCREEN 1: LANDING
           ========================================================================== */
        .porsche-content-landing {
            justify-content: flex-start;
            padding: 30px 24px 20px;
        }

        .porsche-landing-box {
            width: 100%;
            max-width: 440px;
            margin: 4vh auto auto auto;
            text-align: left;
        }

        .porsche-landing-title-container {
            width: 100%;
            max-width: 380px;
            margin-bottom: 24px;
            text-align: left;
        }

        .porsche-landing-header-img {
            width: 100%;
            height: auto;
            object-fit: contain;
            filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.6));
            display: block;
        }

        .porsche-landing-actions {
            margin-top: 10px;
            text-align: left;
        }

        /* ==========================================================================
           SCREEN 2: REGISTRATION
           ========================================================================== */
        .porsche-content-registration {
            justify-content: space-between;
            align-items: center;
            padding: 3vh 24px 4vh;
            min-height: 100%;
        }

        .porsche-registration-form-layout {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            width: 100%;
            min-height: 100%;
            flex: 1;
        }

        .porsche-reg-top-zone {
            width: 100%;
            max-width: 440px;
            margin: 0 auto;
        }

        .porsche-form-fields-container {
            width: 100%;
            margin-top: 6px;
        }

        .porsche-form-group {
            margin-bottom: 0;
            text-align: left;
        }

        .porsche-label {
            display: block;
            font-size: 0.95rem;
            letter-spacing: 0.3px;
            text-transform: none;
            color: var(--porsche-white);
            margin-bottom: 6px;
            font-weight: 700;
        }

        .porsche-input-wrapper {
            position: relative;
            width: 100%;
            display: flex;
            align-items: center;
        }

        .porsche-input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, 0.9);
            pointer-events: none;
            z-index: 2;
        }

        .porsche-input {
            width: 100%;
            height: 44px;
            padding: 10px 16px 10px 44px;
            font-family: var(--porsche-font);
            font-size: 0.9rem;
            color: var(--porsche-white);
            background: rgba(0, 0, 0, 0.28);
            border: 1px solid rgba(255, 255, 255, 0.7);
            border-radius: 6px;
            outline: none;
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            transition: var(--porsche-transition);
        }

        .porsche-input::placeholder {
            color: rgba(255, 255, 255, 0.7);
            font-weight: 300;
        }

        .porsche-input:focus {
            border-color: var(--porsche-white);
            background: rgba(0, 0, 0, 0.45);
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.25);
        }

        .porsche-input.is-invalid {
            border-color: var(--porsche-red);
        }

        .porsche-error-text {
            display: block;
            font-size: 0.72rem;
            color: #ff6b6b;
            margin-top: 3px;
            min-height: 14px;
            font-weight: 300;
        }

        .porsche-reg-bottom-zone {
            width: 100%;
            max-width: 440px;
            margin: auto auto 0 auto;
            padding-top: 16px;
        }

        .porsche-consent-group {
            margin-bottom: 18px;
        }

        .porsche-checkbox-label {
            display: flex;
            align-items: flex-start;
            cursor: pointer;
            gap: 12px;
            user-select: none;
        }

        .porsche-checkbox-label input[type="checkbox"] {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .porsche-custom-checkbox {
            flex-shrink: 0;
            width: 18px;
            height: 18px;
            margin-top: 2px;
            border: 1px solid rgba(255, 255, 255, 0.75);
            border-radius: 4px;
            background: rgba(0, 0, 0, 0.28);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: var(--porsche-transition);
        }

        .porsche-checkbox-label input[type="checkbox"]:checked + .porsche-custom-checkbox {
            background-color: var(--porsche-red);
            border-color: var(--porsche-red);
        }

        .porsche-checkbox-label input[type="checkbox"]:checked + .porsche-custom-checkbox::after {
            content: '';
            width: 5px;
            height: 9px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
            margin-bottom: 2px;
        }

        .porsche-consent-text {
            font-size: 0.72rem;
            line-height: 1.4;
            color: rgba(255, 255, 255, 0.88);
            font-weight: 300;
            text-align: left;
        }

        .porsche-link,
        .porsche-link:visited,
        .porsche-link:hover,
        .porsche-link:active,
        .porsche-link:focus {
            color: #ffffff !important;
            text-decoration: underline;
            font-weight: 700;
        }

        .porsche-form-actions {
            text-align: center;
            margin-top: 8px;
        }

        /* Spinner for loading */
        .spinner-border {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            vertical-align: text-bottom;
            border: 0.15em solid currentColor;
            border-right-color: transparent;
            border-radius: 50%;
            animation: spinner-border .75s linear infinite;
        }

        @keyframes spinner-border {
            to { transform: rotate(360deg); }
        }

        .d-none {
            display: none !important;
        }

        /* ==========================================================================
           SCREEN 3: INSTRUCTIONS
           ========================================================================== */
        .porsche-content-instructions {
            justify-content: flex-start;
            padding: 30px 24px 20px;
        }

        .porsche-instructions-box {
            width: 100%;
            max-width: 440px;
            margin: 6vh auto auto auto;
            padding: 0 10px;
            text-align: left;
        }

        .porsche-instructions-heading {
            font-size: 1.45rem;
            font-weight: 400;
            letter-spacing: 0.5px;
            color: var(--porsche-white);
            margin-bottom: 22px;
            text-align: left;
        }

        .porsche-instructions-text {
            font-size: 1.25rem;
            font-weight: 300;
            letter-spacing: 0.5px;
            color: var(--porsche-white);
            margin-bottom: 30px;
            line-height: 1.45;
            text-align: left;
        }

        .porsche-instructions-actions {
            text-align: left;
        }

        /* ==========================================================================
           SCREEN 4: QUIZ
           ========================================================================== */
        .porsche-content-quiz {
            justify-content: space-between;
            align-items: center;
            padding: 7vh 24px 6vh;
            min-height: 100%;
        }

        .porsche-quiz-top-container {
            width: 100%;
            max-width: 440px;
            margin: 0 auto;
            text-align: left;
        }

        .porsche-question-box {
            width: 100%;
            text-align: left;
            margin-bottom: 20px;
        }

        .porsche-question-text {
            font-size: 1.15rem;
            font-weight: 400;
            line-height: 1.45;
            letter-spacing: 0.3px;
            color: var(--porsche-white);
            text-align: left;
            margin-bottom: 24px;
        }

        .porsche-options-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
            width: 100%;
        }

        .porsche-option-item {
            display: flex;
            align-items: center;
            width: 100%;
            padding: 13px 18px;
            background: rgba(0, 0, 0, 0.28);
            border: 1px solid rgba(255, 255, 255, 0.65);
            border-radius: 6px;
            cursor: pointer;
            transition: var(--porsche-transition);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            text-align: left;
            user-select: none;
        }

        .porsche-option-item:hover {
            border-color: var(--porsche-white);
            background: rgba(255, 255, 255, 0.12);
        }

        .porsche-option-item.selected {
            border-color: var(--porsche-white);
            background: rgba(255, 255, 255, 0.25);
            box-shadow: 0 0 12px rgba(255, 255, 255, 0.25);
        }

        .porsche-option-letter {
            font-weight: 400;
            font-size: 0.95rem;
            margin-right: 14px;
            color: var(--porsche-white);
            flex-shrink: 0;
        }

        .porsche-option-text {
            font-size: 0.9rem;
            font-weight: 300;
            line-height: 1.35;
            color: var(--porsche-white);
        }

        .porsche-quiz-actions {
            width: 100%;
            text-align: center;
            margin-top: auto;
            padding-top: 24px;
        }

        /* ==========================================================================
           SCREEN 5: RESULT
           ========================================================================== */
        .porsche-content-result {
            justify-content: flex-start;
            padding: 5vh 24px 6vh;
            min-height: 100%;
        }

        .porsche-result-container {
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
            text-align: left;
        }

        .porsche-result-header {
            margin-bottom: 14px;
            text-align: left;
        }

        .porsche-result-tagline {
            display: block;
            font-size: 0.95rem;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--porsche-white);
            font-weight: 400;
        }

        .porsche-shield-frame-wrapper {
            width: 100%;
            max-width: 250px;
            margin: 0 auto 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .porsche-shield-img {
            width: 100%;
            height: auto;
            max-height: 290px;
            object-fit: contain;
            filter: drop-shadow(0 8px 20px rgba(0, 0, 0, 0.7));
            animation: shieldFloat 4s ease-in-out infinite alternate;
        }

        @keyframes shieldFloat {
            0% { transform: translateY(0); }
            100% { transform: translateY(-4px); }
        }

        .porsche-result-body {
            margin-bottom: 24px;
            text-align: left;
        }

        .porsche-result-title {
            font-size: 1.25rem;
            font-weight: 400;
            line-height: 1.35;
            letter-spacing: 0.5px;
            color: var(--porsche-white);
            margin-bottom: 14px;
            text-align: left;
        }

        .porsche-result-accent-box {
            border-left: 2px solid var(--porsche-red);
            padding-left: 14px;
            margin-top: 10px;
            margin-bottom: 10px;
            text-align: left;
        }

        .porsche-result-subtitle {
            font-size: 0.88rem;
            font-weight: 400;
            line-height: 1.45;
            color: rgba(255, 255, 255, 0.92);
            margin-bottom: 8px;
            text-align: left;
        }

        .porsche-result-description {
            font-size: 0.8rem;
            font-weight: 300;
            line-height: 1.45;
            color: rgba(255, 255, 255, 0.75);
            text-align: left;
        }

        .porsche-result-actions {
            text-align: left;
            margin-top: 12px;
        }

        /* ==========================================================================
           TABLET & DESKTOP (>= 768px): CONSOLIDATED OVERRIDES (KISS, DRY)
           ========================================================================== */
        @media (min-width: 768px) {
            .porsche-overlay {
                background: linear-gradient(180deg, rgba(11, 12, 14, 0.72) 0%, rgba(11, 12, 14, 0.42) 50%, rgba(11, 12, 14, 0.8) 100%);
            }

            .porsche-brand-logo {
                height: 20px;
                max-width: 220px;
            }

            .porsche-btn {
                min-width: 200px;
                font-size: 1.5rem;
                line-height: normal;
                padding: 12px 42px;
            }

            /* Consistent 600px containers across pages */
            .porsche-landing-box,
            .porsche-reg-top-zone,
            .porsche-reg-bottom-zone,
            .porsche-instructions-box,
            .porsche-quiz-top-container,
            .porsche-result-container {
                max-width: 600px;
            }

            /* Screen 1: Landing */
            .porsche-content-landing {
                padding: 40px 32px 30px;
            }
            .porsche-landing-box {
                margin: 6vh auto auto auto;
            }
            .porsche-landing-title-container {
                max-width: 540px;
                margin-bottom: 28px;
            }

            /* Screen 2: Registration */
            .porsche-content-registration {
                padding: 4vh 32px 5vh;
            }
            .porsche-input {
                height: 48px;
                font-size: 0.95rem;
            }
            .porsche-label {
                font-size: 1.2rem;
            }
            .porsche-consent-text {
                font-size: 1.2rem;
            }

            /* Screen 3: Instructions */
            .porsche-instructions-box {
                margin: 6vh auto auto auto;
                padding: 0;
            }
            .porsche-instructions-heading {
                font-size: 2.5rem;
                margin-bottom: 24px;
            }
            .porsche-instructions-text {
                font-size: 2.5rem;
                line-height: 1.35;
                margin-bottom: 36px;
            }

            /* Screen 4: Quiz */
            .porsche-content-quiz {
                padding: 6vh 32px 5vh;
            }
            .porsche-quiz-top-container {
                margin: 2vh auto auto auto;
            }
            .porsche-question-text {
                font-size: 2.3rem;
                line-height: 1.35;
                margin-bottom: 28px;
            }
            .porsche-options-list {
                gap: 16px;
            }
            .porsche-option-item {
                padding: 18px 24px;
            }
            .porsche-option-letter {
                font-size: 1.8rem;
                margin-right: 18px;
            }
            .porsche-option-text {
                font-size: 1.8rem;
                line-height: 1.35;
            }
            .porsche-quiz-actions {
                padding-top: 24px;
                padding-bottom: 16px;
            }

            /* Screen 5: Result */
            .porsche-content-result {
                padding: 6vh 32px 5vh;
                justify-content: center;
            }
            .porsche-result-container {
                width: 100%;
                min-height: calc(100vh - 11vh);
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                margin: 0 auto;
            }
            .porsche-result-header {
                margin-bottom: 20px;
                text-align: left;
            }
            .porsche-result-tagline {
                font-size: 2.05rem;
                font-weight: 700;
                letter-spacing: 2.5px;
                line-height: 1.25;
            }
            .porsche-shield-frame-wrapper {
                max-width: 340px;
                margin: 3rem auto;
            }
            .porsche-shield-img {
                max-height: 350px;
            }
            .porsche-result-body {
                margin-bottom: 24px;
            }
            .porsche-result-title {
                font-size: 2.5rem;
                font-weight: 400;
                line-height: 1.3;
                margin-bottom: 18px;
            }
            .porsche-result-accent-box {
                border-left-width: 3px;
                padding-left: 18px;
                margin-top: 16px;
                margin-bottom: 16px;
            }
            .porsche-result-subtitle {
                font-size: 1.5rem;
                font-weight: 300;
                line-height: 1.5;
                margin-bottom: 12px;
            }
            .porsche-result-description {
                font-size: 1.5rem;
                line-height: 1.55;
            }
            .porsche-result-actions {
                margin-top: auto;
                padding-top: 10px;
            }
        }
    </style>
</head>
<body>

    <div class="porsche-app-wrapper">
        <!-- Screen 1: Landing -->
        @include('porsche.components.landing')

        <!-- Screen 2: Registration -->
        @include('porsche.components.registration')

        <!-- Screen 3: Instructions -->
        @include('porsche.components.instructions')

        <!-- Screen 4: Quiz -->
        @include('porsche.components.quiz')

        <!-- Screen 5: Result -->
        @include('porsche.components.result')
    </div>

    <script>
        (function() {
            'use strict';

            // Centralized Configuration passed from backend
            const CONFIG = @json($config);
            const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Centralized State Management
            const state = {
                currentStep: @json($initialStep ?? 'landing'), // landing, registration, instructions, quiz, result
                currentQuestionIndex: 1,
                totalQuestions: Object.keys(CONFIG.questions).length || 4,
                answers: {}, // { 1: 'A', 2: 'B', ... }
                registrationId: @json($registration->id ?? null),
                registrationData: @json($registration ? ['name' => $registration->full_name, 'phone' => $registration->contact_number, 'email' => $registration->email] : null),
                resultData: null,
                isSubmitting: false
            };

            // DOM Elements Cache
            const screens = {
                landing: document.getElementById('screen-landing'),
                registration: document.getElementById('screen-registration'),
                instructions: document.getElementById('screen-instructions'),
                quiz: document.getElementById('screen-quiz'),
                result: document.getElementById('screen-result')
            };

            const registrationForm = document.getElementById('porsche-registration-form');
            const btnBegin = document.getElementById('btn-begin');
            const btnInstructionsNext = document.getElementById('btn-instructions-next');
            const btnQuizNext = document.getElementById('btn-quiz-next');
            const btnTryAgain = document.getElementById('btn-try-again');

            const quizQuestionTitle = document.getElementById('quiz-question-title');
            const quizOptionsList = document.getElementById('quiz-options-list');

            const resultTagline = document.getElementById('result-tagline');
            const resultTitle = document.getElementById('result-title');
            const resultSubtitle = document.getElementById('result-subtitle');
            const resultDescription = document.getElementById('result-description');
            const resultShieldImg = document.getElementById('result-shield-img');

            // =========================================================================
            // NAVIGATION & SCREEN SWITCHING
            // =========================================================================
            function switchScreen(targetStep) {
                state.currentStep = targetStep;

                Object.keys(screens).forEach(key => {
                    if (key === targetStep) {
                        screens[key].classList.add('active');
                        screens[key].scrollTop = 0;
                    } else {
                        screens[key].classList.remove('active');
                    }
                });
            }

            // =========================================================================
            // REGISTRATION LOGIC
            // =========================================================================
            function clearErrors() {
                document.querySelectorAll('.porsche-error-text').forEach(el => el.textContent = '');
                document.querySelectorAll('.porsche-input').forEach(el => el.classList.remove('is-invalid'));
            }

            function setFieldError(fieldName, message) {
                const input = document.querySelector(`[name="${fieldName}"]`);
                const errorSpan = document.getElementById(`error-${fieldName}`);
                if (input) input.classList.add('is-invalid');
                if (errorSpan) errorSpan.textContent = message;
            }

            async function handleRegistration(e) {
                e.preventDefault();
                if (state.isSubmitting) return;

                clearErrors();

                const nameInput = document.getElementById('reg-name');
                const phoneInput = document.getElementById('reg-phone');
                const emailInput = document.getElementById('reg-email');
                const consentInput = document.getElementById('reg-consent');

                const fullName = nameInput.value.trim();
                const phone = phoneInput.value.trim();
                const email = emailInput.value.trim();
                const consent = consentInput.checked;

                let hasError = false;

                if (!fullName) {
                    setFieldError('full_name', 'Please enter your full name.');
                    hasError = true;
                }
                if (!phone) {
                    setFieldError('contact_number', 'Please enter your phone number.');
                    hasError = true;
                } else if (!/^[0-9+\s\-()]{8,20}$/.test(phone)) {
                    setFieldError('contact_number', 'Please enter a valid phone number.');
                    hasError = true;
                }
                if (!email) {
                    setFieldError('email', 'Please enter your email address.');
                    hasError = true;
                } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                    setFieldError('email', 'Please enter a valid email address.');
                    hasError = true;
                }
                if (!consent) {
                    setFieldError('consent', 'You must agree to the privacy policy to continue.');
                    hasError = true;
                }

                if (hasError) return;

                // Submit to backend
                state.isSubmitting = true;
                const submitBtn = document.getElementById('btn-continue');
                const btnText = submitBtn.querySelector('.btn-text');
                const btnLoader = submitBtn.querySelector('.btn-loader');
                btnText.classList.add('d-none');
                btnLoader.classList.remove('d-none');
                submitBtn.disabled = true;

                try {
                    const response = await fetch('{{ route('porsche.register') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            full_name: fullName,
                            contact_number: phone,
                            email: email,
                            consent: consent ? 1 : 0
                        })
                    });

                    const data = await response.json();

                    if (!response.ok || !data.success) {
                        if (data.errors) {
                            Object.keys(data.errors).forEach(key => {
                                setFieldError(key, data.errors[key][0]);
                            });
                        } else {
                            alert(data.message || 'Registration failed. Please try again.');
                        }
                        return;
                    }

                    // Success: store registration info
                    state.registrationId = data.registration.id;
                    state.registrationData = data.registration;

                    // Transition to Instructions
                    switchScreen('instructions');

                } catch (err) {
                    console.error('Registration network error:', err);
                    alert('Network error. Please check your connection and retry.');
                } finally {
                    state.isSubmitting = false;
                    btnText.classList.remove('d-none');
                    btnLoader.classList.add('d-none');
                    submitBtn.disabled = false;
                }
            }

            // =========================================================================
            // QUIZ QUESTION RENDERING & FLOW (DRY SINGLE COMPONENT)
            // =========================================================================
            function renderCurrentQuestion() {
                const qIndex = state.currentQuestionIndex;
                const questionData = CONFIG.questions[qIndex];
                if (!questionData) return;

                // Question Title (render newline if two sentences)
                quizQuestionTitle.innerHTML = questionData.question.replace(/\. /g, '.<br>');

                // Render Options (A, B, C)
                quizOptionsList.innerHTML = '';
                const currentAnswer = state.answers[qIndex] || null;

                ['A', 'B', 'C'].forEach(letter => {
                    const optionText = questionData.options[letter];
                    if (!optionText) return;

                    const optionItem = document.createElement('div');
                    optionItem.className = 'porsche-option-item';
                    if (currentAnswer === letter) {
                        optionItem.classList.add('selected');
                    }
                    optionItem.dataset.letter = letter;

                    optionItem.innerHTML = `
                        <span class="porsche-option-letter">${letter}.</span>
                        <span class="porsche-option-text">${optionText}</span>
                    `;

                    optionItem.addEventListener('click', () => selectOption(letter));
                    quizOptionsList.appendChild(optionItem);
                });

                // Continue Button
                btnQuizNext.textContent = 'Continue';
                btnQuizNext.disabled = !currentAnswer;
            }

            function selectOption(letter) {
                const qIndex = state.currentQuestionIndex;
                state.answers[qIndex] = letter;

                // Update visual selection
                document.querySelectorAll('.porsche-option-item').forEach(el => {
                    if (el.dataset.letter === letter) {
                        el.classList.add('selected');
                    } else {
                        el.classList.remove('selected');
                    }
                });

                // Enable Next button
                btnQuizNext.disabled = false;
            }

            async function nextQuestion() {
                if (!state.answers[state.currentQuestionIndex]) return;

                if (state.currentQuestionIndex < state.totalQuestions) {
                    state.currentQuestionIndex++;
                    renderCurrentQuestion();
                } else {
                    // Last question: submit answers
                    await submitQuiz();
                }
            }

            // =========================================================================
            // QUIZ SUBMISSION & RESULT CALCULATION
            // =========================================================================
            async function submitQuiz() {
                if (state.isSubmitting) return;

                state.isSubmitting = true;
                btnQuizNext.disabled = true;
                btnQuizNext.textContent = 'Calculating...';

                try {
                    const response = await fetch('{{ route('porsche.quiz.submit') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            answers: state.answers,
                            registration_id: state.registrationId
                        })
                    });

                    const data = await response.json();

                    if (!response.ok || !data.success) {
                        alert(data.message || 'Error calculating result. Please try again.');
                        btnQuizNext.disabled = false;
                        btnQuizNext.textContent = 'Continue';
                        return;
                    }

                    // Render Result Screen
                    renderResult(data.result);
                    switchScreen('result');

                } catch (err) {
                    console.error('Quiz submission error:', err);
                    alert('Network error while calculating result.');
                    btnQuizNext.disabled = false;
                    btnQuizNext.textContent = 'Continue';
                } finally {
                    state.isSubmitting = false;
                }
            }

            function renderResult(result) {
                state.resultData = result;

                // Update dynamic result contents
                resultTagline.textContent = result.headline || 'YOUR NEXT JOURNEY IS:';
                resultTitle.textContent = result.title;
                resultSubtitle.textContent = result.subtitle;
                resultDescription.textContent = result.description;
                resultShieldImg.src = '{{ asset('') }}' + result.shield_image;
                resultShieldImg.alt = result.title;

                // Update full-screen background
                screens.result.style.backgroundImage = `url('{{ asset('') }}${result.background_image}')`;
                btnTryAgain.textContent = result.button_text || 'DONE';
            }

            // =========================================================================
            // DONE / RETURN TO LANDING FUNCTIONALITY
            // =========================================================================
            function restartQuiz() {
                // Clear quiz answers & reset index
                state.answers = {};
                state.currentQuestionIndex = 1;
                state.resultData = null;
                state.registrationId = null;
                state.registrationData = null;

                if (registrationForm) {
                    registrationForm.reset();
                    clearErrors();
                }

                if (btnQuizNext) {
                    btnQuizNext.disabled = true;
                    btnQuizNext.textContent = 'Continue';
                }

                // Forget backend session
                fetch('{{ route('welcome') }}?reset=1', { headers: { 'X-Requested-With': 'XMLHttpRequest' } }).catch(() => {});

                // Navigate back to landing screen
                switchScreen('landing');
                window.history.replaceState({}, document.title, '{{ route('welcome') }}');
            }

            // =========================================================================
            // EVENT LISTENERS
            // =========================================================================
            btnBegin.addEventListener('click', () => {
                if (state.registrationId) {
                    // Already registered in this session: jump straight to instructions
                    switchScreen('instructions');
                } else {
                    switchScreen('registration');
                }
            });

            registrationForm.addEventListener('submit', handleRegistration);

            btnInstructionsNext.addEventListener('click', () => {
                state.answers = {};
                state.currentQuestionIndex = 1;
                renderCurrentQuestion();
                switchScreen('quiz');
            });

            btnQuizNext.addEventListener('click', nextQuestion);
            btnTryAgain.addEventListener('click', restartQuiz);

            // Initialize screen
            switchScreen(state.currentStep || 'landing');
        })();
    </script>
</body>
</html>
