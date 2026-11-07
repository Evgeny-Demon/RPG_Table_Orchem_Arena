<?php

namespace app\controller;

use app\model\AuthenticationModel;
use app\model\AuthorizationModel;
use app\core\Database;
use app\core\Session;

class AuthenticationController {
    
private object $authenticationModel;
private object $authorizationModel;
private object $database;
private object $session;

public function __construct() {
     $this->database = new Database();
        $this->authenticationModel = new AuthenticationModel($this->database);
        $this->authorizationModel = new AuthorizationModel($this->database);
        $this->session = new Session();
}

public function handle() {
    
    if($this->authenticationModel->authenticat()) {
        $this->authorizationModel->getAuthorization();
         $this->authorizationModel->LoadMasterCompain();

        if ($this->session->get()['role_id'] === 1) { 
            var_dump($_SESSION);
        require_once dirname(__DIR__) . '/view/compainAdmin.php';
        require_once dirname(__DIR__) . '/view/compainlist.php' ;
         }
         if ($this->session->get()['role_id'] === 2) { 
        require_once dirname(__DIR__) . '/view/compainPlayer.php';
        require_once dirname(__DIR__) . '/view/compainlist.php' ;
         }

    } else {
        require_once dirname(__DIR__) . '/view/main.php';
    }
}
}