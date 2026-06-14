<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
  <title>Mari Home Mari Ong – Lucky Draw</title>
  <style>
/* ============================================================
   BASE
============================================================ */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
  --blue:       #1A6CF5;
  --blue-dark:  #1358CC;
  --blue-light: #E8F0FF;
  --white:      #FFFFFF;
  --text-dark:  #1A1A2E;
  --text-mid:   #4A5568;
  --shadow:     0 8px 32px rgba(0,0,0,0.18);
  --radius:     20px;
  --transition: 0.45s cubic-bezier(.4,0,.2,1);
}

html, body {
  width: 100%;
  height: 100%;
  font-family: 'Segoe UI', system-ui, sans-serif;
  background: #D0D8E8;
  overflow: hidden;
}

/* Blurred background */
.bg-blur {
  position: fixed;
  inset: 0;
  background:
    radial-gradient(ellipse at 20% 110%, #b8c6d6 0%, transparent 60%),
    radial-gradient(ellipse at 80% -10%,  #c8d4e4 0%, transparent 60%),
    #D0D8E8;
  z-index: 0;
}

/* ============================================================
   SCREENS  –  each is a fixed full-viewport layer
============================================================ */
.screen {
  position: fixed;
  inset: 0;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: flex-start;
  z-index: 1;
  opacity: 0;
  pointer-events: none;
  transition: opacity var(--transition), transform var(--transition);
  transform: translateY(28px);
}

.screen.active {
  opacity: 1;
  pointer-events: all;
  transform: translateY(0);
}

.screen.exit {
  opacity: 0;
  transform: translateY(-28px);
}

/* ============================================================
   LOGO
============================================================ */
.logo { margin-bottom: 12px; flex-shrink: 0; }
.logo-img { height: 34px; object-fit: contain; }

.logo-svg-fallback {
  display: flex;
  align-items: center;
  gap: 6px;
  font-weight: 700;
  font-size: 15px;
  color: var(--white);
}

/* ============================================================
   SCREEN 1 – START  (fills entire viewport)
============================================================ */
#screen-start {
  padding: 0;
  align-items: center;
  justify-content: flex-end;
  padding-bottom: clamp(360px, calc(63vh + 160px), 1330px);
  background: url('{{ asset('images/images/start.webp') }}') center center / cover no-repeat;
}

.start-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  width: 100%;
  gap: 0;
}

.title-main {
  font-size: clamp(40px, 13vw, 76px);
  font-weight: 900;
  font-style: italic;
  text-align: center;
  line-height: 1.0;
  color: var(--white);
  /* outline / cartoon stroke */
  -webkit-text-stroke: 3px #1a3a9f;
  paint-order: stroke fill;
  text-shadow:
    3px  3px 0 #1a3a9f,
   -3px -3px 0 #1a3a9f,
    3px -3px 0 #1a3a9f,
   -3px  3px 0 #1a3a9f;
  flex-shrink: 0;
}

.giftbox {
  width: min(360px, 80vw);
  height: min(360px, 80vw);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.giftbox img {
  width: 100%;
  height: 100%;
  object-fit: contain;
  animation: giftFloat 3s ease-in-out infinite;
}

.giftbox-fallback {
  font-size: min(130px, 32vw);
  line-height: 1;
  animation: giftFloat 3s ease-in-out infinite;
}

.giftbox img:not([src=""]) ~ .giftbox-fallback { display: none; }
.giftbox img[src=""] { display: none; }

@keyframes giftFloat {
  0%, 100% { transform: translateY(0)    scale(1);    }
  50%       { transform: translateY(-14px) scale(1.04); }
}

/* ============================================================
   SCREEN 2 – GUIDE  (fills entire viewport)
============================================================ */
#screen-guide {
  padding: 0;
  align-items: center;
  justify-content: flex-start;
  background: url('{{ asset('images/images/guide.webp') }}') center center / cover no-repeat;
}

#btn-ready {
  position: static;
  transform: none;
  margin-top: clamp(120px, 22vh, 440px);
}

#screen-guide .hand-icon {
  position: static;
  flex: unset;
  margin-top: clamp(150px, 28vh, 570px);
}

.hand-icon {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
}

.hand-circle {
  width: min(160px, 40vw);
  height: min(160px, 40vw);
  border-radius: 50%;
  border: 3px solid #bbb;
  display: flex;
  align-items: center;
  justify-content: center;
  animation: handPulse 1.8s ease-in-out infinite;
  position: relative;
}

.hand-circle::before {
  content: '';
  position: absolute;
  inset: -13px;
  border-radius: 50%;
  border: 2px dashed #aaa;
  animation: spinSlow 8s linear infinite;
}

