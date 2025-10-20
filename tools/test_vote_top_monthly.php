<?php
// test_vote_top_monthly.php - Тестируем новую логику топа голосующих за месяц

require_once 'bootstrap.php';
require_once 'src/models/VoteTop.php';

try {
    echo "=== Тест топа голосующих за текущий месяц ===\n";
    
    $voteTopModel = new VoteTop(
        DatabaseConnection::getSiteConnection(),
        DatabaseConnection::getAuthConnection()
    );
    
    // Получаем статистику за месяц
    echo "\n1. Статистика за текущий месяц:\n";
    $monthlyStats = $voteTopModel->getMonthlyStatistics();
    echo "   Всего голосующих: " . ($monthlyStats['total_voters'] ?? 0) . "\n";
    echo "   Всего голосов: " . ($monthlyStats['total_votes'] ?? 0) . "\n";
    echo "   Записей голосования: " . ($monthlyStats['total_vote_records'] ?? 0) . "\n";
    
    if ($monthlyStats['first_vote']) {
        echo "   Первый голос: " . date('d.m.Y H:i', strtotime($monthlyStats['first_vote'])) . "\n";
    }
    if ($monthlyStats['last_vote']) {
        echo "   Последний голос: " . date('d.m.Y H:i', strtotime($monthlyStats['last_vote'])) . "\n";
    }
    
    // Получаем топ 10 за месяц
    echo "\n2. Топ 10 голосующих за текущий месяц:\n";
    $topVoters = $voteTopModel->getTopVoters(10);
    
    if (empty($topVoters)) {
        echo "   Пока никто не голосовал в этом месяце.\n";
    } else {
        foreach ($topVoters as $index => $voter) {
            $place = $index + 1;
            echo sprintf("   %2d. %-15s - %3d голосов (последний: %s)\n", 
                $place, 
                $voter['username'], 
                $voter['vote_count'],
                $voter['last_vote'] ? date('d.m.Y H:i', strtotime($voter['last_vote'])) : 'никогда'
            );
        }
    }
    
    // Тестируем позицию конкретного пользователя
    echo "\n3. Тест позиции пользователей:\n";
    $testUsers = ['Admin', 'Toxa65', 'Cool'];
    foreach ($testUsers as $username) {
        $position = $voteTopModel->getUserPosition($username);
        echo "   $username: " . ($position ? "место #$position" : "не в рейтинге") . "\n";
    }
    
    echo "\n=== Тест завершен успешно ===\n";
    
} catch (Exception $e) {
    echo "ОШИБКА: " . $e->getMessage() . "\n";
    echo "Трассировка:\n" . $e->getTraceAsString() . "\n";
}