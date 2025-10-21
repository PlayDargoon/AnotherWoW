<!-- templates/pages/bot-commands.html.php (premium) -->
<div class="cabinet-page document-page">
    <h1>Команды игровых ботов</h1>

    <div class="cabinet-card">
        <div class="cabinet-card-title">
            <img src="/images/icons/menialo.png" width="20" height="20" alt="*">
            О системе ботов
        </div>
        <div class="document-text">
            На сервере доступна система игровых ботов-компаньонов, которые могут помочь вам в игре. Боты реагируют на команды в чате и могут выполнять различные действия: сражаться, лечить, следовать за вами, выполнять квесты и многое другое.
        </div>
        <div class="document-text" style="margin-top:8px; padding:8px; background:rgba(255,200,0,0.1); border-left:3px solid #ffc800;">
            <strong>Важно:</strong> Различаются "Альтботы" (созданные вручную персонажи) и "Рандботы" (автоматически генерируемые ботами системой).
        </div>
    </div>

    <div class="cabinet-card">
        <div class="cabinet-card-title">
            <img src="/images/icons/guild_light.png" width="20" height="20" alt="*">
            Настройка альтботов
        </div>

        <div class="document-section">
            <div class="document-subtitle">Базовое управление ботами</div>
            <div class="table-responsive">
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th style="width:50%;">Команда</th>
                            <th style="width:50%;">Описание</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td><code>.playerbots bot add [имя1,имя2,имя3]</code></td><td>Подключить альтботов</td></tr>
                        <tr><td><code>.playerbots bot remove [имя1,имя2,имя3]</code></td><td>Отключить альтботов</td></tr>
                        <tr><td><code>.playerbots bot add *</code></td><td>Подключить всех ботов из группы/рейда</td></tr>
                        <tr><td><code>.playerbots bot remove *</code></td><td>Отключить всех ботов из группы/рейда</td></tr>
                        <tr><td><code>.playerbots bot addaccount [имя_аккаунта]</code></td><td>Подключить весь аккаунт альтботов</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="document-section">
            <div class="document-subtitle">Настройка персонажа</div>
            <div class="table-responsive">
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th style="width:40%;">Команда</th>
                            <th style="width:60%;">Описание</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td><code>maintenance</code></td><td>Изучить заклинания, пополнить расходники, зачаровать снаряжение</td></tr>
                        <tr><td><code>autogear</code></td><td>Автоматически экипировать бота</td></tr>
                        <tr><td><code>talents</code></td><td>Показать текущую специализацию бота</td></tr>
                        <tr><td><code>talents spec list</code></td><td>Показать доступные специализации</td></tr>
                        <tr><td><code>talents spec [имя_спека]</code></td><td>Изменить специализацию бота</td></tr>
                        <tr><td><code>reset botAI</code></td><td>Сбросить настройки ИИ бота</td></tr>
                        <tr><td><code>reset</code></td><td>Прервать текущие действия бота</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="cabinet-card">
        <div class="cabinet-card-title">
            <img src="/images/icons/game_master.png" width="20" height="20" alt="*">
            Команды группы/рейда
        </div>

        <div class="document-section">
            <div class="document-subtitle">Основные команды</div>
            <div class="table-responsive">
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th style="width:25%;">Команда</th>
                            <th style="width:75%;">Описание</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td><code>summon</code></td><td>Призвать бота к себе</td></tr>
                        <tr><td><code>follow</code></td><td>Заставить бота следовать за вами</td></tr>
                        <tr><td><code>stay</code></td><td>Заставить бота остаться на месте</td></tr>
                        <tr><td><code>attack</code></td><td>Атаковать выбранную цель</td></tr>
                        <tr><td><code>flee</code></td><td>Бежать к игроку, игнорируя всё остальное</td></tr>
                        <tr><td><code>grind</code></td><td>Атаковать всё вокруг</td></tr>
                        <tr><td><code>leave</code></td><td>Покинуть группу</td></tr>
                        <tr><td><code>give leader</code></td><td>Передать лидерство группы мастеру</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="document-section">
            <div class="document-subtitle">Групповые фильтры</div>
            <div class="document-text">
                Вы можете указывать конкретные группы для команд:
            </div>
            <ul class="document-list">
                <li><code>@group1 follow</code> — команда для 1-й группы</li>
                <li><code>@tank attack</code> — команда для танков</li>
                <li><code>@heal follow</code> — команда для лекарей</li>
                <li><code>@dps attack</code> — команда для бойцов</li>
                <li><code>@warrior stay</code> — команда для воинов</li>
            </ul>
        </div>
    </div>

    <div class="cabinet-card">
        <div class="cabinet-card-title">
            <img src="/images/icons/scroll_gold.png" width="20" height="20" alt="*">
            Заклинания и способности
        </div>
        <div class="table-responsive">
            <table class="premium-table">
                <thead>
                    <tr>
                        <th style="width:45%;">Команда</th>
                        <th style="width:55%;">Описание</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td><code>spells</code></td><td>Показать заклинания бота</td></tr>
                    <tr><td><code>cast [заклинание]</code></td><td>Произнести заклинание</td></tr>
                    <tr><td><code>cast [заклинание] on [игрок]</code></td><td>Произнести заклинание на указанного игрока</td></tr>
                    <tr><td><code>ss +[ID_заклинания]</code></td><td>Добавить заклинание в список исключений</td></tr>
                    <tr><td><code>ss -[ID_заклинания]</code></td><td>Убрать заклинание из списка исключений</td></tr>
                    <tr><td><code>ss reset</code></td><td>Очистить список исключённых заклинаний</td></tr>
                    <tr><td><code>trainer</code></td><td>Показать что можно изучить у выбранного учителя</td></tr>
                    <tr><td><code>trainer learn</code></td><td>Изучить всё у выбранного учителя</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="cabinet-card">
        <div class="cabinet-card-title">
            <img src="/images/icons/star_gold.png" width="20" height="20" alt="*">
            Стратегии боя
        </div>

        <div class="document-section">
            <div class="document-subtitle">Общие стратегии</div>
            <div class="document-text">
                Используйте команды <code>co +стратегия</code> или <code>co -стратегия</code> для управления:
            </div>
            <div class="table-responsive">
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th style="width:20%;">Стратегия</th>
                            <th style="width:80%;">Описание</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td><code>tank</code></td><td>Использовать способности удержания агро</td></tr>
                        <tr><td><code>dps</code></td><td>Использовать способности нанесения урона</td></tr>
                        <tr><td><code>heal</code></td><td>Сосредоточиться на лечении группы</td></tr>
                        <tr><td><code>cc</code></td><td>Использовать способности контроля</td></tr>
                        <tr><td><code>assist</code></td><td>Атаковать по одной цели</td></tr>
                        <tr><td><code>aoe</code></td><td>Атаковать множество целей</td></tr>
                        <tr><td><code>boost</code></td><td>Использовать мощные способности</td></tr>
                        <tr><td><code>threat</code></td><td>Избегать получения агро</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="document-section">
            <div class="document-subtitle">Неигровые стратегии</div>
            <div class="document-text">
                Используйте команды <code>nc +стратегия</code> или <code>nc -стратегия</code>:
            </div>
            <div class="table-responsive">
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th style="width:20%;">Стратегия</th>
                            <th style="width:80%;">Описание</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td><code>loot</code></td><td>Включить сбор добычи</td></tr>
                        <tr><td><code>food</code></td><td>Есть/пить для восстановления</td></tr>
                        <tr><td><code>pvp</code></td><td>Включить/выключить PvP режим</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="cabinet-card">
        <div class="cabinet-card-title">
            <img src="/images/icons/amulet.png" width="20" height="20" alt="*">
            Предметы и добыча
        </div>

        <div class="document-section">
            <div class="document-subtitle">Управление предметами</div>
            <div class="table-responsive">
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th style="width:30%;">Команда</th>
                            <th style="width:70%;">Описание</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td><code>e [предмет]</code></td><td>Экипировать предмет</td></tr>
                        <tr><td><code>ue [предмет]</code></td><td>Снять предмет</td></tr>
                        <tr><td><code>u [предмет]</code></td><td>Использовать предмет</td></tr>
                        <tr><td><code>s [предмет]</code></td><td>Продать предмет</td></tr>
                        <tr><td><code>s *</code></td><td>Продать все серые предметы</td></tr>
                        <tr><td><code>b [предмет]</code></td><td>Купить предмет</td></tr>
                        <tr><td><code>destroy [предмет]</code></td><td>Уничтожить предмет</td></tr>
                        <tr><td><code>roll [предмет]</code></td><td>Боты будут бросать кости на предмет</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="document-section">
            <div class="document-subtitle">Настройки добычи</div>
            <div class="table-responsive">
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th style="width:25%;">Команда</th>
                            <th style="width:75%;">Описание</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td><code>ll all</code></td><td>Собирать всё</td></tr>
                        <tr><td><code>ll normal</code></td><td>Собирать всё кроме персональных предметов</td></tr>
                        <tr><td><code>ll gray</code></td><td>Собирать только серые предметы</td></tr>
                        <tr><td><code>ll quest</code></td><td>Собирать только квестовые предметы</td></tr>
                        <tr><td><code>ll skill</code></td><td>Собирать предметы для профессий</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="cabinet-card">
        <div class="cabinet-card-title">
            <img src="/images/icons/scroll.png" width="20" height="20" alt="*">
            Квесты
        </div>
        <div class="table-responsive">
            <table class="premium-table">
                <thead>
                    <tr>
                        <th style="width:30%;">Команда</th>
                        <th style="width:70%;">Описание</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td><code>quests</code></td><td>Показать сводку по квестам</td></tr>
                    <tr><td><code>quests all</code></td><td>Показать все квесты со ссылками</td></tr>
                    <tr><td><code>accept [квест]</code></td><td>Принять квест</td></tr>
                    <tr><td><code>accept *</code></td><td>Принять все доступные квесты</td></tr>
                    <tr><td><code>drop [квест]</code></td><td>Отказаться от квеста</td></tr>
                    <tr><td><code>r [предмет]</code></td><td>Выбрать награду за квест</td></tr>
                    <tr><td><code>talk</code></td><td>Поговорить с выбранным NPC</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="cabinet-card">
        <div class="cabinet-card-title">
            <img src="/images/icons/pet.png" width="20" height="20" alt="*">
            Питомцы
        </div>

        <div class="document-section">
            <div class="document-subtitle">Общие команды для питомцев</div>
            <div class="table-responsive">
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th style="width:30%;">Команда</th>
                            <th style="width:70%;">Описание</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td><code>pet aggressive</code></td><td>Агрессивная стойка питомца</td></tr>
                        <tr><td><code>pet defensive</code></td><td>Защитная стойка питомца</td></tr>
                        <tr><td><code>pet passive</code></td><td>Пассивная стойка питомца</td></tr>
                        <tr><td><code>pet attack</code></td><td>Питомец атакует выбранную цель</td></tr>
                        <tr><td><code>pet follow</code></td><td>Питомец следует за хозяином</td></tr>
                        <tr><td><code>pet stay</code></td><td>Питомец остается на месте</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="document-section">
            <div class="document-subtitle">Команды приручения для охотников</div>
            <div class="table-responsive">
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th style="width:40%;">Команда</th>
                            <th style="width:60%;">Описание</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td><code>tame</code></td><td>Помощь по приручению</td></tr>
                        <tr><td><code>tame name "имя"</code></td><td>Призвать питомца по имени</td></tr>
                        <tr><td><code>tame family "семейство"</code></td><td>Призвать случайного питомца из семейства</td></tr>
                        <tr><td><code>tame rename "новое_имя"</code></td><td>Переименовать текущего питомца</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="cabinet-card">
        <div class="cabinet-card-title">
            <img src="/images/icons/comment_blue.png" width="20" height="20" alt="*">
            Разные команды
        </div>
        <div class="table-responsive">
            <table class="premium-table">
                <thead>
                    <tr>
                        <th style="width:25%;">Команда</th>
                        <th style="width:75%;">Описание</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td><code>stats</code></td><td>Показать статистику бота</td></tr>
                    <tr><td><code>who</code></td><td>Информация о боте: раса, класс, уровень, зона</td></tr>
                    <tr><td><code>home</code></td><td>Установить дом у выбранного трактирщика</td></tr>
                    <tr><td><code>los</code></td><td>Показать видимые объекты, предметы, существ</td></tr>
                    <tr><td><code>help</code></td><td>Показать все доступные команды</td></tr>
                    <tr><td><code>2g 3s 5c</code></td><td>Дать вам золото (пример: 2 золота 3 серебра 5 меди)</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="cabinet-card">
        <div class="cabinet-card-title">
            <img src="/images/icons/star_gold.png" width="20" height="20" alt="*">
            Автоматические реакции ботов
        </div>
        <div class="document-text">
            Боты автоматически выполняют следующие действия, когда лидер группы:
        </div>
        <ul class="document-list">
            <li>Принимает квест — бот также принимает его</li>
            <li>Говорит с квестодателем — бот сдает свои выполненные квесты</li>
            <li>Использует камень встреч — бот телепортируется</li>
            <li>Садится на маунта — бот также садится на маунта</li>
            <li>Проходит через портал подземелья — бот следует за вами</li>
            <li>Открывает торговое окно — бот показывает инвентарь</li>
            <li>Начинает проверку готовности рейда — бот сообщает свой статус</li>
        </ul>
    </div>

    <div class="cabinet-card">
        <div class="cabinet-card-title">
            <img src="/images/icons/question_gold.png" width="20" height="20" alt="?">
            Полезные советы
        </div>
        <ul class="document-list">
            <li><strong>Приоритет команд:</strong> Команды в личном сообщении (/w) выполняются одним ботом, в групповом чате (/p) или рейде (/r) — всеми ботами</li>
            <li><strong>Сложные команды:</strong> Можно комбинировать стратегии: <code>co +tank,+threat,-aoe</code></li>
            <li><strong>Проверка стратегий:</strong> Используйте <code>co ?</code> и <code>nc ?</code> для просмотра активных стратегий</li>
            <li><strong>Экстренная остановка:</strong> Команда <code>reset</code> прерывает все действия бота</li>
            <li><strong>Обучение:</strong> Регулярно используйте <code>maintenance</code> для поддержания ботов в хорошем состоянии</li>
        </ul>
    </div>

    <div class="login-links">
        <a class="link-item" href="/"><img src="/images/icons/home.png" width="12" height="12" alt="*"> На главную</a>
        <a class="link-item" href="/help"><img src="/images/icons/arr_left.png" width="12" height="12" alt="*"> Назад к помощи</a>
        <a class="link-item" href="/support"><img src="/images/icons/question_blue.png" width="12" height="12" alt="*"> Поддержка</a>
    </div>
</div>
