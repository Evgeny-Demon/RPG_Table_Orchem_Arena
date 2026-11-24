<?php

namespace app\controller;

use app\core\Session;

class GetCompainTokenController {

private Session $session;

public function __construct() {
    $this->session = new Session();
}

    public function handle() : void {
        $this->session = new Session();
        $token = $this->session->get()['active_compain_token'] ?? null;

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['token' => $token]);
        exit;
    }
}
