<?php

namespace app\core;

class Cookie {

    public function set (string $name, string $value, int $expires = 0, string $path = "/") : bool {
        return setcookie($name, $value, $expires, $path);
    }

    public function get($key) : mixed {
        return $_COOKIE[$key] ?? null;
    }
}