
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ORCHEM — Панель мастера</title>

    <link rel="stylesheet" href="/assets/css/master.css">
</head>
<body>

<div class="master-page">

    <header class="top-header">

        <div class="header-brand">
            <span class="brand-symbol">✦</span>
            <span class="brand-name">ORCHEM</span>
            <span class="brand-subtitle">VIRTUAL TABLETOP</span>
        </div>

        <div class="header-user">
            <span class="user-role">МАСТЕР</span>
            <span class="user-name">
                <?php echo htmlspecialchars($this->session->get()['login'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
            </span>
        </div>

        <a href="/exit" class="logout-button">Выйти</a>

    </header>

    <main class="master-content">

        <section class="page-heading">
            <span class="heading-decoration">✧</span>
            <h1>Панель мастера</h1>
            <p>Управление игровыми компаниями</p>
        </section>

        <section class="workspace">

            <div class="section-heading">
                <h2>Новая компания</h2>
                <p>Создайте игровое пространство для своего приключения</p>
            </div>

            <form
                method="post"
                enctype="multipart/form-data"
                class="company-form"
            >

                <input type="hidden" name="action_id" value="createcompain">
                <input type="hidden" name="MAX_FILE_SIZE" value="5000000">

                <div class="form-group">
                    <label for="company-name">Название компании</label>
                    <input
                        type="text"
                        name="name"
                        id="company-name"
                        placeholder="Введите название компании"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="file-drop">Аватарка компании</label>

                    <div class="file-input-wrapper">
                        <input
                            type="file"
                            name="file"
                            class="file-drop"
                            id="file-drop"
                            accept="image/*"
                            required
                        >
                    </div>

                    <span class="field-hint">
                        Максимальный размер файла — 5 МБ
                    </span>
                </div>

                <button type="submit" class="create-button">
                    Создать компанию
                </button>

            </form>

        </section>

    </main>

    <footer class="page-footer">
        <span>ORCHEM</span>
        <p>Мир начинается с мастера</p>
    </footer>

</div>

</body>
</html>
