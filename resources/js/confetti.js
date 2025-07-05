/**
 * Displays a confetti effect on the screen.
 * This function is globally available as `window.displayConfetti`.
 *
 * @param {object} options - Optional configuration for the confetti effect.
 * @param {number} options.particleCount - The number of confetti particles to launch.
 * @param {number} options.spread - How far the confetti will spread (in degrees).
 * @param {object} options.origin - The point from which the confetti will launch.
 * @param {number} options.origin.y - The y-coordinate of the origin (0.0 to 1.0).
 *
 * Example usage:
 *
 * // With default settings
 * window.displayConfetti();
 *
 * // With custom settings
 * window.displayConfetti({
 *   particleCount: 200,
 *   spread: 180,
 *   origin: { y: 0.5 }
 * });
 */
window.displayConfetti = function(options = {}) {
    const defaults = {
        particleCount: 100,
        spread: 70,
        origin: { y: 0.6 }
    };

    const settings = { ...defaults, ...options };

    const confettiCanvas = document.createElement('canvas');
    confettiCanvas.style.position = 'fixed';
    confettiCanvas.style.top = 0;
    confettiCanvas.style.left = 0;
    confettiCanvas.style.width = '100%';
    confettiCanvas.style.height = '100%';
    confettiCanvas.style.pointerEvents = 'none';
    confettiCanvas.style.zIndex = 9999;
    document.body.appendChild(confettiCanvas);

    const myConfetti = confetti.create(confettiCanvas, {
        resize: true,
        useWorker: true
    });

    myConfetti(settings);

    setTimeout(() => {
        document.body.removeChild(confettiCanvas);
    }, 5000);
}
