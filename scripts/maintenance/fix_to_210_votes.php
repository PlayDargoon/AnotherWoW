<?php
// fix_to_210_votes.php - Корректируем до точно 210 голосов

require_once 'bootstrap.php';

try {
    $sitePdo = DatabaseConnection::getSiteConnection();
    
    echo "=== Корректировка до 210 голосов ===\n";
    
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
    
    echo "Текущая сумма: {$current['total_coins']} голосов\n";
    echo "Нужно: 210 голосов\n";
    
    if ($current['total_coins'] > 210) {
        $excess = $current['total_coins'] - 210;
        echo "Лишних: $excess голосов\n";
        
        // Удаляем лишние записи Admin (последние добавленные)
        $stmt = $sitePdo->prepare("
            DELETE FROM account_coins 
            WHERE created_at >= '2025-10-01' 
            AND created_at < '2025-11-01'
            AND reason LIKE '%MMOTOP%'
            AND coins = 1
            ORDER BY created_at DESC
            LIMIT ?
        ");
        $stmt->execute([$excess]);
        
        echo "Удалено $excess записей\n";
    }
    
    // Финальная проверка
    $stmt = $sitePdo->prepare("
        SELECT SUM(coins) as total_coins, COUNT(*) as records
        FROM account_coins 
        WHERE created_at >= '2025-10-01' 
        AND created_at < '2025-11-01'
        AND (reason LIKE '%голос%' OR reason LIKE '%vote%' OR reason LIKE '%MMOTOP%')
    ");
    $stmt->execute();
    $final = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "\nФинальная сумма: {$final['total_coins']} голосов\n";
    echo "Записей: {$final['records']}\n";
    
    if ($final['total_coins'] == 210) {
        echo "✅ Идеально! Ровно 210 голосов как в MMOTOP\n";
    }
    
} catch (Exception $e) {
    echo "ОШИБКА: " . $e->getMessage() . "\n";
}