<?php

class Payment
{
    private PDO $db;
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public static function migrate(PDO $db): void
    {
        $db->exec(
            "CREATE TABLE IF NOT EXISTS payments (
                id INT AUTO_INCREMENT PRIMARY KEY,
                yk_id VARCHAR(64) UNIQUE NOT NULL,
                user_id INT NULL,
                amount DECIMAL(10,2) NOT NULL,
                currency VARCHAR(3) NOT NULL DEFAULT 'RUB',
                status VARCHAR(32) NOT NULL,
                description VARCHAR(128) NULL,
                coins INT NULL,
                metadata JSON NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
        );
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO payments (yk_id, user_id, amount, currency, status, description, coins, metadata) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->execute([
            $data['yk_id'],
            $data['user_id'] ?? null,
            $data['amount'],
            $data['currency'] ?? 'RUB',
            $data['status'] ?? 'pending',
            $data['description'] ?? null,
            $data['coins'] ?? null,
            isset($data['metadata']) ? json_encode($data['metadata'], JSON_UNESCAPED_UNICODE) : null,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function updateStatusByYkId(string $ykId, string $status): void
    {
        $stmt = $this->db->prepare("UPDATE payments SET status=? WHERE yk_id=?");
        $stmt->execute([$status, $ykId]);
    }

    public function findByYkId(string $ykId): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM payments WHERE yk_id=? LIMIT 1");
        $stmt->execute([$ykId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }
}
