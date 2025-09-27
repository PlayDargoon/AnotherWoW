<?php
/**
 * Главная страница для демонстрации wireframe с оригинальным дизайном AnotherWoW
 */

// Подключаем основной layout
$pageTitle = "AnotherWoW - Wireframe Demo";
$currentPage = "home";

// Данные для демонстрации
$userInfo = null; // Для неавторизованного пользователя
// Для тестирования авторизованного пользователя раскомментируйте:
/*
$userInfo = [
    'name' => 'TestUser',
    'level' => 80,
    'class' => 'Paladin',
    'guild' => 'TestGuild',
    'isAdmin' => false
];
*/

// Подключаем layout
include 'layout.php';
?>