<?php
// src/controllers/ProfileController.php

class ProfileController
{
    private $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    /**
     * Отображает страницу профиля
     */
    public function index()
    {
        // Проверяем, вошел ли пользователь в систему
        if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
            header('Location: /login');
            exit;
        }

        // Получаем данные пользователя
        $username = $_SESSION['username'];

        // Передаем данные в шаблон
        $data = [
            'contentFile' => 'pages/profile.html.php', // Передаем путь к шаблону
            'username' => $username,
        ];

        // Рендерим шаблон
        renderTemplate('layout.html.php', $data);
    }
}