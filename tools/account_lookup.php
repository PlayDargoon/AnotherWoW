<?php
// tools/account_lookup.php
// Usage: php account_lookup.php --names=Gnofypack,Selonian

require_once __DIR__ . '/../src/services/DatabaseConnection.php';

function parseArgs(array $argv) {
    $out = ['names' => []];
    foreach ($argv as $arg) {
        if (strpos($arg, '--names=') === 0) {
            $val = substr($arg, 8);
            $parts = preg_split('/[,;:\s]+/', $val);
            foreach ($parts as $p) if (strlen($p)) $out['names'][] = $p;
        }
        if (strpos($arg, '--file=') === 0) {
            $file = substr($arg, 7);
            if (file_exists($file)) {
                $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($lines as $l) {
                    $l = trim($l);
                    if ($l !== '') $out['names'][] = $l;
                }
            }
        }
    }
    return $out;
}

$opts = parseArgs($argv);
if (empty($opts['names'])) {
    echo "Использование: php tools/account_lookup.php --names=Gnofypack,Selonian\n";
    exit(1);
}

$pdoChars = DatabaseConnection::getCharactersConnection();
$pdoAuth = DatabaseConnection::getAuthConnection();

foreach ($opts['names'] as $name) {
    $stmt = $pdoChars->prepare('SELECT account, guid, name, class, level FROM characters WHERE name = :n LIMIT 1');
    $stmt->execute([':n' => $name]);
    $char = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$char) {
        echo "$name: персонаж не найден\n";
        continue;
    }
    $accId = $char['account'];
    $stmt2 = $pdoAuth->prepare('SELECT username FROM account WHERE id = :id LIMIT 1');
    $stmt2->execute([':id' => $accId]);
    $acc = $stmt2->fetch(PDO::FETCH_ASSOC);
    $username = $acc['username'] ?? 'не найдено';
    printf("%s (GUID %d, lvl %d, class %d): account %d, username: %s\n", $char['name'], $char['guid'], $char['level'], $char['class'], $accId, $username);
}

echo "\nDone.\n";
