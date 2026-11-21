import './bootstrap';
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

document.addEventListener('show.bs.modal', ({ target: modal, defaultPrevented }) => {
    // Keep the modal and its Bootstrap backdrop in the same stacking context.
    if (!defaultPrevented && modal.matches('.modal') && modal.parentElement !== document.body) {
        document.body.append(modal);
    }
});

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

import '../sass/app.scss';
import './slider.js'


// import GIF.js as a global
import GIF from 'gif.js';
// Load gif.js web worker via Vite to avoid manual file copying
import gifWorker from 'gif.js/dist/gif.worker.js?url';
// Configure default workerScript for gif.js
GIF.defaultOptions = {
  ...GIF.defaultOptions,
  workerScript: gifWorker
};
// Expose GIF globally
window.GIF = GIF;
