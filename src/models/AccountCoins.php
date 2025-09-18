<?php
// src/models/AccountCoins.php
// Модель для работы с начислениями монет
class AccountCoins {
    private $db;
    public function __construct($db) {
        $this->db = $db;
    }
    // Добавить начисление монет
    public function add($accountId, $coins, $reason = null, $createdAt = null) {
        if ($createdAt) {
            $stmt = $this->db->prepare("INSERT INTO account_coins (account_id, coins, reason, created_at) VALUES (?, ?, ?, ?)");
            $stmt->execute([$accountId, $coins, $reason, $createdAt]);
        } else {
            $stmt = $this->db->prepare("INSERT INTO account_coins (account_id, coins, reason) VALUES (?, ?, ?)");
            $stmt->execute([$accountId, $coins, $reason]);
        }
    }
    // Получить сумму монет по аккаунту
    public function getBalance($accountId) {
        $stmt = $this->db->prepare("SELECT SUM(coins) as total FROM account_coins WHERE account_id = ?");
        $stmt->execute([$accountId]);
        $row = $stmt->fetch();
        return $row && $row['total'] !== null ? (int)$row['total'] : 0;
    }
    // Получить историю начислений
    public function getHistory($accountId, $limit = 20) {
        $stmt = $this->db->prepare("SELECT * FROM account_coins WHERE account_id = ? ORDER BY created_at DESC LIMIT ?");
        $stmt->execute([$accountId, $limit]);
        return $stmt->fetchAll();
    }
}
