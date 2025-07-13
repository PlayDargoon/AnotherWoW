<?php
// src/controllers/CabinetController.php

class CabinetController
{
    private $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    public function index()
    {
        // Проверяем, вошёл ли пользователь в систему
        if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
            // Здесь заменяем автоматическое перенаправление на страницу входа на рендеринг специального шаблона
            renderTemplate('layout.html.php', [
                'contentFile' => 'pages/error_authorization_required.html.php'
            ]);
        } else {
            // Если авторизованы, получаем данные пользователя
            $username = $_SESSION['username'];
            $userInfo = $this->userModel->getUserInfoByUsername($username);

            // Передаем данные в шаблон
            $data = [
                'contentFile' => 'pages/cabinet.html.php', // Передаем путь к шаблону
                'userInfo' => $userInfo,
            ];

            // Рендерим шаблон
            renderTemplate('layout.html.php', $data);
        }
    }
}