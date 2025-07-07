<?php
// src/controllers/LoginController.php

class LoginController
{
    private $authModel;

    public function __construct(Auth $authModel)
    {
        $this->authModel = $authModel;
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
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            // Проверяем валидность полей
            if (empty($email) || empty($password)) {
                renderTemplate('pages/login.html.php', ['errors' => ['Все поля обязательны для заполнения.']]);
                return;
            }

            // Пробуем авторизоваться
            if ($this->authModel->authorizeUser($email, $password)) {
                session_start();
                $_SESSION['logged_in'] = true;
                $_SESSION['email'] = $email;
                header('Location: /profile'); // Перенаправляем на страницу профиля
                exit;
            } else {
                renderTemplate('pages/login.html.php', ['errors' => ['Неверный email или пароль.']]);
            }
        }
    }
}