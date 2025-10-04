<?php
// public/index.php

// Инициализация времени начала загрузки страницы для footer
global $loadStart;
$loadStart = microtime(true);

// Загружаем среду
require_once __DIR__ . '/../bootstrap.php';

// Загружаем роутер
require_once __DIR__ . '/../router.php';