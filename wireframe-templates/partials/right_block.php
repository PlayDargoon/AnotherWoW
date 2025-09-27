<div class="body">
    <h1 style="text-align: center;">Информация</h1>
    <br>

    <!-- Статус сервера -->
    <div style="margin-bottom: 15px;">
        <div style="text-align: center; color: #ff6600; font-weight: bold; margin-bottom: 5px;">
            🖥️ Статус сервера
        </div>
        <div class="stat-item">
            ✅ <strong>Статус:</strong> Онлайн
        </div>
        <div class="stat-item">
            👥 <strong>Игроков:</strong> <?= $serverInfo['playersOnline'] ?? 245 ?>
        </div>
        <div class="stat-item">
            ⏱️ <strong>Аптайм:</strong> <?= $serverInfo['uptime'] ?? '99.2%' ?>
        </div>
    </div>

    <!-- Быстрое голосование -->
    <div style="margin-bottom: 15px;">
        <div style="text-align: center; color: #ff6600; font-weight: bold; margin-bottom: 5px;">
            🗳️ Быстрое голосование
        </div>
        
        <div class="vote-item" style="margin: 5px 0; padding: 8px;">
            <div style="font-size: 11px; margin-bottom: 5px;">🥇 MMOTOP.RU</div>
            <div style="font-size: 10px; color: #cccccc; margin-bottom: 5px;">Награда: 1 монета</div>
            <button class="vote-button" style="width: 100%; padding: 6px; font-size: 11px;" 
                    onclick="alert('Переход на MMOTOP для голосования');">
                ГОЛОСОВАТЬ ✅
            </button>
        </div>
        
        <div class="vote-item vote-item-cooldown" style="margin: 5px 0; padding: 8px;">
            <div style="font-size: 11px; margin-bottom: 5px;">🥈 TOP100WOW</div>
            <div style="font-size: 10px; color: #cccccc; margin-bottom: 5px;">Награда: 2 монеты</div>
            <button class="vote-button vote-button-disabled" style="width: 100%; padding: 6px; font-size: 11px;" disabled>
                ОЖИДАНИЕ ⏰
            </button>
        </div>
    </div>

    <!-- Топ игроков -->
    <div style="margin-bottom: 15px;">
        <div style="text-align: center; color: #ff6600; font-weight: bold; margin-bottom: 5px;">
            🏆 Топ игроков
        </div>
        <div style="font-size: 11px;">
            <?php 
            $topPlayers = $topPlayers ?? [
                ['name' => 'DragonSlayer', 'level' => 80, 'class' => 'Воин'],
                ['name' => 'ShadowMage', 'level' => 79, 'class' => 'Маг'],
                ['name' => 'HolyPriest', 'level' => 78, 'class' => 'Жрец']
            ];
            ?>
            <?php foreach ($topPlayers as $index => $player): ?>
                <div style="padding: 2px 0; border-bottom: 1px dotted #555; color: #cccccc;">
                    <?= ($index + 1) ?>. <?= htmlspecialchars($player['name']) ?> 
                    <span style="color: #ffff33;">(Lv.<?= $player['level'] ?>)</span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Последние новости -->
    <div style="margin-bottom: 15px;">
        <div style="text-align: center; color: #ff6600; font-weight: bold; margin-bottom: 5px;">
            📰 Последние новости
        </div>
        <div style="font-size: 10px; color: #cccccc;">
            <div style="padding: 3px 0; border-bottom: 1px dotted #555;">
                <a href="#" style="color: #ffff33;">Обновление сервера</a>
                <div style="color: #999;">27.09.2025</div>
            </div>
            <div style="padding: 3px 0; border-bottom: 1px dotted #555;">
                <a href="#" style="color: #ffff33;">Новый рейд доступен</a>
                <div style="color: #999;">25.09.2025</div>
            </div>
            <div style="padding: 3px 0;">
                <a href="#" style="color: #ffff33;">PvP турнир</a>
                <div style="color: #999;">23.09.2025</div>
            </div>
        </div>
    </div>

    <!-- Полезные ссылки -->
    <div>
        <div style="text-align: center; color: #ff6600; font-weight: bold; margin-bottom: 5px;">
            🔗 Полезные ссылки
        </div>
        <div class="info">
            <img src="../public/images/icons/arr.png" alt="." width="12" height="12" class="i12img">
            <a href="#"><span>Правила сервера</span></a>
        </div>
        <div class="info">
            <img src="../public/images/icons/arr.png" alt="." width="12" height="12" class="i12img">
            <a href="#"><span>Discord</span></a>
        </div>
        <div class="info">
            <img src="../public/images/icons/arr.png" alt="." width="12" height="12" class="i12img">
            <a href="#"><span>Баг-репорты</span></a>
        </div>
    </div>
</div>