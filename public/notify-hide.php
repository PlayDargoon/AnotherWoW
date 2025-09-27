<?php
// Подключаем bootstrap для инициализации
require_once dirname(__DIR__) . '/bootstrap.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || empty($_POST['id'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid session or missing ID']);
    exit;
}

$notifyId = (int)$_POST['id'];
require_once dirname(__DIR__) . '/src/controllers/NotificationController.php';

$controller = new NotificationController();
$ok = $controller->hide($notifyId, $_SESSION['user_id']);

echo json_encode(['success' => (bool)$ok, 'id' => $notifyId]);
