<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Простой тест SMTP без зависимостей
require_once 'src/libs/phpmailer/PHPMailer.php';
require_once 'src/libs/phpmailer/SMTP.php';
require_once 'src/libs/phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailException;

echo "=== Тест SMTP отправки ===\n";

// Конфигурации для тестирования
$configs = [
    ['port' => 2525, 'secure' => false, 'name' => 'Standard'],
    ['port' => 587, 'secure' => PHPMailer::ENCRYPTION_STARTTLS, 'name' => 'TLS'],
    ['port' => 465, 'secure' => PHPMailer::ENCRYPTION_SMTPS, 'name' => 'SSL']
];

$success = false;

foreach ($configs as $config) {
    echo "\n--- Тестируем порт {$config['port']} ({$config['name']}) ---\n";
    
    $mail = new PHPMailer(true);
    
    try {
        // Включаем детальную отладку
        $mail->SMTPDebug = 2;
        $mail->Debugoutput = function($str, $level) {
            echo "DEBUG ($level): $str\n";
        };
        
        // Настройка SMTP
        $mail->isSMTP();
        $mail->Host = 'connect.smtp.bz';
        $mail->SMTPAuth = true;
        $mail->Username = 'system@azeroth.su';
        $mail->Password = 'Vongola@530';
        
        // Настройки для текущей конфигурации
        $mail->Port = $config['port'];
        $mail->SMTPSecure = $config['secure'];
        
        // Дополнительные настройки
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        
        $mail->Timeout = 30;
        
        // Настройка письма
        $mail->setFrom('system@azeroth.su', 'Test');
        $mail->addAddress('test@example.com', 'Test User');
        $mail->Subject = 'Test Email - Port ' . $config['port'];
        $mail->Body = 'This is a test email sent via port ' . $config['port'];
        
        // Пытаемся отправить
        echo "Попытка отправки...\n";
        $mail->send();
        
        echo "✅ УСПЕШНО! Письмо отправлено через порт {$config['port']}\n";
        $success = true;
        break; // Прекращаем тестирование при первом успехе
        
    } catch (Exception $e) {
        echo "❌ ОШИБКА порта {$config['port']}: " . $e->getMessage() . "\n";
        continue;
    }
}

if (!$success) {
    echo "\n❌ Все попытки не удались. Проверьте настройки SMTP.\n";
} else {
    echo "\n✅ Тест завершен успешно!\n";
}

echo "\n=== Тест завершен ===\n";
?>