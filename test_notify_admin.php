<?php
session_start();
require_once __DIR__ . '/src/services/DatabaseConnection.php';
require_once __DIR__ . '/src/models/User.php';
require_once __DIR__ . '/src/models/AccountCoins.php';
require_once __DIR__ . '/src/models/Notification.php';

$authDb = DatabaseConnection::getAuthConnection();
$siteDb = DatabaseConnection::getSiteConnection();

$userModel = new User($authDb);
$coinsModel = new AccountCoins($siteDb);
$notificationModel = new Notification();

// Найти аккаунт Admin
$admin = $authDb->prepare("SELECT id FROM account WHERE username = ? LIMIT 1");
$admin->execute(['Admin']);
$row = $admin->fetch();
if (!$row) {
    echo "Пользователь Admin не найден\n";
    exit(1);
}
$adminId = (int)$row['id'];

// Начислить 1 монету
$coinsModel->add($adminId, 1, 'Тестовое начисление');

// Создать уведомление
$notificationModel->createVoteRewardNotification($adminId, 1);

// Для теста эмулируем сессию
$_SESSION['user_id'] = $adminId;

// Проверить уведомления
$unread = $notificationModel->getUnreadByUserId($adminId);
if ($unread) {
    echo "Уведомление успешно создано:\n";
    print_r($unread[0]);
} else {
    echo "Уведомление не найдено\n";
}
