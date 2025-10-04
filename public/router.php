<?php
// router.php для встроенного PHP сервера

// Если запрашивается статический файл и он существует, отдаем его
if (php_sapi_name() === 'cli-server') {
    $path = $_SERVER['REQUEST_URI'];
    $file = __DIR__ . parse_url($path, PHP_URL_PATH);
    
    // Если это статический файл и он существует
    if (is_file($file)) {
        return false; // Позволяем серверу обработать файл
    }
}

// Иначе перенаправляем на index.php
require_once __DIR__ . '/index.php';