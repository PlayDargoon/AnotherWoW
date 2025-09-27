# Техническая спецификация UI/UX для AnotherWoW

## Обзор системы интерфейсов

Проект AnotherWoW использует серверный рендеринг с PHP и минимальным JavaScript для создания интерфейса игрового сервера World of Warcraft. Интерфейс построен на принципах адаптивного дизайна и модульной архитектуры.

---

## Файловая структура интерфейса

### Templates Structure
```
templates/
├── layout.html.php              # Основной макет
├── pages/                       # Страницы приложения
│   ├── index.html.php          # Главная страница
│   ├── login.html.php          # Авторизация
│   ├── cabinet.html.php        # Кабинет игрока
│   ├── vote.html.php           # Голосование
│   ├── admin_panel.html.php    # Админ-панель
│   └── character_page.html.php # Профиль персонажа
└── partials/                    # Переиспользуемые компоненты
    ├── header.html.php         # Шапка сайта
    ├── left_block.html.php     # Левое меню
    ├── right_block.html.php    # Правая панель
    └── footer.html.php         # Подвал
```

### Assets Structure
```
public/
├── css/
│   └── style.css               # Основные стили
├── js/
│   └── notify.js               # JavaScript для уведомлений
└── images/                     # Изображения и иконки
    ├── icons/                  # UI иконки 12x12, 32x32
    ├── game-icon.jpg           # Иконка приложения
    └── logo.jpg                # Логотип проекта
```

---

## Технические требования к компонентам

### 1. Layout Template (layout.html.php)

#### Назначение
Базовый макет, определяющий общую структуру всех страниц.

#### Ключевые особенности
- Мобильная адаптация через meta-тег viewport
- SEO-оптимизация с мета-тегами Open Graph
- Система уведомлений в верхней части
- Трёхколоночная сетка (left-content-right)

#### Входные данные
```php
$pageTitle = 'Заголовок страницы';
$userInfo = ['username' => 'Player', 'balance' => 150];
$notificationsData = [
    'notifications' => [...],
    'username' => 'Player'
];
```

#### CSS Grid Structure
```css
.main-layout {
    display: grid;
    grid-template-columns: 200px 1fr 200px;
    min-height: 600px;
}

@media (max-width: 768px) {
    .main-layout {
        grid-template-columns: 1fr;
        grid-template-rows: auto auto auto;
    }
}
```

### 2. Header Component (header.html.php)

#### Функциональность
- Отображается только для авторизованных пользователей
- Показывает логотип, имя пользователя и баланс
- Адаптивная навигация

#### Требования к данным
```php
$headerData = [
    'userInfo' => [
        'username' => string,      // Обязательно
        'balance' => int,          // Баланс монет
        'isAdmin' => bool          // Права администратора
    ]
];
```

### 3. Left Block Component (left_block.html.php)

#### Режимы отображения
- **Гость**: Форма авторизации + основная навигация
- **Пользователь**: Профиль + расширенная навигация + быстрые ссылки
- **Администратор**: Дополнительные пункты админ-меню

#### Структура навигации
```php
$navigationItems = [
    ['url' => '/', 'label' => 'Главная', 'icon' => '🏠', 'access' => 'all'],
    ['url' => '/cabinet', 'label' => 'Кабинет', 'icon' => '👤', 'access' => 'user'],
    ['url' => '/admin', 'label' => 'Админ-панель', 'icon' => '⚙️', 'access' => 'admin']
];
```

#### CSS компонентов
```css
.nav-item {
    display: block;
    color: #66b3ff;
    text-decoration: none;
    padding: 5px 10px;
    margin: 2px 0;
    border: 1px dashed #666;
    text-align: center;
    font-size: 12px;
}

.nav-item:hover {
    background: #0066cc;
}
```

### 4. Right Block Component (right_block.html.php)

#### Секции блока
1. **Server Info** - Статус сервера, онлайн, аптайм
2. **Quick Vote** - Быстрые кнопки голосования
3. **Top Players** - Рейтинг игроков
4. **Advertising** - Рекламные баннеры

#### Обновление данных
```javascript
// Автообновление статуса сервера каждые 30 сек
setInterval(function() {
    fetch('/api/server/status')
        .then(response => response.json())
        .then(data => updateServerInfo(data));
}, 30000);
```

### 5. Notifications System

#### Типы уведомлений
- `vote_reward` - Награда за голосование
- `system_message` - Системные сообщения
- `error` - Ошибки
- `success` - Успешные операции

#### Структура уведомления
```php
$notification = [
    'id' => int,              // Уникальный ID
    'type' => string,         // Тип уведомления
    'message' => string,      // Текст сообщения
    'data' => array,          // Дополнительные данные
    'created_at' => datetime, // Время создания
    'is_read' => bool         // Статус прочтения
];
```

#### JavaScript обработка
```javascript
// Скрытие уведомления
function hideNotification(notificationId) {
    fetch('/notify-hide.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({id: notificationId})
    })
    .then(() => {
        document.getElementById('notify-' + notificationId).remove();
    });
}
```

