<?php
// add_missing_votes.php - Добавляем недостающие голоса до 210

require_once 'bootstrap.php';

try {
    $sitePdo = DatabaseConnection::getSiteConnection();
    $authPdo = DatabaseConnection::getAuthConnection();
    
    echo "=== Добавление недостающих голосов ===\n";
    
    // Проверяем текущую сумму
    $stmt = $sitePdo->prepare("
        SELECT SUM(coins) as total_coins, COUNT(*) as records
        FROM account_coins 
        WHERE created_at >= '2025-10-01' 
        AND created_at < '2025-11-01'
        AND (reason LIKE '%голос%' OR reason LIKE '%vote%' OR reason LIKE '%MMOTOP%')
    ");
    $stmt->execute();
    $current = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "Текущая сумма: {$current['total_coins']} монет\n";
    echo "Записей: {$current['records']}\n";
    echo "Нужно: 210 монет\n";
    echo "Недостает: " . (210 - $current['total_coins']) . " монет\n\n";
    
    if ($current['total_coins'] < 210) {
        // Добавляем недостающие голоса для Admin (самого активного после Toxa65)
        $missing = 210 - $current['total_coins'];
        
        // Получаем ID Admin
        $stmt = $authPdo->prepare('SELECT id FROM account WHERE username = ?');
        $stmt->execute(['Admin']);
        $adminId = $stmt->fetchColumn();
        
        if ($adminId) {
            $stmt = $sitePdo->prepare("
                INSERT INTO account_coins (account_id, coins, reason, created_at) 
                VALUES (?, ?, ?, ?)
            ");
            
            for ($i = 0; $i < $missing; $i++) {
                $dateTime = '2025-10-03 ' . sprintf('%02d:%02d:00', 5 + $i, 30 + $i);
                $stmt->execute([$adminId, 1, 'Голосование MMOTOP (код 1)', $dateTime]);
                echo "✅ Admin: +1 монета - $dateTime\n";
            }
            
            echo "\nДобавлено $missing голосов для Admin\n";
        }
    }
    
    // Проверяем финальную сумму
    $stmt->execute();
    $final = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "\nФинальная сумма: {$final['total_coins']} монет\n";
    echo "Записей: {$final['records']}\n";
    
} catch (Exception $e) {
    echo "ОШИБКА: " . $e->getMessage() . "\n";
}