<?php
// tools/list_paladin_accounts.php
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

echo "Найдено паладинов 80 уровня: " . count($paladins) . "\n\n";
echo str_repeat('=', 80) . "\n";
printf("%-20s | %-15s | %-10s | %-20s\n", "Персонаж", "Account ID", "Раса", "Имя аккаунта");
echo str_repeat('=', 80) . "\n";

// Собираем уникальные account ID
$accountIds = array_unique(array_column($paladins, 'account'));

// Получаем имена аккаунтов пачкой
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

// Выводим список
foreach ($paladins as $paladin) {
    $accountName = $accountNames[$paladin['account']] ?? 'Unknown';
    $raceNames = [
        1 => 'Человек',
        3 => 'Дворф',
        10 => 'Эльф крови',
        11 => 'Дреней'
    ];
    $raceName = $raceNames[$paladin['race']] ?? "Раса {$paladin['race']}";
    
    printf("%-20s | %-15s | %-10s | %-20s\n", 
        $paladin['name'], 
        $paladin['account'],
        $raceName,
        $accountName
    );
}

echo str_repeat('=', 80) . "\n";
echo "\nУникальных аккаунтов: " . count($accountNames) . "\n";

// Выводим список уникальных аккаунтов
echo "\n=== Список уникальных аккаунтов с паладинами 80 lvl ===\n";
$uniqueAccounts = [];
foreach ($paladins as $paladin) {
    $accId = $paladin['account'];
    if (!isset($uniqueAccounts[$accId])) {
        $uniqueAccounts[$accId] = [
            'username' => $accountNames[$accId] ?? 'Unknown',
            'paladins' => []
        ];
    }
    $uniqueAccounts[$accId]['paladins'][] = $paladin['name'];
}

foreach ($uniqueAccounts as $accId => $data) {
    echo "\n{$data['username']} (ID: {$accId})\n";
    echo "  Паладины: " . implode(', ', $data['paladins']) . "\n";
}

?>
