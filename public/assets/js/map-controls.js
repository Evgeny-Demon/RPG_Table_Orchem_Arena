
(() => {
    const mapElement = document.querySelector('.map');
    const background = document.getElementById('mapBackground');

    if (!mapElement || !background) {
        console.error('Map or background image not found');
        return;
    }

    let x = 0;
    let y = 0;
    let scale = 1;

    let dragging = false;
    let startX = 0;
    let startY = 0;
    let initialX = 0;
    let initialY = 0;

    function updateMap() {
        background.style.transform =
            `translate(${x}px, ${y}px) scale(${scale})`;
    }

    mapElement.addEventListener('pointerdown', (event) => {
        if (event.button !== 0) return;

        if (event.target.closest('.token')) return;

        dragging = true;
        startX = event.clientX;
        startY = event.clientY;
        initialX = x;
        initialY = y;

        mapElement.setPointerCapture(event.pointerId);
        background.style.cursor = 'grabbing';
    });

    mapElement.addEventListener('pointermove', (event) => {
        if (!dragging) return;

        x = initialX + event.clientX - startX;
        y = initialY + event.clientY - startY;

        updateMap();
    });

    function stopDragging() {
        dragging = false;
        background.style.cursor = 'grab';
    }

    mapElement.addEventListener('pointerup', stopDragging);
    mapElement.addEventListener('pointercancel', stopDragging);

    mapElement.addEventListener('wheel', (event) => {
        event.preventDefault();

        const factor = event.deltaY < 0 ? 1.1 : 0.9;
        scale = Math.max(0.5, Math.min(3, scale * factor));

        updateMap();
    }, { passive: false });
})();