.hand-emoji {
  font-size: min(72px, 18vw);
  animation: handTap 1.8s ease-in-out infinite;
  display: block;
}

@keyframes handPulse { 0%,100% { transform: scale(1);    } 50% { transform: scale(1.07); } }
@keyframes handTap   { 0%,100% { transform: translateY(0); } 50% { transform: translateY(8px); } }
@keyframes spinSlow  { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

/* ============================================================
   BUTTONS
============================================================ */
.btn {
  display: inline-block;
  padding: 20px 146px;
  border: none;
  border-radius: 50px;
  font-size: 18px;
  font-weight: 700;
  letter-spacing: 1.5px;
  cursor: pointer;
  text-transform: uppercase;
  transition: transform 0.15s, box-shadow 0.15s, opacity 0.15s;
  flex-shrink: 0;
}

.btn:active  { transform: scale(0.96) !important; }
.btn:disabled { opacity: 0.5; cursor: not-allowed; }

.btn--white {
  background: var(--white);
  color: var(--blue);
  box-shadow: 0 4px 20px rgba(0,0,0,0.2);
}
.btn--white:hover { transform: translateY(-2px); box-shadow: 0 6px 24px rgba(0,0,0,0.26); }

.btn--blue {
  background: var(--blue);
  color: var(--white);
  box-shadow: 0 4px 18px rgba(26,108,245,0.45);
}
.btn--blue:hover { background: var(--blue-dark); transform: translateY(-2px); }

/* full-width action button sitting below a card */
.btn--wide {
  width: calc(100% - 48px);
  max-width: 480px;
  margin: 0;
  padding: 18px 0;
  font-size: 17px;
}

.btn--sm { padding: 12px 32px; font-size: 14px; margin-top: 16px; }

/* ============================================================
   SCREEN TITLE  (shared by shuffle / result)
============================================================ */
.screen-title {
  font-family: 'Segoe UI Black', 'Arial Black', sans-serif;
  font-size: clamp(26px, 6.5vw, 42px);
  font-weight: 900;
  letter-spacing: 3px;
  text-transform: uppercase;
  color: var(--text-dark);
  padding: 48px 0 24px;
  flex-shrink: 0;
  -webkit-text-stroke: 1px rgba(0,0,0,0.07);
}

/* ============================================================
   SCREEN 3 – SHUFFLE  (carousel)
============================================================ */
#screen-shuffle {
  justify-content: center;
  gap: 28px;
  padding: 40px 32px;
  background: url('{{ asset('images/images/draw.webp') }}') center center / cover no-repeat;
}

/* Logo at top of shuffle screen */
.screen-logo {
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: center;
}
.screen-logo img {
  height: 36px;
  width: auto;
  object-fit: contain;
}

/* The outer white card — fixed, never moves */
.carousel-viewport {
  background: var(--white);
  border-radius: var(--radius);
  box-shadow: var(--shadow);
  width: 100%;
  max-width: 340px;
  padding: 28px 24px 32px;
  display: flex;
  flex-direction: column;
  align-items: center;
  overflow: hidden;
  position: relative;
  flex-shrink: 0;
}

/* Clipping container: content slides inside here */
.carousel-inner {
  width: 100%;
  position: relative;
}

/* The sliding panel that holds image + name */
.carousel-card {
  width: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 14px;
  cursor: pointer;
  user-select: none;
  will-change: transform, opacity;
}

.carousel-img-wrap {
  width: 100%;
  aspect-ratio: 4 / 3;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 14px;
  overflow: hidden;
  background: #f4f7ff;
}

.carousel-img-wrap img {
  max-width: 100%;
  max-height: 100%;
  object-fit: contain;
  display: block;
}

.carousel-name {
  font-size: clamp(16px, 4.5vw, 20px);
  font-weight: 700;
  text-align: center;
  color: var(--blue);
  line-height: 1.3;
  width: 100%;
}

/* Slide-out to left */
@keyframes slideOutLeft {
  from { transform: translateX(0);     opacity: 1; }
  to   { transform: translateX(-48px); opacity: 0; }
}
/* Slide-in from right */
@keyframes slideInRight {
  from { transform: translateX(48px); opacity: 0; }
  to   { transform: translateX(0);    opacity: 1; }
}

.carousel-card.slide-out { animation: slideOutLeft  0.13s ease-in  forwards; transition: none; }
.carousel-card.slide-in  { animation: slideInRight  0.13s ease-out forwards; transition: none; }

/* Fast-shuffle flash */
.carousel-card           { transition: opacity 0.04s linear; }
.carousel-card.flash-out { opacity: 0; }

