<?php
// bootstrap.php

// Устанавливаем кодировку UTF-8
header('Content-Type: text/html; charset=utf-8');

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

// Определение переменной $site_url
$GLOBALS['site_url'] = 'https://azeroth.su'; // Замените на ваш реальный домен

// Всё готово к дальнейшей работе