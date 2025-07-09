<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Кабинет</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<div class="body">

    <h1>Кабинет</h1>

    <div class="pt">
        <p>Логин: <?= $userInfo['username'] ?></p>
        <p>Email: <?= $userInfo['email'] ?></p>
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