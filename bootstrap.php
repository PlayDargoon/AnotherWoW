<?php
// bootstrap.php

// Запускаем сессию
session_start();

// Подключаем автозагрузку классов (PSR-4)
spl_autoload_register(function ($className) {
    $filePath = __DIR__ . '/src/' . str_replace('\\', '/', $className) . '.php';
    if (file_exists($filePath)) {
        require_once $filePath;
    }
});

// Полезные функции
require_once __DIR__ . '/src/utils.php'; // Импортируем полезные функции

// Подключаем службу подключения к базе данных
require_once __DIR__ . '/src/services/DatabaseConnection.php';

// Получаем экземпляры подключений к базам данных
global $authConnection, $worldConnection, $charactersConnection;

// Соединение с базой auth
$authConnection = DatabaseConnection::getAuthConnection();

// Соединение с базой world
$worldConnection = DatabaseConnection::getWorldConnection();

// Соединение с базой персонажей
$charactersConnection = DatabaseConnection::getCharactersConnection();

// Всё готово к дальнейшей работе