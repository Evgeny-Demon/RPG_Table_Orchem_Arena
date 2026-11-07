<?php

namespace app\core;

class Request {
    private array $post;
    private array $get;
    private array $server;

    public function __construct() {
        $this->post = $_POST;
        $this->get = $_GET;
        $this->server = $_SERVER;
    }

    public function getRequestPost() : array {
        return $this->post;
    }

    public function getRequestGet() : array {
        return $this->get;
    }

    public function isPost(): bool {
        return ($this->server['REQUEST_METHOD'] === "POST") ? true : false;
    }

       public function isGet(): bool {
        return ($this->server['REQUEST_METHOD'] === "GET") ? true : false;
    }

    public function getRequestKey($key): mixed {
        return $this->post[$key] ?? null;
    }

    public function getPath(): string {
        $uri = $this->get['uri'] ?? '';
        return parse_url($uri, PHP_URL_PATH) ?? '/';
    } 
}