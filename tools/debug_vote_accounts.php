<?php
// debug_vote_accounts.php
require_once __DIR__ . '/../src/services/DatabaseConnection.php';

try {
    $sitePdo = DatabaseConnection::getSiteConnection();
    $authPdo = DatabaseConnection::getAuthConnection();
    
    echo "=== Отладка аккаунтов в голосовании ===\n\n";
    
    // Получаем уникальные account_id из таблицы account_coins
    $stmt = $sitePdo->query("
        SELECT 
            account_id,
            COUNT(*) as vote_count,
            SUM(coins) as total_coins
        FROM account_coins 
        WHERE reason LIKE '%голос%' OR reason LIKE '%vote%' OR reason LIKE '%MMOTOP%'
        GROUP BY account_id 
        ORDER BY total_coins DESC
    ");
    $voteAccounts = $stmt->fetchAll();
    
    echo "Аккаунты с голосами:\n";
    foreach ($voteAccounts as $acc) {
        echo "- Account ID: {$acc['account_id']}, Голосов: {$acc['vote_count']}, Монет: {$acc['total_coins']}\n";
    }
    
    // Проверяем какие из них есть в таблице account
    $accountIds = array_column($voteAccounts, 'account_id');
    if (!empty($accountIds)) {
        $placeholders = implode(',', array_fill(0, count($accountIds), '?'));
        
        $stmt = $authPdo->prepare("SELECT id, username FROM account WHERE id IN ($placeholders)");
        $stmt->execute($accountIds);
        $foundAccounts = $stmt->fetchAll();
        
        echo "\nНайденные аккаунты в базе auth:\n";
        foreach ($foundAccounts as $acc) {
            echo "- ID: {$acc['id']}, Username: {$acc['username']}\n";
        }
        
        // Проверяем какие аккаунты не найдены
        $foundIds = array_column($foundAccounts, 'id');
        $missingIds = array_diff($accountIds, $foundIds);
        
        if (!empty($missingIds)) {
            echo "\nОтсутствующие аккаунты в базе auth:\n";
            foreach ($missingIds as $missingId) {
                echo "- Account ID: $missingId\n";
            }
        }
    }
    
} catch (Exception $e) {
    echo "Ошибка: " . $e->getMessage() . "\n";
}