<?php
// Инициализация времени начала загрузки страницы для footer
global $loadStart;
$loadStart = microtime(true);
?>
<?php
// public/index.php

// Загружаем среду
require_once '../bootstrap.php';

// Загружаем роутер
require_once '../router.php';