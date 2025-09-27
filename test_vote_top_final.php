<?php
// test_vote_top_final.php
require_once __DIR__ . '/src/services/DatabaseConnection.php';
require_once __DIR__ . '/src/models/VoteTop.php';

try {
    echo "=== Финальный тест VoteTop ===\n\n";
    
    $voteTop = new VoteTop(
        DatabaseConnection::getSiteConnection(),
        DatabaseConnection::getAuthConnection()
    );
    
    // Получаем топ-10 голосующих
    $topVoters = $voteTop->getTopVoters(10);
    
    if (empty($topVoters)) {
        echo "❌ Нет данных о голосующих\n";
    } else {
        echo "✅ Топ голосующих:\n";
        foreach ($topVoters as $index => $voter) {
            $place = $index + 1;
            echo "$place. {$voter['username']} (ID: {$voter['account_id']}) - {$voter['total_votes']} монет, {$voter['vote_count']} голосов, последний: {$voter['last_vote']}\n";
        }
    }
    
    // Тестируем прямой запрос к базе
    echo "\n=== Прямой запрос к базе данных ===\n";
    $sitePdo = DatabaseConnection::getSiteConnection();
    
    $stmt = $sitePdo->query("
        SELECT 
            account_id,
            SUM(coins) as total_votes,
            COUNT(*) as vote_count,
            MAX(created_at) as last_vote
        FROM account_coins
        WHERE reason LIKE '%голос%' OR reason LIKE '%vote%' OR reason LIKE '%MMOTOP%'
        GROUP BY account_id
        ORDER BY total_votes DESC, vote_count DESC
    ");
    
    $directResults = $stmt->fetchAll();
    
    echo "Прямой запрос возвращает " . count($directResults) . " записей:\n";
    foreach ($directResults as $result) {
        echo "Account {$result['account_id']}: {$result['total_votes']} монет, {$result['vote_count']} голосов\n";
    }
    
} catch (Exception $e) {
    echo "Ошибка: " . $e->getMessage() . "\n";
}