/* Winner glow on the outer viewport card */
.carousel-viewport.winner {
  border: 3px solid #FFD700;
  box-shadow: 0 0 0 6px rgba(255,215,0,0.4), var(--shadow);
  animation: winnerPop 0.55s cubic-bezier(.4,0,.2,1) forwards;
}

@keyframes winnerPop {
  0%   { transform: scale(1);    }
  40%  { transform: scale(1.06); }
  70%  { transform: scale(0.98); }
  100% { transform: scale(1.03); }
}

.shuffle-hint {
  flex-shrink: 0;
  font-size: clamp(14px, 3.5vw, 18px);
  color: var(--text-mid);
  letter-spacing: 0.5px;
  animation: hintPulse 1.4s ease-in-out infinite;
}

@keyframes hintPulse { 0%,100% { opacity: 1; } 50% { opacity: 0.4; } }

/* Logo header — outside card, top of screen */
.screen-header-logo {
  height: 36px;
  width: auto;
  max-width: 160px;
  object-fit: contain;
  display: block;
  flex-shrink: 0;
  z-index: 2;
  position: relative;
}

/* ============================================================
   SCREEN 4 – RESULT
============================================================ */
#screen-result {
  justify-content: flex-start;
  align-items: center;
  gap: 24px;
  padding-top: clamp(340px, 46vh, 410px);
  padding-left: 32px;
  padding-right: 32px;
  padding-bottom: 40px;
  background: url('{{ asset('images/images/winner.webp') }}') center center / cover no-repeat;
}

/* Confetti spans the whole screen */
.confetti-container {
  position: absolute;
  inset: 0;
  pointer-events: none;
  overflow: hidden;
  z-index: 1;
}

.confetti-piece {
  position: absolute;
  border-radius: 2px;
  animation: confettiFall linear forwards;
  top: -10px;
}

@keyframes confettiFall {
  0%   { transform: translateY(0)      translateX(0)     rotate(0deg);   opacity: 1; }
  25%  { transform: translateY(25vh)   translateX(15px)  rotate(180deg); opacity: 1; }
  50%  { transform: translateY(50vh)   translateX(-10px) rotate(360deg); opacity: 1; }
  75%  { transform: translateY(75vh)   translateX(12px)  rotate(540deg); opacity: 0.8; }
  100% { transform: translateY(110vh)  translateX(-8px)  rotate(720deg); opacity: 0; }
}

.result-card {
  background: rgba(255, 255, 255, 0.15);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  border: 1px solid rgba(255, 255, 255, 0.3);
  border-radius: var(--radius);
  box-shadow: 0 8px 32px rgba(0,0,0,0.25);
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 28px 24px 32px;
  width: 100%;
  max-width: 380px;
  gap: 20px;
  z-index: 2;
  position: relative;
}

.result-prize-img {
  width: min(260px, 70vw);
  height: min(220px, 55vw);
  object-fit: contain;
  animation: bounceIn 0.6s cubic-bezier(.4,0,.2,1);
}

.result-prize-name {
  font-size: clamp(20px, 5vw, 28px);
  font-weight: 700;
  color: var(--white);
  text-align: center;
  line-height: 1.3;
  text-shadow: 0 1px 4px rgba(0,0,0,0.3);
}

@keyframes bounceIn {
  0%   { transform: scale(0.55); opacity: 0; }
  60%  { transform: scale(1.1);  opacity: 1; }
  100% { transform: scale(1);    }
}

/* ============================================================
   SCREEN 5 – QR
============================================================ */
#screen-qr {
  justify-content: center;
  gap: 24px;
  padding: 48px 32px 40px;
  background: #C8D3E8;
}

.qr-card {
  background: var(--white);
  border-radius: var(--radius);
  box-shadow: var(--shadow);
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 28px 24px 32px;
  width: 100%;
  max-width: 380px;
  gap: 16px;
}

.qr-instruction {
  font-size: clamp(16px, 4.2vw, 22px);
  font-weight: 600;
  color: var(--text-dark);
  text-align: center;
  line-height: 1.5;
}

.qr-box {
  width: min(200px, 54vw);
  height: min(200px, 54vw);
  border-radius: 12px;
  overflow: hidden;
  background: #f0f0f0;
  display: flex;
  align-items: center;
  justify-content: center;
}

.qr-box img,
.qr-box canvas {
  width: 100%;
  height: 100%;
  object-fit: contain;
}

/* ============================================================
   RESPONSIVE TWEAKS
============================================================ */

@media (min-width: 700px) {
  .carousel-viewport { max-width: 420px; }
}

/* Very small phones */
@media (max-width: 360px) {
  .title-main  { font-size: 32px; }
  .giftbox     { width: 200px; height: 200px; }
  .hand-circle { width: 120px; height: 120px; }
  .prize-grid  { gap: 10px; width: calc(100% - 24px); }
}

