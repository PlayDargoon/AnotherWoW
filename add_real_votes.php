<?php
// add_real_votes.php - Добавляем реальные данные голосования из октября 2025

require_once 'bootstrap.php';

try {
    $sitePdo = DatabaseConnection::getSiteConnection();
    $authPdo = DatabaseConnection::getAuthConnection();
    
    echo "=== Добавление реальных данных голосования за октябрь ===\n";
    
    // Реальные данные из примера пользователя
    $realVotes = [
        ['283316526', '01.10.2025 00:10:30', '46.42.148.108', 'cool', 1],
        ['283319763', '01.10.2025 02:46:19', '188.113.189.20', 'Admin', 1],
        ['283353633', '02.10.2025 00:08:26', '46.42.178.153', 'cool', 1],
        ['283355798', '02.10.2025 01:35:57', '188.113.189.20', 'Admin', 1],
        ['283374482', '02.10.2025 14:04:35', '188.113.169.185', 'Toxa65', 4], // 100 монет
        ['283374483', '02.10.2025 14:04:35', '188.113.169.185', 'Toxa65', 4], // 100 монет
        ['283376189', '02.10.2025 15:20:59', '178.130.143.104', 'Amodey', 1],
        ['283391239', '03.10.2025 00:09:28', '46.42.144.19', 'Qwerti', 1],
        ['283395403', '03.10.2025 04:04:39', '188.113.189.20', 'Admin', 1],
        ['283430302', '04.10.2025 02:08:05', '46.42.150.122', 'Qwerti', 1],
        ['283433796', '04.10.2025 06:07:57', '188.113.173.21', 'Admin', 1],
        ['283434180', '04.10.2025 06:19:49', '213.230.125.110', 'root', 1],
    ];
    
    // Конфиг наград
    $rewards = [
        1 => 1,   // 1 монета
        4 => 100  // 100 монет
    ];
    
    // Получаем существующие аккаунты
    $stmt = $authPdo->query("SELECT id, username FROM account");
    $accounts = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    
    // Сначала очищаем тестовые данные
    echo "Очищаем старые тестовые данные...\n";
    $sitePdo->exec("DELETE FROM account_coins WHERE reason LIKE '%тест%'");
    
    $stmt = $sitePdo->prepare("
        INSERT INTO account_coins (account_id, coins, reason, created_at) 
        VALUES (?, ?, ?, ?)
    ");
    
    $totalCoins = 0;
    $addedVotes = 0;
    
    foreach ($realVotes as $vote) {
        list($voteId, $dateTime, $ip, $username, $rewardCode) = $vote;
        
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
        
        // Конвертируем формат даты из dd.mm.yyyy в yyyy-mm-dd
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
    echo "Ожидаемые лидеры:\n";
    echo "1. Toxa65: 200 монет (2 голоса по коду 4)\n";
    echo "2. Admin: 4 монеты (4 голоса по коду 1)\n";
    echo "3. cool: 2 монеты (2 голоса по коду 1)\n";
    echo "4. Qwerti: 2 монеты (2 голоса по коду 1)\n";
    echo "5. Amodey, root: по 1 монете\n";
    
} catch (Exception $e) {
    echo "ОШИБКА: " . $e->getMessage() . "\n";
}