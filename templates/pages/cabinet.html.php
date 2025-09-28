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
        <div class="section-sep"></div>
        <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
            <tr>
                <td style="padding: 8px; width: 30%; vertical-align: top;">
                    <img src="/images/icons/message_incoming.png" width="12" height="12" alt="*"> 
                    <span class="minor">Email:</span>
                </td>
                <td style="padding: 8px;">
                    <?= htmlspecialchars($userInfo['email'] ?? 'Не указан', ENT_QUOTES, 'UTF-8') ?>
                </td>
            </tr>
            <tr>
                <td style="padding: 8px; vertical-align: top;">
                    <img src="/images/icons/clock.png" width="12" height="12" alt="*"> 
                    <span class="minor">Дата регистрации:</span>
                </td>
                <td style="padding: 8px;">
                    <?= $userInfo['joindate'] ? date('d.m.Y H:i:s', strtotime($userInfo['joindate'])) : 'Не указана' ?>
                </td>
            </tr>
            <tr>
                <td style="padding: 8px; vertical-align: top;">
                    <img src="/images/icons/portal_green.png" width="12" height="12" alt="*"> 
                    <span class="minor">Последний вход:</span>
                </td>
                <td style="padding: 8px;">
                    <?= $userInfo['last_login'] ? date('d.m.Y H:i:s', strtotime($userInfo['last_login'])) : 'Никогда не входил' ?>
                </td>
            </tr>
            <tr>
                <td style="padding: 8px; vertical-align: top;">
                    <img src="/images/icons/eye.png" width="12" height="12" alt="*"> 
                    <span class="minor">Последний IP:</span>
                </td>
                <td style="padding: 8px;">
                    <?= htmlspecialchars($userInfo['last_ip'] ?? 'Не определён', ENT_QUOTES, 'UTF-8') ?: 'Не определён' ?>
                </td>
            </tr>
            <tr>
                <td style="padding: 8px; vertical-align: top;">
                    <img src="/images/icons/skull_blue.png" width="12" height="12" alt="*"> 
                    <span class="minor">Статус бана:</span>
                </td>
                <td style="padding: 8px;">
                    <?php if ($banInfo): ?>
                        <span style="color: red;">Забанен</span>
                        <?php if (!empty($banInfo['banreason'])): ?>
                            <br><span class="minor">Причина: <?= htmlspecialchars($banInfo['banreason'], ENT_QUOTES, 'UTF-8') ?></span>
                        <?php endif; ?>
                    <?php else: ?>
                        <span style="color: green;">Не забанен</span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td style="padding: 8px; vertical-align: top;">
                    <img src="/images/icons/cross.png" width="12" height="12" alt="*"> 
                    <span class="minor">Статус мута:</span>
                </td>
                <td style="padding: 8px;">
                    <?php if ($muteInfo): ?>
                        <span style="color: red;">Заглушен</span> 
                        <span class="minor">(до <?= date('d.m.Y H:i:s', $muteInfo['mute_end_time']) ?>)</span>
                        <?php if (!empty($muteInfo['mutereason'])): ?>
                            <br><span class="minor">Причина: <?= htmlspecialchars($muteInfo['mutereason'], ENT_QUOTES, 'UTF-8') ?></span>
                        <?php endif; ?>
                    <?php else: ?>
                        <span style="color: green;">Не заглушен</span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td style="padding: 8px; vertical-align: top;">
                    <img src="/images/icons/health.png" width="12" height="12" alt="*"> 
                    <span class="minor">В игре:</span>
                </td>
                <td style="padding: 8px;">
                    <?php if ($userInfo['online'] > 0): ?>
                        <span style="color: green;"> онлайн</span>
                    <?php else: ?>
                        <span style="color: red;"> оффлайн</span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td style="padding: 8px; vertical-align: top;">
                    <img src="/images/icons/money.png" width="12" height="12" alt="*"> 
                    <span class="minor">Баланс:</span>
                </td>
                <td style="padding: 8px;">
                    <strong><?= $bonusBalance ?> бонусов</strong>
                </td>
            </tr>
        </table>
    </div>
   <div class="section-sep"></div>



<div class="small">
<h2>Ваши персонажи</h2>
<div class="section-sep"></div>

<?php if (empty($characters)): ?>
    <p>У вас нет созданных персонажей.</p>
<?php else: ?>
    <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
        <thead>
            <tr>
                <td style="padding: 10px; font-weight: bold; text-align: center; font-size: 14px;">Фракция</td>
                <td style="padding: 10px; font-weight: bold; text-align: center; font-size: 14px;">Расса</td>
                <td style="padding: 10px; font-weight: bold; text-align: center; font-size: 14px;">Класс</td>
                <td style="padding: 10px; font-weight: bold; font-size: 14px;">Имя персонажа</td>
                <td style="padding: 10px; font-weight: bold; text-align: center; font-size: 14px;">Уровень</td>
                <td style="padding: 10px; font-weight: bold; text-align: center; font-size: 14px;">Время игры</td>
                <td style="padding: 10px; font-weight: bold; text-align: center; font-size: 14px;">Действие</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($characters as $char): ?>
                <tr>
                    <td style="padding: 8px; text-align: center;">
                        <img src="<?= $char['factionImage'] ?>" alt="Фракция">
                    </td>
                    <td style="padding: 8px; text-align: center;">
                        <img src="/images/small/<?= $char['race'].'-'.$char['gender'].'.gif' ?>" alt="расса" class="u12img">
                    </td>
                    <td style="padding: 8px; text-align: center;">
                        <img src="/images/small/<?= $char['class'].'.gif' ?>" alt="класс">
                    </td>
                    <td style="padding: 8px;">
                        <strong style="color: <?= $char['classColor'] ?>;"><?= htmlspecialchars($char['name'], ENT_QUOTES, 'UTF-8') ?></strong>
                        <?php if (!empty($char['roleTextShort'])): ?>
                            <br><span class="bluepost">[<?= htmlspecialchars($char['roleTextShort'], ENT_QUOTES, 'UTF-8') ?>]</span>
                        <?php endif; ?>
                    </td>
                    <td style="padding: 8px; text-align: center;">
                        <strong><?= $char['level'] ?></strong> ур.
                    </td>
                    <td style="padding: 8px; text-align: center;">
                        <span style="color: #6cf; font-size: 11px;"><?= $char['playtime'] ?></span>
                    </td>
                    <td style="padding: 8px; text-align: center;">
                        <a class="btn" href="<?= '/play?id='.$char['guid'] ?>">Управлять</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

</div>

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

