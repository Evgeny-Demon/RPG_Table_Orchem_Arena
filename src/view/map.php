
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orchem Arena</title>

    <link rel="stylesheet" href="/assets/css/map.css">

    <script src="/assets/js/token.js" defer></script>
    <script src="/assets/js/map-controls.js" defer></script>
</head>
<body>
    <?php
    $columns = 30; //ширина
    $rows = 15; //высота
    $cellSize = 50; 
    $totalCells = $columns * $rows;
    ?>

    <div class="map">
        <img
            src="/assets/images/arena1.jpg"
            class="map-background"
            id="mapBackground"
            alt="Игровая карта"
            draggable="false"
        >

        <div
    class="grid"
    style="
        grid-template-columns: repeat(<?= $columns ?>, 50px);
        grid-template-rows: repeat(<?= $rows ?>, 50px);
    "
>
            <?php for ($i = 0; $i < $totalCells; $i++): ?>
                <div class="cell"></div>
            <?php endfor; ?>
        </div>

        <div class="token" id="token"></div>
    </div>
</body>
</html>
