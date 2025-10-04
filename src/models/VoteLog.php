<?php
// src/models/VoteLog.php
// Модель для хранения истории голосований пользователей
class VoteLog {
    private $db;
    public function __construct($db) {
        $this->db = $db;
    }
    // Создать таблицу, если не существует
    public function migrate() {
        $this->db->exec("CREATE TABLE IF NOT EXISTS vote_log (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            username VARCHAR(64) NOT NULL,
            vote_time INT NOT NULL,
            reward INT NOT NULL DEFAULT 0,
            source VARCHAR(32) NOT NULL DEFAULT 'mmotop',
            external_id VARCHAR(64) NULL,
            INDEX idx_user_time (user_id, vote_time),
            INDEX idx_external_id (external_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        // Пытаемся добавить недостающие поля/индексы, если таблица уже существовала
        try {
            $this->db->exec("ALTER TABLE vote_log ADD COLUMN IF NOT EXISTS external_id VARCHAR(64) NULL");
        } catch (\Throwable $e) { /* ignore */ }
        try {
            $this->db->exec("CREATE INDEX IF NOT EXISTS idx_user_time ON vote_log (user_id, vote_time)");
        } catch (\Throwable $e) { /* ignore */ }
        try {
            $this->db->exec("CREATE INDEX IF NOT EXISTS idx_external_id ON vote_log (external_id)");
        } catch (\Throwable $e) { /* ignore */ }
    }
    // Добавить запись о голосовании (с точным временем и внешним ID)
    public function add($userId, $username, $reward, $source = 'mmotop', ?int $voteTime = null, ?string $externalId = null) {
        $voteTime = $voteTime ?: time();
        $stmt = $this->db->prepare("INSERT INTO vote_log (user_id, username, vote_time, reward, source, external_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$userId, $username, $voteTime, $reward, $source, $externalId]);
    }
    // Получить последние голосования пользователя
    public function getUserVotes($userId, $limit = 10) {
        $stmt = $this->db->prepare("SELECT * FROM vote_log WHERE user_id = ? ORDER BY vote_time DESC LIMIT ?");
        $stmt->execute([$userId, $limit]);
        return $stmt->fetchAll();
    }
    // Получить общее количество голосов пользователя
    public function getVoteCount($userId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM vote_log WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    }
}
