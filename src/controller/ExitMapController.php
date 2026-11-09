<?php

namespace app\controller;

use app\core\Session;

class ExitMapController {
    private object $session;
    
    public function __construct() {
        $this->session = new Session();
    }

    public function handle() : void {
        //ищем компанию
        $companyName = $this->session->get()['active_compain_name'];
        $path = VIEW_COMPAIN_DIR . '/' . $companyName . '/admin-panel-view/admin.php';
        require_once $path;
        //подключаем список карт
        require_once dirname(__DIR__) . '/view/maplist.php';
    }
}