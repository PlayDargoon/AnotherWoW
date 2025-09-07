<?php
// src/controllers/LoginController.php

class LoginController
{
    private $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    public function index()
    {
        renderTemplate('layout.html.php', ['contentFile' => 'pages/login.html.php']);
    }

    public function processLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);

            // Проверяем, существует ли такой логин
            if (!$this->userModel->existsUsername($username)) {
                renderTemplate('layout.html.php', ['contentFile' => 'pages/login.html.php', 'message' => 'Логин не найден.']);
                return;
            }

            // Проверяем пароль
            if ($this->userModel->authorizeUser($username, $password)) {
                // Сессия уже запущена в bootstrap.php, поэтому запускать её здесь не нужно
                $_SESSION['logged_in'] = true;
                $_SESSION['username'] = $username;
                header('Location: /cabinet');
                exit;
            } else {
                renderTemplate('layout.html.php', ['contentFile' => 'pages/login.html.php', 'message' => 'Неверный пароль.']);
            }
        }
    }
}