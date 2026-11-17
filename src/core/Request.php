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

    public function getParam(string $key): ?string {
    // 1. Проверяем сломанный $_GET, где ключ — это число, а значение — вся строка
    foreach ($this->get as $val) {
        if (is_string($val) && str_contains($val, $key . '=')) {
            parse_str(parse_url($val, PHP_URL_QUERY) ?? '', $params);
            if (isset($params[$key])) return $params[$key];
        }
    }
    $uri = urldecode($this->server['REQUEST_URI'] ?? '');
    if (preg_match('/[?&]' . preg_quote($key, '/') . '=([^&#]+)/', $uri, $matches)) {
        return $matches[1];
    }

    return null;
}
}