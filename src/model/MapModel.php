<?php

namespace app\model;

use app\core\Request;
use app\core\Session;

class MapModel {

    private object $request;
    private object $session;

    public function __construct() {
        $this->request = new Request();
        $this->session = new Session();
    }

    //создание карты -------------
    private function setFile() : void {
    
        if (!isset($_FILES['file'])) {
            return;
        }
        if ($_FILES['file']['size'] > \MAX_FILE_SIZE) {
            echo "слишком большой файл";
            return;
        }
        if (!in_array($_FILES['file']['type'], \ALLOWED_FILE_TYPES)) {
            echo "неверный тип файла";
            return;
        }
        $path = \COMPAIN_DIR . '/' . $this->session->get()['active_compain_name'] . '/' . 'maps/' . basename($_FILES['file']['name']);
        $tmp_name = $_FILES['file']['tmp_name'];
        move_uploaded_file($tmp_name, $path);
    }

    public function createMap() : void {
        $this->setFile();

        $path = \COMPAIN_DIR . '/' . $this->session->get()['active_compain_name'] . '/' . 'maps-view/' . $this->request->getRequestKey('name') . '.php'; 
        $fileName = basename($_FILES['file']['name']);
        $urlPath = \VIEW_COMPAIN_DIR . '/' . $this->session->get()['active_compain_name'] . '/' . 'maps/' . $fileName;
        $nameMap = $this->request->getRequestPost()['name'];
        

        //параметры сетки бояч
        $columns = $this->request->getRequestKey('columns'); //ширина
        $rows = $this->request->getRequestKey('rows'); //высота
        $cellSize = 50; 
        $totalCells = $columns * $rows;
        $stringTotalCells = '';
        //генерация ячеек сетки боя
        for ($i = 0; $i < $totalCells; $i++) {
                $stringTotalCells .= '<div class="cell"></div>';
        }
        //генерация кнопки удаления карты
        $delButtonContent = <<<BUTTON
        <form action="/delete-map" method="POST">

        <input type="hidden" name="action_id" value="delete-map">

        <input type="hidden" name="map_file" value="{$nameMap}">

        <input type="hidden" name="image_file" value="{$fileName}">

        <button type="submit">Удалить карту</button>

        </form>
BUTTON;
        //генерация страницы по параметрам
        $content = <<<HTML
        <!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orchem Arena map</title>

    <link rel="stylesheet" href="/assets/css/map.css">

    <script src="/assets/js/token.js" defer></script>
    <script src="/assets/js/map-controls.js" defer></script>
</head>
<body>
    {$delButtonContent}
    <a href='/exit-map'>выйти из карты</a>
    <div class="map">
        <img src="/map-image?name={$fileName}"
        class="map-background"
        id="mapBackground"
        alt="Игровая карта"
        draggable="false">

        <div
    class="grid"
    style="
        grid-template-columns: repeat({$columns}, 50px);
        grid-template-rows: repeat({$rows}, 50px);
    "
        >
         {$stringTotalCells}
        </div>

        <div class="token" id="token"></div>
    </div>
</body>
</html>
HTML;
        file_put_contents($path, $content);

    }


    //удаление карты
    public function delMap(): void {
        $keyForMap = $this->request->getRequestKey('image_file');
        $pathImg = COMPAIN_DIR . '/' . $this->session->get()['active_compain_name'] . '/maps/' . $keyForMap;
        unlink($pathImg);

        $keyForHTML = $this->request->getRequestKey('map_file');
        $pathHTML = COMPAIN_DIR . '/' . $this->session->get()['active_compain_name'] . '/maps-view/' . $keyForHTML . '.php';
        unlink($pathHTML);
    }

}