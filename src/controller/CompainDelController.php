<?php

namespace app\controller;
use app\model\CompainModel;
use app\model\AuthorizationModel;
use app\core\Session;
use app\core\Database;

class CompainDelController {

private object $model;
private object $session;
private object $database;
private object $authorizationModel;

    public function __construct() {
        $this->database = new Database();
        $this->model = new CompainModel($this->database);
        $this->authorizationModel = new AuthorizationModel($this->database);
        $this->session = new Session();
    }

    public function handle() {
        $this->model->delCompain();
        $this->authorizationModel->LoadMasterCompain();
        var_dump($_SESSION);
        require_once dirname(__DIR__) . '/view/compainAdmin.php';
        require_once dirname(__DIR__) . '/view/compainlist.php' ;
    }

}