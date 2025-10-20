<?php
// fix_admin_records.php - Исправляем записи Admin до правильных 4

require_once 'bootstrap.php';

try {
    $sitePdo = DatabaseConnection::getSiteConnection();
    $authPdo = DatabaseConnection::getAuthConnection();
    
    echo "=== Исправление записей Admin ===\n";
    
    // Получаем ID Admin
    $stmt = $authPdo->prepare('SELECT id FROM account WHERE username = ?');
    $stmt->execute(['Admin']);
    $adminId = $stmt->fetchColumn();
    
    if ($adminId) {
        // Удаляем ВСЕ записи Admin за октябрь
        echo "1. Удаляем все записи Admin за октябрь...\n";
        $stmt = $sitePdo->prepare('
            DELETE FROM account_coins 
            WHERE account_id = ? 
            AND created_at >= "2025-10-01" 
            AND created_at < "2025-11-01"
            AND (reason LIKE "%голос%" OR reason LIKE "%vote%" OR reason LIKE "%MMOTOP%")
        ');
        $stmt->execute([$adminId]);
        echo "Удалено записей: " . $stmt->rowCount() . "\n\n";
        
        // Добавляем только 4 правильные записи из оригинального примера
        echo "2. Добавляем 4 правильные записи:\n";
        $correctRecords = [
            ['2025-10-01 02:46:19', 'Голосование MMOTOP (код 1)'],
            ['2025-10-02 01:35:57', 'Голосование MMOTOP (код 1)'],
            ['2025-10-03 04:04:39', 'Голосование MMOTOP (код 1)'],
            ['2025-10-04 06:07:57', 'Голосование MMOTOP (код 1)'],
        ];
        
        $stmt = $sitePdo->prepare('
            INSERT INTO account_coins (account_id, coins, reason, created_at) 
            VALUES (?, 1, ?, ?)
        ');
        
        foreach ($correctRecords as $index => $record) {
            list($dateTime, $reason) = $record;
            $stmt->execute([$adminId, $reason, $dateTime]);
            echo "✅ " . ($index + 1) . ". $dateTime - $reason\n";
        }
        
        // Проверяем результат
        echo "\n3. Проверка результата:\n";
        $stmt = $sitePdo->prepare('
            SELECT COUNT(*) as count, SUM(coins) as total
            FROM account_coins 
            WHERE account_id = ? 
            AND created_at >= "2025-10-01" 
            AND created_at < "2025-11-01"
            AND (reason LIKE "%голос%" OR reason LIKE "%vote%" OR reason LIKE "%MMOTOP%")
        ');
        $stmt->execute([$adminId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo "Admin теперь имеет: {$result['count']} записей, {$result['total']} голосов\n";
        
        if ($result['count'] == 4 && $result['total'] == 4) {
            echo "✅ Отлично! Admin теперь имеет правильно 4 голоса\n";
        }
        
        // Проверяем общую сумму
        $stmt = $sitePdo->prepare("
            SELECT SUM(coins) as total_coins
            FROM account_coins 
            WHERE created_at >= '2025-10-01' 
            AND created_at < '2025-11-01'
            AND (reason LIKE '%голос%' OR reason LIKE '%vote%' OR reason LIKE '%MMOTOP%')
        ");
        $stmt->execute();
        $total = $stmt->fetchColumn();
        
        echo "\nОбщая сумма голосов за октябрь: $total\n";
        echo "Ожидается: 207 (200 Toxa65 + 4 Admin + 2 cool + 1 Amodey)\n";
        
    } else {
        echo "❌ Аккаунт Admin не найден!\n";
    }
    
} catch (Exception $e) {
    echo "ОШИБКА: " . $e->getMessage() . "\n";
}