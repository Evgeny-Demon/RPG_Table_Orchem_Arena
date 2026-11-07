<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/compainlist.css">
    <title>compainlist</title>
</head>
<body>

<div class="container">
    <h1>Список кампаний</h1>

    <div class="company-grid">
<?php
$sessionData = $this->session->get();
$masterCompanies = $sessionData['companies'] ?? [];

$counter = 1;

foreach ($masterCompanies as $company) {
    $companyName = $company['name'] ?? '';
    $gameId = $company['game_id'] ?? '';

    if (empty($companyName)) {
        continue;
    }

    // 1. Собираем внутренний серверный путь к папке с аватаркой (используем константу)
    // Получится, например: /var/www/orchem-arena/src/view/compains/Apple/avatar/
    $avatarPath = \VIEW_COMPAIN_DIR . $companyName . '/avatar/';
    $base64Src = ''; // Заглушка, если картинки нет

    // 2. ЧЕЛОВЕЧЕСКИЙ ЧИСТЫЙ БЛОК BASE64 (без лишних циклов)
    if (file_exists($avatarPath)) {
        $files = scandir($avatarPath);
        // Берем самый первый реальный файл из папки (пропуская системные '.' и '..')
        $imageName = $files[2] ?? null; 

        if ($imageName) {
            $fullPath = $avatarPath . $imageName;
            // Считываем файл с диска и кодируем в текст
            $imageData = base64_encode(file_get_contents($fullPath));
            $ext = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
            $mimeType = ($ext === 'png') ? 'image/png' : 'image/jpeg';
            
            // Собираем готовый текстовый источник для тега img
            $base64Src = 'data:' . $mimeType . ';base64,' . $imageData;
        }
    }

    // 3. Формируем GET-ссылку со счетчиком
    $companyLink = '/company/?' . $counter - 1 . '=admin-dashboard';

    $safeLink = htmlspecialchars($companyLink);
    $safeName = htmlspecialchars($companyName);
    $safeGameId = htmlspecialchars((string)$gameId);

    echo <<<HTML
    <div class="company-item">
        <a href="{$safeLink}">
            <!-- Если картинка нашлась — вставится текст base64, если нет — будет пустой src -->
            <img src="{$base64Src}" alt="{$safeName}">
            <div>{$safeName}</div>
        </a>
        
        <form method="post" class="delete-form" onsubmit="return confirm('Удалить кампанию {$safeName}?');">
            <input type="hidden" name="name" value="{$safeName}">
            <input type="hidden" name="game_id" value="{$safeGameId}">
            <button type="submit" name="action_id" value="delcompain" class="delete-btn">удалить компанию</button>
        </form>
    </div>
HTML;

    $counter++;
}
?>
</div>


</div>

</body>
</html>
