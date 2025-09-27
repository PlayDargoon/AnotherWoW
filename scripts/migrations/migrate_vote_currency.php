<?php
// Миграция для vote_rewards и coins
require_once __DIR__ . '/../src/services/DatabaseConnection.php';
$pdo = DatabaseConnection::getSiteConnection();

$pdo->exec("CREATE TABLE IF NOT EXISTS vote_rewards (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    last_vote_time INT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// coins для account (auth база)
$authPdo = DatabaseConnection::getAuthConnection();
$cols = $authPdo->query("SHOW COLUMNS FROM account LIKE 'coins'")->fetch();
if (!$cols) {
    $authPdo->exec("ALTER TABLE account ADD COLUMN coins INT NOT NULL DEFAULT 0");
}
echo "Миграция завершена!";
