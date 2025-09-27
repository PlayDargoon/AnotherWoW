<?php
require_once __DIR__ . '/../src/services/DatabaseConnection.php';

$pdo = DatabaseConnection::getSiteConnection();

// Получаем общий баланс админа
$stmt = $pdo->query("SELECT COUNT(*) as total_records, SUM(coins) as total_balance FROM account_coins WHERE account_id = 401");
$total = $stmt->fetch();

echo "Общий баланс админа: " . $total['total_balance'] . " монет (" . $total['total_records'] . " записей)\n";

// Получаем баланс только от голосования
$stmt = $pdo->query("SELECT COUNT(*) as vote_records, SUM(coins) as vote_balance FROM account_coins WHERE account_id = 401 AND (reason LIKE '%голос%' OR reason LIKE '%vote%' OR reason LIKE '%MMOTOP%')");
$votes = $stmt->fetch();

echo "Баланс от голосования: " . $votes['vote_balance'] . " монет (" . $votes['vote_records'] . " голосов)\n";
echo "Другие источники: " . ($total['total_balance'] - $votes['vote_balance']) . " монет\n";

// Показываем примеры записей НЕ от голосования
echo "\n=== Записи НЕ от голосования ===\n";
$stmt = $pdo->query("SELECT coins, reason, created_at FROM account_coins WHERE account_id = 401 AND reason NOT LIKE '%голос%' AND reason NOT LIKE '%vote%' AND reason NOT LIKE '%MMOTOP%' ORDER BY created_at");
$other = $stmt->fetchAll();
foreach ($other as $record) {
    echo "Монет: " . $record['coins'] . ", Причина: " . $record['reason'] . ", Дата: " . $record['created_at'] . "\n";
}
?>