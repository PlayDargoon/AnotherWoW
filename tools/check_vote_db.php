<?php

// check_vote_db.php
// Проверка структуры баз данных через контроллер миграций

require_once __DIR__ . '/../src/services/DatabaseConnection.php';
require_once __DIR__ . '/../bootstrap.php';

try {
    require_once __DIR__ . '/../src/controllers/MigrationController.php';

    echo "=== Проверка баланса админа ===\n\n";

    $controller = new MigrationController();

    $sitePdo = DatabaseConnection::getSiteConnection();$controller->checkDatabase();
    $authPdo = DatabaseConnection::getAuthConnection();
    
    // Ищем админа в базе auth
    $stmt = $authPdo->query("SELECT id, username FROM account WHERE username = 'Admin'");
    $admin = $stmt->fetch();
    
    if (!$admin) {
        echo "❌ Админ не найден в базе auth\n";
        exit;
    }
    
    echo "✅ Админ найден: ID {$admin['id']}, Username: {$admin['username']}\n\n";
    
    // Получаем ВСЕ записи по админу в account_coins
    $stmt = $sitePdo->prepare("
        SELECT 
            id,
            coins,
            reason,
            created_at
        FROM account_coins 
        WHERE account_id = :account_id
        ORDER BY created_at ASC
    ");
    $stmt->execute(['account_id' => $admin['id']]);
    $allRecords = $stmt->fetchAll();
    
    echo "=== Все записи админа в account_coins ===\n";
    $totalCoins = 0;
    foreach ($allRecords as $record) {
        $totalCoins += $record['coins'];
        echo "ID: {$record['id']}, Монет: {$record['coins']}, Причина: {$record['reason']}, Дата: {$record['created_at']}\n";
    }
    echo "\nОбщий баланс: $totalCoins монет\n\n";
    
    // Получаем только записи связанные с голосованием
    $stmt = $sitePdo->prepare("
        SELECT 
            id,
            coins,
            reason,
            created_at
        FROM account_coins 
        WHERE account_id = :account_id
            AND (reason LIKE '%голос%' OR reason LIKE '%vote%' OR reason LIKE '%MMOTOP%')
        ORDER BY created_at ASC
    ");
    $stmt->execute(['account_id' => $admin['id']]);
    $voteRecords = $stmt->fetchAll();
    
    echo "=== Записи админа связанные с голосованием ===\n";
    $voteCoins = 0;
    foreach ($voteRecords as $record) {
        $voteCoins += $record['coins'];
        echo "ID: {$record['id']}, Монет: {$record['coins']}, Причина: {$record['reason']}, Дата: {$record['created_at']}\n";
    }
    echo "\nБаланс от голосования: $voteCoins монет\n";
    echo "Количество голосов: " . count($voteRecords) . "\n\n";
    
    // Получаем записи НЕ связанные с голосованием
    $stmt = $sitePdo->prepare("
        SELECT 
            id,
            coins,
            reason,
            created_at
        FROM account_coins 
        WHERE account_id = :account_id
            AND reason NOT LIKE '%голос%' 
            AND reason NOT LIKE '%vote%' 
            AND reason NOT LIKE '%MMOTOP%'
        ORDER BY created_at ASC
    ");
    $stmt->execute(['account_id' => $admin['id']]);
    $otherRecords = $stmt->fetchAll();
    
    if (!empty($otherRecords)) {
        echo "=== Записи админа НЕ связанные с голосованием ===\n";
        $otherCoins = 0;
        foreach ($otherRecords as $record) {
            $otherCoins += $record['coins'];
            echo "ID: {$record['id']}, Монет: {$record['coins']}, Причина: {$record['reason']}, Дата: {$record['created_at']}\n";
        }
        echo "\nБаланс от других источников: $otherCoins монет\n";
    }
    
    echo "\n=== Итого ===\n";
    echo "Голосование: $voteCoins монет\n";
    echo "Другие источники: " . ($totalCoins - $voteCoins) . " монет\n";
    echo "Общий баланс: $totalCoins монет\n";
    
} catch (Exception $e) {
    echo "❌ Ошибка: " . $e->getMessage() . "\n";
}