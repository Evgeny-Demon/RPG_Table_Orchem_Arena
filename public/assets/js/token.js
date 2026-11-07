
const token = document.getElementById('token');
const map = token.parentElement;
const grid = map.querySelector('.grid');

const cellSize = 50;
const tokenSize = 40;
const padding = (cellSize - tokenSize) / 2;

// Перетаскивание мышью
token.addEventListener('mousedown', function (event) {
    event.preventDefault();

    const mapRect = map.getBoundingClientRect();

    // Запоминаем текущую позицию токена относительно карты
    const startLeft = token.offsetLeft;
    const startTop = token.offsetTop;

    // Смещение курсора относительно токена
    const offsetX = event.clientX - (mapRect.left + startLeft);
    const offsetY = event.clientY - (mapRect.top + startTop);

    token.style.cursor = 'grabbing';

    function onMouseMove(event) {
        // Положение токена относительно начала карты
        let x = event.clientX - mapRect.left - offsetX;
        let y = event.clientY - mapRect.top - offsetY;

        // Привязка к ближайшей клетке
        x = Math.round((x - padding) / cellSize) * cellSize + padding;
        y = Math.round((y - padding) / cellSize) * cellSize + padding;

        // Динамические границы сетки
        const maxX = grid.offsetWidth - tokenSize - padding;
        const maxY = grid.offsetHeight - tokenSize - padding;

        x = Math.max(padding, Math.min(x, maxX));
        y = Math.max(padding, Math.min(y, maxY));

        token.style.left = x + 'px';
        token.style.top = y + 'px';
    }

    function onMouseUp() {
        token.style.cursor = 'grab';

        document.removeEventListener('mousemove', onMouseMove);
        document.removeEventListener('mouseup', onMouseUp);
    }

    document.addEventListener('mousemove', onMouseMove);
    document.addEventListener('mouseup', onMouseUp);
});

// Движение токена стрелками клавиатуры
document.addEventListener('keydown', function (event) {
    const keys = ['ArrowUp', 'ArrowDown', 'ArrowLeft', 'ArrowRight'];

    if (!keys.includes(event.key)) return;

    event.preventDefault();

    let x = token.offsetLeft;
    let y = token.offsetTop;

    switch (event.key) {
        case 'ArrowUp':
            y -= cellSize;
            break;
        case 'ArrowDown':
            y += cellSize;
            break;
        case 'ArrowLeft':
            x -= cellSize;
            break;
        case 'ArrowRight':
            x += cellSize;
            break;
    }

    // Динамические границы сетки
    const maxX = grid.offsetWidth - tokenSize - padding;
    const maxY = grid.offsetHeight - tokenSize - padding;

    x = Math.max(padding, Math.min(x, maxX));
    y = Math.max(padding, Math.min(y, maxY));

    token.style.left = x + 'px';
    token.style.top = y + 'px';
});
