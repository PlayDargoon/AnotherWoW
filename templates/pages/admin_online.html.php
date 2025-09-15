<!-- templates/pages/admin_online.html.php -->
<?php require_once __DIR__ . '/../../src/helpers/getFactionImage.php'; ?>
<div class="touch-influenced block-border">
    <div class="exp-head-out">
        <div>
            <div class="exp-head-in"></div>
        </div>
    </div>
    <div class="body">
        <h1>Игроки онлайн</h1>
    </div>

    
    <div class="body">
    <?php // Если есть аккаунты с онлайн-персонажами, выводим их списком ?>
    <?php if (!empty($accountsOnline)): ?>
            <div class="pt">
                <!-- Список аккаунтов с онлайн-персонажами -->
                <ul style="list-style:none; padding:0;">
                <?php foreach ($accountsOnline as $acc): ?>
                    <!-- Блок одного аккаунта -->
                    <li class="pt small minor" style="border-bottom:1px solid #444; padding-bottom:12px; margin-bottom:16px;">
                        <b>Учетная запись:</b>
                        <div class="pt yellow small" style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap; padding: 2px 0;">
                            <span style="display: flex; align-items: center; gap: 2px;">
                                <img src="/images/icons/game_master.png" alt="ID" width="12" height="12">
                                <span style="min-width:24px; color:#888; display:inline-block;"><?= htmlspecialchars($acc['id']) ?></span>
                            </span>
                            <span style="display: flex; align-items: center; gap: 2px;">
                                <img src="/images/icons/guild_light.png" alt="Логин" width="12" height="12">
                                <span style="min-width:48px; color:#888; display:inline-block;"><?= htmlspecialchars($acc['username']) ?></span>
                            </span>
                            <span style="display: flex; align-items: center; gap: 2px;">
                                <img src="/images/icons/message_incoming.png" alt="Email" width="12" height="12">
                                <span style="min-width:80px; color:#888; display:inline-block;"><?= htmlspecialchars($acc['email']) ?></span>
                            </span>
                            <span style="display: flex; align-items: center; gap: 2px;">
                                <img src="/images/icons/amulet.png" alt="IP" width="12" height="12">
                                <span style="min-width:64px; color:#888; display:inline-block;"><?= htmlspecialchars($acc['last_ip']) ?></span>
                            </span>
                        </div>
                        <b>Персонажи онлайн:</b>
                        <?php // Список онлайн-персонажей аккаунта ?>
                         <span class="btn"><?php if (!empty($acc['characters'])): ?></span>
                            <ul class="char-list" style="list-style:none; margin-left:16px; padding:0;">
                                <?php foreach ($acc['characters'] as $char): ?>
                                    <!-- Один онлайн-персонаж -->
                                    <li style="display: flex; align-items: center; gap: 4px; padding: 2px 0;">
                                        <img src="/images/icons/game_master.png" alt="GUID" width="14" height="14">
                                        <span class="small" style="color:#888; min-width:38px; display:inline-block;"><?= $char['guid'] ?></span>
                                        <img src="<?= getFactionImage($char['race']) ?>" alt="Фракция" width="16" height="16">
                                        <img src="/images/small/<?= $char['race'] . '-' . $char['gender'] ?>.gif" alt="Раса" width="16" height="16">
                                        <img src="/images/small/<?= $char['class'] ?>.gif" alt="Класс" width="16" height="16">
                                        <img src="/images/icons/sex_<?= $char['gender'] == 1 ? 'female' : 'male' ?>.png" alt="Пол" width="14" height="14">
                                        <?php
                                        // Цвета классов WoW
                                        $classColors = [
                                            1 => '#C69B6D', // Воин
                                            2 => '#F48CBA', // Паладин
                                            3 => '#AAD372', // Охотник
                                            4 => '#FFF468', // Разбойник
                                            5 => '#FFFFFF', // Жрец
                                            6 => '#C41E3A', // Рыцарь Смерти
                                            7 => '#0070DD', // Шаман
                                            8 => '#3FC7EB', // Маг
                                            9 => '#8788EE', // Чернокнижник
                                            10 => '#00FF98', // Монах
                                            11 => '#FF7C0A', // Друид
                                            12 => '#A330C9', // Охотник на Демонов
                                            13 => '#33937F', // Пробуждающий (Evoker)
                                        ];
                                        $classColor = isset($classColors[$char['class']]) ? $classColors[$char['class']] : '#FFF';
                                        // Форматируем игровое время
                                        $tt = isset($char['totaltime']) ? (int)$char['totaltime'] : 0;
                                        $days = floor($tt / 86400);
                                        $hours = floor(($tt % 86400) / 3600);
                                        $minutes = floor(($tt % 3600) / 60);
                                        $playtime = ($days > 0 ? $days.'д ' : '') . ($hours > 0 ? $hours.'ч ' : '') . $minutes.'м';
                                        ?>
                                        <span style="font-weight:bold; margin-left:4px; color:<?= $classColor ?>;">
                                            <?= htmlspecialchars($char['name']) ?>
                                        </span>
                                        <span class="small" style="color:#aaa; margin-left:4px;">[<?= $char['level'] ?> ур.] <span style="color:#6cf; font-size:11px; margin-left:2px;">(<?= $playtime ?>)</span></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <span>Нет онлайн-персонажей</span>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
                </ul>
            </div>
        <?php else: ?>
            <!-- Если нет подходящих аккаунтов -->
            <div class="pt"><div class="small">Нет игроков онлайн, подходящих под условия.</div></div>
        <?php endif; ?>
        <div class="pt">
            <a class="btn" href="/admin-panel">
                <img src="/images/icons/arr.png" width="12" height="12" alt="*">
                Назад в админ-панель
            </a>
        </div>
    </div>
</div>
