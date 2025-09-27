<div class="header-navigation">
    <div style="display: flex; justify-content: space-between; align-items: center; width: 100%; max-width: 740px; margin: 0 auto; padding: 0 20px;">
        <!-- Логотип/название сервера -->
        <div style="display: flex; align-items: center;">
            <img src="../public/images/game-icon.jpg" alt="Azeroth" width="32" height="32" style="margin-right: 10px;">
            <span style="font-size: 18px; font-weight: bold; color: #ff6600;">AZEROTH</span>
        </div>
        
        <!-- Информация о пользователе -->
        <div style="display: flex; align-items: center; gap: 15px;">
            <span style="color: #ffff33;">
                👤 <strong><?= htmlspecialchars($userInfo['username'] ?? 'PlayerName') ?></strong>
            </span>
            <span style="color: #ffff33;">
                💰 <strong><?= $userInfo['balance'] ?? 150 ?> монет</strong>
            </span>
            <a href="/logout" class="btn" style="margin-left: 10px;">Выход</a>
        </div>
    </div>
</div>