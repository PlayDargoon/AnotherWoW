<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<div class="body">

    <h1>Вход</h1>

    <div class="pt" style="text-align:center">
        <img src="/images/rasporyaditel_310.jpg" width="310" height="103" alt="?">
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger" style="margin-top: 20px;">
            <p><?= $errors[0] ?></p>
        </div>
    <?php endif; ?>

    <form id="loginForm" action="/login" method="post">
        <div>
            <label for="username"><span class="info">Логин</span>:</label><br>
            <input id="username" type="text" value="" name="username" required>
        </div>
        <div class="pt">
            <label for="password"><span class="info">Пароль</span>:</label><br>
            <input id="password" type="password" value="" name="password" required>
        </div>
        <div class="pt">
            <input id="submit" type="submit" class="headerButton _c-pointer" name="p::submit" value="Войти">
        </div>
    </form>

    <div class="pt">
        <span class="small"><span>Есть аккаунт в одной из социальных сетей? Авторизуйся и управляй несколькими персонажами из своего личного кабинета.</span> </span>
        <div class="small">
            <a href="#"><img src="/images/icons/FB24x24r.png" width="24" height="24" alt="FB"></a>
            <a href="#"><img src="/images/icons/VK24x24r.png" width="24" height="24" alt="ВКонтакте"></a>
            <a href="#"><img src="/images/icons/OK24x24r.png" width="24" height="24" alt="Одноклассники"></a>
        </div>
    </div>

</div>

<div class="footer nav block-border-top">
    <ol>
        <li>
            <img class="i12img" src="/images/icons/home.png" alt="." width="12px" height="12px"> <a href="/">На главную</a>
        </li>
        <li>
            <img class="i12img" src="/images/icons/question_blue.png" alt="." width="12px" height="12px"> <a href="#">Первая помощь</a>
        </li>
    </ol>
</div>

</body>
</html>