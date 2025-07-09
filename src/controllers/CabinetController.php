<?php
// src/controllers/CabinetController.php

class CabinetController
{
    private $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    /**
     * Отображает страницу кабинета
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
        $userInfo = $this->userModel->getUserInfoByUsername($username);

        // Диагностика: выводим данные пользователя
        var_dump($userInfo); // DEBUG: смотрим данные пользователя

        // Передаем данные в шаблон
        $data = [
            'contentFile' => 'pages/cabinet.html.php', // Передаем путь к шаблону
            'userInfo' => $userInfo,
        ];

        // Рендерим шаблон
        renderTemplate('layout.html.php', $data);
    }
}