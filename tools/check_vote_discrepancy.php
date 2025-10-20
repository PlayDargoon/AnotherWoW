<?php
require_once 'bootstrap.php';
use src\services\DatabaseConnection;

try {
    $db = DatabaseConnection::getInstance();
    $siteDb = $db->getSiteConnection();
    
    // Проверяем все голоса за октябрь
    $query = "SELECT COUNT(*) as total FROM votes 
               WHERE YEAR(vote_date) = 2025 AND MONTH(vote_date) = 10";
    $stmt = $siteDb->prepare($query);
    $stmt->execute();
    $totalOctober = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "Всего голосов за октябрь в БД: " . $totalOctober['total'] . PHP_EOL;
    
    // Проверяем количество по пользователям
    $query = "SELECT username, COUNT(*) as vote_count, SUM(coin_reward) as total_coins
               FROM votes 
               WHERE YEAR(vote_date) = 2025 AND MONTH(vote_date) = 10
               GROUP BY username 
               ORDER BY total_coins DESC, vote_count DESC";
    $stmt = $siteDb->prepare($query);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "\nРаспределение голосов по пользователям:\n";
    $totalFromUsers = 0;
    foreach ($users as $user) {
        echo $user['username'] . ": " . $user['vote_count'] . " голосов, " . $user['total_coins'] . " монет\n";
        $totalFromUsers += $user['vote_count'];
    }
    
    echo "\nСумма голосов по пользователям: " . $totalFromUsers . PHP_EOL;
    
    // Проверяем последние записи
    echo "\nПоследние 10 записей:\n";
    $query = "SELECT id, vote_date, username, coin_reward, vote_source FROM votes 
               WHERE YEAR(vote_date) = 2025 AND MONTH(vote_date) = 10
               ORDER BY vote_date DESC LIMIT 10";
    $stmt = $siteDb->prepare($query);
    $stmt->execute();
    $recent = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($recent as $vote) {
        echo $vote['id'] . "\t" . $vote['vote_date'] . "\t" . $vote['username'] . "\t" . $vote['coin_reward'] . "\t" . ($vote['vote_source'] ?? 'NULL') . PHP_EOL;
    }
    
    // Проверяем дубликаты
    echo "\nПроверка на дубликаты (одинаковые username, vote_date, coin_reward):\n";
    $query = "SELECT username, DATE(vote_date) as vote_day, coin_reward, COUNT(*) as duplicate_count
               FROM votes 
               WHERE YEAR(vote_date) = 2025 AND MONTH(vote_date) = 10
               GROUP BY username, DATE(vote_date), coin_reward
               HAVING COUNT(*) > 1
               ORDER BY duplicate_count DESC";
    $stmt = $siteDb->prepare($query);
    $stmt->execute();
    $duplicates = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($duplicates)) {
        echo "Дубликатов не найдено\n";
    } else {
        foreach ($duplicates as $dup) {
            echo $dup['username'] . " " . $dup['vote_day'] . " reward:" . $dup['coin_reward'] . " - " . $dup['duplicate_count'] . " раз\n";
        }
    }
    
} catch (Exception $e) {
    echo 'Ошибка: ' . $e->getMessage() . PHP_EOL;
}
?>