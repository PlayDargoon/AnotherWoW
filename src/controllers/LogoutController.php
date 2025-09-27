<?php
// src/controllers/LogoutController.php
require_once __DIR__ . '/../helpers/safe_redirect.php';

class LogoutController
{
    public function index()
    {
        // Включаем буферизацию вывода для дополнительной защиты
        if (!ob_get_level()) {
            ob_start();
        }
        
        // Безопасное уничтожение сессии
        safeSessionDestroy();
        
        // Безопасный редирект на главную страницу
        safeRedirect('/');
    }
}