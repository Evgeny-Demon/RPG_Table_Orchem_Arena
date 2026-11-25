<?php

namespace app\controller;
use app\contracts\ControllerInterface;

use app\model\ChatModel;
use app\core\Request;


class ChatController implements ControllerInterface {

    private object $model;
    private object $request;

    public function __construct() {
        $this->model = new ChatModel();
        $this->request = new Request();
    }

    public function handle(): void {
        if($this->request->getRequestKey('action_id') === 'get-history-chat') {
            //достаем из БД чат и отправляем веб-сокетами наверх
            $row = $this->model->SelectChatFromDB();
            $this->model->sendWS($row);  
        }

        if($this->request->getRequestKey('action_id') === 'write-chat') {
            $this->model->writeChat();
        }
    }

}