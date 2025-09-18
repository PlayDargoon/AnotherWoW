<?php
// src/controllers/RestorePasswordController.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailException;

// src/controllers/RestorePasswordController.php

class RestorePasswordController
{
    private $userModel;
    private $siteModel;

    public function __construct(User $userModel, Site $siteModel)
    {
        $this->userModel = $userModel;
        $this->siteModel = $siteModel;
    }

    /**
     * Показывает основную страницу восстановления пароля (форма для ввода email)
     */
    public function index()
    {
        renderTemplate('layout.html.php', ['contentFile' => 'pages/restore_password.html.php']);
        renderTemplate('layout.html.php', [
            'contentFile' => 'pages/restore_password.html.php',
            'pageTitle' => 'Восстановление пароля',
        ]);
    }

    /**
     * Отправляет письмо с токеном для восстановления пароля
     */
    public function sendResetLink()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];

            // Проверяем, существует ли такой email
            $user = $this->userModel->findByEmail($email);
            if (!$user) {
                renderTemplate('layout.html.php', [
                    'contentFile' => 'pages/restore_password.html.php',
                    'error' => 'Пользователь с таким email не зарегистрирован.',
                    'pageTitle' => 'Восстановление пароля',
                ]);
                return;
            }

            // Генерируем случайный токен
            $token = bin2hex(random_bytes(5)); // Токен длиной 10 символов
            $this->siteModel->saveResetToken($user['id'], $token);

            // Готовим ссылку для восстановления пароля
            $restoreUrl = $GLOBALS['site_url'] . "/verify-token";

            // Отправляем письмо
            $this->sendMail($email, $restoreUrl, $token, $user); // Передаём $user

            renderTemplate('layout.html.php', [
                'contentFile' => 'pages/restore_password.html.php',
                'message' => 'На указанный email отправлена инструкция по восстановлению пароля.',
                'pageTitle' => 'Восстановление пароля',
            ]);
        }
    }

    private function sendMail($email, $restoreUrl, $token, $user)
{
    $mail = new PHPMailer(true);
    try {
        // Настройка SMTP
        $mail->isSMTP();
        $mail->Host = 'connect.smtp.bz';
        $mail->SMTPAuth = true;
        $mail->Username = 'system@azeroth.su';
        $mail->Password = 'Vongola@530';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Данные письма
        $mail->setFrom('system@azeroth.su', 'Azeroth Support');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Восстановление пароля';
        $mail->CharSet = 'UTF-8'; // Указываем кодировку письма

        // HTML-шаблон письма
        $body = '
            <!DOCTYPE html>
            <html lang="ru">
            <head>
                <meta charset="UTF-8">
                <title>Восстановление пароля</title>
            </head>
            <body style="background-color: #474747; color: #646464; font-size: 12px; font-family: Arial, Helvetica, sans-serif;">
                <div style="background: linear-gradient(to top, #757090, #928dab); text-align: center; padding: 10px;">
                    <h1 style="color: #fff;">Здравствуй, ' . $user['username'] . '!</h1>
                </div>

                <table width="320" align="center" border="0" cellpadding="0" cellspacing="0" bgcolor="#ffffff" style="border-collapse: separate; border-spacing: 15px 15px;">
                    <tbody>
                        <tr>
                            <td>
                                <p style="font-size: 12px; color: #646464;">
                                    Чтобы восстановить пароль на сервере Azeroth для пользователя ' . $user['username'] . ',
                                    перейдите по <a href="' . $restoreUrl . '" style="color: #0267e3;" target="_blank">ссылке</a> и введите токен восстановления: <strong>' . $token . '</strong>.<br>
                                    Если ты не запрашивал восстановление пароля, просто удали это письмо.
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td align="right">
                                <p style="font-size: 12px; color: #646464;">Команда Azeroth.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <table width="320" align="center" border="0" cellpadding="0" cellspacing="0" bgcolor="#474747">
                    <tbody>
                        <tr>
                            <td style="padding-top: 10px; text-align: center;">
                                <address style="font-size: 11px; color: #d8d8d8; line-height: 1.3em; text-align: center;">
                                    © 2025 PlayDragon Games<br>
                                    <a href="#" style="color: #d8d8d8; text-decoration: underline;">Соглашение</a> |
                                    <a href="@" style="color: #d8d8d8; text-decoration: underline;">Политика конфиденциальности</a><br>
                                    Поддержка: <a href="mailto:support@azeroth.su" style="color: #d8d8d8; text-decoration: underline;">support@azeroth.su</a>
                                </address>
                            </td>
                        </tr>
                        <tr>
                            <td height="15"></td>
                        </tr>
                    </tbody>
                </table>
            </body>
            </html>
        ';

        $mail->Body = $body;
        $mail->AltBody = strip_tags($body); // альтернативный текст для клиентов без HTML

        $mail->send();
    } catch (MailException $e) {
        renderTemplate('layout.html.php', ['contentFile' => 'pages/restore_password.html.php', 'error' => 'Ошибка отправки письма. Обратитесь в техническую поддержку.']);
        renderTemplate('layout.html.php', [
            'contentFile' => 'pages/restore_password.html.php',
            'error' => 'Ошибка отправки письма. Обратитесь в техническую поддержку.',
            'pageTitle' => 'Восстановление пароля',
        ]);
    }
}

    /**
     * Проверяет токен восстановления пароля и возвращает пользователя, если токен верный
     */
    public function verifyToken()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['token'];

            // Проверяем токен на валидность
            $user = $this->siteModel->validateResetToken($token);
            if (!$user) {
                renderTemplate('layout.html.php', [
                    'contentFile' => 'pages/verify_token.html.php',
                    'error' => 'Токен недействителен или срок действия истек.',
                    'pageTitle' => 'Восстановление пароля',
                ]);
                return;
            }

            // Перенаправляем на страницу для ввода нового пароля
            renderTemplate('layout.html.php', [
                'contentFile' => 'pages/set_new_password.html.php',
                'token' => $token,
                'pageTitle' => 'Восстановление пароля',
            ]);
        } else {
            renderTemplate('layout.html.php', [
                'contentFile' => 'pages/verify_token.html.php',
                'pageTitle' => 'Восстановление пароля',
            ]);
        }
    }

    public function showVerifyTokenForm()
    {
        renderTemplate('layout.html.php', ['contentFile' => 'pages/verify_token.html.php']);
        renderTemplate('layout.html.php', [
            'contentFile' => 'pages/verify_token.html.php',
            'pageTitle' => 'Восстановление пароля',
        ]);
    }

    public function showSetPasswordForm($token)
    {
        renderTemplate('layout.html.php', ['contentFile' => 'pages/set_new_password.html.php', 'token' => $token]);
        renderTemplate('layout.html.php', [
            'contentFile' => 'pages/set_new_password.html.php',
            'token' => $token,
            'pageTitle' => 'Восстановление пароля',
        ]);
    }

   public function setNewPassword($token)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];

        if ($password !== $confirmPassword) {
            renderTemplate('layout.html.php', [
                'contentFile' => 'pages/set_new_password.html.php',
                'token' => $token,
                'error' => 'Пароли не совпадают.',
                'pageTitle' => 'Восстановление пароля',
            ]);
            return;
        }

        // Проверяем токен на валидность
        $user = $this->siteModel->validateResetToken($token);
        if (!$user) {
            renderTemplate('layout.html.php', [
                'contentFile' => 'pages/set_new_password.html.php',
                'token' => $token,
                'error' => 'Токен недействителен или срок действия истек.',
                'pageTitle' => 'Восстановление пароля',
            ]);
            return;
        }

        // Меняем пароль
        $this->userModel->changePassword($user['id'], $password, $user); // Передаём $user

        // Перенаправляем на страницу входа
        header('Location: /login');
        exit;
    } else {
        renderTemplate('layout.html.php', ['contentFile' => 'pages/set_new_password.html.php', 'token' => $token]);
        renderTemplate('layout.html.php', [
            'contentFile' => 'pages/set_new_password.html.php',
            'token' => $token,
            'pageTitle' => 'Восстановление пароля',
        ]);
    }
}
}