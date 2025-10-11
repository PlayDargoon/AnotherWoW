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
        // Базовое создание таблицы (если ее нет)
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

        // Доопределяем схему для уже существующей таблицы — без IF NOT EXISTS (совместимость с MySQL 5.7/5.6)
        // 1) Колонка external_id
        try {
            $stmt = $this->db->query("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'vote_log' AND COLUMN_NAME = 'external_id'");
            $hasExternalId = (int)$stmt->fetchColumn() > 0;
            if (!$hasExternalId) {
                $this->db->exec("ALTER TABLE vote_log ADD COLUMN external_id VARCHAR(64) NULL AFTER source");
            }
        } catch (\Throwable $e) { /* ignore */ }
        // 2) Индекс по (user_id, vote_time)
        try {
            $idx = $this->db->query("SHOW INDEX FROM vote_log WHERE Key_name = 'idx_user_time'")->fetch();
            if (!$idx) {
                $this->db->exec("CREATE INDEX idx_user_time ON vote_log (user_id, vote_time)");
            }
        } catch (\Throwable $e) { /* ignore */ }
        // 3) Индекс по external_id
        try {
            $idx2 = $this->db->query("SHOW INDEX FROM vote_log WHERE Key_name = 'idx_external_id'")->fetch();
            if (!$idx2) {
                $this->db->exec("CREATE INDEX idx_external_id ON vote_log (external_id)");
            }
        } catch (\Throwable $e) { /* ignore */ }
    }
    // Добавить запись о голосовании (с точным временем и внешним ID)
    public function add($userId, $username, $reward, $source = 'mmotop', ?int $voteTime = null, ?string $externalId = null) {
        $voteTime = $voteTime ?: time();
        try {
            $stmt = $this->db->prepare("INSERT INTO vote_log (user_id, username, vote_time, reward, source, external_id) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$userId, $username, $voteTime, $reward, $source, $externalId]);
        } catch (\PDOException $e) {
            // Если колонка external_id отсутствует — выполним миграцию и повторим вставку без срыва выполнения
            $isUnknownColumn = $e->getCode() === '42S22' || stripos($e->getMessage() ?? '', 'Unknown column') !== false;
            if ($isUnknownColumn && stripos($e->getMessage(), 'external_id') !== false) {
                // Попробуем мигрировать и повторить вставку
                $this->migrate();
                try {
                    $stmt = $this->db->prepare("INSERT INTO vote_log (user_id, username, vote_time, reward, source, external_id) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$userId, $username, $voteTime, $reward, $source, $externalId]);
                    return;
                } catch (\Throwable $e2) {
                    // В крайнем случае — деградация без external_id
                    $stmt = $this->db->prepare("INSERT INTO vote_log (user_id, username, vote_time, reward, source) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$userId, $username, $voteTime, $reward, $source]);
                    return;
                }
            }
            throw $e;
        }
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
