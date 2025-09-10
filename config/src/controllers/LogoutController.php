<?php
// src/controllers/LogoutController.php

class LogoutController
{
    public function index()
    {
        // Уничтожаем сессию
        session_destroy();
        unset($_SESSION['logged_in']);
        unset($_SESSION['username']);

        // Перенаправляем на главную страницу
        header('Location: /');
        exit;
    }
}