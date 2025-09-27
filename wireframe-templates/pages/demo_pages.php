<!-- Демонстрация различных страниц wireframe -->

<!-- Домашняя страница -->
<div id="page-home" class="wireframe-page" style="display: block;">
    <!-- Приветствие -->
    <div style="text-align: center; margin-bottom: 20px;">
        <h2 style="color: #ff6600; margin-bottom: 10px;">🏰 Добро пожаловать в AnotherWoW!</h2>
        <p style="color: #cccccc; font-size: 14px;">
            Погрузитесь в мир Азерота на нашем приватном сервере World of Warcraft
        </p>
    </div>

    <!-- Основные блоки информации -->
    <div style="display: flex; gap: 15px; margin-bottom: 20px;">
        <!-- Информация о сервере -->
        <div style="flex: 1; background-color: rgba(51, 51, 102, 0.3); border: 1px solid #555555; border-radius: 5px; padding: 15px;">
            <h3 style="color: #ff6600; margin-bottom: 10px; text-align: center;">⚔️ О Сервере</h3>
            <div style="color: #cccccc; font-size: 13px; line-height: 1.5;">
                <div style="margin-bottom: 5px;"><strong>Версия:</strong> 3.3.5a WotLK</div>
                <div style="margin-bottom: 5px;"><strong>Рейты:</strong> x5 опыт, x3 профессии</div>
                <div style="margin-bottom: 5px;"><strong>Максимальный уровень:</strong> 80</div>
                <div style="margin-bottom: 5px;"><strong>PvP:</strong> Включен</div>
                <div style="margin-bottom: 5px;"><strong>Режим:</strong> Blizzlike+</div>
            </div>
        </div>

        <!-- Статистика -->
        <div style="flex: 1; background-color: rgba(51, 51, 102, 0.3); border: 1px solid #555555; border-radius: 5px; padding: 15px;">
            <h3 style="color: #ff6600; margin-bottom: 10px; text-align: center;">📊 Статистика</h3>
            <div style="color: #cccccc; font-size: 13px; line-height: 1.5;">
                <div style="margin-bottom: 5px;"><strong>Игроков онлайн:</strong> <span style="color: #00ff00;">247</span></div>
                <div style="margin-bottom: 5px;"><strong>Всего аккаунтов:</strong> 15,842</div>
                <div style="margin-bottom: 5px;"><strong>Активных персонажей:</strong> 8,156</div>
                <div style="margin-bottom: 5px;"><strong>Uptime:</strong> <span style="color: #00ff00;">99.2%</span></div>
                <div style="margin-bottom: 5px;"><strong>Средний пинг:</strong> 45ms</div>
            </div>
        </div>
    </div>

    <!-- Последние новости -->
    <div style="background-color: rgba(51, 51, 102, 0.3); border: 1px solid #555555; border-radius: 5px; padding: 15px; margin-bottom: 20px;">
        <h3 style="color: #ff6600; margin-bottom: 15px; text-align: center;">📰 Последние новости</h3>
        
        <div style="border-bottom: 1px solid #555555; padding-bottom: 10px; margin-bottom: 10px;">
            <div style="color: #ffff33; font-weight: bold; margin-bottom: 5px;">
                <a href="#" style="color: #ffff33; text-decoration: none;">🎉 Обновление сервера v2.1</a>
            </div>
            <div style="color: #999999; font-size: 12px; margin-bottom: 5px;">25 сентября 2025</div>
            <div style="color: #cccccc; font-size: 13px;">
                Исправлены критические баги в рейдах, добавлены новые квесты и улучшена производительность сервера.
            </div>
        </div>

        <div style="border-bottom: 1px solid #555555; padding-bottom: 10px; margin-bottom: 10px;">
            <div style="color: #ffff33; font-weight: bold; margin-bottom: 5px;">
                <a href="#" style="color: #ffff33; text-decoration: none;">⚔️ PvP Турнир 2x2</a>
            </div>
            <div style="color: #999999; font-size: 12px; margin-bottom: 5px;">20 сентября 2025</div>
            <div style="color: #cccccc; font-size: 13px;">
                Стартует новый сезон арены! Призы: эпическая экипировка и уникальные маунты.
            </div>
        </div>

        <div>
            <div style="color: #ffff33; font-weight: bold; margin-bottom: 5px;">
                <a href="#" style="color: #ffff33; text-decoration: none;">🏰 Открытие Цитадели Ледяной Короны</a>
            </div>
            <div style="color: #999999; font-size: 12px; margin-bottom: 5px;">15 сентября 2025</div>
            <div style="color: #cccccc; font-size: 13px;">
                Король-лич ждет самых смелых героев! Полностью рабочий контент ICC с уникальными наградами.
            </div>
        </div>
    </div>

    <!-- Призыв к действию -->
    <div style="text-align: center; background-color: rgba(255, 102, 0, 0.1); border: 2px solid #ff6600; border-radius: 8px; padding: 20px;">
        <h3 style="color: #ff6600; margin-bottom: 15px;">🎮 Начни свое приключение уже сегодня!</h3>
        <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
            <a href="#" onclick="showPage('register'); return false;" style="background-color: #ff6600; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">
                📝 Регистрация
            </a>
            <a href="#" style="background-color: #333366; color: #ffff33; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block; border: 1px solid #555555;">
                💾 Скачать клиент
            </a>
            <a href="#" style="background-color: #333366; color: #ffff33; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block; border: 1px solid #555555;">
                💬 Discord
            </a>
        </div>
    </div>
