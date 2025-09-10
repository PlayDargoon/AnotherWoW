<?php
// src/controllers/AdminPanelController.php

class AdminPanelController
{
    private $userModel;

    // Конструктор, принимающий экземпляр модели User
    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    /**
     * Индексная страница админ-панели
     */
    public function index()
    {
        // Проверяем, существует ли сессия и идентификатор пользователя
        if (!isset($_SESSION['user_id'])) {
            // Если идентификатор пользователя не установлен, перенаправляем на главную страницу
            header('Location: /');
            exit;
        }

        // Получаем ID пользователя
        $userId = $_SESSION['user_id'];

        // Проверяем уровень доступа
        $accessLevel = $this->userModel->getUserAccessLevel($userId);

        var_dump($accessLevel); // Выведет уровень доступа для отладки
        exit;

        if ($accessLevel != 4) {
            // Если доступ запрещён (уровень отличается от 4), перенаправляем на домашнюю страницу
            header('Location: /cabinet');
            exit;
        }

        // Всё хорошо, отображаем панель администратора
        renderTemplate('layout.html.php', ['contentFile' => 'pages/admin_panel.html.php']);
    }
}