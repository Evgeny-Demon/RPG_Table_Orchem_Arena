<?php

namespace app\core;

class Session {
    private array $session;

    public function __construct() {
        $this->start();
        $this->session = &$_SESSION;
    }

    private function start() : void {
        if(session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function destroy() : void {
        session_destroy();
    }

    public function status() : bool {
        return (session_status() === PHP_SESSION_ACTIVE) ? true : false;
    }

    public function set(string $key, mixed $value) : void {
        $this->session[$key] = $value;
    }

    public function get() : array {
        return $this->session;
    }

    public function clear() : void {
        $this->session = [];
    }

    public function deleteElement($key) : void {
        unset($this->session[$key]);
    }


}