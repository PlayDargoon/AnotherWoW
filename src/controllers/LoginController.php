<?php
// src/controllers/LoginController.php

class LoginController
{
    private $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    /**
     * Отображает форму входа
     */
    public function index()
    {
        // Передаем данные в шаблон
        $data = [
            'contentFile' => 'pages/login.html.php', // Путь к шаблону
        ];

        // Рендерим шаблон
        renderTemplate('layout.html.php', $data);
    }

    /**
     * Обрабатывает POST-запрос и авторизирует пользователя
     */
    public function processLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);

            // Проверяем валидность полей
            if (empty($username) || empty($password)) {
                renderTemplate('pages/login.html.php', ['errors' => ['Все поля обязательны для заполнения.']]);
                return;
            }

            // Пробуем авторизоваться
            if ($this->userModel->authorizeUser($username, $password)) {
                session_start();
                $_SESSION['logged_in'] = true;
                $_SESSION['username'] = $username;
                header('Location: /profile'); // Перенаправляем на страницу кабинета
                exit;
            } else {
                renderTemplate('pages/login.html.php', ['errors' => ['Неверное имя пользователя или пароль.']]);
            }
        }
    }
}