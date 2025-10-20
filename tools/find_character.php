<?php
require_once __DIR__ . '/../src/services/DatabaseConnection.php';

$pdo = DatabaseConnection::getCharactersConnection();
$authDb = DatabaseConnection::getAuthConnection();

// Определим имя персонажа из аргументов CLI
$name = 'Aengru';
foreach ($argv as $arg) {
    if (preg_match('/^--?name=(.+)$/i', $arg, $m)) {
        $name = $m[1];
    }
}
// Позиционный аргумент (первый, если не опция)
if (isset($argv[1]) && $argv[1] !== '' && $argv[1][0] !== '-') {
    $name = $argv[1];
}

// Сначала найдем персонажа и его account_id
$stmt = $pdo->prepare('SELECT c.guid, c.name, c.race, c.class, c.level, c.totaltime, c.logout_time, c.account 
                       FROM characters c 
                       WHERE c.name = :name');
$stmt->execute([':name' => $name]);

$char = $stmt->fetch(PDO::FETCH_ASSOC);
if ($char) {
    // Теперь найдем информацию об аккаунте
    $accountStmt = $authDb->prepare('SELECT username, email FROM account WHERE id = :id');
    $accountStmt->execute([':id' => $char['account']]);
    $account = $accountStmt->fetch(PDO::FETCH_ASSOC);
    
    echo "\nПерсонаж {$char['name']}:\n";
    echo "GUID: {$char['guid']}\n";
    if ($account) {
        echo "Аккаунт: {$account['username']}\n";
        if (!empty($account['email'])) echo "Email: {$account['email']}\n";
    }
    echo "Уровень: {$char['level']}\n";
    echo "Класс: {$char['class']}\n";
    echo "Раса: {$char['race']}\n";
    if (isset($char['totaltime'])) echo "Время в игре: " . floor($char['totaltime'] / 3600) . " ч " . floor(($char['totaltime'] % 3600) / 60) . " мин\n";
    if (isset($char['logout_time']) && $char['logout_time'] > 0) echo "Последний выход: " . date('d.m.Y H:i', $char['logout_time']) . "\n";
} else {
    echo "Персонаж не найден\n";
}