/* ============================================================
   COUNTDOWN OVERLAY
============================================================ */
.countdown-overlay {
  position: fixed;
  inset: 0;
  z-index: 100;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(10, 30, 80, 0.82);
  transition: opacity 0.25s ease;
}

.countdown-overlay.hidden {
  opacity: 0;
  pointer-events: none;
}

.countdown-number {
  font-size: clamp(120px, 30vw, 200px);
  font-weight: 900;
  color: var(--white);
  line-height: 1;
  text-shadow: 0 6px 32px rgba(0,0,0,0.5);
  animation: countPop 0.4s cubic-bezier(.4,0,.2,1) both;
}

@keyframes countPop {
  0%   { transform: scale(1.8); opacity: 0; }
  60%  { transform: scale(0.9); }
  100% { transform: scale(1);   opacity: 1; }
}
  </style>
</head>
<body>

  <!-- ░░ BACKGROUND ░░ -->
  <div class="bg-blur"></div>

  <!-- ░░ SCREEN 1 – START ░░ -->
  <section id="screen-start" class="screen active">
    <button class="btn btn--white" id="btn-start">START</button>
  </section>

  <!-- ░░ SCREEN 2 – GUIDE ░░ -->
  <section id="screen-guide" class="screen">
    <button class="btn btn--blue" id="btn-ready">I'M READY</button>
    <div class="hand-icon">
      <div class="hand-circle">
        <span class="hand-emoji">👆</span>
      </div>
    </div>
  </section>

  <!-- ░░ SCREEN 3 – SHUFFLE ░░ -->
  <section id="screen-shuffle" class="screen">
    <div class="screen-logo">
      <img src="{{ asset('images/images/logo_dark.webp') }}" alt="iProperty" onerror="this.style.display='none'" />
    </div>
    <div class="carousel-viewport" id="carousel-viewport">
      <div class="carousel-inner">
        <div class="carousel-card" id="carousel-card">
          <div class="carousel-img-wrap" id="carousel-img-wrap"></div>
          <p class="carousel-name" id="carousel-name"></p>
        </div>
      </div>
    </div>
    <p class="shuffle-hint" id="shuffle-hint">Tap to stop the shuffle</p>
  </section>

  <!-- ░░ SCREEN 4 – RESULT ░░ -->
  <section id="screen-result" class="screen">
    <div id="confetti-container" class="confetti-container"></div>
    <button class="btn btn--white btn--wide" id="btn-continue">CONTINUE</button>
    <div class="result-card" id="result-card">
      <img id="result-img" src="" alt="" class="result-prize-img" />
      <p id="result-name" class="result-prize-name"></p>
    </div>
  </section>

  <!-- ░░ SCREEN 5 – QR ░░ -->
  <section id="screen-qr" class="screen">
    <img class="screen-header-logo" src="{{ asset('images/images/logo_dark.webp') }}" alt="iProperty" onerror="this.style.display='none'" />
    <button class="btn btn--blue btn--wide" id="btn-finish">FINISH</button>
    <div class="qr-card">
      <p class="qr-instruction">Scan this QR<br/>to claim your prize.</p>
      <div class="qr-box">
        <img id="qr-image" src="" alt="QR Code" onerror="this.style.display='none'" />
        <canvas id="qr-canvas"></canvas>
      </div>
      <button class="btn btn--blue btn--sm" id="btn-redraw">REDRAW</button>
    </div>
  </section>

  <!-- ░░ COUNTDOWN OVERLAY ░░ -->
  <div id="countdown-overlay" class="countdown-overlay hidden">
    <span class="countdown-number" id="countdown-number">3</span>
  </div>

  <script>
/* ============================================================
   app.js  –  Mari Home Mari Ong  Lucky Draw
============================================================ */

'use strict';

const GIFT_IMAGE_BASE = "{{ asset('images/gift') }}";
const INITIAL_STOCKS = @json($stocks ?? []);

// ─────────────────────────────────────────────
// CONFIG  (edit this to change prizes / weights)
// ─────────────────────────────────────────────
const CONFIG = {
  shuffleIntervalMs:  80,    // speed of slot-machine highlight
  shuffleDuration:   3000,   // how long auto-shuffle runs before waiting for tap
  countdownStepMs:   1000,
  countdownGoHoldMs:  700,
  confettiCount:      60,
  qrBaseUrl: 'https://api.qrserver.com/v1/create-qr-code/?size=170x170&data=',
  qrClaimUrl: "{{ url('/prize/') }}",
  bgmSrc: "{{ asset('sounds/Lucky Draw BGM.mp3') }}",
  bgmVolume: 0.5,
  buttonSfxSrc: "{{ asset('sounds/Lucky Draw Button.mp3') }}",
  countdownSfxSrc: "{{ asset('sounds/Lucky Draw Countdown.mp3') }}",
  revealSfxSrc: "{{ asset('sounds/Lucky Draw Prize Reveal.mp3') }}",
  buttonSfxVolume: 0.9,
  countdownSfxVolume: 0.9,
  revealSfxVolume: 0.95,
};

