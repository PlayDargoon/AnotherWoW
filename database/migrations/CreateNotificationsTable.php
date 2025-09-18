<?php
require_once __DIR__ . '/../../src/services/DatabaseConnection.php';

class CreateNotificationsTable
{
    private $db;

    public function __construct()
    {
        $this->db = DatabaseConnection::getSiteConnection();
    }

    /**
     * Выполнить миграцию
     */
    public function up()
    {
        $sql = "CREATE TABLE IF NOT EXISTS notifications (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            type VARCHAR(50) NOT NULL,
            message TEXT NOT NULL,
            data JSON NULL,
            is_read TINYINT(1) DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_user_id (user_id),
            INDEX idx_is_read (is_read),
            INDEX idx_created_at (created_at),
            INDEX idx_user_unread (user_id, is_read)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $this->db->exec($sql);
        echo "✓ Таблица notifications создана\n";
    }

    /**
     * Отменить миграцию
     */
    public function down()
    {
        $sql = "DROP TABLE IF EXISTS notifications";
        $this->db->exec($sql);
        echo "✓ Таблица notifications удалена\n";
    }

    /**
     * Проверить существование таблицы
     */
    public function exists()
    {
        try {
            $result = $this->db->query("SHOW TABLES LIKE 'notifications'");
            return $result->rowCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Получить описание миграции
     */
    public function getDescription()
    {
        return "Создание таблицы уведомлений для пользователей";
    }
}