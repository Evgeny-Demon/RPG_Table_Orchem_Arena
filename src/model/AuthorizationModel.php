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
    private object $randomaizer;

    public function __construct(Database $db) {
        $this->db = $db->connectDataBase();
        $this->session = new Session();
        $this->request = new Request();
        $this->randomaizer = new \Random\Randomizer();
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
                $sqlCompainTable = 'SELECT game_id, id_master, name FROM table_arena.compains where id_master = :id_master';
                $stmt = $this->db->prepare($sqlCompainTable);
                $stmt->execute([':id_master' => $this->session->get()['id']]);

                // пишем в переменную $companiesList и её же сохраняем в сессию
                $companiesList = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $this->session->set('companies', $companiesList);
            
        }
    }

    //получение сгенерированного токена для подключения игроков
    public function getCompainPlayersToken() : string {
        $active_game_id = $this->session->get()['active_compain_id'];
        $sql = "SELECT game_token from table_arena.compains where game_id = :active_game_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':active_game_id' => $active_game_id]);
        $token = $stmt->fetch(\PDO::FETCH_ASSOC);
        $token = $token['game_token'];

        if($token === null) {
        $token = $this->createCompainPlayersToken();
        $this->setCompainPlayersTokenInDB($token);
        }

        return $token;
    }
    
    //создание токена мастером для подключения игроков к компании
    private function createCompainPlayersToken() : string {
        $alphabet = 'ABCDEFGHJKMNPQRSTUVWXYZ123456789';
        $token = "";
        for($i = 3; $i > 0; --$i) {
            $code = $this->randomaizer->getBytesFromString($alphabet, 7);
            $token .=  "-" . $code;
        }
        return $token = 'CONNECT' . $token;
    }

    private function setCompainPlayersTokenInDB(string $token) : void {
        $compain = $this->session->get()['active_compain_id'];
        $sql = 'UPDATE table_arena.compains SET game_token = :game_token WHERE game_id = :compain_id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':game_token' => $token, ':compain_id' => $compain]);
        
    }

    //подключение игрока
    public function connectPlayer(string $key) : bool {
        $sqlSearchToken = "SELECT game_id, name from table_arena.compains where game_token = :token";
        $stmt = $this->db->prepare($sqlSearchToken);
        $stmt->execute([':token' => $key]);
        $active_compain = $stmt->fetch(\PDO::FETCH_ASSOC);
        if(!empty($active_compain['game_id'])) {
            $this->session->set('active_compain_id', $active_compain['game_id']);
            $this->session->set('active_compain_name', $active_compain['name']);
            return true;
        } else {
            return false;
        }
        //нужно добавить таблицу чтобы её потом джоинить ТАБЛИЦА С ИГРОКАМИ - 5 слотов для игроков. И потом вытягивать веб сокетами кто подключен 
        //если игрок не добавит картинку то нужно тянуть что-то свое - какого нибудь крестьянина
        // нужно подумать над тем как сохранить подключенных игроков чтобы им не пришлсоь потстоянно заново подключаться к компании
    }

}