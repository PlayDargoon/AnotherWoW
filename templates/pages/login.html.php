<?php
// templates/pages/login.html.php

?>

<div class="body">
    <h2>Вход</h2>
    <div style="text-align:center;" class="p2">
        <img src="/images/rasporyaditel_310.jpg" width="310" height="103">
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <p><?= $error ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="/login" method="POST">
        <label for="char_name" class="green">Имя персонажа:</label><br>
        <input type="text" id="char_name" name="char_name" required><br>
        <label for="password" class="green">Пароль:</label><br>
        <input type="password" id="password" name="password" required><br>

        <div class="pt">
            <input id="submit" type="submit" class="headerButton _c-pointer" name="p::submit" value="Войти">
        </div>
    </form>

    <div class="smail pt">
        <img src="/images/icons/rasp.png" alt="*" style="float:left;margin-right:8px;" class="ic32" width="32" height="32">
        Есть аккаунт в одной из социальных сетей? Авторизуйся и управляй несколькими персонажами из своего личного кабинета.
        <div style="clear: both;"></div>
    </div>

    <div class="pt">
        <a class="headerButton" href="/reset_password">
            <img src="/images/icons/book_red.png" width="12" height="12" alt="." class="link-icon">Забыли пароль?
        </a>
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