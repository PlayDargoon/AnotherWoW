<?php
// check_vote_data.php
require_once __DIR__ . '/src/services/DatabaseConnection.php';
require_once __DIR__ . '/src/models/VoteTop.php';

try {
    echo "=== Проверка данных голосования ===\n\n";
    
    $pdo = DatabaseConnection::getSiteConnection();
    
    // Проверяем есть ли таблица account_coins
    $stmt = $pdo->query("SHOW TABLES LIKE 'account_coins'");
    if ($stmt->rowCount() == 0) {
        echo "❌ Таблица account_coins не найдена!\n";
        exit;
    }
    echo "✅ Таблица account_coins найдена\n";
    
    // Проверяем структуру таблицы
    echo "\n=== Структура таблицы account_coins ===\n";
    $stmt = $pdo->query("DESCRIBE account_coins");
    while ($row = $stmt->fetch()) {
        echo "- {$row['Field']} ({$row['Type']})\n";
    }
    
    // Проверяем общее количество записей
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM account_coins");
    $total = $stmt->fetch()['total'];
    echo "\n=== Общее количество записей: $total ===\n";
    
    // Проверяем записи связанные с голосованием
    $stmt = $pdo->query("
        SELECT COUNT(*) as count 
        FROM account_coins 
        WHERE reason LIKE '%голос%' OR reason LIKE '%vote%' OR reason LIKE '%MMOTOP%'
    ");
    $voteCount = $stmt->fetch()['count'];
    echo "Записей с голосованием: $voteCount\n";
    
    // Показываем примеры записей
    echo "\n=== Примеры записей ===\n";
    $stmt = $pdo->query("SELECT * FROM account_coins ORDER BY created_at DESC LIMIT 10");
    while ($row = $stmt->fetch()) {
        echo "ID: {$row['id']}, Account: {$row['account_id']}, Username: {$row['username']}, Coins: {$row['coins']}, Reason: {$row['reason']}, Date: {$row['created_at']}\n";
    }
    
    // Тестируем модель VoteTop
    echo "\n=== Тест исправленной модели VoteTop ===\n";
    $voteTop = new VoteTop($pdo, DatabaseConnection::getAuthConnection());
    $topVoters = $voteTop->getTopVoters(5);
    
    if (empty($topVoters)) {
        echo "❌ Модель VoteTop не возвращает данные\n";
    } else {
        echo "✅ Модель VoteTop работает:\n";
        foreach ($topVoters as $voter) {
            echo "- {$voter['username']} (ID: {$voter['account_id']}): {$voter['total_votes']} голосов, {$voter['vote_count']} записей\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Ошибка: " . $e->getMessage() . "\n";
}