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
        // Если пользователь уже залогинен, перенаправляем в кабинет
        if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            header('Location: /cabinet');
            exit;
        }
        
        // Генерируем новую капчу
        $captchaQuestion = CaptchaService::generateAndStoreCaptcha();
        
        // Передаем данные в шаблон
        $data = [
            'contentFile' => 'pages/register.html.php',
            'pageTitle' => 'Регистрация',
            'captchaQuestion' => $captchaQuestion
        ];
        renderTemplate('layout.html.php', $data);
    }

    /**
     * Обрабатывает POST-запрос и регистрирует пользователя
     */
     public function processRegistration()
    {
        // Если пользователь уже залогинен, перенаправляем в кабинет
        if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            header('Location: /cabinet');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $captchaAnswer = trim($_POST['captcha_answer'] ?? '');

            // Проверяем валидность полей
            if (empty($username) || empty($email) || empty($password)) {
                $captchaQuestion = CaptchaService::generateAndStoreCaptcha();
                renderTemplate('layout.html.php', [
                    'contentFile' => 'pages/register.html.php', 
                    'error' => 'Все поля обязательны для заполнения.',
                    'captchaQuestion' => $captchaQuestion
                ]);
                return;
            }
            
            // Проверяем капчу
            if (!CaptchaService::verifyCaptcha($captchaAnswer)) {
                $captchaQuestion = CaptchaService::generateAndStoreCaptcha();
                renderTemplate('layout.html.php', [
                    'contentFile' => 'pages/register.html.php', 
                    'error' => 'Неверный ответ на математический пример. Попробуйте снова.',
                    'captchaQuestion' => $captchaQuestion
                ]);
                return;
            }
            
            // Проверяем согласие с документами
            $agreePrivacy = isset($_POST['agree_privacy']) && $_POST['agree_privacy'] === 'on';
            $agreeTerms = isset($_POST['agree_terms']) && $_POST['agree_terms'] === 'on';
            $agreeRules = isset($_POST['agree_rules']) && $_POST['agree_rules'] === 'on';
            
            if (!$agreePrivacy || !$agreeTerms || !$agreeRules) {
                $captchaQuestion = CaptchaService::generateAndStoreCaptcha();
                renderTemplate('layout.html.php', [
                    'contentFile' => 'pages/register.html.php', 
                    'error' => 'Необходимо согласиться со всеми условиями для завершения регистрации.',
                    'captchaQuestion' => $captchaQuestion
                ]);
                return;
            }

            // Проверяем, занят ли логин
            if ($this->userModel->existsUsername($username)) {
                $captchaQuestion = CaptchaService::generateAndStoreCaptcha();
                renderTemplate('layout.html.php', [
                    'contentFile' => 'pages/register.html.php', 
                    'error' => 'Логин уже занят.',
                    'captchaQuestion' => $captchaQuestion
                ]);
                return;
            }

            // Проверяем, занят ли email
            if ($this->userModel->findByEmail($email)) {
                $captchaQuestion = CaptchaService::generateAndStoreCaptcha();
                renderTemplate('layout.html.php', [
                    'contentFile' => 'pages/register.html.php', 
                    'error' => 'Такой email уже используется.',
                    'captchaQuestion' => $captchaQuestion
                ]);
                return;
            }

            // Генерация Salt и Verifier
            $salt = generateSalt();
            $verifier = calculateSRP6Verifier($username, $password, $salt);

            // Регистрируем пользователя
            $newUserId = $this->userModel->createNewUser($username, $email, $salt, $verifier);

            if ($newUserId) {
                header('Location: /login'); // Перенаправляем на страницу входа
                exit;
            } else {
                $captchaQuestion = CaptchaService::generateAndStoreCaptcha();
                renderTemplate('layout.html.php', [
                    'contentFile' => 'pages/register.html.php', 
                    'error' => 'Возникла ошибка при регистрации. Попробуйте позже.',
                    'captchaQuestion' => $captchaQuestion
                ]);
            }
        }
    }
}