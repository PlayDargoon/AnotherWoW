<?php
session_start();
header('Content-Type: application/json');
if (!isset($_SESSION['user_id']) || empty($_POST['id'])) {
    echo json_encode(['success' => false]);
    exit;
}
$notifyId = (int)$_POST['id'];
$dbPath = dirname(__DIR__) . '/src/services/DatabaseConnection.php';
if (file_exists($dbPath)) require_once $dbPath;
require_once dirname(__DIR__) . '/src/controllers/NotificationController.php';

$ok = $controller->hide($notifyId, $_SESSION['user_id']);
echo json_encode(['success' => (bool)$ok]);
