<div class="body">
    <h1 style="text-align: center;">Меню</h1>
    <br>

    <!-- Навигационные ссылки -->
    <div class="info">
        <img src="../public/images/icons/arr.png" alt="." width="12" height="12" class="i12img">
        <a href="#" onclick="showPage('home'); return false;"><span>Главная страница</span></a>
    </div>

    <?php if (!empty($userInfo)): ?>
        <!-- Для авторизованных пользователей -->
        <div class="info">
            <img src="../public/images/icons/arr.png" alt="." width="12" height="12" class="i12img">
            <a href="#" onclick="showPage('cabinet'); return false;"><span>Личный кабинет</span></a>
        </div>
        
        <div class="info">
            <img src="../public/images/icons/arr.png" alt="." width="12" height="12" class="i12img">
            <a href="#" onclick="showPage('vote'); return false;"><span>Голосовать за сервер</span></a>
        </div>

        <!-- Информация о пользователе -->
        <div style="margin-top: 15px; padding: 10px; background-color: rgba(51, 51, 102, 0.3); border: 1px solid #555555; border-radius: 3px;">
            <div style="text-align: center; color: #ff6600; font-weight: bold; margin-bottom: 5px;">
                Профиль
            </div>
            <div style="font-size: 11px;">
                <div>👤 <?= htmlspecialchars($userInfo['username'] ?? 'PlayerName') ?></div>
                <div>💰 <?= $userInfo['balance'] ?? 150 ?> монет</div>
                <div>📅 Онлайн: <?= date('H:i') ?></div>
            </div>
        </div>

        <?php if (!empty($userInfo['isAdmin'])): ?>
            <!-- Для администраторов -->
            <div class="info">
                <img src="../public/images/icons/arr.png" alt="." width="12" height="12" class="i12img">
                <a href="#" onclick="showPage('admin'); return false;"><span style="color: #ff6600;">Админ-панель</span></a>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <!-- Для неавторизованных пользователей -->
        <div class="info">
            <img src="../public/images/icons/arr.png" alt="." width="12" height="12" class="i12img">
            <a href="#" onclick="showPage('login'); return false;"><span>Вход</span></a>
        </div>
        
        <div class="info">
            <img src="../public/images/icons/arr.png" alt="." width="12" height="12" class="i12img">
            <a href="/register"><span>Регистрация</span></a>
        </div>

        <!-- Форма быстрого входа -->
        <div style="margin-top: 15px; padding: 10px; background-color: rgba(51, 51, 102, 0.3); border: 1px solid #555555; border-radius: 3px;">
            <div style="text-align: center; color: #ff6600; font-weight: bold; margin-bottom: 10px;">
                Быстрый вход
            </div>
            <form>
                <div class="form-group">
                    <input type="text" placeholder="Логин" style="margin-bottom: 5px;">
                    <input type="password" placeholder="Пароль" style="margin-bottom: 10px;">
                    <button type="button" class="btn" style="width: 100%;" onclick="showPage('cabinet'); alert('Вход выполнен (демо)');">
                        ВОЙТИ
                    </button>
                </div>
            </form>
        </div>
    <?php endif; ?>

    <!-- Дополнительные ссылки -->
    <div class="info">
        <img src="../public/images/icons/arr.png" alt="." width="12" height="12" class="i12img">
        <a href="#"><span>Скачать клиент</span></a>
    </div>

    <div class="info">
        <img src="../public/images/icons/arr.png" alt="." width="12" height="12" class="i12img">
        <a href="#"><span>Форум</span></a>
    </div>

    <div class="info">
        <img src="../public/images/icons/arr.png" alt="." width="12" height="12" class="i12img">
        <a href="#"><span>Новости</span></a>
    </div>

    <div class="info">
        <img src="../public/images/icons/arr.png" alt="." width="12" height="12" class="i12img">
        <a href="#"><span>Поддержка</span></a>
    </div>
</div>