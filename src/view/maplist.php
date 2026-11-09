<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/maplist.css">
    <title>ORCHEM — Список карт</title>
</head>

<body>

    <div class="container">

    <h1>Список карт</h1>

    <?php //вытаскиваем список карт из активной компании
        $maps = \VIEW_COMPAIN_DIR . $this->session->get()['active_compain_name'] . '/maps-view/';
        $count = 0;
        if(is_dir($maps)) {
            foreach (scandir($maps) as $map) {
                if($map === '..' || $map === '.') {
                    continue;
                };

                echo <<<HTML
                         <a href="/get-map?name={$map}">{$map}</a>       
HTML;
                $count++;
            }
        }
    ?>
</body>
</html>
