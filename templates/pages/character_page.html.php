<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Страница персонажа</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<div class="body">

    <h1><span> <?= $serverName ?> - <?= $character['name'] ?></span></h1>

    <div class="small">
        <span><?= $races[$character['race']] ?></span> <span><?= $classes[$character['class']] ?></span>, <span><?= $character['level'] ?></span> ур. <img src="<?= $factionImage ?>" alt="Фракция" width="12" height="12">
    </div>

    <?php if (!empty($roleText)): ?>
        <div class="block">
            <img src="/images/icons/guild_moderate.png" alt="." width="12" height="12">
            <span class="bluepost"><?= $roleText ?></span>
        </div>
    <?php endif; ?>

    <div class="pt">
        <p>Здоровье: <?= $character['health'] ?></p>
    </div>

    <div>
        <img src="/images/icons/clock.png" alt="."> 
        <span>Игровое время: <?= gmdate("H:i:s", $character['totaltime']) ?></span>
    </div>

    <div class="pt small minor">
        <span class="minor">Статус: <?= $character['online'] ? 'Онлайн' : 'Оффлайн' ?></span>
    </div>

    <div>
        <span class="minor"><?= formatCreationDate($character['creation_date']) ?></span>
    </div>

    <div class="pt small minor">
        <img src="/images/icons/game_master.png" alt="." width="12" height="12"> ID персонажа: <span><?= $character['guid'] ?></span>
    </div>

    <div class="pt">
        <a href="/cabinet">Вернуться в кабинет</a>
    </div>

</div>

</body>
</html>