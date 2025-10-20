<?php
// tools/list_real_paladins.php
require_once __DIR__ . '/../src/services/DatabaseConnection.php';

$charDb = DatabaseConnection::getCharactersConnection();
$authDb = DatabaseConnection::getAuthConnection();

// Получаем всех паладинов 80 уровня
$stmt = $charDb->query('SELECT guid, name, level, race, account FROM characters WHERE class = 2 AND level = 80 ORDER BY name');
$paladins = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($paladins)) {
    echo "Паладинов 80 уровня не найдено\n";
    exit;
}

// Собираем уникальные account ID
$accountIds = array_unique(array_column($paladins, 'account'));

// Получаем имена аккаунтов
$accountNames = [];
if (!empty($accountIds)) {
    $placeholders = implode(',', array_fill(0, count($accountIds), '?'));
    $stmt = $authDb->prepare("SELECT id, username FROM account WHERE id IN ({$placeholders})");
    $stmt->execute($accountIds);
    $accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($accounts as $acc) {
        $accountNames[$acc['id']] = $acc['username'];
    }
}

// Фильтруем только НЕ-ботовые аккаунты
$realPaladins = [];
foreach ($paladins as $paladin) {
    $accountName = $accountNames[$paladin['account']] ?? 'Unknown';
    // Пропускаем аккаунты, начинающиеся с RNDBOT
    if (strpos($accountName, 'RNDBOT') !== 0) {
        $realPaladins[] = [
            'character' => $paladin['name'],
            'account_id' => $paladin['account'],
            'account_name' => $accountName,
            'race' => $paladin['race'],
            'guid' => $paladin['guid']
        ];
    }
}

if (empty($realPaladins)) {
    echo "Паладинов 80 уровня у реальных игроков (не RNDBOT) не найдено\n";
    exit;
}

echo "Найдено паладинов 80 уровня у реальных игроков: " . count($realPaladins) . "\n\n";
echo str_repeat('=', 90) . "\n";
printf("%-20s | %-10s | %-15s | %-25s | %-10s\n", "Персонаж", "GUID", "Account ID", "Имя аккаунта", "Раса");
echo str_repeat('=', 90) . "\n";

$raceNames = [
    1 => 'Человек',
    3 => 'Дворф',
    10 => 'Эльф крови',
    11 => 'Дреней'
];

foreach ($realPaladins as $p) {
    $raceName = $raceNames[$p['race']] ?? "Раса {$p['race']}";
    printf("%-20s | %-10s | %-15s | %-25s | %-10s\n", 
        $p['character'],
        $p['guid'],
        $p['account_id'],
        $p['account_name'],
        $raceName
    );
}

echo str_repeat('=', 90) . "\n";

// Выводим список уникальных аккаунтов
$uniqueAccounts = [];
foreach ($realPaladins as $p) {
    $accName = $p['account_name'];
    if (!isset($uniqueAccounts[$accName])) {
        $uniqueAccounts[$accName] = [];
    }
    $uniqueAccounts[$accName][] = $p['character'];
}

echo "\n=== Список аккаунтов с паладинами 80 lvl ===\n";
foreach ($uniqueAccounts as $accName => $chars) {
    echo "\n{$accName}\n";
    echo "  Паладины: " . implode(', ', $chars) . "\n";
}

?>
