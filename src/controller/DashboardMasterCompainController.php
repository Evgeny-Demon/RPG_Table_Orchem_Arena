<?php

namespace app\controller;

use app\core\Request;
use app\core\Session;

class DashboardMasterCompainController {

    private object $request;
    private object $session;

    public function __construct() {
    $this->request = new Request();
    $this->session = new Session();
    }

    public function handle(): void {
        //берем ключ гет запроса и по нему подключаем страницу
        //по ключу мы находим компанию в сесссии и формируем ключ для подключения страницы 
        $keyGetRequest = key($this->request->getRequestGet());
        //ключ это название компании
        $key = $this->session->get()["companies"][$keyGetRequest]['name'];
        require_once dirname(__DIR__) . '/compains' . '/' . $key . '/admin-panel-view/admin.php';
        
    }
}
