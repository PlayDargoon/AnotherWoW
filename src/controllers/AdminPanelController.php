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
        // Проверяем только права доступа (gmlevel)
        $userId = $_SESSION['user_id'] ?? null;
        $accessLevel = $userId ? $this->userModel->getUserAccessLevel($userId) : 0;

        if ($accessLevel < 4) {
            // Недостаточно прав — показываем ошибку и ссылку назад в кабинет
            renderTemplate('layout.html.php', [
                'contentFile' => 'pages/admin_panel_error.html.php',
            ]);
            return;
        }

        // Всё хорошо, отображаем панель администратора
        renderTemplate('layout.html.php', [
            'contentFile' => 'pages/admin_panel.html.php',
        ]);
    }
}