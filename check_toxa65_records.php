<?php
// check_toxa65_records.php - Проверяем все записи для Toxa65

require_once 'bootstrap.php';

try {
    $sitePdo = DatabaseConnection::getSiteConnection();
    $authPdo = DatabaseConnection::getAuthConnection();
    
    echo "=== Проверка записей для Toxa65 ===\n";
    
    // Получаем ID Toxa65
    $stmt = $authPdo->prepare('SELECT id FROM account WHERE username = ?');
    $stmt->execute(['Toxa65']);
    $accountId = $stmt->fetchColumn();
    
    echo "Account ID для Toxa65: $accountId\n\n";
    
    if ($accountId) {
        // Получаем все записи за октябрь
        $stmt = $sitePdo->prepare('
            SELECT account_id, coins, reason, created_at 
            FROM account_coins 
            WHERE account_id = ? 
            AND created_at >= "2025-10-01" 
            AND created_at < "2025-11-01"
            ORDER BY created_at
        ');
        $stmt->execute([$accountId]);
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "Все записи за октябрь для Toxa65:\n";
        $total = 0;
        foreach ($records as $record) {
            echo "- {$record['created_at']}: +{$record['coins']} монет ({$record['reason']})\n";
            $total += $record['coins'];
        }
        echo "\nИтого монет: $total\n";
        echo "Ожидалось: 200 (2 × код 4 = 2 × 100)\n";
        
        if ($total != 200) {
            echo "\n⚠️  ПРОБЛЕМА: Найдено $total монет вместо ожидаемых 200!\n";
            echo "Лишние записи нужно удалить.\n";
        }
    } else {
        echo "❌ Аккаунт Toxa65 не найден!\n";
    }
    
} catch (Exception $e) {
    echo "ОШИБКА: " . $e->getMessage() . "\n";
}