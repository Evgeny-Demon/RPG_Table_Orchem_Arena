<?php

namespace app\controller;
use app\contracts\ControllerInterface;

use app\model\AuthorizationModel;
use app\core\Database;
use app\core\Request;
use app\core\Session;

class ConnectPlayerController implements ControllerInterface {
    private object $model;
    private object $request;
    private Session $session;
    
    public function __construct() {
        $this->model = new AuthorizationModel(new Database());
        $this->request = new Request(); 
        $this->session = new Session();
    }

    public function handle(): void {
        $token = $this->request->getRequestGet()['connect_code'];
        

        if($this->model->connectPlayer((string)$token)) {
            
            require_once dirname(__DIR__) . '/view/maplist.php';
            require_once COMPAIN_DIR . '/' . $this->session->get()['active_compain_name'] . '/chat-view/chat.php';

            
        } else {
            
            require_once dirname(__DIR__) . '/view/playerAutorizationCompain.php';                          
        }
    }
}
