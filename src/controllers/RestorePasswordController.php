<?php
// src/controllers/RestorePasswordController.php

// Подключаем PHPMailer
require_once __DIR__ . '/../libs/phpmailer/PHPMailer.php';
require_once __DIR__ . '/../libs/phpmailer/SMTP.php';
require_once __DIR__ . '/../libs/phpmailer/Exception.php';

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
        try {
            $this->sendMail($email, $restoreUrl, $token, $user); // Передаём $user
            renderTemplate('layout.html.php', [
                'contentFile' => 'pages/restore_password.html.php',
                'message' => 'На указанный email отправлена инструкция по восстановлению пароля.',
                'pageTitle' => 'Восстановление пароля',
            ]);
        } catch (\Exception $e) {
            renderTemplate('layout.html.php', [
                'contentFile' => 'pages/restore_password.html.php',
                'error' => $e->getMessage(),
                'pageTitle' => 'Восстановление пароля',
            ]);
        }
    }

    private function sendMail($email, $restoreUrl, $token, $user)
{
    // Режим отладки отключен - реальная отправка
    $debugMode = false; // Установить в true для эмуляции
    
    if ($debugMode) {
        // Эмулируем успешную отправку для тестирования
        error_log("DEBUG: Эмуляция отправки письма на $email с токеном $token");
        error_log("DEBUG: Ссылка восстановления: $restoreUrl");
        return; // Выходим без реальной отправки
    }
    
    // Попробуем разные конфигурации портов
    $configs = [
        ['port' => 2525, 'secure' => false, 'name' => 'Standard'],
        ['port' => 587, 'secure' => PHPMailer::ENCRYPTION_STARTTLS, 'name' => 'TLS'],
        ['port' => 465, 'secure' => PHPMailer::ENCRYPTION_SMTPS, 'name' => 'SSL']
    ];
    
    $lastError = '';
    
    foreach ($configs as $config) {
        $mail = new PHPMailer(true);
        try {
            error_log("Пробуем отправку через порт {$config['port']} ({$config['name']})");
            
            // Отключаем отладку для множественных попыток
            $mail->SMTPDebug = 0;
            
            // Настройка SMTP для connect.smtp.bz
            $mail->isSMTP();
            $mail->Host = 'connect.smtp.bz';
            $mail->SMTPAuth = true;
            $mail->Username = 'system@azeroth.su';
            $mail->Password = 'Vongola@530';
            
            // Настройки для текущей конфигурации
            $mail->Port = $config['port'];
            $mail->SMTPSecure = $config['secure'];
            
            // Дополнительные настройки для совместимости
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            
            // Увеличиваем таймауты для медленных соединений
            $mail->Timeout = 30;
            $mail->SMTPKeepAlive = true;

        // Данные письма
        $mail->setFrom('system@azeroth.su', 'Azeroth Support');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Восстановление пароля';
        $mail->CharSet = 'UTF-8'; // Указываем кодировку письма

        // HTML-шаблон письма в стиле сайта
        $body = '
            <!DOCTYPE html>
            <html lang="ru">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Восстановление пароля - Azeroth</title>
                <style>
                    body {
                        margin: 0;
                        padding: 0;
                        font-family: Verdana, Arial, sans-serif;
                        background-color: #333333;
                        color: #ffffff;
                        line-height: 1.4;
                    }
                    .email-container {
                        max-width: 600px;
                        margin: 0 auto;
                        background-color: #000033;
                        border: 2px solid #333366;
                        border-radius: 8px;
                        overflow: hidden;
                    }
                    .header {
                        background: linear-gradient(135deg, #333366 0%, #000033 50%, #333366 100%);
                        padding: 20px;
                        text-align: center;
                        border-bottom: 2px solid #ff6600;
                    }
                    .logo {
                        font-size: 28px;
                        font-weight: bold;
                        color: #ff6600;
                        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
                        margin-bottom: 5px;
                    }
                    .subtitle {
                        font-size: 14px;
                        color: #ffff33;
                        margin: 0;
                    }
                    .content {
                        padding: 30px 25px;
                        background-color: #000033;
                    }
                    .greeting {
                        font-size: 18px;
                        color: #ff6600;
                        margin-bottom: 20px;
                        font-weight: bold;
                    }
                    .message {
                        font-size: 14px;
                        color: #ffffff;
                        margin-bottom: 20px;
                        line-height: 1.6;
                    }
                    .button-container {
                        text-align: center;
                        margin: 30px 0;
                    }
                    .restore-button {
                        display: inline-block;
                        background: linear-gradient(135deg, #ff6600 0%, #ff8800 50%, #ff6600 100%);
                        color: #ffffff !important;
                        text-decoration: none;
                        padding: 15px 35px;
                        border-radius: 6px;
                        font-weight: bold;
                        font-size: 16px;
                        border: 2px solid #ffff33;
                        box-shadow: 0 4px 8px rgba(0,0,0,0.3);
                        text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
                    }
                    .warning {
                        background-color: #333366;
                        border: 1px solid #ffff33;
                        border-radius: 4px;
                        padding: 15px;
                        margin: 20px 0;
                        font-size: 13px;
                        color: #ffff33;
                    }
                    .footer {
                        background-color: #333366;
                        padding: 20px;
                        text-align: center;
                        border-top: 2px solid #ff6600;
                    }
                    .footer-text {
                        font-size: 12px;
                        color: #cccccc;
                        margin: 5px 0;
                    }
                    .footer-links {
                        margin-top: 15px;
                    }
                    .footer-links a {
                        color: #ffff33;
                        text-decoration: underline;
                        margin: 0 10px;
                        font-size: 12px;
                    }
                    .separator {
                        height: 2px;
                        background: linear-gradient(90deg, #ff6600 0%, #ffff33 50%, #ff6600 100%);
                        margin: 20px 0;
                    }
                    .username {
                        color: #ffff33;
                        font-weight: bold;
                    }
                    .token-info {
                        background-color: #333366;
                        border: 1px dashed #ffff33;
                        border-radius: 4px;
                        padding: 10px;
                        margin: 15px 0;
                        text-align: center;
                        font-family: monospace;
                        font-size: 14px;
                        color: #ffff33;
                    }
                </style>
            </head>
            <body>
                <div class="email-container">
                    <!-- Заголовок -->
                    <div class="header">
                        <div class="logo">⚔️ AZEROTH ⚔️</div>
                        <div class="subtitle">Мир приключений ждет тебя</div>
                    </div>
                    
                    <!-- Основной контент -->
                    <div class="content">
                        <div class="greeting">Приветствую, <span class="username">' . htmlspecialchars($user['username']) . '</span>!</div>
                        
                        <div class="message">
                            Мы получили запрос на восстановление пароля для твоего аккаунта на сервере <strong style="color: #ff6600;">Azeroth</strong>.
                        </div>
                        
                        <div class="message">
                            Для установки нового пароля нажми на кнопку ниже:
                        </div>
                        
                        <div class="button-container">
                            <a href="' . $restoreUrl . '" class="restore-button">
                                🔐 Восстановить пароль
                            </a>
                        </div>
                        
                        <div class="separator"></div>
                        
                        <div class="token-info">
                            <strong>Токен восстановления:</strong><br>
                            ' . $token . '
                        </div>
                        
                        <div class="warning">
                            <strong>⚠️ Важная информация:</strong><br>
                            • Ссылка действительна только 24 часа<br>
                            • Если ты не запрашивал восстановление пароля, просто проигнорируй это письмо<br>
                            • Никому не передавай эту ссылку и токен
                        </div>
                        
                        <div class="message" style="font-size: 12px; color: #cccccc; margin-top: 25px;">
                            Если кнопка не работает, скопируй эту ссылку в браузер:<br>
                            <span style="color: #ffff33; word-break: break-all; font-family: monospace;">' . $restoreUrl . '</span>
                        </div>
                    </div>
                    
                    <!-- Футер -->
                    <div class="footer">
                        <div class="footer-text">© 2025 Azeroth Gaming Server</div>
                        <div class="footer-text">Мир магии, приключений и легендарных битв</div>
                        <div class="footer-links">
                            <a href="https://azeroth.su">🏠 Главная</a>
                            <a href="https://azeroth.su/cabinet">👤 Кабинет</a>
                            <a href="mailto:support@azeroth.su">📧 Поддержка</a>
                        </div>
                    </div>
                </div>
            </body>
            </html>
        ';

        $mail->Body = $body;
        $mail->AltBody = strip_tags($body); // альтернативный текст для клиентов без HTML

        $mail->send();
        
        // Если достигли этой точки - отправка успешна
        error_log("Письмо успешно отправлено через порт {$config['port']} ({$config['name']})");
        return; // Выходим из функции при успешной отправке
        
    } catch (Exception $e) {
        $lastError = $e->getMessage();
        error_log("Ошибка порта {$config['port']} ({$config['name']}): " . $lastError);
        continue; // Пробуем следующую конфигурацию
    }
}

// Если все конфигурации не сработали
error_log('SMTP Error: Все порты недоступны. Последняя ошибка: ' . $lastError);
throw new \Exception('Ошибка отправки письма. Обратитесь в техническую поддержку.');
}

    /**
     * Проверяет токен восстановления пароля и возвращает пользователя, если токен верный
     */
    public function verifyToken()
    {
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
    }

    public function showVerifyTokenForm()
    {
        renderTemplate('layout.html.php', [
            'contentFile' => 'pages/verify_token.html.php',
            'pageTitle' => 'Восстановление пароля',
        ]);
    }

    public function showSetPasswordForm($token)
    {
        renderTemplate('layout.html.php', [
            'contentFile' => 'pages/set_new_password.html.php',
            'token' => $token,
            'pageTitle' => 'Восстановление пароля',
        ]);
    }

   public function setNewPassword($token)
{
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
}
}