<?php

namespace app\controller;

use app\core\Request;
use app\core\Session;

class GetMapController {

    private object $session;
    private object $request;

    public function __construct() {
        $this->session = new Session();
        $this->request = new Request();
    }

    public function handle() : void {
        //показываем картинку
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    if ($path === '/map-image') {
        $this->showImage();
        return;
    }
        
        //подключаем сетку
        $companyName = $this->session->get()['active_compain_name'];
        $mapName = $this->request->getRequestGet()['name'];
        $path = \VIEW_COMPAIN_DIR . $companyName . '/maps-view/' . $mapName;
        require_once $path;
    }


    private function showImage(): void {

        $company = $this->session->get()['active_compain_name'];
        $fileName = basename($_GET['name']);

        $path = \COMPAIN_DIR . '/' . $company . '/maps/' . $fileName;

        $mimeType = mime_content_type($path);

        header('Content-Type: ' . $mimeType);

        readfile($path);
        exit;
    }
}