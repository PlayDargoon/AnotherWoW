<?php
// src/controllers/NotificationController.php
require_once __DIR__ . '/../services/DatabaseConnection.php';

class NotificationController {
    private $notificationModel;

    public function __construct() {
        $this->notificationModel = new Notification();
    }

    // Получить все непрочитанные уведомления пользователя
    public function getUnread($userId) {
        return $this->notificationModel->getUnreadByUserId($userId);
    }

    // Пометить уведомление как прочитанное (скрыть)
    public function hide($notificationId, $userId) {
        return $this->notificationModel->markAsRead($notificationId, $userId);
    }

    // Получить уведомления и ник для layout
    public static function getUserNotifications($session) {
        $result = [
            'username' => null
        ];
        $username = isset($session['username']) ? $session['username'] : null;
        $result['username'] = $username;
        return $result;
    }

    // Склонение монет
    public static function coinsDeclension($n) {
        $n = abs((int)$n);
        if ($n % 10 == 1 && $n % 100 != 11) return 'монету';
        if ($n % 10 >= 2 && $n % 10 <= 4 && ($n % 100 < 10 || $n % 100 >= 20)) return 'монеты';
        return 'монет';
    }
}