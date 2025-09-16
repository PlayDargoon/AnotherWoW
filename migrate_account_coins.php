<?php
// Миграция для создания таблицы account_coins в базе acore_site
// Запускать через php migrate_account_coins.php


require_once __DIR__ . '/bootstrap.php';
$pdo = DatabaseConnection::getSiteConnection();

$sql = "
CREATE TABLE IF NOT EXISTS account_coins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    account_id INT NOT NULL,
    coins INT NOT NULL,
    reason VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

try {
    $pdo->exec($sql);
    echo "Таблица account_coins успешно создана или уже существует.\n";
} catch (PDOException $e) {
    echo "Ошибка при создании таблицы: " . $e->getMessage() . "\n";
    exit(1);
}