/**
 * Prize list – extend or replace freely.
 * `image` can be a file path or a data URI.
 * `emoji` is shown as a fallback when the image fails to load.
 * `weight` controls relative probability (higher = more likely).
 */
const prizes = [
  {
    id: 'aeon',
    dbId: 1,
    name: 'AEON RM10 Gift Voucher',
    image: `${GIFT_IMAGE_BASE}/AEON RM 10 Gift Voucher_2x.webp`,
    emoji: '🛒',
    color: '#E8F5E9',
    weight: 12,
  },
  {
    id: 'br',
    dbId: 2,
    name: 'Baskin Robbins Voucher',
    image: `${GIFT_IMAGE_BASE}/Baskin Robbins Voucher_2x.webp`,
    emoji: '🍦',
    color: '#FFF3E0',
    weight: 12,
  },
  {
    id: 'lanyard',
    dbId: 8,
    name: 'iProperty Phone Lanyard',
    image: `${GIFT_IMAGE_BASE}/iProperty  Phone Lanyard_2x.webp`,
    emoji: '📱',
    color: '#E8F0FF',
    weight: 12,
  },
  {
    id: 'notebook',
    dbId: 4,
    name: 'iProperty Notebook',
    image: `${GIFT_IMAGE_BASE}/iProperty Notebook_2x.webp`,
    emoji: '📓',
    color: '#EDE7F6',
    weight: 12,
  },
  {
    id: 'fan',
    dbId: 3,
    name: 'Neck Fan',
    image: `${GIFT_IMAGE_BASE}/Neck Fan_2x.webp`,
    emoji: '💨',
    color: '#E3F2FD',
    weight: 13,
  },
  {
    id: 'kopi',
    dbId: 5,
    name: 'Towel',
    image: `${GIFT_IMAGE_BASE}/Towel 1_2x.webp`,
    emoji: '☕',
    color: '#FFF8E1',
    weight: 13,
  },
  {
    id: 'texas',
    dbId: 6,
    name: 'Texas Chicken RM5 Cash Voucher',
    image: `${GIFT_IMAGE_BASE}/Texas Chicken RM 5 Cash Voucher_2x.webp`,
    emoji: '🍗',
    color: '#FFF3E0',
    weight: 13,
  },
  {
    id: 'watsons',
    dbId: 7,
    name: 'Watsons RM10 Gift Voucher',
    image: `${GIFT_IMAGE_BASE}/Watsons RM 10 Gift Voucher _2x.webp`,
    emoji: '🧴',
    color: '#E8F5E9',
    weight: 13,
  },{
    id: 'duffel',
    dbId: 9,
    name: 'Mini Duffel Bag',
    image: `${GIFT_IMAGE_BASE}/Mini Duffel Bag 1_2x.webp`,
    emoji: '🎒',
    color: '#E8F5E9',
    weight: 13,
  },
];

// ─────────────────────────────────────────────
// ACTIVE PRIZES  (populated from API before each draw)
// ─────────────────────────────────────────────
let activePrizes   = [...prizes]; // in-stock only – used for winner selection
let displayPrizes  = [...prizes]; // all prizes – used for carousel display

/**
 * Apply stock levels embedded in the initial Blade response.
 */
function applyInitialStocks() {
  const stockMap = {};
  for (const item of INITIAL_STOCKS) {
    stockMap[item.id] = parseInt(item.stock_level, 10) || 0;
  }

  const filtered = prizes.filter(p => (stockMap[p.dbId] ?? 0) > 0);
  activePrizes  = filtered.length > 0 ? filtered : [...prizes];
  displayPrizes = [...prizes];
}

// ─────────────────────────────────────────────
// STATE
// ─────────────────────────────────────────────
const state = {
  currentScreen: 'start',
  shuffleTimer:   null,
  shuffleActive:  false,
  highlightIndex: 0,
  winnerPrize:    null,
  canTap:         false,
};

// ─────────────────────────────────────────────
// DOM helpers
// ─────────────────────────────────────────────
const $  = (id) => document.getElementById(id);
const $$ = (sel) => document.querySelectorAll(sel);

// ─────────────────────────────────────────────
// AUDIO (GLOBAL BGM)
// ─────────────────────────────────────────────
const bgmAudio = new Audio(CONFIG.bgmSrc);
bgmAudio.loop = true;
bgmAudio.preload = 'auto';
bgmAudio.volume = CONFIG.bgmVolume;

