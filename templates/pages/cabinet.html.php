    <head>
        <title>Кабинет пользователя <?= htmlspecialchars($userInfo['username']) ?></title>      
    </head>
      

<div class="body">
                  <h2>Игровой кабинет</h2>



                  
    <div class="pt" style="text-align:center">
        <div class="block-border">
            <img src="/images/taverna.jpg" width="725" height="227" alt="таверна">
        </div>
    </div>
 <div class="section-sep"></div>

 
                 <?php if ($userAccessLevel >= 4): ?>
        <ol>
           <li><img src="/images/icons/settings.png" width="12" height="12" alt="*"> <a href="/admin-panel">Админ Панель</a></li>
        </ol>
                 <?php endif; ?>

<div class="section-sep"></div>

  
<div class="pt">

    <img src="/images/icons/menialo.png" alt="*" style="float:left;margin-right:8px; " class="ic32" width="32" height="32">

        <div class="small">
            Продолжи свое путешествие и стань легендарным героем!<br/>
            Выбери персонажа, которым ты хочешь управлять:
        </div>
        <br>

        <div class="pt">
        <h2>Информация учетной записи</h2>
        
           <ol>
               <li><img src="#" width="12" height="12" alt="*"> <span class="minor">Емаил:</span> <span>Мой емаил</span></a></li>
               <li><img src="#" width="12" height="12" alt="*"> <span class="minor">Дата регистрации:</span> <span>Моя дата регистрации</span></a></li>
               <li><img src="#" width="12" height="12" alt="*"> <span class="minor">Последний вход</span> <span>Мой последний вход в игру формат Г:М:Д Ч:М:С</span></a></li>
               <li><img src="#" width="12" height="12" alt="*"> <span class="minor">Последний IP</span> <span>Мой последний IP с которого входил в игру</span></a></li>
               <li><img src="#" width="12" height="12" alt="*"> <span class="minor">Забанен</span> <span>Есть бан или нет</span></a></li>
               <li><img src="#" width="12" height="12" alt="*"> <span class="minor">Мут</span> <span>Есть мут или нет</span></a></li>
               <li><img src="#" width="12" height="12" alt="*"> <span class="minor">В игре</span> <span>В игре сейчас или нет</span></a></li>
               <li><img src="#" width="12" height="12" alt="*"> <span class="minor">Бонусов</span> <span>Общий баланс бонусов</span></a></li>
            </ol>
    </div>
   <div class="section-sep"></div>



<div class="small">
<h2>Ваши персонажи</h2>
<div class="section-sep"></div>
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

    

    <div class="pt">
        <h2>Голосование</h2>
        <div class="section-sep"></div>
        <ol>
            <li><img src="/images/icons/feather.png" width="12" height="12" alt="*"> <a href="/vote"  width="12" height="12" alt="*">Голосовать за проект</a></li>
            <li><img class="i12img" src="/images/icons/journal_12.png" alt="." width="12px" height="12px"> <a href="/cabinet/coins-history">История начислений</a></li>
            <li><img src="/images/icons/vip_icon.png" width="12" height="12" alt="*"> <a href="/vote/top">Топ голосующих</a></li>
        </ol>
    </div>
<div class="section-sep"></div>

    <div class="pt">
        <h2>Настройки</h2>
        
           <ol>
               <li><img src="/images/icons/arr.png" width="12" height="12" alt="*"> <a href="#"> Изменить email</a></li>
               <li><img src="/images/icons/arr.png" width="12" height="12" alt="*"> <a href="#">Изменить телефон</a></li>
               <li><img src="/images/icons/question_blue.png" width="12" height="12" alt="*"> <a href="#">Сменить пароль</a></li>
            </ol>
    </div>
   <div class="section-sep"></div>
        
    
    </div>


</div>

<div class="footer nav block-border-top">
    <ol>
       <li><img class="i12img" src="/images/icons/home.png" alt="." width="12px" height="12px"> <a href="/">На главную</a></li>
      
       <li><img src="/images/icons/cross.png" alt="." width="12" height="12"> <a ignorewebview="true" href="/logout">Выйти из кабинета</a></li>
       <li> <img class="i12img" src="/images/icons/question_blue.png" alt="." width="12px" height="12px"> <a href="#">Первая помощь</a></li>

    </ol>
</div>


<div class="footer nav block-border-top">
    <ol>
        <li>
            <img src="/images/icons/addfriends.png" alt="." width="12" height="12" class="i12img">
    <a href="https://yoomoney.ru/fundraise/1D220FUHMKN.250928" class=""><span>Поддержать сервер</span></a>
        </li>
        
    </ol>
</div>

</body>

