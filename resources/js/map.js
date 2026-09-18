const mapPage = document.getElementById('map-page');

if (mapPage) {
    const toggle = mapPage.querySelector('.map-view-toggle');
    const locations = mapPage.querySelectorAll('[data-location]');

    function selectLocation(id) {
        locations.forEach(location => {
            const selected = location.dataset.location === id;
            location.classList.toggle('is-selected', selected);
            if (location.tagName === 'BUTTON') {
                location.setAttribute('aria-pressed', String(selected));
            }
        });
    }

    locations.forEach(location => {
        location.addEventListener('click', () => selectLocation(location.dataset.location));
        if (location.tagName === 'A') {
            location.addEventListener('focus', () => selectLocation(location.dataset.location));
        }
    });

    toggle.addEventListener('click', () => {
        const view = mapPage.dataset.view === 'journey' ? 'rewards' : 'journey';
        mapPage.dataset.view = view;
        mapPage.querySelectorAll('[data-map-layer]').forEach(layer => {
            layer.hidden = layer.dataset.mapLayer !== view;
        });
        mapPage.querySelectorAll('[data-map-panel]').forEach(panel => {
            panel.hidden = panel.dataset.mapPanel !== view;
        });
        toggle.setAttribute('aria-label', view === 'journey' ? 'Show more rewards' : 'Show map legend');
        selectLocation(null);
    });
}
