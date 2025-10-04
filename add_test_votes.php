<?php
// add_test_votes.php - Добавляем тестовые данные для демонстрации топ 10

require_once 'bootstrap.php';

try {
    $sitePdo = DatabaseConnection::getSiteConnection();
    $authPdo = DatabaseConnection::getAuthConnection();
    
    echo "=== Добавление тестовых голосов ===\n";
    
    // Получаем список существующих аккаунтов
    $stmt = $authPdo->query("SELECT id, username FROM account LIMIT 10");
    $accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($accounts)) {
        echo "Нет доступных аккаунтов для тестирования.\n";
        exit;
    }
    
    echo "Найдено аккаунтов: " . count($accounts) . "\n";
    
    // Добавляем тестовые голоса для демонстрации топ 10
    $currentMonth = date('Y-m-01');
    $testVotes = [
        ['account_id' => null, 'votes' => 25, 'username' => 'Admin'],
        ['account_id' => null, 'votes' => 18, 'username' => 'Toxa65'], 
        ['account_id' => null, 'votes' => 15, 'username' => 'Cool'],
    ];
    
    // Добавляем дополнительные тестовые аккаунты если есть
    $extraAccounts = array_slice($accounts, 3, 7); // Берем следующие 7 аккаунтов
    foreach ($extraAccounts as $index => $account) {
        $testVotes[] = [
            'account_id' => $account['id'],
            'votes' => rand(1, 12),
            'username' => $account['username']
        ];
    }
    
    // Сопоставляем имена пользователей с ID аккаунтов
    foreach ($testVotes as &$vote) {
        if ($vote['account_id'] === null && $vote['username']) {
            foreach ($accounts as $account) {
                if ($account['username'] === $vote['username']) {
                    $vote['account_id'] = $account['id'];
                    break;
                }
            }
        }
    }
    
    $stmt = $sitePdo->prepare("
        INSERT INTO account_coins (account_id, coins, reason, created_at) 
        VALUES (?, 1, 'Голос на MMOTOP (тест)', ?)
    ");
    
    $addedVotes = 0;
    foreach ($testVotes as $vote) {
        if ($vote['account_id']) {
            echo "Добавляем голоса для {$vote['username']} (ID: {$vote['account_id']}): ";
            
            for ($i = 0; $i < $vote['votes']; $i++) {
                // Случайное время в пределах текущего месяца
                $randomDay = rand(1, date('j')); // От 1 до текущего дня месяца
                $randomTime = sprintf('%02d:%02d:%02d', rand(0, 23), rand(0, 59), rand(0, 59));
                $voteTime = date('Y-m-') . sprintf('%02d', $randomDay) . ' ' . $randomTime;
                
                $stmt->execute([$vote['account_id'], $voteTime]);
                $addedVotes++;
            }
            echo "{$vote['votes']} голосов\n";
        }
    }
    
    echo "\nВсего добавлено голосов: $addedVotes\n";
    echo "=== Тестовые данные добавлены ===\n";
    
} catch (Exception $e) {
    echo "ОШИБКА: " . $e->getMessage() . "\n";
}