const buttonSfxAudio = new Audio(CONFIG.buttonSfxSrc);
buttonSfxAudio.preload = 'auto';
buttonSfxAudio.volume = CONFIG.buttonSfxVolume;

const countdownSfxAudio = new Audio(CONFIG.countdownSfxSrc);
countdownSfxAudio.preload = 'auto';
countdownSfxAudio.volume = CONFIG.countdownSfxVolume;

const revealSfxAudio = new Audio(CONFIG.revealSfxSrc);
revealSfxAudio.preload = 'auto';
revealSfxAudio.volume = CONFIG.revealSfxVolume;

let audioUnlocked = false;

function ensureBgmPlaying() {
  if (bgmAudio.paused) {
    bgmAudio.play().catch(() => {
      // Browser autoplay policy may still block until user gesture.
    });
  }
}

function playSfx(audio) {
  audio.currentTime = 0;
  audio.play().catch(() => {
    // Ignore blocked plays; next user gesture will unlock audio.
  });
}

function playButtonSfx() {
  playSfx(buttonSfxAudio);
}

function playCountdownSfx() {
  playSfx(countdownSfxAudio);
}

function playRevealSfx() {
  playSfx(revealSfxAudio);
}

function unlockAudioAndPlay() {
  if (audioUnlocked) return;
  audioUnlocked = true;

  // Prime SFX assets for more reliable immediate playback later.
  buttonSfxAudio.load();
  countdownSfxAudio.load();
  revealSfxAudio.load();

  ensureBgmPlaying();
}

function initGlobalAudio() {
  // First interaction unlocks and starts BGM; after that it stays across screens.
  document.addEventListener('pointerdown', unlockAudioAndPlay, { once: true });
  document.addEventListener('keydown', unlockAudioAndPlay, { once: true });

  // Try once on load for permissive browsers.
  ensureBgmPlaying();

  document.addEventListener('visibilitychange', () => {
    if (!document.hidden && audioUnlocked) {
      ensureBgmPlaying();
    }
  });
}

// ─────────────────────────────────────────────
// SCREEN TRANSITIONS
// ─────────────────────────────────────────────
function showScreen(id) {
  const current = document.querySelector('.screen.active');
  const next    = $(`screen-${id}`);
  if (!next || current === next) return;

  ensureBgmPlaying();

  if (current) {
    current.classList.add('exit');
    current.classList.remove('active');
    current.addEventListener('transitionend', () => {
      current.classList.remove('exit');
    }, { once: true });
  }

  // slight delay so exit animation is visible
  requestAnimationFrame(() => {
    requestAnimationFrame(() => {
      next.classList.add('active');
    });
  });

  state.currentScreen = id;
}

// ─────────────────────────────────────────────
// CAROUSEL SHUFFLE LOGIC
// ─────────────────────────────────────────────
const SLIDE_MS = 130; // duration must match CSS animation

/** Render a prize into the carousel card (no animation) */
function setCarouselCard(prize) {
  const imgWrap = $('carousel-img-wrap');
  const nameEl  = $('carousel-name');

  imgWrap.innerHTML = '';
  const img = document.createElement('img');
  img.src = prize.image;
  img.alt = prize.name;
  img.addEventListener('error', () => {
    const span = document.createElement('span');
    span.style.cssText = 'font-size:64px;line-height:1';
    span.textContent = prize.emoji;
    imgWrap.innerHTML = '';
    imgWrap.appendChild(span);
  });
  imgWrap.appendChild(img);
  nameEl.textContent = prize.name;
}

/**
 * Slide out current card, call onDone to swap content,
 * then slide new card in. Uses setTimeout — NOT animationend —
 * so it always completes regardless of interruption.
 */
function slideToNext(onDone) {
  const card = $('carousel-card');
  // Clear any lingering classes
  card.classList.remove('slide-in', 'slide-out', 'flash-out');
  $('carousel-viewport').classList.remove('winner');
  void card.offsetWidth; // force reflow

  card.classList.add('slide-out');
  setTimeout(() => {
    onDone();                          // swap content while hidden
    card.classList.remove('slide-out');
    void card.offsetWidth;             // force reflow
    card.classList.add('slide-in');
    setTimeout(() => {
      card.classList.remove('slide-in');
    }, SLIDE_MS);
  }, SLIDE_MS);
}

function startShuffle() {
  state.shuffleActive  = true;
  state.canTap         = true;
  state.highlightIndex = 0;

  // Show first card immediately, no animation
  setCarouselCard(displayPrizes[0]);

  doFastStep();
}

