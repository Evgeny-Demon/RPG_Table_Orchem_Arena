<?php

namespace app\controller;

use app\core\Request;
use app\core\Session;
use app\model\ChatModel;

class DashboardMasterCompainController {

    private object $request;
    private object $session;
    private object $chatModel;

    public function __construct() {
    $this->request = new Request();
    $this->session = new Session();
    $this->chatModel = new ChatModel();
    }

    //ВНИМАНИЕ - КОНТРОЛЛЕР ОБРАБАТЫВАЕТ ДВА ВИДА ЗАПРОСОВ
    public function handle(): void {
        if($this->request->isGet()) {
        //id компании из гет запросап
        $gameId = $this->request->getParam('game_id') ?? null;

        //берем ключ гет запроса и по нему подключаем страницу
        //по ключу мы находим компанию в сесссии и формируем ключ для подключения страницы 
        $keyGetRequest = key($this->request->getRequestGet());
        //ключ это название компании
        $key = $this->session->get()["companies"][$keyGetRequest]['name'];
        
        
        
        require_once dirname(__DIR__) . '/compains' . '/' . $key . '/admin-panel-view/admin.php';

        //по этому же ключу создаем запись о том, что компания активна в сессии чтобы потом работать с маршрутами компании и самой компанией
        $active_compain_name = $key;
        $this->session->set('active_compain_name', $key);
        $this->session->set('active_compain_id', $gameId);

        //подключаем список карт
        require_once dirname(__DIR__) . '/view/maplist.php';
        }

               //подключаем чат
        require_once COMPAIN_DIR . '/' . $this->session->get()['active_compain_name'] . '/chat-view/chat.php';
                //и сразу после подключения чата на JS фронте мы генерим аджакс пост запрос который летит в этот же контроллер по новому маршруту
        if($this->request->isPost()) {
            //достаем из БД чат и отправляем веб-сокетами наверх
            $row = $this->chatModel->SelectChatFromDB();
            $this->chatModel->sendWS($row);
            
        }

    }
}
