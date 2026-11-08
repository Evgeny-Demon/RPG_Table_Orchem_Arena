
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/compainlist.css">
    <title>ORCHEM — Список кампаний</title>
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

            $avatarPath = \VIEW_COMPAIN_DIR . $companyName . '/avatar/';
            $base64Src = '';

            if (file_exists($avatarPath)) {
                $files = scandir($avatarPath);
                $imageName = $files[2] ?? null;

                if ($imageName) {
                    $fullPath = $avatarPath . $imageName;
                    $imageData = base64_encode(file_get_contents($fullPath));
                    $ext = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
                    $mimeType = ($ext === 'png') ? 'image/png' : 'image/jpeg';

                    $base64Src = 'data:' . $mimeType . ';base64,' . $imageData;
                }
            }

            // Маршрут не изменяем
            $companyLink = '/company/?' . $counter - 1 . '=admin-dashboard';

            $safeLink = htmlspecialchars($companyLink);
            $safeName = htmlspecialchars($companyName);
            $safeGameId = htmlspecialchars((string)$gameId);

            echo <<<HTML
            <div class="company-item">

                <a href="{$safeLink}" class="company-link">
                    <img src="{$base64Src}" alt="{$safeName}">
                    <div class="company-name">{$safeName}</div>
                </a>

                <form method="post" class="delete-form"
                    onsubmit="return confirm('Удалить кампанию {$safeName}?');">

                    <input type="hidden" name="name" value="{$safeName}">
                    <input type="hidden" name="game_id" value="{$safeGameId}">

                    <button type="submit"
                        name="action_id"
                        value="delcompain"
                        class="delete-btn">
                        Удалить компанию
                    </button>

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
