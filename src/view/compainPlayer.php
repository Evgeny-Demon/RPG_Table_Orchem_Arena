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
    <form method='post'>
    <button name='action_id' value='exit'>Выйти</button>
    </form>
    
</body>
</html>