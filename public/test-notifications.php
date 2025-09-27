<?php
// Тест скрытия уведомлений
require_once '../bootstrap.php';

echo "Тест скрытия уведомлений\n";
echo "User ID: " . ($_SESSION['user_id'] ?? 'не установлен') . "\n";
echo "Username: " . ($_SESSION['username'] ?? 'не установлен') . "\n";

if (isset($_SESSION['user_id'])) {
    require_once '../src/controllers/NotificationController.php';
    $controller = new NotificationController();
    
    // Получаем непрочитанные уведомления
    $notifications = $controller->getUnread($_SESSION['user_id']);
    echo "Найдено уведомлений: " . count($notifications) . "\n";
    
    foreach ($notifications as $notify) {
        echo "ID: " . $notify['id'] . ", Сообщение: " . $notify['message'] . "\n";
    }
} else {
    echo "Пользователь не авторизован\n";
}
?>