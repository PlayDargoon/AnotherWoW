<!-- Демо всех страниц для wireframe -->

<!-- Главная страница -->
<div class="page-content" id="home">
    <h1 style="text-align: center;">🏰 Добро пожаловать на игровой сервер Azeroth!</h1>
    <br>

    <!-- Системные уведомления -->
    <div style="background-color: rgba(255, 102, 0, 0.1); border: 1px solid #ff6600; padding: 10px; margin: 10px 0; border-radius: 5px;">
        <div class="pt">
            <img src="/images/icons/attention_gold.png" alt="." width="12" height="12"> 
            <span class="bluepost" style="font-size:small;">Сайт работает в тестовом режиме и находится в процессе активной доработки.<br></span>
        </div>
        <div class="pt">
            <img src="/images/icons/attention_gold.png" alt="." width="12" height="12"> 
            <span class="bluepost" style="font-size:small;">На сервере идет Альфа-тестирование.<br><br></span>
        </div>
    </div>

    <!-- Изображение баннера -->
    <div class="pt" style="text-align:center">
        <div style="width: 310px; height: 103px; background: #555; margin: 0 auto; display: flex; align-items: center; justify-content: center; border: 1px solid #777;">
            [БАННЕР СЕРВЕРА]
        </div>
    </div>
    <br>

    <!-- Информация о подключении -->
    <div class="event" style="padding:4px;border:1px solid #CCCCCC;">
        <div class="minor mt5 small">
            <img src="/images/icons/question_blue.png" width="12" height="12" alt="VIP" class="ml5"> 
            <span class="va-m">Уже сейчас вы можете принять участие в тестировании игрового сервера, 
                для этого вам понадобиться скачать клиент WoW 3.3.5 по ссылке ниже, 
                либо использовать свой клиент 3.3.5, необходимо будет заменить данные в realmlist на наш 
                <strong style="color: #ffff33;">set realmlist logon.azeroth.su</strong>. 
                После установки/изменения данных подключения, зарегистрируйтесь на нашем сайте.
            </span>
        </div>
    </div>

    <!-- Статистика сервера -->
    <div style="margin-top: 15px;">
        <h2>📊 Статистика сервера</h2>
        <div class="stat-item">👥 <strong>Игроков онлайн:</strong> 245</div>
        <div class="stat-item">🏆 <strong>Всего персонажей:</strong> 1,247</div>
        <div class="stat-item">👑 <strong>Активных гильдий:</strong> 23</div>
        <div class="stat-item">⚔️ <strong>Версия:</strong> 3.3.5a</div>
    </div>

    <!-- Кнопка скачать клиент -->
    <div style="text-align: center; margin: 20px 0;">
        <a class="headerButtonMy" href="#" style="font-size: 16px; padding: 15px 30px;">
            📥 Скачать клиент WoW 3.3.5
        </a>
    </div>
</div>

<!-- Страница авторизации -->
<div class="page-content" id="login">
    <h1 style="text-align: center;">🔐 Авторизация</h1>
    <br>

    <div style="max-width: 400px; margin: 0 auto; background-color: rgba(51, 51, 102, 0.3); padding: 20px; border: 1px solid #555555; border-radius: 5px;">
        <form>
            <div class="form-group">
                <label>Логин от игрового аккаунта:</label>
                <input type="text" name="username" placeholder="Введите ваш логин">
            </div>
            
            <div class="form-group">
                <label>Пароль:</label>
                <input type="password" name="password" placeholder="Введите пароль">
            </div>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" name="remember"> Запомнить меня на этом устройстве
                </label>
            </div>
            
            <div style="text-align: center; margin-top: 20px;">
                <button type="button" class="headerButtonMy" style="font-size: 16px; padding: 15px 30px;" 
                        onclick="showPage('cabinet'); alert('Добро пожаловать! (демо)');">
                    ВОЙТИ В ИГРУ
                </button>
            </div>
            
            <div style="text-align: center; margin-top: 15px;">
                <a href="#" style="color: #ffff33;">Забыли пароль?</a> | 
                <a href="#" style="color: #ffff33;">Создать аккаунт</a>
            </div>
        </form>
    </div>

    <div style="margin-top: 20px; padding: 15px; background-color: rgba(0, 0, 51, 0.5); border: 1px solid #555; border-radius: 5px;">
        <h2>ℹ️ Информация для входа</h2>
        <div class="minor">
            <p>• Используйте логин и пароль от вашего игрового аккаунта</p>
            <p>• Если у вас нет аккаунта, создайте его через игровой клиент</p>
            <p>• При проблемах с входом обратитесь в поддержку</p>
        </div>
    </div>