/** Fast shuffle: quick opacity flash — no slide (faster than SLIDE_MS) */
function doFastStep() {
  if (!state.shuffleActive) return;

  state.highlightIndex = (state.highlightIndex + 1) % displayPrizes.length;
  const next = displayPrizes[state.highlightIndex];
  const card = $('carousel-card');

  card.classList.add('flash-out');
  setTimeout(() => {
    setCarouselCard(next);
    card.classList.remove('flash-out');
    state.shuffleTimer = setTimeout(doFastStep, CONFIG.shuffleIntervalMs);
  }, 40);
}

function stopShuffle() {
  state.canTap        = false;
  state.shuffleActive = false;
  clearTimeout(state.shuffleTimer);

  // Deceleration: 4 slowing slides, then land on winner
  const delays  = [220, 360, 500, 650];
  let   step    = 0;

  function decelStep() {
    if (step < delays.length) {
      const delay = delays[step++];
      state.shuffleTimer = setTimeout(() => {
        state.highlightIndex = (state.highlightIndex + 1) % displayPrizes.length;
        slideToNext(() => setCarouselCard(displayPrizes[state.highlightIndex]));
        setTimeout(decelStep, SLIDE_MS * 2); // wait for slide to finish
      }, delay);
    } else {
      const winner      = pickWeightedRandom(activePrizes);
      state.winnerPrize = winner;

      state.shuffleTimer = setTimeout(() => {
        slideToNext(() => {
          setCarouselCard(winner);
          setTimeout(() => $('carousel-viewport').classList.add('winner'), SLIDE_MS + 20);
        });
        setTimeout(() => showResult(winner), SLIDE_MS * 2 + 800);
      }, 400);
    }
  }

  decelStep();
}

function onCardTap() {
  if (!state.shuffleActive) return;
  stopShuffle();
}

// ─────────────────────────────────────────────
// WEIGHTED RANDOM
// ─────────────────────────────────────────────
function pickWeightedRandom(items) {
  const totalWeight = items.reduce((s, i) => s + (i.weight ?? 1), 0);
  let rand = Math.random() * totalWeight;
  for (const item of items) {
    rand -= (item.weight ?? 1);
    if (rand <= 0) return item;
  }
  return items[items.length - 1];
}

// ─────────────────────────────────────────────
// RESULT SCREEN
// ─────────────────────────────────────────────
function showResult(prize) {
  playRevealSfx();

  const img = $('result-img');
  img.src = prize.image;
  img.alt = prize.name;
  img.onerror = function () {
    const span = document.createElement('span');
    span.style.cssText = 'font-size:80px;line-height:1';
    span.textContent = prize.emoji;
    img.replaceWith(span);
  };
  $('result-name').textContent = prize.name;

  spawnConfetti();
  showScreen('result');
}

// ─────────────────────────────────────────────
// CONFETTI
// ─────────────────────────────────────────────
function spawnConfetti() {
  const container = $('confetti-container');
  container.innerHTML = '';

  const colors = ['#FF6B6B','#FFD93D','#6BCB77','#4D96FF','#FF922B','#CC5DE8','#F06595'];

  for (let i = 0; i < CONFIG.confettiCount; i++) {
    const piece = document.createElement('div');
    piece.className = 'confetti-piece';
    piece.style.cssText = `
      left: ${Math.random() * 100}%;
      background: ${colors[Math.floor(Math.random() * colors.length)]};
      width: ${6 + Math.random() * 8}px;
      height: ${12 + Math.random() * 8}px;
      border-radius: 2px;
      animation-duration: ${3 + Math.random() * 2}s;
      animation-delay: ${Math.random() * 1.5}s;
    `;
    container.appendChild(piece);
  }

  // Clean up after animations
  setTimeout(() => { container.innerHTML = ''; }, 7000);
}

// ─────────────────────────────────────────────
// QR SCREEN
// ─────────────────────────────────────────────
function showQR(prize) {
  const prizeUrl  = `${CONFIG.qrClaimUrl}/${prize.dbId}`;
  const claimUrl  = encodeURIComponent(prizeUrl);
  const qrSrc     = `${CONFIG.qrBaseUrl}${claimUrl}`;

  const qrImg     = $('qr-image');
  const qrCanvas  = $('qr-canvas');

  // Try remote QR image first; fall back to inline canvas QR
  qrImg.style.display = 'block';
  qrCanvas.style.display = 'none';
  qrImg.src = qrSrc;
  qrImg.onerror = () => {
    qrImg.style.display = 'none';
    qrCanvas.style.display = 'block';
    drawCanvasQR(qrCanvas, prizeUrl);
  };

  showScreen('qr');
}

