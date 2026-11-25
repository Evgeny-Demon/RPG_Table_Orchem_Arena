<?php

namespace app\controller;


use app\core\Session;
use app\core\Request;

//POST
use app\controller\RegisterController;
use app\controller\AuthenticationController;
use app\controller\CompainCreateController;
use app\controller\CompainDelController;
use app\controller\DeleteMapController;
use app\controller\CreateCompainTokenController;

//контролллер для аджакс запросов чата
use app\controller\ChatController;

//GET
use app\controller\DashboardMasterCompainController;
use app\controller\CreateMapController;
use app\controller\LogoutCompainController;
use app\controller\GetMapController;
use app\controller\ExitMapController;
use app\controller\GetCompainTokenController;




class Router {

    public function __construct() {

        $this->request = new Request();
        $this->requestID = [
        'POST' => [
            'register' => RegisterController::class,
            'login' => AuthenticationController::class,
            'createcompain' => CompainCreateController::class, //создание компании
            'delcompain' => CompainDelController::class, //удаление компании
            'create-map' => CreateMapController::class, //создание карты
            'delete-map' => DeleteMapController::class, //удалление 
            'write-chat' => ChatController::class, //обработка ajax запроса с фронтенда
            'get-history-chat' => ChatController::class, //аджакс запрос на поднятие истории чата
        ],
        'GET' => [
            '/exit' => LogoutController::class,
            '/exit-compain' => LogoutCompainController::class,
            '/company/'=> DashboardMasterCompainController::class, //переход в админку мастера
            '/map-image' => GetMapController::class, //вспомогательный процесс контроллера для получения фоновой картинки для карты боя
            '/get-map' => GetMapController::class, //переход на карту боя
            '/exit-map' => ExitMapController::class,
             '/get-active-token' => GetCompainTokenController::class, //копирование токена компании мастером из сессии для рассылки игрокам и авторизации в компании
             '/connect_player_code' => ConnectPlayerController::class //подключение игрока по токену компании
        ]
    ];
    }
    private array $getRequestID;
    private object $request; 
    private array $requestID;
    private object $session;
    

    public function route():void {

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