<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Azeroth - Сервер WoW  <?= $pageTitle ?? 'Главная страница' ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta charset="utf-8">
    <meta name="description" content="Azeroth - это культовый сервер World of Warcraft, предлагающий захватывающий мир фэнтези, полный приключений, магии и эпических сражений! Исследуй подземелья, создавай мощное оружие и сражайся с другими игроками в захватывающих битвах!">
    <meta property="og:type" content="игра">
    <meta property="og:url" content="https://azeroth.su">
    <meta property="og:description" content="Azeroth - это культовый сервер World of Warcraft, предлагающий захватывающий мир фэнтези, полный приключений, магии и эпических сражений! Исследуй подземелья, создавай мощное оружие и сражайся с другими игроками в захватывающих битвах!">
    <meta name="keywords" content="игры, RPG, MMORPG, фэнтези, онлайн, wap, бесплатно, Azeroth, World of Warcraft, магия, играть онлайн, ролевые игры, лучшие онлайн игры, браузерная игра, дополнения, патч 3.3.5, King Leeroy, боты, player bot, PvP, PvE, рейд, инстанс, гильдия, квесты, прокачка, гильдия, PvP арена, PvE контент, рейд боссы, инстанс подземелья, гильдия рейд, квесты, прокачка персонажа, гильдия PvP, PvE контент, рейд боссы, инстанс подземелья, гильдия рейд, квесты, прокачка персонажа, WoW Classic, Burning Crusade, Wrath of the Lich King, Cataclysm, Mists of Pandaria, Warlords of Draenor, Legion, Battle for Azeroth, Shadowlands, Dragonflight, гильдия рейд лидер, квесты ежедневные, прокачка профессии, гильдия PvP рейтинг, PvE контент рейд, рейд боссы инстанс, гильдия рейд лидер, квесты ежедневные, прокачка профессии, гильдия PvP рейтинг, PvE контент рейд, рейд боссы инстанс, классы, воины, паладины, жрецы, маги, чародеи, друиды, охотники, разбойники, чернокнижники, рыцари смерти, шаманы, профессии, добыча, кожевничество, кузнечное дело, инженерное дело, травничество, алхимия, кулинарное дело, шитье, ювелирное дело, начертание, рыболовство, первопроходец, кулинарное дело, шитье, ювелирное дело, начертание, рыболовство, первопроходец, WoW, World of Warcraft, Azeroth, патч 3.3.5, дополнения, King Leeroy, боты, player bot, PvP, PvE, рейд, инстанс, гильдия, квесты, прокачка, гильдия, PvP арена, PvE контент, рейд боссы, инстанс подземелья, гильдия рейд, квесты, прокачка персонажа, гильдия PvP, PvE контент, рейд боссы, инстанс подземелья, гильдия рейд, квесты, прокачка персонажа, WoW Classic, Burning Crusade, Wrath of the Lich King, Cataclysm, Mists of Pandaria, Warlords of Draenor, Legion, Battle for Azeroth, Shadowlands, Dragonflight, гильдия рейд лидер, квесты ежедневные, прокачка профессии, гильдия PvP рейтинг, PvE контент рейд, рейд боссы инстанс, гильдия рейд лидер, квесты ежедневные, прокачка профессии, гильдия PvP рейтинг, PvE контент рейд, рейд боссы инстанс">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="icon" sizes="192x192" href="/images/game-icon.jpg">
    <link rel="icon" href="/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/premium-style.css?v=<?= time() ?>">
    <script src="/js/notify.js"></script>
</head>



<body>
<?php if (isset($GLOBALS['viewGlobals'])) extract($GLOBALS['viewGlobals']); ?>

    <div>

    <?php if (!empty($userInfo) && isset($userInfo['username'])): ?>
        <?php include __DIR__ . '/partials/header.html.php'; ?>
    <?php endif; ?>

    <?php if (!empty($notificationsData['notifications'])): ?>
        <?php foreach ($notificationsData['notifications'] as $notify): ?>
            <div class="premium-notification" id="notify-<?= (int)$notify['id'] ?>">
                <div class="premium-notification-icon">
                    <?php if ($notify['type'] === 'admin_coins'): ?>
                        <span class="notification-emoji">👑</span>
                    <?php else: ?>
                        <span class="notification-emoji">💰</span>
                    <?php endif; ?>
                </div>
                <div class="premium-notification-content">
                    <div class="premium-notification-title">
                        <?php if ($notify['type'] === 'admin_coins'): ?>
                            Сообщение от администрации
                        <?php else: ?>
                            Получены бонусные монеты!
                        <?php endif; ?>
                    </div>
                    <div class="premium-notification-message">
                        <?php if ($notify['type'] === 'admin_coins'): ?>
                            <strong><?= htmlspecialchars($notificationsData['username']) ?></strong>, <?= htmlspecialchars($notify['message']) ?>
                        <?php else: ?>
                            <strong><?= htmlspecialchars($notificationsData['username']) ?></strong>, ты получил <span class="coins-highlight"><?= isset($notify['coinsText']) ? $notify['coinsText'] : '' ?></span> за голосование!
                        <?php endif; ?>
                    </div>
                    <div class="premium-notification-footer">
                        <?php if ($notify['type'] === 'admin_coins'): ?>
                            ⚔️ Администрация проекта
                        <?php else: ?>
                            ✨ Спасибо, что поддерживаешь проект!
                        <?php endif; ?>
                    </div>
                </div>
                <button class="premium-notification-close hide-notify-btn" data-id="<?= (int)$notify['id'] ?>">
                    <span class="close-icon">✕</span>
                </button>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

        <!-- Основной контейнер с тремя колонками -->
        <div class="layout-container">
            <!-- Левая колонка (меню) -->
            <aside class="layout-sidebar layout-sidebar-left test3">
                <?php include __DIR__ . '/partials/left_block.html.php'; ?>
            </aside>
            
            <!-- Центральная колонка (основной контент) -->
            <main class="layout-content">
                <?php if (!empty($contentFile)): ?>
                    <?php include __DIR__ . '/' . $contentFile; ?>
                <?php endif; ?>
            </main>
            
            <!-- Правая колонка (статус сервера) -->
            <aside class="layout-sidebar layout-sidebar-right test2">
                <?php include __DIR__ . '/partials/right_block.html.php'; ?>
            </aside>
        </div>
        <!-- /Основной контейнер -->
    </div>
    <div class="b-mt-footer">
    <?php include __DIR__ . '/partials/footer.html.php'; ?>
    </div>
    <?php if (!empty($extraScripts) && is_array($extraScripts)): ?>
        <?php foreach ($extraScripts as $scriptSrc): ?>
            <script src="<?= htmlspecialchars($scriptSrc, ENT_QUOTES, 'UTF-8') ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    <script src="/js/slider.js"></script>
</body>
</html>