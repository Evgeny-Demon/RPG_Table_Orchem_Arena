<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Player</title>
</head>
<body>
    <body>
    <h1>Player <?php echo $this->session->get()['login']; ?><h1>
    <form method='GET'>
    <button src='/exit'>Выйти</button>
    </form>

    <form action="/connect_player_code" method="GET">
    <input
        type="text"
        name="connect_code"
        placeholder="CONNECT-XXXXX-XXXXX-XXXXX"
        minlength="31"
        maxlength="31"
        required
    >

    <button type="submit">Подключиться</button>
</form>
    
</body>
</html>