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
            last_vote_time INT NOT NULL DEFAULT 0,
            UNIQUE KEY unique_user (user_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        
        // Не добавляем колонки в базы AzerothCore - только читаем из них
        // Все монеты хранятся в account_coins в базе acore_site
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
    // Начислить валюту (используем AccountCoins вместо прямого обновления users)
    public function addCoins($userId, $amount) {
        // Вместо обновления несуществующей таблицы users, 
        // используем систему AccountCoins для начисления монет
        require_once __DIR__ . '/AccountCoins.php';
        $coinsModel = new AccountCoins($this->db);
        $coinsModel->add($userId, $amount, 'Голосование');
    }
    // Получить баланс (через AccountCoins)
    public function getCoins($userId) {
        require_once __DIR__ . '/AccountCoins.php';
        $coinsModel = new AccountCoins($this->db);
        return $coinsModel->getBalance($userId);
    }
}
