<!-- templates/pages/Maintenance.html.php (premium) -->
<div class="cabinet-page">
    <div style="text-align:center; margin-bottom:16px;">
        <img src="/images/azeroth.png" width="310" height="174" alt="Логотип Azeroth" style="max-width:100%; height:auto;">
    </div>
    
    <h1 style="text-align:center;">Добро пожаловать на игровой сервер Azeroth PlayerBots 3.3.5</h1>

    <div class="cabinet-card" style="border-left:3px solid #ff9800; margin-bottom:12px;">
        <div class="cabinet-card-title">
            <img src="/images/icons/attention_gold.png" width="20" height="20" alt="!">
            Техническое обслуживание
        </div>
        <div class="info-main-text">
            Сайт находится в режиме технического обслуживания.
        </div>
        <div class="minor" style="margin-top:8px;">
            На сервере идет Альфа-тестирование.
        </div>
    </div>

    <div class="cabinet-card">
        <div class="cabinet-card-title">
            <img src="/images/icons/game_master.png" width="20" height="20" alt="*">
            Статус сервера
        </div>
        <div class="cabinet-info-list">
            <div class="info-row">
                <span class="info-label">Сервер</span>
                <span class="info-value">
                    <img width="12" height="12" alt="в" src="<?php echo $iconPath; ?>">
                    <span class="<?php echo $statusClass; ?>"><?php echo $serverStatus; ?></span>
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Игроков в сети</span>
                <span class="info-value">
                    <img src="/images/icons/guilds.png" width="12" height="12" alt="*">
                    <?php echo $playerCounts['online_players']; ?>
                    <span class="minor">
                        (<img src="/images/small/alliance.png" width="15" height="15" alt="A"> 
                        <?php echo $playerCountsByFaction['alliance_players']; ?> 
                        vs 
                        <img src="/images/small/orda.png" width="15" height="15" alt="H"> 
                        <?php echo $playerCountsByFaction['horde_players']; ?>)
                    </span>
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Время работы</span>
                <span class="info-value">
                    <img src="/images/icons/clock.png" width="12" height="12" alt="*">
                    <?php echo $uptime['days'] . ' д. ' . $uptime['hours'] . ' ч. ' . $uptime['minutes'] . ' м. ' . $uptime['seconds'] . ' с.'; ?>
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Игровой мир</span>
                <span class="info-value">
                    <img src="/images/icons/home.png" width="12" height="12" alt="*">
                    <?php echo $realmInfo['name']; ?>
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Реалм лист</span>
                <span class="info-value" style="font-family:monospace; background:rgba(0,0,0,0.3); padding:2px 6px; border-radius:3px;">
                    set realmlist <?php echo $realmInfo['address']; ?>
                </span>
            </div>
        </div>

        <div class="document-section">
            <div class="minor">
                <img src="/images/icons/question_blue.png" width="12" height="12" alt="?">
                Уже сейчас вы можете принять участие в тестировании игрового сервера, для этого вам понадобится скачать клиент WoW 3.3.5 по ссылке ниже, либо использовать свой клиент 3.3.5, необходимо будет заменить данные в realmlist на наш set realmlist logon.azeroth.su. После установки/изменения данных подключения, зарегистрируйтесь на нашем сайте.
            </div>
        </div>
        <div class="document-section">
            <div class="minor">
                <img src="/images/icons/question_blue.png" width="12" height="12" alt="?">
                Для более гибкого управления своими ботами и комфортной игры скачайте Аддон 
                <a href="https://cloud.mail.ru/public/RZ3Z/GA636MRSK" style="color:#4da3ff;">MultiBot</a>
            </div>
        </div>

        <div class="restore-button" style="display:flex; gap:12px; justify-content:center; margin-top:16px;">
            <a href="https://cloud.mail.ru/public/ej91/Bzmxrc6Bm" style="text-decoration:none;">
                <button type="button">Скачать клиент</button>
            </a>
            <a href="/register" style="text-decoration:none;">
                <button type="button">Зарегистрироваться</button>
            </a>
        </div>
    </div>
    <div class="cabinet-card">
        <div class="cabinet-card-title">
            <img src="/images/icons/rare-vendor_32x32.png" width="24" height="24" alt="*">
            Проект в разработке
        </div>
        <div class="document-text">
            Этот проект всё ещё находится в разработке. Если вы столкнётесь с какими-либо ошибками или сбоями, пожалуйста, сообщайте о них в тикет в игре. Ваши ценные отзывы помогут нам улучшить этот проект совместными усилиями.
        </div>
        <div class="document-text" style="margin-top:12px;">
            У нас есть <a href="https://t.me/+Y6-arC5q8WliNGRi" style="color:#4da3ff;">группа в телеграм</a>, где вы можете обсуждать проект, задавать вопросы и участвовать в жизни сообщества!
        </div>
    </div>

    <div class="cabinet-card">
        <div class="cabinet-card-title">
            <img src="/images/icons/scroll_gold.png" width="20" height="20" alt="*">
            Особенности сервера
        </div>
        <ul class="document-list">
            <li>Возможность входа в игру за альтернативных персонажей в качестве ботов, что позволяет игрокам взаимодействовать с другими своими персонажами, создавать группы, повышать уровень и т. д.</li>
            <li>Случайные боты, которые бродят по миру, выполняют задания и в остальном ведут себя как игроки, имитируя опыт MMO.</li>
            <li>Боты, способные проходить большинство рейдов и полей боя.</li>
            <li>Широкие возможности настройки поведения ботов.</li>
            <li>Отличная производительность даже при использовании тысяч ботов.</li>
        </ul>
    </div>

    <div class="login-links">
        <a class="link-item" href="/register"><img src="/images/icons/user.png" width="12" height="12" alt="*"> Регистрация</a>
        <a class="link-item" href="/help"><img src="/images/icons/question_gold.png" width="12" height="12" alt="*"> Помощь новичкам</a>
    </div>
</div>