<?php

namespace app\controller;

use app\core\Session;
use app\model\MapModel;

class CreateMapController {

    private object $model;
    private object $session;

    public function __construct() {
        $this->model = new MapModel();
        $this->session = new Session();
    }

    public function handle(): void {

        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if ($path === '/map-image') {
            $this->showImage();
            return;
        }

        $this->model->createMap();

        $active_game_key = $this->session->get()['active_compain_name'];

        require_once \COMPAIN_DIR . '/' . $active_game_key . '/maps-view/Карта1.php';
    }

    public function showImage(): void {

        $company = $this->session->get()['active_compain_name'];
        $fileName = basename($_GET['name']);

        $path = \COMPAIN_DIR . '/' . $company . '/maps/' . $fileName;

        $mimeType = mime_content_type($path);

        header('Content-Type: ' . $mimeType);

        readfile($path);
        exit;
    }
}