</div>

<!-- Кабинет игрока -->
<div class="page-content" id="cabinet">
    <h1>👤 Личный кабинет</h1>
    <br>

    <!-- Информация о профиле -->
    <div class="character-info">
        <h3>📋 Информация об аккаунте</h3>
        <div class="character-stat">
            <span class="label">👤 Имя аккаунта:</span>
            <span class="value">PlayerName</span>
        </div>
        <div class="character-stat">
            <span class="label">📧 Email:</span>
            <span class="value">player@example.com</span>
        </div>
        <div class="character-stat">
            <span class="label">💰 Баланс монет:</span>
            <span class="value">150</span>
        </div>
        <div class="character-stat">
            <span class="label">📅 Дата регистрации:</span>
            <span class="value">15.09.2025</span>
        </div>
        <div class="character-stat">
            <span class="label">🕐 Последний вход:</span>
            <span class="value">27.09.2025 21:45</span>
        </div>
    </div>

    <!-- Управление аккаунтом -->
    <div style="margin: 15px 0;">
        <h2>⚙️ Управление аккаунтом</h2>
        <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 10px;">
            <button class="headerButton" onclick="alert('Изменение пароля (демо)')">🔑 Изменить пароль</button>
            <button class="headerButton" onclick="alert('История монет (демо)')">💰 История монет</button>
            <button class="headerButton" onclick="alert('Настройки (демо)')">⚙️ Настройки</button>
        </div>
    </div>

    <!-- Таблица персонажей -->
    <div style="margin-top: 15px;">
        <h2>⚔️ Мои персонажи</h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Имя персонажа</th>
                    <th>Уровень</th>
                    <th>Класс</th>
                    <th>Гильдия</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>WarriorHero</td>
                    <td>80</td>
                    <td>⚔️ Воин</td>
                    <td>Best Guild</td>
                    <td>
                        <button class="btn" onclick="alert('Просмотр персонажа: WarriorHero')">Просмотр</button>
                    </td>
                </tr>
                <tr>
                    <td>MageWizard</td>
                    <td>75</td>
                    <td>🔮 Маг</td>
                    <td>-</td>
                    <td>
                        <button class="btn" onclick="alert('Просмотр персонажа: MageWizard')">Просмотр</button>
                    </td>
                </tr>
                <tr>
                    <td>HolyHealer</td>
                    <td>68</td>
                    <td>✨ Жрец</td>
                    <td>Healers United</td>
                    <td>
                        <button class="btn" onclick="alert('Просмотр персонажа: HolyHealer')">Просмотр</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- История монет -->
    <div style="margin-top: 15px;">
        <h2>💰 Последние операции с монетами</h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Дата</th>
                    <th>Операция</th>
                    <th>Количество</th>
                    <th>Баланс</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>27.09.2025</td>
                    <td>Голосование (MMOTOP)</td>
                    <td>+1</td>
                    <td>150</td>
                </tr>
                <tr>
                    <td>26.09.2025</td>
                    <td>Голосование (TOP100WOW)</td>
                    <td>+2</td>
                    <td>149</td>
                </tr>
                <tr>
                    <td>25.09.2025</td>
                    <td>Голосование (MMOTOP)</td>
                    <td>+1</td>
                    <td>147</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Страница голосования -->
