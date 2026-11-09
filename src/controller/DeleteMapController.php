<?php

namespace app\controller;

use app\core\Session;
use app\model\MapModel;

class DeleteMapController {

    private object $model;
    private object $session;

    public function __construct() {
        $this->model = new MapModel;
        $this->session = new Session();
    }

    public function handle() : void {
        //ищем компанию
        $companyName = $this->session->get()['active_compain_name'];
        $path = VIEW_COMPAIN_DIR . '/' . $companyName . '/admin-panel-view/admin.php';
        require_once $path;

        //удаляем файл сетки и файл карты
        $this->model->delMap();

         //подключаем список карт
        require_once dirname(__DIR__) . '/view/maplist.php';
    } 
    

}