/**
 * Minimal QR-code-like placeholder drawn on canvas
 * (replace with a real QR library such as qrcode.js for production)
 */
function drawCanvasQR(canvas, text) {
  const size = 170;
  canvas.width  = size;
  canvas.height = size;
  const ctx = canvas.getContext('2d');

  // White background
  ctx.fillStyle = '#fff';
  ctx.fillRect(0, 0, size, size);

  // Deterministic-ish "QR-like" grid from text hash
  const hash = simpleHash(text);
  const cells = 21;
  const cell  = Math.floor(size / cells);

  ctx.fillStyle = '#111';
  for (let r = 0; r < cells; r++) {
    for (let c = 0; c < cells; c++) {
      const isCorner = isFinderPattern(r, c, cells);
      const bit = isCorner || ((hash >> ((r * cells + c) % 32)) & 1);
      if (bit) ctx.fillRect(c * cell, r * cell, cell - 1, cell - 1);
    }
  }

  // Label
  ctx.fillStyle = '#333';
  ctx.font = '9px sans-serif';
  ctx.textAlign = 'center';
  ctx.fillText('SCAN TO CLAIM', size / 2, size - 3);
}

function isFinderPattern(r, c, n) {
  const inCorner = (rr, cc) =>
    (rr <= 6 && cc <= 6) ||
    (rr <= 6 && cc >= n - 7) ||
    (rr >= n - 7 && cc <= 6);
  return inCorner(r, c);
}

function simpleHash(str) {
  let h = 0x811c9dc5;
  for (let i = 0; i < str.length; i++) {
    h ^= str.charCodeAt(i);
    h = (h * 0x01000193) >>> 0;
  }
  return h;
}

// ─────────────────────────────────────────────
// RESET
// ─────────────────────────────────────────────
function resetGame() {
  clearTimeout(state.shuffleTimer);
  state.shuffleActive  = false;
  state.canTap         = false;
  state.winnerPrize    = null;
  state.highlightIndex = 0;
  $('carousel-viewport').classList.remove('winner');
  $('carousel-card').classList.remove('slide-in', 'slide-out', 'winner', 'flash-out');
  showScreen('start');
}

// ─────────────────────────────────────────────
// COUNTDOWN
// ─────────────────────────────────────────────
function startCountdown(seconds, onDone) {
  const overlay = $('countdown-overlay');
  const numEl   = $('countdown-number');
  const steps   = [];
  for (let i = seconds; i >= 1; i--) steps.push(String(i));
  steps.push('GO!');

  let i = 0;

  function showStep() {
    // restart animation
    numEl.style.animation = 'none';
    void numEl.offsetWidth;
    numEl.textContent = steps[i];
    numEl.style.animation = 'countPop 0.4s cubic-bezier(.4,0,.2,1) both';

    i++;
    if (i < steps.length) {
      setTimeout(showStep, CONFIG.countdownStepMs);
    } else {
      // 'GO!' shown — wait briefly then finish
      setTimeout(() => {
        overlay.classList.add('hidden');
        setTimeout(onDone, 250);
      }, CONFIG.countdownGoHoldMs);
    }
  }

  overlay.classList.remove('hidden');
  playCountdownSfx();
  showStep();
}

// ─────────────────────────────────────────────
// BUTTON WIRING
// ─────────────────────────────────────────────
function initEventListeners() {
  $('btn-start').addEventListener('click', () => {
    playButtonSfx();
    showScreen('guide');
  });

  $('btn-ready').addEventListener('click', () => {
    playButtonSfx();
    startCountdown(3, () => {
      showScreen('shuffle');
      setTimeout(startShuffle, 300);
    });
  });

  // Tap the carousel card to stop the shuffle
  $('screen-shuffle').addEventListener('click', onCardTap);

  $('btn-continue').addEventListener('click', () => {
    playButtonSfx();
    showQR(state.winnerPrize);
  });

  $('btn-redraw').addEventListener('click', () => {
    playButtonSfx();
    applyInitialStocks();
    showScreen('shuffle');
    setTimeout(startShuffle, 300);
  });

  $('btn-finish').addEventListener('click', () => {
    playButtonSfx();
    resetGame();
  });
}

// ─────────────────────────────────────────────
// LOGO FALLBACK  (called from HTML onerror)
// ─────────────────────────────────────────────
/* global logoFallback */
window.logoFallback = function () {
  const span = document.createElement('span');
  span.className = 'logo-svg-fallback';
  span.innerHTML = `
    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
      <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
    </svg>
    iProperty
  `;
  return span;
};

// ─────────────────────────────────────────────
// INIT
// ─────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  applyInitialStocks();
  initGlobalAudio();
  initEventListeners();
});
  </script>
</body>
</html>
