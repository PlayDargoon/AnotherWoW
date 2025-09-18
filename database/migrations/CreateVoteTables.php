<?php
// database/migrations/CreateVoteTables.php
// Миграция для создания таблиц системы голосования

require_once __DIR__ . '/../../bootstrap.php';
require_once __DIR__ . '/../../src/models/VoteLog.php';
require_once __DIR__ . '/../../src/models/VoteReward.php';

echo "Создание таблиц для системы голосования...\n";

try {
    $siteDb = DatabaseConnection::getSiteConnection();
    
    // Создаем таблицу vote_log
    $voteLog = new VoteLog($siteDb);
    $voteLog->migrate();
    echo "✓ Таблица vote_log создана\n";
    
    // Создаем таблицу vote_rewards
    $voteReward = new VoteReward($siteDb);
    $voteReward->migrate();
    echo "✓ Таблица vote_rewards создана\n";
    
    // Создаем таблицу account_coins если её нет
    $siteDb->exec("CREATE TABLE IF NOT EXISTS account_coins (
        id INT AUTO_INCREMENT PRIMARY KEY,
        account_id INT NOT NULL,
        coins INT NOT NULL,
        reason VARCHAR(255) DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_account_id (account_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    echo "✓ Таблица account_coins создана\n";
    
    echo "Все таблицы успешно созданы!\n";
    
} catch (Exception $e) {
    echo "Ошибка создания таблиц: " . $e->getMessage() . "\n";
    exit(1);
}