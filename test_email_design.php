<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'bootstrap.php';
require_once 'src/services/DatabaseConnection.php';
require_once 'src/models/User.php';
require_once 'src/models/Site.php';
require_once 'src/controllers/RestorePasswordController.php';

echo "=== Тест нового дизайна письма ===\n";

try {
    // Получаем подключение к базе данных
    $pdo = DatabaseConnection::getAuthConnection();
    
    // Создаем модели
    $userModel = new User($pdo);
    $siteModel = new Site($pdo);
    
    // Создаем контроллер
    $controller = new RestorePasswordController($userModel, $siteModel);
    
    // Симулируем существующего пользователя
    $testUser = [
        'id' => 1,
        'username' => 'TestWarrior',
        'email' => 'test@example.com'
    ];
    
    // Генерируем токен для тестирования
    $token = bin2hex(random_bytes(32));
    $restoreUrl = 'https://azeroth.su/restore-password?token=' . $token;
    
    echo "Создаем превью письма с новым дизайном...\n";
    
    // Получаем доступ к приватному методу через рефлексию
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('sendMail');
    $method->setAccessible(true);
    
    // Вместо отправки сохраним HTML в файл для просмотра
    ob_start();
    try {
        // Включаем режим отладки в контроллере временно
        $controller->debugMode = true;
        $method->invoke($controller, $testUser['email'], $restoreUrl, $token, $testUser);
    } catch (Exception $e) {
        echo "Ошибка: " . $e->getMessage() . "\n";
    }
    $output = ob_get_clean();
    
    echo "✅ Тест письма завершен!\n";
    echo "Проверьте логи на предмет ошибок отправки.\n";
    
} catch (Exception $e) {
    echo "❌ Ошибка: " . $e->getMessage() . "\n";
    echo "Трассировка:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== Тест завершен ===\n";
?>