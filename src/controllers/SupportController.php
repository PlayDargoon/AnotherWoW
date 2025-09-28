<?php

class SupportController {
    public function handle() {
        $message = '';
        $messageType = '';

        // Загружаем конфиг почты
        $mailConfig = require __DIR__ . '/../../config/mail.php';
        $supportEmail = $mailConfig['support_email'] ?? 'support@azeroth.su';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $subject = trim($_POST['subject'] ?? '');
            $messageText = trim($_POST['message'] ?? '');
            
            // Валидация
            $errors = [];
            if (empty($name)) $errors[] = 'Имя обязательно для заполнения';
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Укажите корректный email';
            if (empty($subject)) $errors[] = 'Тема обязательна для заполнения';
            if (empty($messageText)) $errors[] = 'Сообщение обязательно для заполнения';
            
            if (empty($errors)) {
                $success = $this->sendSupportMessage($mailConfig, $supportEmail, $name, $email, $subject, $messageText);
                if ($success) {
                    $message = 'Ваше сообщение успешно отправлено! Мы ответим в течение 24 часов.';
                    $messageType = 'success';
                } else {
                    $message = 'Произошла ошибка при отправке сообщения. Попробуйте позже.';
                    $messageType = 'error';
                }
            } else {
                $message = implode('<br>', $errors);
                $messageType = 'error';
            }
        }
        
        // Рендер через общий layout
        $data = [
            'contentFile' => 'pages/support.html.php',
            'pageTitle' => 'Поддержка игроков',
            'message' => $message,
            'messageType' => $messageType,
            'supportEmail' => $supportEmail,
        ];
        renderTemplate('layout.html.php', $data);
    }

    private function sendSupportMessage(array $mailConfig, string $to, string $name, string $email, string $subject, string $messageText) {
        $useSmtp = (bool)($mailConfig['smtp']['enabled'] ?? false);

        $body = "<h3>Новое сообщение в поддержку</h3>".
                "<p><strong>Имя:</strong> " . htmlspecialchars($name) . "</p>".
                "<p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>".
                "<p><strong>Тема:</strong> " . htmlspecialchars($subject) . "</p>".
                "<p><strong>Сообщение:</strong></p>".
                "<p>" . nl2br(htmlspecialchars($messageText)) . "</p>";

        if ($useSmtp) {
            // Отправка через PHPMailer
            require_once __DIR__ . '/../libs/phpmailer/PHPMailer.php';
            require_once __DIR__ . '/../libs/phpmailer/SMTP.php';
            require_once __DIR__ . '/../libs/phpmailer/Exception.php';

            try {
                $mail = new PHPMailer\PHPMailer\PHPMailer(true);
                $mail->isSMTP();
                $mail->Host       = $mailConfig['smtp']['host'] ?? '';
                $mail->Port       = (int)($mailConfig['smtp']['port'] ?? 587);
                $mail->SMTPAuth   = (bool)($mailConfig['smtp']['auth'] ?? true);
                $mail->Username   = $mailConfig['smtp']['username'] ?? '';
                $mail->Password   = $mailConfig['smtp']['password'] ?? '';
                $enc = $mailConfig['smtp']['encryption'] ?? '';
                if ($enc) $mail->SMTPSecure = $enc;

                $mail->CharSet = 'UTF-8';
                $mail->setFrom($mailConfig['from_email'] ?? 'no-reply@azeroth.su', $mailConfig['from_name'] ?? 'Azeroth Support');
                $mail->addAddress($to);
                // Reply-To на пользователя
                if (!empty($email)) $mail->addReplyTo($email, $name ?: $email);

                $mail->isHTML(true);
                $mail->Subject = 'Поддержка: ' . $subject;
                $mail->Body    = $body;

                return $mail->send();
            } catch (\Throwable $e) {
                error_log('Mail error: ' . $e->getMessage());
                return false;
            }
        } else {
            // Отправка через mail()
            $headers = "From: " . ($mailConfig['from_email'] ?? 'no-reply@azeroth.su') . "\r\n";
            if (!empty($email)) $headers .= "Reply-To: $email\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            return @mail($to, "Поддержка: $subject", $body, $headers);
        }
    }
}