---

## Спецификации страниц

### 1. Home Page (index.html.php)

#### Контент-блоки
```html
<!-- Welcome Banner -->
<h1>Добро пожаловать на игровой сервер Azeroth!</h1>

<!-- System Notifications -->
<div class="system-alerts">
    <div class="alert">⚠️ Сайт в тестовом режиме</div>
    <div class="alert">🧪 Альфа-тестирование</div>
</div>

<!-- Server Instructions -->
<div class="server-info">
    <p>Инструкция по подключению...</p>
    <p>Realmlist: set realmlist logon.azeroth.su</p>
</div>

<!-- Server Statistics -->
<div class="server-stats">
    <div>👥 Онлайн: <?= $onlineCount ?></div>
    <div>🏆 Персонажей: <?= $charactersCount ?></div>
</div>
```

### 2. Login Page (login.html.php)

#### Форма авторизации
```html
<form method="POST" action="/login" class="login-form">
    <div class="form-group">
        <label>Логин:</label>
        <input type="text" name="username" required>
    </div>
    <div class="form-group">
        <label>Пароль:</label>
        <input type="password" name="password" required>
    </div>
    <div class="form-group">
        <label>
            <input type="checkbox" name="remember"> Запомнить меня
        </label>
    </div>
    <button type="submit">ВОЙТИ В ИГРУ</button>
</form>
```

#### Валидация на клиенте
```javascript
document.querySelector('.login-form').addEventListener('submit', function(e) {
    const username = this.username.value.trim();
    const password = this.password.value;
    
    if (!username || !password) {
        e.preventDefault();
        alert('Заполните все поля');
        return false;
    }
    
    if (username.length < 3) {
        e.preventDefault();
        alert('Логин должен содержать минимум 3 символа');
        return false;
    }
});
```

### 3. Vote Page (vote.html.php)

#### Список рейтингов
```php
foreach ($voteServices as $service): ?>
<div class="vote-item <?= $service['available'] ? 'vote-available' : 'vote-cooldown' ?>">
    <h4><?= $service['name'] ?></h4>
    <div>Награда: <?= $service['reward'] ?> монет</div>
    <div>Cooldown: <?= $service['cooldown_text'] ?></div>
    <?php if ($service['available']): ?>
        <button class="btn vote-btn" data-service="<?= $service['id'] ?>">
            ГОЛОСОВАТЬ ✅
        </button>
    <?php else: ?>
        <button class="btn" disabled>ОЖИДАНИЕ ⏰</button>
    <?php endif; ?>
</div>
<?php endforeach; ?>
```

#### AJAX голосование
```javascript
document.querySelectorAll('.vote-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const serviceId = this.dataset.service;
        
        fetch('/api/vote/prepare', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({service_id: serviceId})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.open(data.vote_url, '_blank');
                // Показать уведомление о необходимости подтверждения
                showVoteInstructions();
            }
        });
    });
});
```

### 4. Cabinet Page (cabinet.html.php)

#### Профиль пользователя
```html
<div class="profile-section">
    <h3>📋 Профиль</h3>
    <div class="profile-info">
        <div>👤 Имя: <?= htmlspecialchars($userInfo['username']) ?></div>
        <div>📧 Email: <?= htmlspecialchars($userInfo['email'] ?? 'Не указан') ?></div>
        <div>💰 Баланс: <?= $userInfo['balance'] ?> монет</div>
        <div>📅 Регистрация: <?= date('d.m.Y', strtotime($userInfo['created_at'])) ?></div>
    </div>
</div>
```

#### Таблица персонажей
```html
<div class="characters-section">
    <h3>⚔️ Персонажи</h3>
    <table class="characters-table">
        <thead>
            <tr>
                <th>Имя</th>
                <th>Уровень</th>
                <th>Класс</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($characters as $char): ?>
            <tr>
                <td><?= htmlspecialchars($char['name']) ?></td>
                <td><?= $char['level'] ?></td>
                <td><?= $char['class_name'] ?></td>
                <td>
                    <a href="/character/<?= urlencode($char['name']) ?>" class="btn">
                        Просмотр
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
```

---

## Стилевое руководство

### Цветовая палитра
```css
:root {
    --primary-color: #0066cc;      /* Основной синий */
    --secondary-color: #ffd700;    /* Золотой акцент */
    --success-color: #28a745;      /* Зеленый успех */
    --danger-color: #dc3545;       /* Красная ошибка */
    --warning-color: #ffc107;      /* Желтое предупреждение */
    --dark-bg: #1a1a1a;           /* Темный фон */
    --light-bg: #2a2a2a;          /* Светло-темный фон */
    --border-color: #555;          /* Цвет границ */
    --text-color: #ffffff;         /* Основной текст */
    --text-muted: #cccccc;         /* Приглушенный текст */
}
```

