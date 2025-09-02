<?php
// src/controllers/RestorePasswordController.php

class RestorePasswordController
{
    private $userModel;
    private $pdo;

    public function __construct(User $userModel, \PDO $pdo)
    {
        $this->userModel = $userModel;
        $this->pdo = $pdo;
    }

    public function requestPasswordRecovery()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $email = $_POST["email"];

            // Проверяем, существует ли пользователь с данным email
            $user = $this->userModel->findByEmail($email);
            if (!$user) {
                // Пользователь не найден
                $data = ['message' => 'Пользователь с таким email не зарегистрирован'];
                renderTemplate('layout.html.php', ['contentFile' => 'pages/error.html.php', 'data' => $data]);
                return;
            }

            // Генерируем временный токен для сброса пароля
            $token = bin2hex(random_bytes(16)); // Случайный токен длиной 32 символа
            $this->userModel->saveResetToken($user['id'], $token);

            // Отправляем письмо с ссылкой для сброса пароля
            $this->sendResetLink($user, $token);

            // Сообщаем пользователю, что письмо отправлено
            $data = ['message' => 'Ссылка для сброса пароля отправлена на вашу почту'];
            renderTemplate('layout.html.php', ['contentFile' => 'pages/message.html.php', 'data' => $data]);
        } else {
            // Простая форма для ввода email
            renderTemplate('layout.html.php', ['contentFile' => 'pages/restore_password.html.php']);
        }
    }

    protected function sendResetLink(array $user, string $token)
    {
        // Параметры SMTP
        $smtp_host = 'connect.smtp.bz';
        $smtp_port = 2525;
        $smtp_user = 'system@azeroth.su';
        $smtp_pass = 'Vongola@530';
        $from_email = 'system@azeroth.su';
        $from_name = 'Azeroth Support';

        // Создаем экземпляр PHPMailer
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);

        try {
            // Настройки SMTP
            $mail->SMTPDebug = 0; // Debug mode off
            $mail->isSMTP();
            $mail->Host = $smtp_host;
            $mail->SMTPAuth = true;
            $mail->Username = $smtp_user;
            $mail->Password = $smtp_pass;
            $mail->SMTPSecure = 'tls';
            $mail->Port = $smtp_port;

            // Параметры почты
            $mail->setFrom($from_email, $from_name);
            $mail->addAddress($user['email'], $user['username']);

            // Содержимое письма
            $mail->isHTML(true);
            $mail->Subject = 'Восстановление пароля';
            $mail->Body = "<html><body>
                            <h2>Привет, {$user['username']}</h2>
                            <p>Вы запросили восстановление пароля. Перейдите по следующей ссылке для смены пароля:</p>
                            <a href=\"{$GLOBALS['site_url']}/reset-password/?token={$token}\">Сменить пароль</a>
                            <p>Если вы не запрашивали смену пароля, игнорируйте данное письмо.</p>
                        </body></html>";

            // Отправляем письмо
            $mail->send();
        } catch (\Exception $e) {
            // Логируем ошибку отправки письма
            error_log("Ошибка отправки письма: " . $e->getMessage());
        }
    }

    public function resetPassword($token)
{
    // Проверка токена
    $stmt = $this->pdo->prepare("
        SELECT pr.user_id, acc.username
        FROM password_reset_tokens pr
        JOIN account acc ON pr.user_id = acc.id
        WHERE pr.token = :token AND pr.expires_at >= NOW()
    ");
    $stmt->execute(['token' => $token]);
    $row = $stmt->fetch(\PDO::FETCH_ASSOC);

    if (!$row) {
        // Токен недействителен или просрочен
        renderTemplate('layout.html.php', ['contentFile' => 'pages/token_expired.html.php']);
        return;
    }

    // Удаляем использованный токен
    $this->deleteUsedToken($token);

    // Отображаем форму для ввода нового пароля
    renderTemplate('layout.html.php', ['contentFile' => 'pages/reset_password.html.php', 'data' => ['userId' => $row['user_id']]]);
}

    protected function deleteUsedToken($token)
    {
        $stmt = $this->pdo->prepare("DELETE FROM password_reset_tokens WHERE token = :token");
        $stmt->execute(['token' => $token]);
    }

    public function processResetPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_POST['userId'];
            $newPassword = $_POST['newPassword'];

            // Обновление пароля пользователя
            $this->updatePassword($userId, $newPassword);

            // Уведомление пользователя о том, что пароль успешно изменен
            renderTemplate('layout.html.php', ['contentFile' => 'pages/password_reset_success.html.php']);
        } else {
            // Отображение формы для ввода нового пароля
            renderTemplate('layout.html.php', ['contentFile' => 'pages/reset_password.html.php']);
        }
    }

    protected function updatePassword($userId, $newPassword)
    {
        // Логика обновления пароля пользователя
        // Например, хеширование нового пароля и обновление в базе данных
        // ...
    }
}