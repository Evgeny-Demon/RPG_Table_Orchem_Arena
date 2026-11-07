<?php

namespace app\controller;

use app\core\Session;

class LogoutController {

    private object $session;
    public function __construct() {
        $this->session = new Session();
    }
    
    public function handle() : void {
        $this->session = new Session();
        $this->session->clear();
        
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
        require_once dirname(__DIR__) . '/view/main.php';
    }
}