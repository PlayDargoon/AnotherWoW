    <head>
        <title>Кабинет пользователя <?= htmlspecialchars($userInfo['username']) ?></title>      
    </head>
      

<div class="body">
      



    <h2>Игровой кабинет</h2>

    <?php if (isset($userInfo) && !empty($userInfo)): ?>
    <h2 class="pb10 _font-art font16">Общие данные</h2>
    <br>
    <div class="pt" style="margin-left:10px;">
        <ol>
           <li><span class="yellow">Баланс: <span class="info"><b><?= (int)(new \AccountCoins(\DatabaseConnection::getSiteConnection()))->getBalance($userInfo['id']) ?></b></span> голосов</span> </li>
           <li><a href="/cabinet/coins-history">История начислений</a></li>
           <li><a href="/vote/top">Топ голосующих</a></li>
        </ol>
    </div>
    <br>
    <?php endif; ?>

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
                            <?= htmlspecialchars($char['name'], ENT_QUOTES, 'UTF-8') ?>
                        </a>
                         <?php if (!empty($char['roleTextShort'])): ?>
                            <span class="bluepost">[<?= htmlspecialchars($char['roleTextShort'], ENT_QUOTES, 'UTF-8') ?>]</span>
                        <?php endif; ?>,
                        <span><?= $char['level'] ?> ур.</span>
                    </span>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>

    

    <div class="pt">

    <?php if ($userAccessLevel >= 4): ?>
        <div>
            <a href="/admin-panel">
                <img src="/images/icons/arr.png" width="12" height="12" alt="*">
                Админ Панель
            </a>
        </div>
    <?php endif; ?>

    <div class="pt">
        <div class="info">
            <img src="/images/icons/arr.png" width="12" height="12" alt="*">
            <a href="/vote"  width="12" height="12" alt="*">Голосовать за проект</a>
        </div>
    </div>


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