<?php
// list_account_characters.php — Список персонажей по логину аккаунта (auth.account.username)

require_once __DIR__ . '/../src/services/DatabaseConnection.php';

function classNameById(int $id): string {
    $map = [
        1 => 'Воин',
        2 => 'Паладин',
        3 => 'Охотник',
        4 => 'Разбойник',
        5 => 'Жрец',
        6 => 'Рыцарь смерти',
        7 => 'Шаман',
        8 => 'Маг',
        9 => 'Чернокнижник',
        11 => 'Друид',
    ];
    return $map[$id] ?? (string)$id;
}

$username = null;
foreach ($argv as $i => $arg) {
    if ($i === 0) continue;
    if (preg_match('/^--?user(name)?=(.+)$/i', $arg, $m)) {
        $username = $m[2];
        break;
    }
}
if (!$username && isset($argv[1]) && $argv[1] !== '' && $argv[1][0] !== '-') {
    $username = $argv[1];
}

if (!$username) {
    fwrite(STDERR, "Usage: php tools/list_account_characters.php --username=LOGIN\n");
    exit(2);
}

$auth = DatabaseConnection::getAuthConnection();
$chars = DatabaseConnection::getCharactersConnection();

// Ищем аккаунт без учета регистра
$stmt = $auth->prepare('SELECT id, username, email FROM account WHERE LOWER(username) = LOWER(:u) LIMIT 1');
$stmt->execute([':u' => $username]);
$acc = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$acc) {
    echo "Аккаунт '{$username}' не найден.\n";
    exit(0);
}

echo "Аккаунт: {$acc['username']} (ID: {$acc['id']})" . (!empty($acc['email']) ? ", email: {$acc['email']}" : '') . "\n";

$stmt = $chars->prepare('SELECT guid, name, level, class, race, gender, online, totaltime FROM characters WHERE account = :id ORDER BY level DESC, name ASC');
$stmt->execute([':id' => $acc['id']]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$rows) {
    echo "Персонажей не найдено.\n";
    exit(0);
}

printf("%-4s | %-20s | %-5s | %-15s | %-6s | %-7s\n", 'GUID', 'Имя', 'Уров.', 'Класс', 'Раса', 'Онлайн');
echo str_repeat('-', 68) . "\n";
foreach ($rows as $r) {
    $className = classNameById((int)$r['class']);
    $online = ((int)$r['online']) === 1 ? 'да' : 'нет';
    printf("%-4d | %-20s | %-5d | %-15s | %-6s | %-7s\n",
        (int)$r['guid'], $r['name'], (int)$r['level'], $className, (string)$r['race'], $online
    );
}

// Краткая сводка
$maxLevel = max(array_map(fn($x)=>(int)$x['level'], $rows));
$count = count($rows);
echo "\nВсего персонажей: {$count}. Максимальный уровень: {$maxLevel}.\n";
