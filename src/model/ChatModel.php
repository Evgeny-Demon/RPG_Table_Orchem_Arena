<?php

namespace app\model;

use app\core\Session;
use app\core\Database;
use app\core\Request;

class ChatModel {
    private object $request;
    private object $session;
    private object $db;
    private object $centrifugo;

    public function __construct() {
        $this->request = new Request();
        $this->session = new Session();
        $this->db = new Database();
        $this->db = $this->db->connectDataBase();
        $this->centrifugo = new \phpcent\Client("http://orchem-centrifugo:8000/api");
        $this->centrifugo->setApiKey("orchem_dev_api_key");
    }

    public function handle () : void {
        
        $text = $this->request->getRequestKey('write-chat');
        $login = $this->session->get()['login'];
        $active_compain_id = $this->session->get()['active_compain_id'];
        //пока что запись пишет логин - потом нужно будет добавить имя персонажа
        $sqlwriteChat = "UPDATE table_arena.compains SET chat = COALESCE(chat, '') || E'\n' || :login || ' ' || :text where game_id = :active_compain_id";
        $stmt = $this->db->prepare($sqlwriteChat);
        $stmt->execute([':text' => $text, ':active_compain_id' => $active_compain_id, ':login' => $login]);
        $row = $this->SelectChatFromDB();
        $this->sendWS($row);
        $this->sendJsonResponse();
    }

            //веб-сокеты отправка данных в канал центрифуги и ответ джейсона на фронтенд
    public function SelectChatFromDB() : array {
        $active_compain_id = $this->session->get()['active_compain_id'];
        $selectSqlChat = "SELECT chat FROM table_arena.compains where game_id = :active_compain_id";
        $stmt = $this->db->prepare($selectSqlChat);
        $stmt->execute([':active_compain_id' => $active_compain_id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC); 
        return $row;
    }

    public function sendWS(array $row) : void {
        $active_compain_id = $this->session->get()['active_compain_id'];
        // Отправляем в Centrifugo ассоциативный массив ['text' => 'весь длинный чат']
        $this->centrifugo->publish($active_compain_id, ['text' => $row['chat'] ?? '']);
    }

    private function sendJsonResponse() : void {
        header('Content-type: application/json; charset=utf-8');
        echo json_encode(['status' => 'ok']);
    }
}