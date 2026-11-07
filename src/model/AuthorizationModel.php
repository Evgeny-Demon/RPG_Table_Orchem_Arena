<?php

namespace app\model;

use app\core\Database;
use app\core\Session;
use app\core\Request;

class AuthorizationModel {
    private \PDO $db;
    private Session $session;
    private Request $request;
    private array $compainsDB = ['game_id', 'id_master', 'name'];

    public function __construct(Database $db) {
        $this->db = $db->connectDataBase();
        $this->session = new Session();
        $this->request = new Request();
    }


    public function getAuthorization() : void {
        $login = $this->request->getRequestKey('login');
        $sqlRole = 
        'SELECT id, role_id, login from "table_arena"."users" where login=:login';
        $stmt = $this->db->prepare($sqlRole);
        $stmt->execute([':login' => $login]);
        $stmt = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        $this->session->clear();
        foreach ($stmt as $k => $value) {
            $this->session->set($k, $value); 
        };

    }

        //проверка компаний которые создал мастер

    public function LoadMasterCompain() : void {
        if($this->session->get()['role_id'] === 1) { //проверяем а мастер ли это?
            $count = count($this->session->get());
    
            if ($count > 3) { 
                for ($i = $count - 1; $i >= 3; $i--) { 
                    $this->session->deleteElement($i);
                }
                        
            }
                $sqlCompainTable = 'SELECT * FROM table_arena.compains where id_master = :id_master';
                $stmt = $this->db->prepare($sqlCompainTable);
                $stmt->execute([':id_master' => $this->session->get()['id']]);

                // ИСПРАВЛЕНО: Явно пишем в переменную $companiesList и её же сохраняем в сессию
                $companiesList = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $this->session->set('companies', $companiesList);
            
        }
    }    
}