<!-- templates/pages/Maintenance.html.php -->
<div class="body">
    <div style="text-align:center;" class="p2">
        <img src="/images/azeroth.png" width="310" height="174" alt="Логотип"> <!-- ВАШ ЛОГОТИП -->
    </div>
    <h1 style="text-align: center;">Добро пожаловать на игровой сервер Azeroth PlayerBots 3.3.5</h1>

    <div class="pt">
        <img src="/images/icons/attention_gold.png" alt="." width="12" height="12"> <span class="bluepost" style="font-size:small;">Сайт находится в режиме технического обслуживания.<br></span>
    </div>

    <div class="pt">
        <img src="/images/icons/attention_gold.png" alt="." width="12" height="12"> <span class="bluepost" style="font-size:small;">На сервере идет Альфа-тестирование.<br><br></span>
    </div>

    <div class="event" style="padding:4px;border:1px solid #CCCCCC;">
        <div class="yellow">Сервер:
        <img width="12" height="12" alt="в" src="<?php echo $iconPath; ?>">
            <span class="<?php echo $statusClass; ?>" id="id1e"><span><?php echo $serverStatus; ?></span></span>

</br>            
            Игроков: 
            <img src="/images/icons/guilds.png" alt="." width="12" height="12"> 
            <span class="info"><?php echo $playerCounts['online_players']; ?></span>

</br>
            Время работы: 
            <img src="/images/icons/clock.png" alt=".">  
            <?php echo $uptime['days'] . ' д. ' . $uptime['hours'] . ' ч. ' . $uptime['minutes'] . ' м. ' . $uptime['seconds'] . ' с.'; ?></span>
</br>        
             
             Игровой мир:
            <img src="/images/icons/home.png" alt=".">  
            <?php echo $realmInfo['name']; ?>
<br>

            Реалм лист:
            <img src="/images/icons/settings.png" alt=".">  
             set realmlist <?php echo $realmInfo['address']; ?>

</br>
<div class="minor mt5 small">
                <img src="/images/icons/question_blue.png" width="12" height="12" alt="VIP" class="ml5"> <span class="va-m">Уже сейчас вы можете принять участие в тестировании игрового сервера, 
                    для этого вам понадобиться скачать клиент WoW 3.3.5 по ссылке ниже, 
                    либой использовать свой клиент 3.3.5, необходимо будет заменить данные в realmlist на нашиши set realmlist logon.azeroth.su. 
                    После установки/изменении данных подключения, зарегистрируйтесь на нашем сайте. </span>
</br>
                    <img src="/images/icons/question_blue.png" width="12" height="12" alt="VIP" class="ml5"> <span class="va-m">Для более гибкого управления своими ботами и комфортной игры скачайте Аддон 
                         <a href="https://cloud.mail.ru/public/vEob/NNLGfCd2t" class="">MultiBot</a>  </span>
            </div>

             
            

</br>

  <div style="text-align: center;">
    <a class="iLegendary" href="#" style="display: inline-block; margin-right: 10px;">
        <h1 style="text-align: center;">Скачать клиент</h1>
    </a>
    <a class="iLegendary" href="/register" style="display: inline-block;">
        <h1 style="text-align: center;">Зарегистрироваться</h1>
    </a>
</div> </div> </div>


 <div style="text-align:center;" class="p2">
        <img src="/images/icon.png" width="550" height="174" alt="Логотип"> 
    </div>


<h2 style="color:#ff6600">Особенности сервера включают в себя:</h2>


     <ol class="pt">
<li> 
    <img src="/images/icons/arr.png" alt="." width="12" height="12" class="link-icon"><span>Возможность входа в игру за альтернативных персонажей в качестве ботов, 
        что позволяет игрокам взаимодействовать с другими своими персонажами, создавать группы, повышать уровень и т. д.</span>
</li>
</br>
<li> 
    <img src="/images/icons/arr.png" alt="." width="12" height="12" class="link-icon"><span>Случайные боты, которые бродят по миру, 
        выполняют задания и в остальном ведут себя как игроки, имитируя опыт MMO.</span>
</li>
</br>
<li> 
    <img src="/images/icons/arr.png" alt="." width="12" height="12" class="link-icon"><span>Боты, способные проходить большинство рейдов и полей боя.</span>
</li>
</br>
<li> 
    <img src="/images/icons/arr.png" alt="." width="12" height="12" class="link-icon"><span>Широкие возможности настройки поведения ботов.</span>
</li>
</br>
<li> 
    <img src="/images/icons/arr.png" alt="." width="12" height="12" class="link-icon"><span>Отличная производительность даже при использовании тысяч ботов.</span>
</li>

    </ol>


</br>


<table>
<tbody><tr>
<td align="left" width="40" valign="top"><img src="/images/icons/rare-vendor_32x32.png" alt=""></td>
<td>
<span class="bluepost" style="word-wrap: break-word;">
    <p class="text-center"> 
        Этот проект всё ещё находится в разработке. Если вы столкнётесь с какими-либо ошибками или сбоями, 
        пожалуйста, 
        сообщайте о них в тикет в игре. 
        Ваши ценные отзывы помогут нам улучшить этот проект совместными усилиями.
    </p>

     <p class="text-center"> 
     У нас есть <a href="https://t.me/+Y6-arC5q8WliNGRi" class="">группа в телеграм</a>, где вы можете обсуждать проект, задавать вопросы и участвовать в жизни сообщества!
    </p>
</span>

</td>
</tr>
</tbody></table>



</div>