<?php

namespace app\core;

class Database {

    private object $pdo;

    public function __construct () {
        $this->pdo = new \PDO('pgsql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD, [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false
        ]); 
        $this->pdo->exec('SET search_path TO table_arena, public');
    }


    

    public function connectDataBase() : object {
        return $this->pdo;
    }

}


