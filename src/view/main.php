
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ORCHEM — Virtual Tabletop</title>

    <link rel="stylesheet" href="/assets/css/auth.css">
</head>
<body>

<div class="auth-page">

    <header class="brand">
        <div class="brand-decoration">✦</div>
        <h1>ORCHEM</h1>
        <p>VIRTUAL TABLETOP</p>
        <div class="brand-divider">
            <span></span>
            ✧
            <span></span>
        </div>
    </header>

    <main class="auth-container">

        <section class="auth-card">

            <h2>Вход</h2>
            <p class="section-description">Вернитесь к своему приключению</p>

            <form method="POST">

                <input type="hidden" name="action_id" value="login">

                <div class="form-group">
                    <label for="login">Логин</label>
                    <input
                        type="text"
                        id="login"
                        name="login"
                        placeholder="Введите логин"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="login-pass">Пароль</label>
                    <input
                        type="password"
                        id="login-pass"
                        name="pass"
                        placeholder="Введите пароль"
                        required
                    >
                </div>

                <label class="remember-me">
                    <input type="checkbox" name="remember_me" value="1">
                    <span>Запомнить меня</span>
                </label>

                <button type="submit" class="auth-button">
                    Войти
                </button>

            </form>

        </section>

        <div class="vertical-divider"></div>

        <section class="auth-card">

            <h2>Регистрация</h2>
            <p class="section-description">Создайте свою историю</p>

            <form method="POST">

                <input type="hidden" name="action_id" value="register">

                <input
                    type="hidden"
                    name="token"
                    value="<?= htmlspecialchars($token ?? '', ENT_QUOTES, 'UTF-8') ?>"
                >

                <div class="form-group">
                    <label for="register-login">Логин</label>
                    <input
                        type="text"
                        id="register-login"
                        name="login"
                        placeholder="Придумайте логин"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="register-pass">Пароль</label>
                    <input
                        type="password"
                        id="register-pass"
                        name="pass"
                        placeholder="Придумайте пароль"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="role">Роль</label>
                    <select name="role_id" id="role" required>
                        <option value="1">Мастер</option>
                        <option value="2">Игрок</option>
                    </select>
                </div>

                <button type="submit" class="auth-button">
                    Создать аккаунт
                </button>

            </form>

        </section>

    </main>

    <footer class="page-footer">
        <span>ORCHEM</span>
        <p>Твоя история начинается здесь</p>
    </footer>

</div>

</body>
</html>
