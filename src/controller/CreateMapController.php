<?php

namespace app\controller;

class CreateMapController {
    public function handle(): void {
        require_once dirname(__DIR__) . '/view/map.php';
    }
}