<?php
// Включаем буферизацию вывода для предотвращения "headers already sent"
ob_start();

// Подключаем bootstrap
require_once __DIR__ . '/bootstrap.php';

// Подключаем сервисы
require_once __DIR__ . '/src/services/DatabaseConnection.php';
require_once __DIR__ . '/src/services/VoteService.php';
require_once __DIR__ . '/src/services/YooKassaService.php';

// Подключаем все модели
require_once __DIR__ . '/src/models/User.php';
require_once __DIR__ . '/src/models/Character.php';
require_once __DIR__ . '/src/models/Uptime.php';
require_once __DIR__ . '/src/models/Site.php';
require_once __DIR__ . '/src/models/Notification.php';
require_once __DIR__ . '/src/models/AccountCoins.php';
require_once __DIR__ . '/src/models/Payment.php';
require_once __DIR__ . '/src/models/News.php';
require_once __DIR__ . '/src/models/VoteLog.php';
require_once __DIR__ . '/src/models/VoteReward.php';
require_once __DIR__ . '/src/models/VoteTop.php';

// Подключаем хелперы
require_once __DIR__ . '/src/helpers/getFactionImage.php';
require_once __DIR__ . '/src/helpers/formatCreationDate.php';
require_once __DIR__ . '/src/helpers/getGMRole.php';
require_once __DIR__ . '/src/helpers/serverInfo_helper.php';
require_once __DIR__ . '/src/helpers/safe_redirect.php';
require_once __DIR__ . '/src/helpers/ip_helper.php';

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
require_once __DIR__ . '/src/controllers/CoinsAdminController.php';
require_once __DIR__ . '/src/controllers/NewsController.php';
require_once __DIR__ . '/src/controllers/NewsListController.php';
require_once __DIR__ . '/src/controllers/VoteController.php';
require_once __DIR__ . '/src/controllers/VoteTopController.php';
require_once __DIR__ . '/src/controllers/TermsController.php';
require_once __DIR__ . '/src/controllers/PrivacyController.php';
require_once __DIR__ . '/src/controllers/RulesController.php';
require_once __DIR__ . '/src/controllers/HelpController.php';
require_once __DIR__ . '/src/controllers/SupportController.php';
require_once __DIR__ . '/src/controllers/PaymentController.php';
require_once __DIR__ . '/src/controllers/YooKassaWebhookController.php';
require_once __DIR__ . '/src/controllers/MigrationController.php';
require_once __DIR__ . '/src/controllers/ProgressionController.php';
require_once __DIR__ . '/src/controllers/ShopController.php';
require_once __DIR__ . '/src/controllers/ShopHistoryController.php';

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
    $coinsModel = new CachedAccountCoins(DatabaseConnection::getSiteConnection());
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

    case '/shop':
        if (!isset($_SESSION['user_id'])) {
            // Доступ в магазин только для авторизованных пользователей
            safeRedirect('/login');
            break;
        }
        renderTemplate('layout.html.php', [
            'contentFile' => 'pages/shop.html.php',
            'pageTitle' => 'Магазин',
            'serverInfo' => $serverInfo,
            'extraScripts' => ['/js/shop.js', '/js/shop-buy.js']
        ]);
        break;
    case '/shop/buy':
        $controller = new ShopController($userModel, $characterModel);
        $controller->buy();
        break;

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
    case '/vote/top':
        $controller = new VoteTopController();
        $controller->index();
        break;

    case '/cabinet/characters.json':
        header('Content-Type: application/json; charset=utf-8');
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['characters' => []], JSON_UNESCAPED_UNICODE);
            break;
        }
        $userInfo = $userModel->getUserInfoByUsername($_SESSION['username'] ?? '');
        $chars = $characterModel->getCharactersByUserId($userInfo['id'] ?? 0);
        $out = [];
        foreach ($chars as $c) {
            $out[] = [
                'guid' => (int)$c['guid'],
                'name' => $c['name'],
                'level' => (int)$c['level']
            ];
        }
        echo json_encode(['characters' => $out], JSON_UNESCAPED_UNICODE);
        break;
    
    case '/news':
        $controller = new NewsListController();
        $controller->handle();
        break;
    case '/progression':
        // Прогрессия через контроллер
        $controller = new ProgressionController();
        $controller->index();
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
    case '/admin/coins':
        $controller = new CoinsAdminController($userModel);
        $controller->index();
        break;
    case '/about':
        renderTemplate('layout.html.php', [
            'contentFile' => 'pages/about.html.php',
            'pageTitle' => 'О проекте',
            'serverInfo' => $serverInfo
        ]);
        break;
        
    case '/terms': // Пользовательское соглашение
        $controller = new TermsController();
        $controller->index();
        break;
        
    case '/privacy': // Политика конфиденциальности
        $controller = new PrivacyController();
        $controller->handle();
        break;
        
    case '/rules': // Правила игровых миров
        $controller = new RulesController();
        $controller->index();
        break;
        
    case '/help': // Помощь новичкам
        $controller = new HelpController();
        $controller->handle();
        break;
        
    case '/help/bot-commands': // Команды игровых ботов
        renderTemplate('layout.html.php', [
            'contentFile' => 'pages/bot-commands.html.php',
            'pageTitle' => 'Команды игровых ботов',
        ]);
        break;
        
    case '/support': // Поддержка игроков
        $controller = new SupportController();
        $controller->handle();
        break;

    case '/payment/create': // Старт оплаты (создание платежа)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new PaymentController();
            $controller->create();
        } else {
            // Минимальная форма создания платежа (для теста)
            renderTemplate('layout.html.php', [
                'contentFile' => 'pages/payment_create_form.html.php',
                'pageTitle' => 'Пополнение баланса',
            ]);
        }
        break;

    case '/payment/return': // Возврат с оплаты
        $controller = new PaymentController();
        $controller->return();
        break;

    case '/payment/error': // Страница ошибки оплаты (редирект из ЮKassa)
        $controller = new PaymentController();
        $controller->error();
        break;

    case '/yookassa/webhook': // Вебхук ЮKassa
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new YooKassaWebhookController();
            $controller->handle();
        } else {
            http_response_code(405);
            echo 'Method Not Allowed';
        }
        break;

    case '/migrate/payments': // Запуск миграции платежей (для локальных тестов)
        $mc = new MigrationController();
        $mc->runMigration('CreatePaymentsTable.php');
        echo "Миграция выполнена";
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

    case '/shop/history':
        $controller = new ShopHistoryController();
        $controller->index();
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