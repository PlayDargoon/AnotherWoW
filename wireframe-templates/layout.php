<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Azeroth - Wireframe Template - <?= $pageTitle ?? 'Главная страница' ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Тестовый wireframe шаблон для проекта AnotherWoW">
    <link rel="icon" href="../public/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="css/full-style.css">
    <script src="js/notify.js"></script>
    <script>
        // Простой JavaScript для уведомлений
        function hideNotification(id) {
            const element = document.getElementById('notify-' + id);
            if (element) {
                element.style.display = 'none';
            }
        }
        
        // Переключение страниц для демонстрации
        function showPage(pageId) {
            const pages = document.querySelectorAll('.page-content');
            pages.forEach(page => page.style.display = 'none');
            
            const targetPage = document.getElementById(pageId);
            if (targetPage) {
                targetPage.style.display = 'block';
            }
            
            // Обновляем активную кнопку навигации
            const navButtons = document.querySelectorAll('.nav-button');
            navButtons.forEach(btn => btn.classList.remove('active'));
            
            const activeBtn = document.querySelector(`[onclick="showPage('${pageId}')"]`);
            if (activeBtn) {
                activeBtn.classList.add('active');
            }
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            // Показываем первую страницу по умолчанию
            showPage('home');
        });
    </script>
    <style>
        /* Минимальные стили только для навигации wireframe */
        .nav-button {
            background-color: #333366;
            color: #ffff33;
            padding: 8px 15px;
            margin: 5px;
            border: 1px solid #555555;
            cursor: pointer;
            border-radius: 3px;
        }
        .nav-button:hover, .nav-button.active {
            background-color: #555588;
            color: #ffffff;
        }
        .page-content {
            display: none;
        }
        .demo-nav {
            text-align: center;
            padding: 10px;
            background-color: #333366;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <!-- Демо-навигация для переключения страниц -->
    <div class="demo-nav">
        <button class="nav-button" onclick="showPage('home')">🏠 Главная</button>
        <button class="nav-button" onclick="showPage('login')">🔐 Авторизация</button>
        <button class="nav-button" onclick="showPage('cabinet')">👤 Кабинет</button>
        <button class="nav-button" onclick="showPage('vote')">🗳️ Голосование</button>
        <button class="nav-button" onclick="showPage('admin')">⚙️ Админ-панель</button>
    </div>

    <!-- Уведомления -->
    <?php if (!empty($notifications)): ?>
        <?php foreach ($notifications as $notify): ?>
            <div class="event" id="notify-<?= $notify['id'] ?>">
                <div class="notify-inner">
                    <img src="../public/images/refreshed-32x32.png" alt="" width="32" height="32" class="img-npc">
                    <b><?= htmlspecialchars($notify['username'] ?? 'Игрок') ?></b>, ты получил <?= $notify['coinsText'] ?? '1 монету' ?> за голосование!
                    <div class="mt10">Спасибо, что поддерживаешь проект.</div>
                    <div class="clearer"></div>
                </div>
                <div>
                    <a class="btn hide-notify-btn" href="#" onclick="hideNotification(<?= $notify['id'] ?>); return false;">
                        <img src="../public/images/icons/tick.png" alt="x" width="12" height="12" class="link-icon">Спасибо. Скрыть
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <div class="block-border">
        <!-- Хедер для авторизованных пользователей -->
        <?php if (!empty($userInfo)): ?>
            <?php include 'partials/header.php'; ?>
        <?php endif; ?>

        <!-- Левый блок -->
        <div class="test3 block-border">
            <?php include 'partials/left_block.php'; ?>
        </div>
        
        <!-- Правый блок -->
        <div class="test2 block-border">
            <?php include 'partials/right_block.php'; ?>
        </div>

        <!-- Основной контент -->
        <div class="body">
            <?php 
            // Подключаем контент в зависимости от страницы
            $page = $currentPage ?? 'home';
            $pageFile = "pages/{$page}.php";
            
            if (file_exists($pageFile)) {
                include $pageFile;
            } else {
                // Если файл не найден, показываем демо-страницы
                include 'pages/demo_pages.php';
            }
            ?>
        </div>

        <!-- Футер -->
        <div class="footer">
            <div style="text-align: center; padding: 10px;">
                © 2025 Azeroth Server - Wireframe Template Demo | 
                <a href="#" style="color: #ffff33;">Контакты</a> | 
                <a href="#" style="color: #ffff33;">Поддержка</a>
            </div>
        </div>
    </div>
</body>
</html>