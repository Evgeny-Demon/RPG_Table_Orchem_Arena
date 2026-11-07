<?php

namespace app\controller;

use app\model\CompainModel;
use app\model\AuthorizationModel;
use app\core\Session;
use app\core\Database;

class CompainCreateController {

    private object $compainModel;
    private object $authorizationModel;
    private object $session;
    private object $database;

    public function __construct() {
        $this->database = new Database();
        $this->compainModel = new CompainModel($this->database);
        $this->authorizationModel = new AuthorizationModel($this->database);
        $this->session = new Session();
    }

    public function handle() {
        $this->compainModel->createCompain();
        $this->authorizationModel->LoadMasterCompain();
        var_dump($_SESSION);
        require_once dirname(__DIR__) . '/view/compainAdmin.php';
        require_once dirname(__DIR__) . '/view/compainlist.php' ; 

        //тепероь нужно прикрутить к сессииай ди мастера который создал игру чтобьы только он мог удалять игру которую создал
}
}