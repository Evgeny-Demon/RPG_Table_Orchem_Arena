<?php

namespace app\controller;

use app\core\Session;

class LogoutCompainController {

    private object $session;

    public function __construct() {
        $this->session = new Session();
    }

    public function handle() : void {
        if ($this->session->get()['role_id'] === 1) { 
            //убираем компанию из сессии из статуса активна
            $this->session->deleteElement('active_compain_name');
            var_dump($_SESSION);
        require_once dirname(__DIR__) . '/view/compainAdmin.php';
        require_once dirname(__DIR__) . '/view/compainlist.php' ;
         }
         if ($this->session->get()['role_id'] === 2) { 
        require_once dirname(__DIR__) . '/view/compainPlayer.php';
        require_once dirname(__DIR__) . '/view/compainlist.php' ;
         } 
    }
}