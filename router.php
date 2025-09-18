<?php
// Подключаем контроллер уведомлений
require_once __DIR__ . '/src/controllers/NotificationController.php';


// Готовим уведомления и ник для layout (доступно во всех шаблонах)
if (isset($_SESSION['user_id'])) {
    // Синхронизируем голоса для текущего пользователя (быстро, с кешем)
    require_once __DIR__ . '/src/services/VoteService.php';
    $voteService = new VoteService();
    $voteService->syncVotesForUser($_SESSION['user_id']);

    require_once __DIR__ . '/src/models/Notification.php';
    $notifyModel = new Notification();
    $unread = $notifyModel->getUnreadByUserId($_SESSION['user_id']);
    // Добавим текст с правильным склонением монет, если есть coins в data
    foreach ($unread as &$n) {
        if (!empty($n['data']) && is_array($n['data']) && isset($n['data']['coins'])) {
            $coins = (int)$n['data']['coins'];
            $n['coinsText'] = $coins . ' ' . NotificationController::coinsDeclension($coins);
        }
    }
    unset($n);
    $GLOBALS['viewGlobals']['notificationsData'] = [
        'username' => $_SESSION['username'] ?? null,
        'notifications' => $unread,
    ];
}



// Получаем URI запроса
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Готовим данные для блока статуса сервера (right_block)



require_once __DIR__ . '/src/helpers/getFactionImage.php';
require_once __DIR__ . '/src/helpers/formatCreationDate.php';
require_once __DIR__ . '/src/helpers/getGMRole.php';
require_once __DIR__ . '/src/helpers/getFactionImage.php';

// Подключаем PHPMailer
require_once __DIR__ . '/src/libs/phpmailer/Exception.php';
require_once __DIR__ . '/src/libs/phpmailer/PHPMailer.php';
require_once __DIR__ . '/src/libs/phpmailer/SMTP.php';

// Подключаем контроллеры
require_once __DIR__ . '/src/controllers/IndexController.php';
require_once __DIR__ . '/src/controllers/RegisterController.php';
require_once __DIR__ . '/src/controllers/LoginController.php';
require_once __DIR__ . '/src/controllers/CabinetController.php'; // Подключаем контроллер кабинета
require_once __DIR__ . '/src/controllers/CharacterPageController.php'; // Подключаем контроллер персонажа
require_once __DIR__ . '/src/controllers/ErrorController.php';
require_once __DIR__ . '/src/controllers/LogoutController.php'; // Подключаем контроллер выхода
require_once __DIR__ . '/src/controllers/MaintenanceController.php'; // Подключаем контроллер технического обслуживания
require_once __DIR__ . '/src/controllers/SiteController.php'; // Подключаем контроллер site
require_once __DIR__ . '/src/controllers/RestorePasswordController.php'; // Восстановление пароля

require_once __DIR__ . '/src/controllers/AdminPanelController.php'; // Админ панель
require_once __DIR__ . '/src/controllers/AdminOnlineController.php'; // Игроки онлайн (админ)
require_once __DIR__ . '/src/controllers/NewsController.php'; // Новости

require_once __DIR__ . '/src/controllers/NewsListController.php'; // Список новостей для пользователей
require_once __DIR__ . '/src/controllers/VoteController.php'; // Голосование


// Подключаем модели
require_once __DIR__ . '/src/models/User.php';
require_once __DIR__ . '/src/models/Character.php'; // Подключаем модель персонажей
require_once __DIR__ . '/src/models/Uptime.php'; // Подключаем модель Uptime
require_once __DIR__ . '/src/models/Site.php'; // Подключаем модель site

// Сервис подключения к базе данных
require_once __DIR__ . '/src/services/DatabaseConnection.php';

// Экземпляры моделей

// Передача userInfo и coins для header
if (isset($_SESSION['user_id'])) {
    $userModel = new User(DatabaseConnection::getAuthConnection());
    $userInfo = $userModel->getUserInfoByUsername($_SESSION['username'] ?? '');
    require_once __DIR__ . '/src/models/AccountCoins.php';
    $coinsModel = new AccountCoins(DatabaseConnection::getSiteConnection());
    $coins = $coinsModel->getBalance($userInfo['id'] ?? 0);
    $GLOBALS['viewGlobals']['userInfo'] = $userInfo;
    $GLOBALS['viewGlobals']['coins'] = $coins;
}

// Экземпляры моделей
$userModel = new User(DatabaseConnection::getAuthConnection()); // Подключение к auth базе
$characterModel = new Character(DatabaseConnection::getCharactersConnection()); // Подключение к базе персонажей
$siteModel = new Site(DatabaseConnection::getSiteConnection()); // Подключение к базе сайта
$uptimeModel = new Uptime(DatabaseConnection::getAuthConnection());

