<?php

namespace app\model;

use app\core\Cookie;
use app\core\Database;
use app\core\Request;


class AuthenticationModel {
    private \PDO $db;
    private Request $request;
    private string $login;
    private string $password;
    private Cookie $cookie;

    public function __construct(Database $database) {
        $this->request = New Request();
        $this->db = $database->connectDataBase();
        $this->cookie = new Cookie();
    }

    public function authenticat() : bool {

            $this->password = $this->request->getRequestKey('pass');
            $this->login = $this->request->getRequestKey('login');

            if($this->getUser($this->login, $this->password)) {
                //мы прошли аутентификацию
                echo 'мы прошли аутентификацию';
                return true;
            } else {
                //мы не прошли аутентификацию
                return false;
            }
            
    }
    

    private function getUser($login, $password) : bool {
        //проверка токена куки в БД - если токен найден, система пускает пользователя - если не найден, идет дальнейшая проверка
        if($this->cookie->get('user') !== null) {
            $selectCookie = 'select token_cookie from "table_arena"."users" where login=:login';
            $stmt = $this->db->prepare($selectCookie);
            $stmt->execute([':login' => $login]);
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
    
            if($row && $row["token_cookie"]  === $this->cookie->get('user')) {
                return true;
            }
            
        }

        //если куки нету, проверка пользователя по логину и паролю в БД
        $selectSql = 'select login, password from "table_arena"."users" where login=:login';
        $stmt = $this->db->prepare($selectSql);
        $stmt->execute([':login' => $login]);
        $stmt = $stmt->fetch(\PDO::FETCH_ASSOC);
        if(password_verify((string)$password, $stmt['password']) === true && (string)$login === $stmt['login']) {
                //добавление токена на куки в БД после аутентификации по ключу rememver me
            if($this->request->getRequestKey('remember_me')) {
                $token_cookie = hash('gost-crypto', random_int(0,999999));
                $updateSql = 'UPDATE "table_arena"."users" SET token_cookie =:token WHERE login=:login';
                $stmt = $this->db->prepare($updateSql);
                $stmt->execute([':login' => $login, ':token' => $token_cookie]);
        
                $this->cookie->set('user', $token_cookie, time() + 60 * 60 * 24 * 30, "/");
            }
            return true; //отдает тру только в том случае, если система успешно пустила пользователя
    }
    
    return false;
    }

}