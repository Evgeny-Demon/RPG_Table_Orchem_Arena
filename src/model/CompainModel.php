<?php

namespace app\model;

use app\core\Request;
use app\core\Database;
use app\core\Session;

class CompainModel {
    
    private object $request;
    private string $targetDir;
    private array $arrayDir = ['tokens/', 'maps/', 'chat/', 'maps-view/', 'avatar/', 'admin-panel-view/'];
    private int $compainKey;
    private object $db;
    private object $session;


    public function __construct(Database $database) {
        $this->request = new Request();
        $this->db = $database->connectDataBase();
        $this->session = new Session();

    }

    //слздание компании -------------
    private function setFile() : void {
    
        if (!isset($_FILES['file'])) {
            return;
        }
        if ($_FILES['file']['size'] > \MAX_FILE_SIZE) {
            echo "слишком большой файл";
            return;
        }
        if (!in_array($_FILES['file']['type'], \ALLOWED_FILE_TYPES)) {
            echo "неверный тип файла";
            return;
    }

    $target_file = $this->targetDir . 'avatar/' . basename($_FILES['file']['name']);
    $tmp_name = $_FILES['file']['tmp_name'];
    
    move_uploaded_file($tmp_name, $target_file);
    }

    private function setCompainInDB() {
        $compainName = $this->request->getRequestKey('name');
        $masterID = $this->session->get()['id'];
        $sqlInsert = 'INSERT into table_arena.compains (id_master, name) VALUES (:id_mas, :comp_name);';
        $stml = $this->db->prepare($sqlInsert);
        $stml->execute([':id_mas' => $masterID, ':comp_name' => $compainName]);

    }

    private function createMasterAdminFileHTML() : void {
        $fileName = 'admin.php';
        $compainName = $this->request->getRequestKey('name');
        $filePath = dirname(__DIR__) . '/compains/' . $compainName . '/' . array_pop($this->arrayDir) . $fileName;


$content = <<<HTML
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/admin-dashboard.css">
    <title>ORCHEM — Администрирование</title>
</head>
<body>

<header class="topbar">
    <div class="brand">ORCHEM</div>

    <div class="topbar-right">
        <span>{$compainName}</span>
        <a href="/exit-compain">Выйти</a>
    </div>
</header>

<main class="dashboard">

    <section class="create-panel">

        <div class="panel-title">
            <h1>Создать карту</h1>
        </div>

        <form method="POST" enctype="multipart/form-data">

            <input type="hidden" name="action_id" value="create-map">
            <input type="hidden" name="MAX_FILE_SIZE" value="5000000">

            <div class="form-group map-name">
                <label for="map-name">Название</label>
                <input
                    type="text"
                    name="name"
                    id="map-name"
                    placeholder="Название карты"
                    required
                >
            </div>

            <div class="form-group">
                <label for="columns">Колонки</label>
                <select name="columns" id="columns" required>
                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="20">20</option>
                    <option value="25">25</option>
                    <option value="30" selected>30</option>
                    <option value="35">35</option>
                    <option value="40">40</option>
                    <option value="50">50</option>
                </select>
            </div>

            <div class="form-group">
                <label for="rows">Строки</label>
                <select name="rows" id="rows" required>
                    <option value="10">10</option>
                    <option value="15" selected>15</option>
                    <option value="20">20</option>
                    <option value="25">25</option>
                    <option value="30">30</option>
                    <option value="40">40</option>
                </select>
            </div>

            <div class="form-group file-group">
                <label for="map-file">Изображение</label>
                <input
                    type="file"
                    name="file"
                    id="map-file"
                    accept="image/*"
                    required
                >
            </div>

            <button type="submit">Создать</button>

        </form>

    </section>

</main>

</body>
</html>
HTML;

        file_put_contents($filePath, $content);
    }

    public function createCompain() : void {
        $compainName = $this->request->getRequestKey('name');
        $this->targetDir = dirname(__DIR__) . '/compains/' . $compainName . '/';
        mkdir($this->targetDir, 0777, true);

        foreach ($this->arrayDir as $v) {
            mkdir($this->targetDir . $v, 0777, true);  
        }

        $this->setFile() ;
        $this->setCompainInDB();
        $this->createMasterAdminFileHTML();
    }

    //удаление компании -------------
    private function delCompainInDB() {
        $compainName = $this->request->getRequestKey('name');
        $compainID = $this->request->getRequestKey('game_id');
        $sqlDelete = 'DELETE FROM table_arena.compains WHERE game_id = :game_id;';
        $stmt = $this->db->prepare($sqlDelete);
        $stmt->execute([':game_id' => $compainID]);
    }

public function delCompain() {
    $compainName = $this->request->getRequestKey('name');

    $arrayCompainDir = scandir(\COMPAIN_DIR);
    for($i = 0; $i < count($arrayCompainDir); $i++) {
        // Проверяем совпадение имени компании
        if($compainName === $arrayCompainDir[$i]) {
            
            
            foreach($this->arrayDir as $j) {
                // Собираем точный путь к подпапке компании (используем имя папки, а не индекс $i)
                $subDirPath = \COMPAIN_DIR . '/' . $arrayCompainDir[$i] . '/' . $j;
                
                if (file_exists($subDirPath) && is_dir($subDirPath)) {
                    // Сканируем файлы внутри этой подпапки
                    $files = scandir($subDirPath);
                    
                    foreach($files as $file) {
                        // Пропускаем системные точки
                        if ($file === '.' || $file === '..') {
                            continue;
                        }
                        
                        // Полный путь к конкретной картинке или файлу
                        $fullFilePath = $subDirPath . $file;
                        
                        if (is_file($fullFilePath)) {
                            unlink($fullFilePath); // Удаляем файл
                        }
                    }
                    // После того как все файлы удалены, удаляем пустую подпапку
                    rmdir($subDirPath);
                }
            }
            
            // В самом конце, когда все внутренние папки стёрты, удаляем корень компании
            $companyRootPath = \COMPAIN_DIR . '/' . $arrayCompainDir[$i];
            rmdir($companyRootPath);
            
            break; // Компания удалена, останавливаем главный цикл for
        }
    }

    $this->delCompainInDB();
}

} 
    