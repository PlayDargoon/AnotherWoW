<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Кабинет</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<div class="body">

    <div class="mt20 p2 small i">
        Кабинет пользователя <strong class="yellow"><?= $userInfo['username'] ?></strong>
    </div>
    <div class="b-page-bg">
        <img src="/images/cabinet_310_blue.jpg" width="310" height="103">
    </div>
    <h2>Игровой кабинет</h2>

    <div class="pt">
        <div class="small">
            Продолжи свое путешествие и стань легендарным героем!<br/>
            Выбери персонажа, которым ты хочешь управлять:
        </div>
        <br>
<div class="small">


<span>Фракция</span> | <span>Расса</span> | <span>Класс</span> | <span>Имя персонажа</span> | <span>Уровень</span>



</div>
        <?php if (empty($characters)): ?>
            <p>У вас нет созданных персонажей.</p>
        <?php else: ?>
            <?php foreach ($characters as $char): ?>
                <div>
                    <span>
                        <img src="<?= $char['factionImage'] ?>" alt="Фракция" >
                        <img src="/images/small/<?= $char['race'].'-'.$char['gender'].'.gif' ?>" alt="расса" class="u12img" >
                        <img src="/images/small/<?= $char['class'].'.gif' ?>" alt="класс">
                        
                        <a class="btn" href="<?= '/play?id='.$char['guid'] ?>">
                            <span><?= htmlspecialchars($char['name'], ENT_QUOTES, 'UTF-8') ?></a></span>
                         <?php if (!empty($char['roleText'])): ?>
                            <span class="bluepost">[<?= substr($char['roleText'], strpos($char['roleText'], '[')+1, -1) ?>]</span>
                        <?php endif; ?>,
                        <span><?= $char['level'] ?> ур.</span>
                    </span>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>

    

    <div class="pt">

    <?php if ($userAccessLevel > 0): ?>
        <div>
            <a href="/admin-panel">
                <img src="/images/icons/arr.png" width="12" height="12" alt="*">
                Админ Панель
            </a>
        </div>
    <?php endif; ?>

        <div>
            <a href="#">
                <img src="/images/icons/arr.png" width="12" height="12" alt="*">
                Изменить email
            </a>
        </div>
        <div>
            <a href="#">
                <img src="/images/icons/arr.png" width="12" height="12" alt="*">
                Изменить телефон
            </a>
        </div>
    </div>

    <div class="pt">
        <div>
            <img src="/images/icons/question_blue.png" width="12" height="12" alt="*">
            <a href="#">Сменить пароль</a>
        </div>

        <div>
            <img src="/images/icons/cross.png" alt="." width="12" height="12">
            <a ignorewebview="true" href="/logout">Выйти из кабинета</a>
        </div>
    </div>

</div>

</body>
</html>