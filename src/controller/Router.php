<?php

namespace app\controller;


use app\core\Session;
use app\core\Request;
use app\controller\RegisterController;
use app\controller\AuthenticationController;
use app\controller\CompainCreateController;
use app\controller\CompainDelController;

use app\controller\DashboardMasterCompainController;
use app\controller\CreateMapController;


class Router {

    public function __construct() {

        $this->request = new Request();
        $this->requestID = [
        'POST' => [
            'register' => RegisterController::class,
            'login' => AuthenticationController::class,
            'createcompain' => CompainCreateController::class,
            'delcompain' => CompainDelController::class
        ],
        'GET' => [
            '/exit' => LogoutController::class,
            '/company/'=> DashboardMasterCompainController::class,
            '/create-map' => CreateMapController::class
        ]
    ];
    }
    private array $getRequestID;
    private object $request; 
    private array $requestID;
    private object $session;
    

    public function route() {

        if ($this->request->isPost()) {
            foreach($this->requestID['POST'] as $key => $value) {
                if($key === $this->request->getRequestKey('action_id')) {
                    $controller = new $value ();
                    $controller->handle();
                    break;
                }   
            }
        }

        // Если зашли на главную и это ОБЫЧНЫЙ просмотр (не отправка формы)
        if ($_SERVER['REQUEST_URI'] === '/' && !($this->request->isPost())) {
            require_once dirname(__DIR__) . '/view/main.php';
            return;
        }

        if ($this->request->isGet()) {
            $key = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $controller = new $this->requestID['GET'][$key](); 
            $this->request->getPath();
            $controller->handle();
        }


    }


}