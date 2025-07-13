<?php
// src/controllers/LoginController.php

class LoginController
{
    private $authModel;

    public function __construct(Auth $authModel)
    {
        $this->authModel = $authModel;
    }

    public function index()
    {
        // Отображение формы входа
        renderTemplate('layout.html.php', ['contentFile' => 'pages/login.html.php']);
    }

    public function processLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);

            // Проверяем пароль
            if ($this->authModel->authorizeUser($username, $password)) {
                session_start();
                $_SESSION['logged_in'] = true;
                $_SESSION['username'] = $username;
                header('Location: /cabinet');
                exit;
            } else {
                renderTemplate('pages/login.html.php', ['errors' => ['Неверный логин или пароль.']]);
            }
        }
    }
}