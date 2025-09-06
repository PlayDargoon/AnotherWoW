<?php
// router.php

// Загружаем среду
require_once __DIR__ . '/bootstrap.php';

// Подключаем вспомогательный файл
require_once __DIR__ . '/src/helpers/srp_helpers.php';
require_once __DIR__ . '/src/helpers/convertMoney.php';
require_once __DIR__ . '/src/helpers/getFactionImage.php';
require_once __DIR__ . '/src/helpers/formatCreationDate.php';
require_once __DIR__ . '/src/helpers/getGMRole.php';

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


// Подключаем модели
require_once __DIR__ . '/src/models/User.php';
require_once __DIR__ . '/src/models/Character.php'; // Подключаем модель персонажей
require_once __DIR__ . '/src/models/Uptime.php'; // Подключаем модель Uptime

// Сервис подключения к базе данных
require_once __DIR__ . '/src/services/DatabaseConnection.php';

// Экземпляры моделей
$userModel = new User(DatabaseConnection::getAuthConnection()); // Подключение к auth базе
$characterModel = new Character(DatabaseConnection::getCharactersConnection()); // Подключение к базе персонажей

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
    case '/': // Главная страница
        $controller = new IndexController($characterModel); // Передаем модель персонажей
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

    case '/cabinet': // Кабинет пользователя
        $controller = new CabinetController($userModel, $characterModel);
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