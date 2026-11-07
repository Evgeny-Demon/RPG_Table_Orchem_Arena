<!DOCTYPE html>

<script>
// Если адрес в строке не равен "/", мы молча меняем его на "/"
if (window.location.pathname !== '/') {
    window.history.replaceState({}, '', '/');
}
</script>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="post">
    <input type="hidden" name="action_id" value="login">
    <input type="text" name="login" placeholder="Логин"><br/>
    <input type="password" name="pass"> <br/>
    
    <input type="submit" value="Вход">
    <label>
        <input type="checkbox" name="remember_me" value="1"> Запомнить меня
    </label>
    </form>

    <form method="post">
    <input type="hidden" name="action_id" value="register">
    <input type="text" name="login" placeholder="Логин"><br/>
    <input type="password" name="pass"> <br/>
    <input type="hidden" name="token" value="<?=$token?>"> <br/>
    <input type="submit" value="Регистрация">
    <select name="role_id">
    <option value="1">Master</option>
    <option value="2">Player</option>
    </select>
    </form>

    <a href='create-map'>перейти на карту</a>
    
</body>
</html>