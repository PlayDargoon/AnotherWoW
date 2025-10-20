<?php
require_once 'bootstrap.php';

try {
    $authPdo = DatabaseConnection::getAuthConnection();
    $sitePdo = DatabaseConnection::getSiteConnection();
    
    echo "=== АНАЛИЗ БАЛАНСА КОНКРЕТНОГО ПОЛЬЗОВАТЕЛЯ ===\n\n";
    
    // Найдём пользователя Admin
    $stmt = $authPdo->query("SELECT id, username FROM account WHERE username = 'Admin'");
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        echo "👤 Пользователь: " . $user['username'] . "\n";
        echo "🆔 Account ID: " . $user['id'] . "\n\n";
        
        // Проверим его баланс в сайтовой БД
        $stmt = $sitePdo->prepare("SELECT SUM(coins) as total_balance FROM account_coins WHERE account_id = ?");
        $stmt->execute([$user['id']]);
        $balance = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo "💰 Текущий баланс в account_coins: " . ($balance['total_balance'] ?: 0) . " бонусов\n";
        
        // Покажем последние транзакции
        $stmt = $sitePdo->prepare("SELECT coins, reason, created_at FROM account_coins WHERE account_id = ? ORDER BY created_at DESC LIMIT 5");
        $stmt->execute([$user['id']]);
        $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "\n📊 Последние 5 транзакций:\n";
        echo "===========================\n";
        foreach ($transactions as $trans) {
            echo "+" . $trans['coins'] . " бонус - " . ($trans['reason'] ?: 'Без причины') . " (" . $trans['created_at'] . ")\n";
        }
        
        // Проверим общую статистику
        $stmt = $sitePdo->prepare("SELECT COUNT(*) as count, MIN(created_at) as first_trans, MAX(created_at) as last_trans FROM account_coins WHERE account_id = ?");
        $stmt->execute([$user['id']]);
        $stats = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo "\n📈 Статистика транзакций:\n";
        echo "=========================\n";
        echo "Всего транзакций: " . $stats['count'] . "\n";
        echo "Первая транзакция: " . ($stats['first_trans'] ?: 'Нет') . "\n";
        echo "Последняя транзакция: " . ($stats['last_trans'] ?: 'Нет') . "\n";
        
    } else {
        echo "❌ Пользователь Admin не найден\n";
        
        // Покажем доступных пользователей с балансом
        echo "\nПользователи с балансом в account_coins:\n";
        $stmt = $sitePdo->query("
            SELECT ac.account_id, SUM(ac.coins) as balance, COUNT(*) as transactions
            FROM account_coins ac 
            GROUP BY ac.account_id 
            ORDER BY balance DESC 
            LIMIT 5
        ");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($users as $u) {
            echo "Account ID " . $u['account_id'] . ": " . $u['balance'] . " бонусов (" . $u['transactions'] . " транзакций)\n";
        }
    }
    
} catch (Exception $e) {
    echo "Ошибка: " . $e->getMessage() . "\n";
}
?>