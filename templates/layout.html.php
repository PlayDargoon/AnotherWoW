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
    <script src="/js/notify.js"></script>
</head>



<body>
<?php if (isset($GLOBALS['viewGlobals'])) extract($GLOBALS['viewGlobals']); ?>

<?php if (!empty($notificationsData['notifications'])): ?>
    <?php foreach ($notificationsData['notifications'] as $notify): ?>
        <div class="event block-border-bottom" id="notify-<?= (int)$notify['id'] ?>">
            <div class="notify-inner">
                <img src="/images/refreshed-32x32.png" alt="" width="32" height="32" class="img-npc">
                <b><?= htmlspecialchars($notificationsData['username']) ?></b>, ты получил <?= $notify['coinsText'] ?> за голосование!
                <div class="mt10">Спасибо, что поддерживаешь проект.</div>
                <div class="clearer"></div>
            </div>
            <div>
                <a class="btn hide-notify-btn" data-id="<?= (int)$notify['id'] ?>" href="#"><img src="/images/icons/tick.png" alt="x" width="12" height="12" class="link-icon">Спасибо. Скрыть</a>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
    <div class="block-border">

    <?php if (!empty($userInfo) && isset($userInfo['username'])): ?>
    <?php include 'partials/header.html.php'; ?>
<?php endif; ?>

        <div class="test3 block-border">
            <?php include 'partials/left_block.html.php'; ?>
        </div>
        <div class="test2 block-border">
            <?php include 'partials/right_block.html.php'; ?>
        </div>
        <?php if (!empty($contentFile)): ?>
            <?php include $contentFile; ?>
        <?php endif; ?>
    </div>
    <div class="b-mt-footer">
        <?php include 'partials/footer.html.php'; ?>
    </div>
</body>
</html>