<div class="page-content" id="vote">
    <h1>🗳️ Голосование за сервер</h1>
    <br>

    <!-- Баннер -->
    <div class="pt" style="text-align:center">
        <div style="width: 310px; height: 103px; background: #1a4d1a; margin: 0 auto; display: flex; align-items: center; justify-content: center; border: 1px solid #2d5d2d;">
            [БАННЕР ГОЛОСОВАНИЯ]
        </div>
    </div>
    <br>

    <!-- Описание -->
    <div class="small pt">
        <img src="/images/icons/menialo.png" alt="*" style="float:left;margin-right:8px;" class="ic32" width="32" height="32">
        Приветствую тебя, герой! У Вас появилась возможность получать бонусы в игре, путем голосования за Azeroth на сайтах с рейтингами сервера. 
        Я расскажу как проголосовать и условие получения награды. На данный момент для голосования подключены MMOTOP и TOP100WOW.
    </div>
    <br>

    <!-- Список рейтингов -->
    <div style="margin: 20px 0;">
        <h2>🏆 Доступные рейтинги</h2>

        <!-- MMOTOP -->
        <div class="vote-item" style="margin: 15px 0;">
            <h4>🥇 MMOTOP.RU</h4>
            <div class="stat-item">💰 <strong>Награда:</strong> 1 монета</div>
            <div class="stat-item">⏰ <strong>Периодичность:</strong> каждые 16 часов</div>
            <div class="stat-item">✅ <strong>Статус:</strong> Доступно для голосования</div>
            <div style="text-align: center; margin-top: 15px;">
                <button class="vote-button" onclick="alert('Переход на MMOTOP для голосования')">
                    Я голосую за AZEROTH!
                </button>
            </div>
        </div>

        <!-- TOP100WOW -->
        <div class="vote-item vote-item-cooldown" style="margin: 15px 0;">
            <h4>🥈 TOP100WOW.RU</h4>
            <div class="stat-item">💰 <strong>Награда:</strong> 2 монеты</div>
            <div class="stat-item">⏰ <strong>Периодичность:</strong> каждые 12 часов</div>
            <div class="stat-item">❌ <strong>Статус:</strong> Ожидание (через 8 часов)</div>
            <div style="text-align: center; margin-top: 15px;">
                <button class="vote-button vote-button-disabled" disabled>
                    Ожидание... ⏰
                </button>
            </div>
        </div>
    </div>

    <!-- Инструкция по голосованию -->
    <div style="margin: 20px 0;">
        <h2>❓ Как голосовать?</h2>
        <ul style="list-style: none; padding: 0;">
            <li style="padding: 5px 0; border-bottom: 1px dotted #555;">
                <img src="/images/icons/arr1.png" alt=">" width="12" height="12"> 
                Авторизируетесь на сайте рейтинга через ВК/ФБ или создайте аккаунт
            </li>
            <li style="padding: 5px 0; border-bottom: 1px dotted #555;">
                <img src="/images/icons/arr1.png" alt=">" width="12" height="12"> 
                Сдвигаете ползунок или нажимаете кнопку голосования
            </li>
            <li style="padding: 5px 0; border-bottom: 1px dotted #555;">
                <img src="/images/icons/arr1.png" alt=">" width="12" height="12"> 
                Вводите <span class="yellow">ЛОГИН</span> от игрового аккаунта (НЕ ник персонажа!)
            </li>
            <li style="padding: 5px 0; border-bottom: 1px dotted #555;">
                <img src="/images/icons/arr1.png" alt=">" width="12" height="12"> 
                Ждете уведомления о зачислении голоса
            </li>
            <li style="padding: 5px 0;">
                <img src="/images/icons/arr1.png" alt=">" width="12" height="12"> 
                Если сайт недоступен, попробуйте через VPN
            </li>
        </ul>
    </div>

    <!-- Правила начисления -->
    <div style="margin: 20px 0;">
        <h2>💡 Как начисляются монеты?</h2>
        <ul style="list-style: none; padding: 0;">
            <li style="padding: 5px 0; border-bottom: 1px dotted #555;">
                <img src="/images/icons/arr1.png" alt=">" width="12" height="12"> 
                За каждый голос начисляется указанное количество монет
            </li>
            <li style="padding: 5px 0; border-bottom: 1px dotted #555;">
                <img src="/images/icons/arr1.png" alt=">" width="12" height="12"> 
                Голосовать можно согласно периодичности каждого рейтинга
            </li>
            <li style="padding: 5px 0; border-bottom: 1px dotted #555;">
                <img src="/images/icons/arr1.png" alt=">" width="12" height="12"> 
                Монеты начисляются в течение часа после голосования
            </li>
            <li style="padding: 5px 0; border-bottom: 1px dotted #555;">
                <img src="/images/icons/arr1.png" alt=">" width="12" height="12"> 
                Голос засчитывается только при правильном указании логина
            </li>
            <li style="padding: 5px 0;">
                <img src="/images/icons/arr1.png" alt=">" width="12" height="12"> 
                При проблемах обращайтесь в поддержку
            </li>
        </ul>
    </div>

    <!-- Статистика голосования -->
    <div style="margin: 20px 0;">
        <h2>📊 Ваша статистика</h2>
        <div class="stat-item">💰 <strong>Получено монет за голосование:</strong> 45</div>
        <div class="stat-item">🗳️ <strong>Всего голосов:</strong> 67</div>
        <div class="stat-item">📅 <strong>Последний голос:</strong> 27.09.2025 20:15</div>
        <div class="stat-item">🏆 <strong>Место в рейтинге голосующих:</strong> 12</div>
    </div>

    <p style="text-align: center; margin: 20px 0; font-weight: bold; color: #ffff33;">
        Спасибо за вашу поддержку! Каждый голос важен для развития сервера.
    </p>
</div>

