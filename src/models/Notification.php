<?php

require_once __DIR__ . '/../services/DatabaseConnection.php';

class Notification
{
    private $db;

    public function __construct()
    {
        $this->db = DatabaseConnection::getSiteConnection();
    }

    /**
     * Создать новое уведомление
     */
    public function create($userId, $type, $message, ?array $data = null)
    {
        $sql = "INSERT INTO notifications (user_id, type, message, data, is_read, created_at) 
                VALUES (?, ?, ?, ?, 0, NOW())";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $userId,
            $type,
            $message,
            $data ? json_encode($data) : null
        ]);
    }

    /**
     * Получить непрочитанные уведомления пользователя
     */
    public function getUnreadByUserId($userId)
    {
        $sql = "SELECT * FROM notifications 
                WHERE user_id = ? AND is_read = 0 
                ORDER BY created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        
        $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Декодируем JSON данные
        foreach ($notifications as &$notification) {
            if ($notification['data']) {
                $notification['data'] = json_decode($notification['data'], true);
            }
        }
        
        return $notifications;
    }

    /**
     * Отметить уведомление как прочитанное
     */
    public function markAsRead($notificationId, $userId)
    {
        $sql = "UPDATE notifications SET is_read = 1 
                WHERE id = ? AND user_id = ?";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$notificationId, $userId]);
    }

    /**
     * Получить количество непрочитанных уведомлений
     */
    public function getUnreadCount($userId)
    {
        $sql = "SELECT COUNT(*) FROM notifications 
                WHERE user_id = ? AND is_read = 0";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        
        return (int)$stmt->fetchColumn();
    }

    /**
     * Создать уведомление о начислении монет за голосование
     */
    public function createVoteRewardNotification($userId, $coinsAmount)
    {
        $message = "Вам начислена {$coinsAmount} монет за голосование, спасибо что поддерживаете наш проект.";
        
        $data = [
            'coins' => $coinsAmount,
            'type' => 'vote_reward'
        ];
        
        return $this->create($userId, 'vote_reward', $message, $data);
    }

    /**
     * Удалить старые прочитанные уведомления (старше 30 дней)
     */
    public function cleanupOldNotifications()
    {
        $sql = "DELETE FROM notifications 
                WHERE is_read = 1 AND created_at < DATE_SUB(NOW(), INTERVAL 30 DAY)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute();
    }
}