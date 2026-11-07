<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>adminmaster</title>
</head>
<body>
    <h1>Master <?php echo $this->session->get()['login']; ?><h1>
    
   <a href="/exit" style="display: inline-block; padding: 6px 12px; background-color: #f0f0f0; border: 1px solid #ccc; border-radius: 4px; font-family: sans-serif; font-size: 14px; color: #333; text-decoration: none; cursor: pointer; text-align: center;">Выйти</a>


    <form method="post" enctype="multipart/form-data" class="bar-form-upload">
    <input type="hidden" name="action_id" value="createcompain">
    <input type="hidden" name="MAX_FILE_SIZE" value="5000000">

    <!-- Текстовое поле -->
    <input type="text" name="name" placeholder="Название компании" required><br/>
    <label for="file-drop">Аватарка компании:</label>
    <input type="file" name="file" class="file-drop" id="file-drop" required><br/><br/>
    <input type="submit" value="Создать компанию">
</form>


</body>
</html>