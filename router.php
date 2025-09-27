<?php
// Включаем буферизацию вывода для предотвращения "headers already sent"
ob_start();

// Подключаем сервисы
require_once __DIR__ . '/src/services/DatabaseConnection.php';
require_once __DIR__ . '/src/services/VoteService.php';

// Подключаем все модели
require_once __DIR__ . '/src/models/User.php';
require_once __DIR__ . '/src/models/Character.php';
require_once __DIR__ . '/src/models/Uptime.php';
require_once __DIR__ . '/src/models/Site.php';
require_once __DIR__ . '/src/models/Notification.php';
require_once __DIR__ . '/src/models/AccountCoins.php';
require_once __DIR__ . '/src/models/News.php';
require_once __DIR__ . '/src/models/VoteLog.php';
require_once __DIR__ . '/src/models/VoteReward.php';

// Подключаем хелперы
require_once __DIR__ . '/src/helpers/getFactionImage.php';
require_once __DIR__ . '/src/helpers/formatCreationDate.php';
require_once __DIR__ . '/src/helpers/getGMRole.php';
require_once __DIR__ . '/src/helpers/serverInfo_helper.php';

// Подключаем PHPMailer
require_once __DIR__ . '/src/libs/phpmailer/Exception.php';
require_once __DIR__ . '/src/libs/phpmailer/PHPMailer.php';
require_once __DIR__ . '/src/libs/phpmailer/SMTP.php';

// Подключаем контроллеры
require_once __DIR__ . '/src/controllers/NotificationController.php';
require_once __DIR__ . '/src/controllers/IndexController.php';
require_once __DIR__ . '/src/controllers/RegisterController.php';
require_once __DIR__ . '/src/controllers/LoginController.php';
require_once __DIR__ . '/src/controllers/LogoutController.php';
require_once __DIR__ . '/src/controllers/CabinetController.php';
require_once __DIR__ . '/src/controllers/CharacterPageController.php';
require_once __DIR__ . '/src/controllers/ErrorController.php';
require_once __DIR__ . '/src/controllers/MaintenanceController.php';
require_once __DIR__ . '/src/controllers/SiteController.php';
require_once __DIR__ . '/src/controllers/RestorePasswordController.php';
require_once __DIR__ . '/src/controllers/AdminPanelController.php';
require_once __DIR__ . '/src/controllers/AdminOnlineController.php';
require_once __DIR__ . '/src/controllers/NewsController.php';
require_once __DIR__ . '/src/controllers/NewsListController.php';
require_once __DIR__ . '/src/controllers/VoteController.php';


// Готовим уведомления и ник для layout (доступно во всех шаблонах)
if (isset($_SESSION['user_id'])) {
    // Синхронизируем голоса для текущего пользователя (быстро, с кешем)
    $voteService = new VoteService();
    $voteService->syncVotesForUser($_SESSION['user_id']);

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

// Передача userInfo и coins для header
if (isset($_SESSION['user_id'])) {
    $userModel = new User(DatabaseConnection::getAuthConnection());
    $userInfo = $userModel->getUserInfoByUsername($_SESSION['username'] ?? '');
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
        $controller = new RestorePasswordController($userModel, $siteModel);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Пост-обработчик для отправки ссылки на восстановление
            $controller->sendResetLink();
        } else {
            // Просто показать страницу с формой
            $controller->index();
        }
        break;

    // Маршрут для проверки токена
    case '/verify-token':
        $controller = new RestorePasswordController($userModel, $siteModel);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Обработать пост-запрос на проверку токена
            $controller->verifyToken();
        } else {
            // Просто показать страницу для ввода токена
            $controller->showVerifyTokenForm();
        }
        break;


    // Маршрут для установки нового пароля
    case '/set-new-password':
        $controller = new RestorePasswordController($userModel, $siteModel);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Обработать пост-запрос на смену пароля
            $token = $_POST['token']; // Получаем токен из формы
            $controller->setNewPassword($token); // Передаём токен
        } else {
            // Просто показать страницу для ввода нового пароля
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

// Завершаем буферизацию и отправляем вывод
ob_end_flush();