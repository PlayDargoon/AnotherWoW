<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'bootstrap.php';
require_once 'src/services/DatabaseConnection.php';
require_once 'src/models/User.php';
require_once 'src/models/Site.php';
require_once 'src/controllers/RestorePasswordController.php';

echo "=== Тест системы восстановления пароля ===\n";

try {
    // Получаем подключение к базе данных
    $pdo = DatabaseConnection::getAuthConnection();
    
    // Создаем модели
    $userModel = new User($pdo);
    $siteModel = new Site($pdo);
    
    // Создаем контроллер
    $controller = new RestorePasswordController($userModel, $siteModel);
    
    // Симулируем POST запрос для отправки письма
    $_POST['email'] = 'test@example.com';
    $_SERVER['REQUEST_METHOD'] = 'POST';
    
    echo "Тестируем отправку письма восстановления...\n";
    
    // Включаем буферизацию вывода чтобы перехватить рендеринг
    ob_start();
    $controller->sendResetLink();
    $output = ob_get_clean();
    
    echo "Запрос обработан успешно!\n";
    echo "Длина ответа: " . strlen($output) . " символов\n";
    
    // Проверяем логи на ошибки
    if (file_exists('error.log')) {
        $log = file_get_contents('error.log');
        if (strpos($log, 'SMTP Error') !== false) {
            echo "⚠️  Обнаружены SMTP ошибки в логе\n";
        } else {
            echo "✅ SMTP ошибок не обнаружено\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Ошибка: " . $e->getMessage() . "\n";
    echo "Трассировка:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== Тест завершен ===\n";
?>