<?php
// src/controllers/RegisterController.php


class RegisterController
{
    private $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    /**
     * Отображает форму регистрации
     */
    public function index()
    {
        // Передаем данные в шаблон
        $data = [
            'contentFile' => 'pages/register.html.php', // Передаем путь к шаблону
        ];

        // Рендерим шаблон
        renderTemplate('layout.html.php', $data);
    }

    /**
     * Обрабатывает POST-запрос и регистрирует пользователя
     */
     public function processRegistration()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            // Проверяем валидность полей
            if (empty($username) || empty($email) || empty($password)) {
                renderTemplate('layout.html.php', ['contentFile' => 'pages/register.html.php', 'error' => 'Все поля обязательны для заполнения.']);
                return;
            }

            // Проверяем, занят ли логин
            if ($this->userModel->existsUsername($username)) {
                renderTemplate('layout.html.php', ['contentFile' => 'pages/register.html.php', 'error' => 'Логин уже занят.']);
                return;
            }

            // Проверяем, занят ли email
            if ($this->userModel->existsEmail($email)) {
                renderTemplate('layout.html.php', ['contentFile' => 'pages/register.html.php', 'error' => 'Такой email уже используется.']);
                return;
            }

            // Генерация Salt и Verifier
            $salt = generateSalt();
            $verifier = calculateSRP6Verifier($username, $password, $salt);

            // Регистрируем пользователя
            $newUserId = $this->userModel->createNewUser($username, $email, $salt, $verifier);

            if ($newUserId) {
                header('Location: /'); // Перенаправляем на главную страницу
                exit;
            } else {
                renderTemplate('layout.html.php', ['contentFile' => 'pages/register.html.php', 'error' => 'Возникла ошибка при регистрации. Попробуйте позже.']);
            }
        }
    }
}