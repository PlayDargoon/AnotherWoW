<?php
// wireframe-demo.php - Главный файл для демонстрации wireframe шаблонов

// Подготовка данных для демонстрации
$pageTitle = 'Wireframe Demo';

// Имитация пользователя
$userInfo = [
    'username' => 'PlayerName',
    'balance' => 150,
    'isAdmin' => true,
    'email' => 'player@example.com'
];

// Имитация уведомлений
$notifications = [
    [
        'id' => 1,
        'username' => 'PlayerName',
        'coinsText' => '1 монету',
        'type' => 'vote_reward'
    ]
];

// Информация о сервере
$serverInfo = [
    'playersOnline' => 245,
    'uptime' => '99.2%',
    'status' => 'online'
];

// Топ игроков
$topPlayers = [
    ['name' => 'DragonSlayer', 'level' => 80, 'class' => 'Воин'],
    ['name' => 'ShadowMage', 'level' => 79, 'class' => 'Маг'],
    ['name' => 'HolyPriest', 'level' => 78, 'class' => 'Жрец']
];

// Подключаем layout
include 'layout.php';
?>