// Готовим данные для блока статуса сервера (right_block)
require_once __DIR__ . '/src/helpers/serverInfo_helper.php';
$serverInfo = getServerInfo($characterModel, $uptimeModel);
// Делаем serverInfo доступным во всех шаблонах через renderTemplate (см. src/utils.php)
$GLOBALS['viewGlobals']['serverInfo'] = $serverInfo;



// Получаем URI запроса
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);


// Проверка технического обслуживания
$maintenanceMode = false; // Установите в true, чтобы включить режим технического обслуживания

if ($maintenanceMode && $uri !== '/register') {
    // Отображаем страницу технического обслуживания, если это не страница регистрации
    $controller = new MaintenanceController();
    $controller->index();
    exit;
}

// Обработчики маршрутов
switch ($uri) {

    case '/forum-test':
        renderTemplate('layout.html.php', [
            'contentFile' => 'pages/forum_test.html.php',
            'pageTitle' => 'Тестовый форум',
            'serverInfo' => $serverInfo
        ]);
        break;

    case '/cabinet/coins-history':
        $controller = new CabinetController($userModel, $characterModel);
        $controller->coinsHistory();
        break;
    case '/vote':
        $controller = new VoteController();
        $controller->index();
        break;
    case '/news':
        $controller = new NewsListController();
        $controller->handle();
        break;
    case '/news/manage':
        $controller = new NewsController();
        $controller->manage();
        break;
    case '/news/create':
        $controller = new NewsController();
        $controller->create();
        break;
    case '/news/store':
        $controller = new NewsController();
        $controller->store();
        break;
    case '/news/delete':
        $controller = new NewsController();
        $controller->delete();
        break;

    case '/admin-online':
        $controller = new AdminOnlineController();
        $controller->index();
        break;
    case '/about':
        renderTemplate('layout.html.php', [
            'contentFile' => 'pages/about.html.php',
            'pageTitle' => 'О проекте',
            'serverInfo' => $serverInfo
        ]);
        break;

    case '/design-demo-vten':
        renderTemplate('layout.html.php', [
            'contentFile' => 'pages/design_demo_vten.html.php',
            'pageTitle' => 'Достижения — демо',
        ]);
        break;
    case '/': // Главная страница
        $controller = new IndexController($characterModel, $uptimeModel); // Передаем модель персонажей
        $controller->index();
        break;

    case '/register': // Регистрация
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new RegisterController($userModel);
            $controller->processRegistration();
        } else {
            $controller = new RegisterController($userModel);
            $controller->index();
        }
        break;

    case '/login': // Вход
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new LoginController($userModel);
            $controller->processLogin();
        } else {
            $controller = new LoginController($userModel);
            $controller->index();
        }
        break;

    // Маршрут для страницы восстановления пароля
    case '/restore-password':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Пост-обработчик для отправки ссылки на восстановление
            $controller = new RestorePasswordController($userModel, $siteModel);
            $controller->sendResetLink();
        } else {
            // Просто показать страницу с формой
            $controller = new RestorePasswordController($userModel, $siteModel);
            $controller->index();
        }
        break;

    // Маршрут для проверки токена
    case '/verify-token':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Обработать пост-запрос на проверку токена
            $controller = new RestorePasswordController($userModel, $siteModel);
            $controller->verifyToken();
        } else {
            // Просто показать страницу для ввода токена
            $controller = new RestorePasswordController($userModel, $siteModel);
            $controller->showVerifyTokenForm();
        }
        break;


    // Маршрут для установки нового пароля
    case '/set-new-password':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Обработать пост-запрос на смену пароля
            $token = $_POST['token']; // Получаем токен из формы
            $controller = new RestorePasswordController($userModel, $siteModel);
            $controller->setNewPassword($token); // Передаём токен
        } else {
            // Просто показать страницу для ввода нового пароля
            $controller = new RestorePasswordController($userModel, $siteModel);
            $controller->showSetPasswordForm($token);
        }
        break;

    case '/cabinet': // Кабинет пользователя
        $controller = new CabinetController($userModel, $characterModel);
        $controller->index();
        break;

        // Маршрут для админ-панели
    case '/admin-panel':
        $controller = new AdminPanelController($userModel);
        $controller->index();
        break;
        

    case '/play': // Страница персонажа
        // Получаем GUID персонажа из параметров GET
        $characterGuid = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        if ($characterGuid !== null) {
            $controller = new CharacterPageController($characterModel, $userModel); // Передаем обе модели
            $controller->showCharacter($characterGuid);
        } else {
            // Если параметр не найден, показываем ошибку
            $controller = new ErrorController();
            $controller->notFound();
        }
        break;

    case '/logout': // Выход из системы
        $controller = new LogoutController();
        $controller->index();
        break;



    default:
        // Обработка 404 ошибки
        $controller = new ErrorController();
        $controller->notFound();
        break;

        
}