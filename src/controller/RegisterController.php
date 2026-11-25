<?php

namespace app\controller;
use app\contracts\ControllerInterface;

use app\model\RegisterModel;
use app\core\Database;

class RegisterController implements ControllerInterface {
    
private object $model;
private object $database;

public function __construct() {
     $this->database = new Database();
        $this->model = new RegisterModel($this->database);
}

public function handle(): void {
    $this->model->setUser();
    require_once dirname(__DIR__) . '/view/main.php';
}
}