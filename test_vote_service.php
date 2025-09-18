<?php
// test_vote_service.php
// Тестирование системы голосования через контроллеры
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/src/controllers/VoteSyncController.php';
require_once __DIR__ . '/src/controllers/MigrationController.php';

echo "Тестирование системы голосования...\n\n";

// 1. Проверка базы данных
echo "=== Проверка базы данных ===\n";
$migrationController = new MigrationController();
$migrationController->checkDatabase();

// 2. Тестирование синхронизации
echo "\n=== Тестирование синхронизации ===\n";
$voteController = new VoteSyncController();

// Получаем статистику до синхронизации
$statsBefore = $voteController->getStats();
echo "Статистика до синхронизации:\n";
if (isset($statsBefore['error'])) {
    echo "Ошибка: " . $statsBefore['error'] . "\n";
} else {
    echo "- Всего голосов: " . $statsBefore['total_votes'] . "\n";
    echo "- Транзакций монет: " . $statsBefore['coin_transactions'] . "\n";
    echo "- Всего монет начислено: " . $statsBefore['total_coins_awarded'] . "\n";
}

// Запускаем синхронизацию
echo "\nЗапуск синхронизации...\n";
$success = $voteController->syncFromCli();

if ($success) {
    // Получаем статистику после синхронизации
    $statsAfter = $voteController->getStats();
    echo "\nСтатистика после синхронизации:\n";
    if (isset($statsAfter['error'])) {
        echo "Ошибка: " . $statsAfter['error'] . "\n";
    } else {
        echo "- Всего голосов: " . $statsAfter['total_votes'] . "\n";
        echo "- Транзакций монет: " . $statsAfter['coin_transactions'] . "\n";
        echo "- Всего монет начислено: " . $statsAfter['total_coins_awarded'] . "\n";
        
        $newVotes = $statsAfter['total_votes'] - $statsBefore['total_votes'];
        echo "- Новых голосов обработано: $newVotes\n";
    }
    
    // Показываем последние голоса
    echo "\n=== Последние голоса ===\n";
    $recentVotes = $voteController->getRecentVotes(5);
    if (isset($recentVotes['error'])) {
        echo "Ошибка: " . $recentVotes['error'] . "\n";
    } else {
        foreach ($recentVotes as $vote) {
            $time = date('Y-m-d H:i:s', $vote['vote_time']);
            echo "- {$vote['username']}: {$vote['reward']} монет ($time)\n";
        }
    }
}

echo "\nТестирование завершено!\n";