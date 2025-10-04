<?php
// check_admin_records.php - Проверяем все записи для Admin

require_once 'bootstrap.php';

try {
    $sitePdo = DatabaseConnection::getSiteConnection();
    $authPdo = DatabaseConnection::getAuthConnection();
    
    echo "=== Проверка записей для Admin ===\n";
    
    // Получаем ID Admin
    $stmt = $authPdo->prepare('SELECT id FROM account WHERE username = ?');
    $stmt->execute(['Admin']);
    $accountId = $stmt->fetchColumn();
    
    echo "Account ID для Admin: $accountId\n\n";
    
    if ($accountId) {
        // Получаем все записи за октябрь
        $stmt = $sitePdo->prepare('
            SELECT account_id, coins, reason, created_at 
            FROM account_coins 
            WHERE account_id = ? 
            AND created_at >= "2025-10-01" 
            AND created_at < "2025-11-01"
            AND (reason LIKE "%голос%" OR reason LIKE "%vote%" OR reason LIKE "%MMOTOP%")
            ORDER BY created_at
        ');
        $stmt->execute([$accountId]);
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "Все записи за октябрь для Admin:\n";
        $total = 0;
        foreach ($records as $index => $record) {
            echo ($index + 1) . ". {$record['created_at']}: +{$record['coins']} монет ({$record['reason']})\n";
            $total += $record['coins'];
        }
        echo "\nИтого записей: " . count($records) . "\n";
        echo "Итого монет/голосов: $total\n";
        echo "Ожидалось: 4 записи по 1 голосу = 4 голоса\n";
        
        if (count($records) != 4 || $total != 4) {
            echo "\n⚠️  ПРОБЛЕМА: Найдено " . count($records) . " записей с суммой $total вместо ожидаемых 4 записей по 4 голоса!\n";
            
            if (count($records) > 4) {
                echo "\nЛишние записи (нужно удалить):\n";
                $toDelete = array_slice($records, 4); // Берем записи после 4-й
                foreach ($toDelete as $record) {
                    echo "- {$record['created_at']}: {$record['reason']}\n";
                }
            }
        }
        
        echo "\nОригинальные записи из вашего примера должны быть:\n";
        echo "1. 01.10.2025 02:46:19 - Admin - код 1\n";
        echo "2. 02.10.2025 01:35:57 - Admin - код 1\n";
        echo "3. 03.10.2025 04:04:39 - Admin - код 1\n";
        echo "4. 04.10.2025 06:07:57 - Admin - код 1\n";
        
    } else {
        echo "❌ Аккаунт Admin не найден!\n";
    }
    
} catch (Exception $e) {
    echo "ОШИБКА: " . $e->getMessage() . "\n";
}