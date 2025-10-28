import './bootstrap';
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

import '../sass/app.scss';
import './slider.js'
import './station-connectors.js'

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
