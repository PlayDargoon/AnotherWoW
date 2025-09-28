<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Страница персонажа</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<div class="body">

    <h1><span> <?= htmlspecialchars($serverName) ?> - <?= htmlspecialchars($character['name']) ?></span></h1>

    <div class="small">
    <span><?= htmlspecialchars($races[$character['race']]) ?></span> <span><?= htmlspecialchars($classes[$character['class']]) ?></span>, <span><?= htmlspecialchars($character['level']) ?></span> ур. <img src="<?= htmlspecialchars($factionImage) ?>" alt="Фракция" width="12" height="12">
    </div>

    <?php if (!empty($roleText)): ?>
        <div class="block">
            <img src="/images/icons/guild_moderate.png" alt="." width="12" height="12">
            <span class="bluepost"><?= htmlspecialchars($roleText) ?></span>
        </div>
    <?php endif; ?>

   <div class="pb pt">
        <h2><a href="#">Характеристики</a></h2>
        <div>
            <ol class="mt3">
                <li>
                    <img src="/images/icons/health.png" alt="." width="12" height="12" class="link-icon">
                    <span class="minor">Выносливость:</span> <span><?= htmlspecialchars($stats['stamina']) ?></span> (<span><?= htmlspecialchars($stats['maxhealth']) ?></span> здоровья)
                </li>
                <li>
                    <img src="/images/icons/strength.png" alt="." width="12" height="12" class="link-icon">
                    <span class="minor">Сила:</span> <span><?= htmlspecialchars($stats['strength']) ?></span> (сила атаки ~<span><?= htmlspecialchars($stats['attackPower']) ?></span>)
                </li>
                <li>
                    <img src="/images/icons/crit.png" alt="." width="12" height="12" class="link-icon">
                    <span class="minor">Крит:</span> <span><?= htmlspecialchars($stats['critPct']) ?>%</span> 
                </li>
                <li>
                    <img src="/images/icons/armor.png" alt="." width="12" height="12" class="link-icon">
                    <span class="minor">Броня:</span> <span><?= htmlspecialchars($stats['armor']) ?></span>
                </li>
                <li>
                    <img src="/images/icons/effectEvade.png" class="link-icon">
                    <span class="minor">Ловкость:</span> <span><?= htmlspecialchars($stats['agility']) ?></span>
                </li>
                <li>
                    <img src="/images/icons/experience_stroke.png" alt="" class="link-icon">
                    <span class="minor">Опыт:</span> <span><?= htmlspecialchars($character['xp']) ?></span>
                </li>
               
            </ol>
        </div>
    </div>

   

    

<div class="pt">

<div>
<img src="/images/icons/clock.png" alt="."> <span>Игровое время: <?= htmlspecialchars(gmdate("H:i:s", $character['totaltime'])) ?></span>
</div>
<div>
<span><?= $character['online'] ? 'Онлайн' : 'Оффлайн' ?>, Локация</span>
</div>
<div>
<span class="minor"><?= htmlspecialchars(formatCreationDate($character['creation_date'])) ?></span>
</div>



</div>

<div class="pt small minor">
    <img src="/images/icons/game_master.png" alt="." width="12" height="12"> ID персонажа: <span><?= htmlspecialchars($character['guid']) ?></span>
    </div>

</div>


<div class="footer nav block-border-top">
    <ol>
        <li>
            <img src="/images/icons/arr_left.png" alt="." width="12" height="12" class="i12img"> <a href="/cabinet" class=""><span>В кабинет</span></a>
        </li>
        
    </ol>
</div>

</body>
</html>