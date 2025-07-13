<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Страница персонажа</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<div class="body">

    <h1>Страница персонажа</h1>

    <div class="pt">
        <p>Имя персонажа: <?= $character['name'] ?></p>
        <p>Раса: <?= $character['race'] ?></p>
        <p>Класс: <?= $character['class'] ?></p>
        <p>Уровень: <?= $character['level'] ?></p>
        <p>Здоровье: <?= $character['health'] ?></p>
    </div>

   

    <div class="pt">
        <a href="/cabinet">Вернуться в кабинет</a>
    </div>

</div>

</body>
</html>