### Типографика
```css
body {
    font-family: Arial, sans-serif;
    font-size: 14px;
    line-height: 1.4;
    color: var(--text-color);
    background: var(--dark-bg);
}

h1 { font-size: 24px; color: var(--secondary-color); }
h2 { font-size: 20px; color: var(--secondary-color); }
h3 { font-size: 16px; color: var(--secondary-color); text-transform: uppercase; }

.small { font-size: 12px; }
.large { font-size: 18px; }
.text-muted { color: var(--text-muted); }
```

### Кнопки
```css
.btn {
    display: inline-block;
    padding: 8px 15px;
    background: var(--primary-color);
    color: white;
    border: none;
    border-radius: 3px;
    text-decoration: none;
    cursor: pointer;
    font-size: 12px;
    transition: background 0.3s ease;
}

.btn:hover { background: #004499; }
.btn:disabled { opacity: 0.5; cursor: not-allowed; }

.btn-success { background: var(--success-color); }
.btn-danger { background: var(--danger-color); }
.btn-warning { background: var(--warning-color); }
```

### Формы
```css
.form-group {
    margin: 10px 0;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-size: 12px;
    color: var(--text-muted);
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 8px;
    border: 1px solid var(--border-color);
    background: var(--dark-bg);
    color: var(--text-color);
    border-radius: 3px;
    font-size: 12px;
}

.form-group input:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 5px rgba(0, 102, 204, 0.3);
}
```

### Блоки контента
```css
.block {
    border: 1px solid var(--border-color);
    margin: 10px 0;
    padding: 15px;
    background: var(--light-bg);
    border-radius: 5px;
    position: relative;
}

.block h3 {
    margin: 0 0 10px 0;
    padding-bottom: 5px;
    border-bottom: 1px solid var(--border-color);
}

.block-border {
    border: 2px solid var(--border-color);
}
```

### Иконки
```css
.icon {
    width: 12px;
    height: 12px;
    vertical-align: middle;
    margin-right: 5px;
}

.icon-large {
    width: 32px;
    height: 32px;
}

.link-icon {
    margin-right: 3px;
}
```

---

## Техническая реализация

### PHP Controller Pattern
```php
class PageController {
    protected $viewData = [];
    
    public function render(string $template, array $data = []): void {
        $this->viewData = array_merge($this->viewData, $data);
        
        // Глобальные данные для всех шаблонов
        $GLOBALS['viewGlobals'] = [
            'userInfo' => $this->getCurrentUser(),
            'notificationsData' => $this->getNotifications(),
            'serverInfo' => $this->getServerInfo()
        ];
        
        // Рендер с layout
        include 'templates/layout.html.php';
    }
    
    protected function getCurrentUser(): ?array {
        // Логика получения текущего пользователя
    }
    
    protected function getNotifications(): array {
        // Логика получения уведомлений
    }
}
```

### JavaScript Module Pattern
```javascript
// Модуль уведомлений
const NotificationManager = {
    init: function() {
        this.bindEvents();
        this.loadNotifications();
    },
    
    bindEvents: function() {
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('hide-notify-btn')) {
                e.preventDefault();
                NotificationManager.hideNotification(e.target.dataset.id);
            }
        });
    },
    
    hideNotification: function(id) {
        fetch('/notify-hide.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({id: parseInt(id)})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('notify-' + id).remove();
            }
        })
        .catch(error => console.error('Error:', error));
    }
};

// Инициализация при загрузке DOM
document.addEventListener('DOMContentLoaded', NotificationManager.init);
```

### CSS Architecture (BEM-inspired)
```css
/* Block */
.notification { }

/* Elements */
.notification__content { }
.notification__button { }
.notification__icon { }

/* Modifiers */
.notification--success { background: var(--success-color); }
.notification--error { background: var(--danger-color); }
.notification--hidden { display: none; }

/* States */
.notification.is-hiding {
    opacity: 0;
    transform: translateY(-100%);
    transition: all 0.3s ease;
}
```

---

## Performance Guidelines

### Оптимизация загрузки
1. **CSS**: Минификация и объединение файлов
2. **JavaScript**: Lazy loading для неблокирующих скриптов
3. **Images**: Использование WebP с fallback
4. **Caching**: HTTP-кеширование статических ресурсов

### Мобильная оптимизация
```css
/* Touch-friendly кнопки */
@media (max-width: 768px) {
    .btn {
        min-height: 44px;
        padding: 12px 20px;
        font-size: 14px;
    }
    
    .nav-item {
        min-height: 44px;
        line-height: 44px;
    }
}
```

### Доступность (A11Y)
```html
<!-- ARIA labels для кнопок -->
<button aria-label="Скрыть уведомление" class="hide-notify-btn">×</button>

<!-- Alt-тексты для изображений -->
<img src="/images/icons/tick.png" alt="Успешно" class="icon">

<!-- Семантическая разметка -->
<nav aria-label="Основная навигация">
    <ul role="menubar">
        <li role="menuitem"><a href="/">Главная</a></li>
    </ul>
</nav>
```

Этот wireframe и техническая спецификация обеспечивают полное понимание архитектуры интерфейса проекта AnotherWoW и служат руководством для разработки и поддержки системы.