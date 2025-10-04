<?php
// fix_vote_data.php - Очищаем и добавляем правильные данные голосования

require_once 'bootstrap.php';

try {
    $sitePdo = DatabaseConnection::getSiteConnection();
    $authPdo = DatabaseConnection::getAuthConnection();
    
    echo "=== Исправление данных голосования ===\n";
    
    // Полностью очищаем данные голосования за октябрь
    echo "1. Очищаем все данные голосования за октябрь...\n";
    $sitePdo->exec("
        DELETE FROM account_coins 
        WHERE created_at >= '2025-10-01' 
        AND created_at < '2025-11-01'
        AND (reason LIKE '%голос%' OR reason LIKE '%vote%' OR reason LIKE '%MMOTOP%')
    ");
    
    // Реальные данные из примера пользователя (ТОЛЬКО эти!)
    $realVotes = [
        ['01.10.2025 00:10:30', 'cool', 1],
        ['01.10.2025 02:46:19', 'Admin', 1],
        ['02.10.2025 00:08:26', 'cool', 1],
        ['02.10.2025 01:35:57', 'Admin', 1],
        ['02.10.2025 14:04:35', 'Toxa65', 4], // 100 монет - ПЕРВЫЙ голос
        ['02.10.2025 14:04:36', 'Toxa65', 4], // 100 монет - ВТОРОЙ голос (добавляем 1 сек)
        ['02.10.2025 15:20:59', 'Amodey', 1],
        ['03.10.2025 04:04:39', 'Admin', 1],
        ['04.10.2025 06:07:57', 'Admin', 1],
        ['04.10.2025 06:19:49', 'root', 1],
    ];
    
    // Конфиг наград
    $rewards = [
        1 => 1,   // 1 монета
        4 => 100  // 100 монет
    ];
    
    // Получаем существующие аккаунты
    $stmt = $authPdo->query("SELECT id, username FROM account");
    $accounts = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    
    $stmt = $sitePdo->prepare("
        INSERT INTO account_coins (account_id, coins, reason, created_at) 
        VALUES (?, ?, ?, ?)
    ");
    
    echo "2. Добавляем правильные данные:\n";
    $totalCoins = 0;
    $addedVotes = 0;
    
    foreach ($realVotes as $vote) {
        list($dateTime, $username, $rewardCode) = $vote;
        
        // Ищем account_id по username
        $accountId = null;
        foreach ($accounts as $id => $name) {
            if (strtolower($name) === strtolower($username)) {
                $accountId = $id;
                break;
            }
        }
        
        if (!$accountId) {
            echo "⚠️  Аккаунт '$username' не найден, пропускаем\n";
            continue;
        }
        
        $coins = $rewards[$rewardCode] ?? 1;
        $reason = "Голосование MMOTOP (код $rewardCode)";
        
        // Конвертируем формат даты
        $dateTimeParts = explode(' ', $dateTime);
        $datePart = $dateTimeParts[0];
        $timePart = $dateTimeParts[1];
        
        $dateComponents = explode('.', $datePart);
        $mysqlDate = $dateComponents[2] . '-' . $dateComponents[1] . '-' . $dateComponents[0] . ' ' . $timePart;
        
        $stmt->execute([$accountId, $coins, $reason, $mysqlDate]);
        
        echo "✅ $username: +$coins монет (код $rewardCode) - $dateTime\n";
        $totalCoins += $coins;
        $addedVotes++;
    }
    
    echo "\n=== Результат ===\n";
    echo "Добавлено голосований: $addedVotes\n";
    echo "Всего монет начислено: $totalCoins\n";
    echo "\nОжидаемые результаты:\n";
    echo "1. Toxa65: 200 монет (2 голоса × код 4)\n";
    echo "2. Admin: 4 монеты (4 голоса × код 1)\n";
    echo "3. cool: 2 монеты (2 голоса × код 1)\n";
    echo "4. Amodey, root: по 1 монете (по 1 голосу × код 1)\n";
    
} catch (Exception $e) {
    echo "ОШИБКА: " . $e->getMessage() . "\n";
}