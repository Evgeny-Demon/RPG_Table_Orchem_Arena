<?php

namespace app\controller;

use app\model\RegisterModel;
use app\core\Database;

class RegisterController {
    
private object $model;
private object $database;

public function __construct() {
     $this->database = new Database();
        $this->model = new RegisterModel($this->database);
}

public function handle() {
    $this->model->setUser();
    require_once dirname(__DIR__) . '/view/main.php';
}
}