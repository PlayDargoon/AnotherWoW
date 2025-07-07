<?php
// router.php

// Загружаем среду
require_once __DIR__ . '/bootstrap.php';

// Подключаем контроллеры
require_once __DIR__ . '/src/controllers/IndexController.php';
require_once __DIR__ . '/src/controllers/RegisterController.php';
require_once __DIR__ . '/src/controllers/LoginController.php';
require_once __DIR__ . '/src/controllers/ProfileController.php';
require_once __DIR__ . '/src/controllers/ErrorController.php';

// Подключаем модели
require_once __DIR__ . '/src/models/User.php';
require_once __DIR__ . '/src/models/Auth.php'; // Подключаем модель авторизации
require_once __DIR__ . '/src/models/Character.php'; // Подключаем модель персонажей

// Сервис подключения к базе данных
require_once __DIR__ . '/src/services/DatabaseConnection.php';

// Экземпляры моделей
$userModel = new User(DatabaseConnection::getAuthConnection()); // Подключение к auth базе
$authModel = new Auth(DatabaseConnection::getAuthConnection()); // Подключение к auth базе
$characterModel = new Character(DatabaseConnection::getCharactersConnection()); // Подключение к базе персонажей

// Получаем URI запроса
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

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
            $controller = new LoginController($authModel);
            $controller->processLogin();
        } else {
            $controller = new LoginController($authModel);
            $controller->index();
        }
        break;

    case '/profile': // Кабинет пользователя
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
            $controller = new ProfileController($userModel);
            $controller->index();
        } else {
            header('Location: /login');
            exit;
        }
        break;

    default:
        // Обработка 404 ошибки
        $controller = new ErrorController();
        $controller->notFound();
        break;
}