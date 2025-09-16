<?php
// src/models/VoteReward.php
// Модель для работы с голосами и валютой
class VoteReward {
    private $db;
    public function __construct($db) {
        $this->db = $db;
    }
    // Создать таблицы, если не существуют
    public function migrate() {
        $this->db->exec("CREATE TABLE IF NOT EXISTS vote_rewards (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            last_vote_time INT NOT NULL DEFAULT 0
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        $this->db->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS coins INT NOT NULL DEFAULT 0");
    }
    // Получить время последнего голосования
    public function getLastVoteTime($userId) {
        $stmt = $this->db->prepare("SELECT last_vote_time FROM vote_rewards WHERE user_id = ?");
        $stmt->execute([$userId]);
        $row = $stmt->fetch();
        return $row ? (int)$row['last_vote_time'] : 0;
    }
    // Обновить время голосования
    public function setLastVoteTime($userId, $time) {
        $stmt = $this->db->prepare("INSERT INTO vote_rewards (user_id, last_vote_time) VALUES (?, ?) ON DUPLICATE KEY UPDATE last_vote_time = VALUES(last_vote_time)");
        $stmt->execute([$userId, $time]);
    }
    // Начислить валюту
    public function addCoins($userId, $amount) {
        $stmt = $this->db->prepare("UPDATE users SET coins = coins + ? WHERE id = ?");
        $stmt->execute([$amount, $userId]);
    }
    // Получить баланс
    public function getCoins($userId) {
        $stmt = $this->db->prepare("SELECT coins FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $row = $stmt->fetch();
        return $row ? (int)$row['coins'] : 0;
    }
}
