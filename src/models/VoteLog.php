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
            source VARCHAR(32) NOT NULL DEFAULT 'mmotop'
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    }
    // Добавить запись о голосовании
    public function add($userId, $username, $reward, $source = 'mmotop') {
        $stmt = $this->db->prepare("INSERT INTO vote_log (user_id, username, vote_time, reward, source) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$userId, $username, time(), $reward, $source]);
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
