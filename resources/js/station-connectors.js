// Responsive SVG connectors between station items
// Draws smooth cubic Bezier curves connecting the image circles
// Runs only if .station-selection-container exists

(function () {
  function qs(selector, scope = document) { return scope.querySelector(selector); }
  function qsa(selector, scope = document) { return Array.from(scope.querySelectorAll(selector)); }

  const container = qs('.station-selection-container');
  if (!container) return; // only on dashboard page

  // Create overlay host and SVG once
  const overlay = document.createElement('div');
  overlay.className = 'station-connectors';
  const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
  svg.setAttribute('class', 'station-connectors__svg');
  svg.setAttribute('aria-hidden', 'true');
  overlay.appendChild(svg);
  container.appendChild(overlay);

  // Define gradient + glow once
  const defs = document.createElementNS('http://www.w3.org/2000/svg', 'defs');

  const gradient = document.createElementNS('http://www.w3.org/2000/svg', 'linearGradient');
  gradient.setAttribute('id', 'connectorBlue');
  gradient.setAttribute('x1', '0');
  gradient.setAttribute('y1', '0');
  gradient.setAttribute('x2', '1');
  gradient.setAttribute('y2', '0');

  const s1 = document.createElementNS('http://www.w3.org/2000/svg', 'stop');
  s1.setAttribute('offset', '0%');
  s1.setAttribute('stop-color', '#0008FF');
  const s2 = document.createElementNS('http://www.w3.org/2000/svg', 'stop');
  s2.setAttribute('offset', '50%');
  s2.setAttribute('stop-color', '#1E00FF');
  const s3 = document.createElementNS('http://www.w3.org/2000/svg', 'stop');
  s3.setAttribute('offset', '100%');
  s3.setAttribute('stop-color', '#0051FF');
  gradient.appendChild(s1); gradient.appendChild(s2); gradient.appendChild(s3);

  const filter = document.createElementNS('http://www.w3.org/2000/svg', 'filter');
  filter.setAttribute('id', 'glow');
  filter.setAttribute('x', '-20%');
  filter.setAttribute('y', '-20%');
  filter.setAttribute('width', '140%');
  filter.setAttribute('height', '140%');
  const blur = document.createElementNS('http://www.w3.org/2000/svg', 'feGaussianBlur');
  blur.setAttribute('stdDeviation', '3');
  blur.setAttribute('result', 'blur');
  const merge = document.createElementNS('http://www.w3.org/2000/svg', 'feMerge');
  const m1 = document.createElementNS('http://www.w3.org/2000/svg', 'feMergeNode');
  m1.setAttribute('in', 'blur');
  const m2 = document.createElementNS('http://www.w3.org/2000/svg', 'feMergeNode');
  m2.setAttribute('in', 'SourceGraphic');
  merge.appendChild(m1); merge.appendChild(m2);
  filter.appendChild(blur); filter.appendChild(merge);

  defs.appendChild(gradient);
  defs.appendChild(filter);
  svg.appendChild(defs);

  function pathD(start, end) {
    // start, end: {x, y}
    const dx = end.x - start.x;
    // control points pull along x direction for smooth S
    const c1 = { x: start.x + dx * 0.35, y: start.y };
    const c2 = { x: end.x - dx * 0.35, y: end.y };
    return `M ${start.x},${start.y} C ${c1.x},${c1.y} ${c2.x},${c2.y} ${end.x},${end.y}`;
  }

  function draw() {
    const rect = container.getBoundingClientRect();
    const width = Math.ceil(rect.width);
    const height = Math.ceil(container.scrollHeight); // content height including gaps

    svg.setAttribute('width', width);
    svg.setAttribute('height', height);
    svg.setAttribute('viewBox', `0 0 ${width} ${height}`);

    // Clear previous paths
    qsa('path.station-connector-path', svg).forEach(p => p.remove());

    // Collect targets
    const items = qsa('.station-custom-btn', container);
    const circles = items.map(item => qs('.station-image-container', item)).filter(Boolean);

    for (let i = 0; i < circles.length - 1; i++) {
      const a = circles[i].getBoundingClientRect();
      const b = circles[i + 1].getBoundingClientRect();

      const radiusA = a.width / 2;
      const radiusB = b.width / 2;

      const centerA = { x: a.left - rect.left + radiusA, y: a.top - rect.top + radiusA };
      const centerB = { x: b.left - rect.left + radiusB, y: b.top - rect.top + radiusB };

      // Start/end on the horizontal sides facing each other
      const start = { ...centerA };
      const end = { ...centerB };
      if (centerA.x <= centerB.x) {
        start.x += radiusA; // right edge of A
        end.x -= radiusB;   // left edge of B
      } else {
        start.x -= radiusA; // left edge of A
        end.x += radiusB;   // right edge of B
      }

      const d = pathD(start, end);

      // Primary path
      const p1 = document.createElementNS('http://www.w3.org/2000/svg', 'path');
      p1.setAttribute('class', 'station-connector-path');
      p1.setAttribute('d', d);
      p1.setAttribute('fill', 'none');
      p1.setAttribute('stroke', 'url(#connectorBlue)');
      p1.setAttribute('stroke-width', '4');
      p1.setAttribute('filter', 'url(#glow)');
      p1.setAttribute('stroke-linecap', 'round');
      svg.appendChild(p1);

      // A subtle parallel stroke for depth
      const p2 = document.createElementNS('http://www.w3.org/2000/svg', 'path');
      p2.setAttribute('class', 'station-connector-path');
      p2.setAttribute('d', d);
      p2.setAttribute('fill', 'none');
      p2.setAttribute('stroke', 'url(#connectorBlue)');
      p2.setAttribute('stroke-width', '2');
      p2.setAttribute('opacity', '0.6');
      p2.setAttribute('filter', 'url(#glow)');
      p2.setAttribute('stroke-linecap', 'round');
      svg.appendChild(p2);
    }
  }

  // Debounced resize for performance
  let resizeTimer;
  function onResize() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(draw, 100);
  }

  // Draw after fonts/images settle to ensure sizes are correct
  window.addEventListener('load', draw);
  window.addEventListener('resize', onResize);
  // Also draw when images inside circles load
  qsa('.station-image-container img', container).forEach(img => {
    if (img.complete) return; img.addEventListener('load', draw, { once: true });
  });

})();