</div>

<!-- Страница входа -->
<div id="page-login" class="wireframe-page" style="display: none;">
    <div style="padding: 20px; text-align: center;">
        <h2 style="color: #ff6600; margin-bottom: 20px;">🔐 Вход в аккаунт</h2>
        
        <div style="max-width: 400px; margin: 0 auto; background-color: rgba(51, 51, 102, 0.3); border: 1px solid #555555; border-radius: 5px; padding: 20px;">
            <form>
                <div style="margin-bottom: 15px; text-align: left;">
                    <label style="color: #cccccc; display: block; margin-bottom: 5px;">Логин:</label>
                    <input type="text" style="width: 100%; padding: 8px; background-color: #333333; color: #cccccc; border: 1px solid #555555; border-radius: 3px;">
                </div>
                
                <div style="margin-bottom: 20px; text-align: left;">
                    <label style="color: #cccccc; display: block; margin-bottom: 5px;">Пароль:</label>
                    <input type="password" style="width: 100%; padding: 8px; background-color: #333333; color: #cccccc; border: 1px solid #555555; border-radius: 3px;">
                </div>
                
                <div style="margin-bottom: 15px;">
                    <button type="button" style="background-color: #ff6600; color: white; padding: 12px 30px; border: none; border-radius: 5px; font-weight: bold; cursor: pointer;">
                        Войти
                    </button>
                </div>
                
                <div style="font-size: 13px; color: #cccccc;">
                    Нет аккаунта? <a href="#" onclick="showPage('register'); return false;" style="color: #ffff33;">Зарегистрироваться</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Функция переключения между страницами
window.showPage = function(pageName) {
    // Скрываем все страницы
    const pages = document.querySelectorAll('.wireframe-page');
    pages.forEach(page => {
        page.style.display = 'none';
    });
    
    // Показываем нужную страницу
    const targetPage = document.getElementById('page-' + pageName);
    if (targetPage) {
        targetPage.style.display = 'block';
        
        // Показываем уведомление о переключении
        if (typeof showNotification === 'function') {
            const pageNames = {
                'home': 'Главная страница',
                'login': 'Вход в аккаунт',
                'register': 'Регистрация',
                'vote': 'Голосование',
                'cabinet': 'Личный кабинет',
                'admin': 'Админ-панель'
            };
            showNotification(`📄 Переключено на: ${pageNames[pageName] || pageName}`, 'info');
        }
    }
};

// Показываем демо-уведомление через 3 секунды
setTimeout(function() {
    if (typeof showNotification === 'function') {
        showNotification('🎉 Добро пожаловать в wireframe демонстрацию AnotherWoW с оригинальным стилем!', 'success');
    }
}, 3000);
</script>