<?php
// Создаем превью нового дизайна письма

$user = ['username' => 'TestWarrior'];
$token = 'abc123def456ghi789jkl012mno345pqr678stu901vwx234yzABCD';
$restoreUrl = 'https://azeroth.su/restore-password?token=' . $token;

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

// Сохраняем превью в файл
file_put_contents('email_preview.html', $body);

echo "✅ Превью письма создано в файле email_preview.html\n";
echo "Откройте этот файл в браузере, чтобы посмотреть на новый дизайн!\n";
?>