<!-- Админ-панель -->
<div class="page-content" id="admin">
    <h1 style="color: #ff6600;">⚙️ Панель администратора</h1>
    <br>

    <div style="background-color: rgba(102, 0, 0, 0.2); border: 1px solid #660000; padding: 10px; margin: 10px 0; border-radius: 5px; text-align: center;">
        <strong>⚠️ ВНИМАНИЕ:</strong> Вы находитесь в режиме администратора. Будьте осторожны с выполняемыми действиями!
    </div>

    <!-- Быстрые действия -->
    <div class="admin-section">
        <h3>🚀 Быстрые действия</h3>
        <div class="admin-buttons">
            <button class="admin-button" onclick="alert('Управление пользователями (демо)')">👥 Пользователи</button>
            <button class="admin-button" onclick="alert('Управление новостями (демо)')">📰 Новости</button>
            <button class="admin-button" onclick="alert('Система монет (демо)')">💰 Монеты</button>
            <button class="admin-button" onclick="alert('Настройки голосования (демо)')">🗳️ Голосование</button>
            <button class="admin-button" onclick="alert('Статистика сервера (демо)')">📊 Статистика</button>
            <button class="admin-button" onclick="alert('Управление сервером (демо)')">🖥️ Сервер</button>
        </div>
    </div>

    <!-- Системная информация -->
    <div class="admin-section">
        <h3>🖥️ Системная информация</h3>
        <div class="stat-item">🖥️ <strong>Статус серверов:</strong> Auth: ✅ | World: ✅ | Characters: ✅</div>
        <div class="stat-item">👥 <strong>Игроков онлайн:</strong> 245 (максимум: 312)</div>
        <div class="stat-item">💾 <strong>Использование кеша:</strong> 15MB / 128MB (12%)</div>
        <div class="stat-item">📊 <strong>Производительность БД:</strong> 95% (отлично)</div>
        <div class="stat-item">⏱️ <strong>Аптайм сервера:</strong> 12 дней 15 часов (99.2%)</div>
        <div class="stat-item">🔄 <strong>Последняя синхронизация голосов:</strong> 5 минут назад</div>
    </div>

    <!-- Статистика пользователей -->
    <div class="admin-section">
        <h3>👥 Статистика пользователей</h3>
        <div class="stat-item">📝 <strong>Всего аккаунтов:</strong> 1,247</div>
        <div class="stat-item">🎮 <strong>Активных игроков (за месяц):</strong> 892</div>
        <div class="stat-item">🆕 <strong>Новых регистраций (сегодня):</strong> 12</div>
        <div class="stat-item">👑 <strong>Администраторов:</strong> 5</div>
        <div class="stat-item">🛡️ <strong>Модераторов:</strong> 15</div>
    </div>

    <!-- Логи последних действий -->
    <div class="admin-section">
        <h3>📋 Последние системные события</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Время</th>
                    <th>Тип</th>
                    <th>Описание</th>
                    <th>Пользователь</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>21:45</td>
                    <td>💰 Монеты</td>
                    <td>Начислено 1 монету за голосование</td>
                    <td>PlayerName</td>
                </tr>
                <tr>
                    <td>21:40</td>
                    <td>🗳️ Голос</td>
                    <td>Получен голос с MMOTOP</td>
                    <td>System</td>
                </tr>
                <tr>
                    <td>21:35</td>
                    <td>👥 Вход</td>
                    <td>Пользователь авторизовался</td>
                    <td>NewPlayer</td>
                </tr>
                <tr>
                    <td>21:30</td>
                    <td>⚙️ Система</td>
                    <td>Очистка кеша выполнена</td>
                    <td>Admin</td>
                </tr>
                <tr>
                    <td>21:25</td>
                    <td>📰 Новость</td>
                    <td>Опубликована новость "Обновление"</td>
                    <td>Admin</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Управление системой -->
    <div class="admin-section">
        <h3>🔧 Управление системой</h3>
        <div class="admin-buttons">
            <button class="admin-button" style="background-color: #cc6600;" onclick="alert('Очистка кеша (демо)')">🗑️ Очистить кеш</button>
            <button class="admin-button" style="background-color: #cc6600;" onclick="alert('Синхронизация голосов (демо)')">🔄 Синхронизировать голоса</button>
            <button class="admin-button" style="background-color: #006600;" onclick="alert('Резервное копирование (демо)')">💾 Backup БД</button>
            <button class="admin-button" style="background-color: #990000;" onclick="confirm('Вы уверены, что хотите перезагрузить сервер? (демо)') && alert('Перезагрузка сервера (демо)')">🔄 Перезагрузка</button>
        </div>
    </div>
</div>