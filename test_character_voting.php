<?php
// test_character_voting.php - Тестируем обработку голосов с именами персонажей

require_once 'bootstrap.php';
require_once 'src/services/VoteService.php';

try {
    echo "=== Тест обработки голосов с именами персонажей ===\n";
    
    $voteService = new VoteService();
    
    // Создаем тестовый контент с именами персонажей вместо логинов
    $testContent = "283500001\t05.10.2025 10:00:00\t127.0.0.1\tPlaydragon\t1\n";  // Имя персонажа, аккаунт Admin
    $testContent .= "283500002\t05.10.2025 10:05:00\t127.0.0.1\tПрист\t1\n";      // Имя персонажа, аккаунт Admin  
    $testContent .= "283500003\t05.10.2025 10:10:00\t127.0.0.1\tAdmin\t1\n";      // Логин аккаунта
    $testContent .= "283500004\t05.10.2025 10:15:00\t127.0.0.1\tНесуществующий\t1\n"; // Несуществующий
    
    echo "Тестовый контент:\n";
    echo $testContent . "\n";
    
    // Обрабатываем голоса
    echo "Обработка голосов:\n";
    $result = $voteService->processVotesFromContent($testContent);
    
    echo "Результат обработки:\n";
    echo "- Обработано: {$result['processed']}\n";
    echo "- Пропущено: {$result['skipped']}\n";
    echo "- Ошибки: " . count($result['errors']) . "\n";
    
    if (!empty($result['errors'])) {
        echo "\nОшибки:\n";
        foreach ($result['errors'] as $error) {
            echo "  - $error\n";
        }
    }
    
    // Проверяем добавленные записи в базе
    echo "\nПроверяем добавленные записи:\n";
    $sitePdo = \DatabaseConnection::getSiteConnection();
    $stmt = $sitePdo->prepare("
        SELECT account_id, coins, reason, created_at 
        FROM account_coins 
        WHERE created_at >= '2025-10-05 10:00:00' 
        AND created_at <= '2025-10-05 10:20:00'
        AND reason LIKE '%mmotop%'
        ORDER BY created_at
    ");
    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($records as $record) {
        echo "- Account ID: {$record['account_id']}, Монеты: {$record['coins']}, Время: {$record['created_at']}\n";
        echo "  Причина: {$record['reason']}\n";
    }
    
    echo "\n=== Тест завершен ===\n";
    
} catch (Exception $e) {
    echo "ОШИБКА: " . $e->getMessage() . "\n";
    echo "Трассировка: " . $e->getTraceAsString() . "\n";
}