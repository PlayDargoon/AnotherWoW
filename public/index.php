<?php
// public/index.php

// Инициализация времени начала загрузки страницы для footer
global $loadStart;
$loadStart = microtime(true);

// Загружаем среду
require_once '../bootstrap.php';

// Загружаем роутер
require_once '../router.php';