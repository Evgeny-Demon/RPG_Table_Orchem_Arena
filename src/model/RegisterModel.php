<?php

namespace app\model;

use app\core\Database;
use app\core\Request;

class RegisterModel {

private object $request;
private object $db;

   public function __construct(Database $database) {
        $this->db = $database->connectDataBase();
        $this->request = new Request();
    }

    public function setUser() : bool {
        $login = $this->request->getRequestKey('login');
        $password = $this->request->getRequestKey('pass');
        //проверка заполнения логина и пароля
        if(empty($login) || empty($password)) {
            echo "Заполните поля";
            return false;
        }

        //проверка наличия логина в БД
        $selectSql = 'select count(login) as count from "table_arena"."users" where login=:login';

        $stml = $this->db->prepare($selectSql);
        $stml->execute([':login' => $login]);
        $row = $stml->fetch(\PDO::FETCH_ASSOC);
        if($row['count'] > 0) {
            echo "логин существует";
            return false;
        }

        //логика добавления в базу данных
        $password = password_hash($password, PASSWORD_DEFAULT);
        $insertSql = 'insert into "table_arena"."users" (login, password, role_id) values (:login, :password, :role_id)';
        $role = $this->request->getRequestKey('role_id');
        $stml = $this->db->prepare($insertSql);
        $stml->execute([':login' => $login, ':password' => $password, ':role_id' => $role]);
        echo "Вы успешно зарегестрировались";
        return true;
    }


}