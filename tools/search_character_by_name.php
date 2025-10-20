<?php
// search_character_by_name.php — Поиск персонажа по имени (без учета регистра), вывод уровня и класса

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

// Получаем имя из аргументов
$name = null;
foreach ($argv as $i => $arg) {
    if ($i === 0) continue;
    if (preg_match('/^--?name=(.+)$/i', $arg, $m)) {
        $name = $m[1];
        break;
    }
}
if (!$name && isset($argv[1]) && $argv[1] !== '' && $argv[1][0] !== '-') {
    $name = $argv[1];
}

if (!$name) {
    fwrite(STDERR, "Укажите имя персонажа: php tools/search_character_by_name.php --name=Имя\n");
    exit(2);
}

$pdo = DatabaseConnection::getCharactersConnection();

// 1) Точный поиск без учета регистра
$stmt = $pdo->prepare("SELECT guid, name, level, class, race, account FROM characters WHERE LOWER(name) = LOWER(:name) ORDER BY guid LIMIT 10");
$stmt->execute([':name' => $name]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$rows) {
    // 2) LIKE-поиск по подстроке, без учета регистра
    $pattern = '%' . $name . '%';
    $stmt = $pdo->prepare("SELECT guid, name, level, class, race, account FROM characters WHERE LOWER(name) LIKE LOWER(:pat) ORDER BY name, guid LIMIT 20");
    $stmt->execute([':pat' => $pattern]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if (!$rows) {
    echo "Персонажи не найдены по запросу '{$name}'.\n";
    exit(0);
}

printf("%-20s | %-6s | %-5s | %-15s | %-8s\n", 'Имя', 'GUID', 'Уров.', 'Класс', 'Раса');
echo str_repeat('-', 62) . "\n";
foreach ($rows as $r) {
    $className = classNameById((int)$r['class']);
    printf("%-20s | %-6d | %-5d | %-15s | %-8s\n",
        $r['name'], (int)$r['guid'], (int)$r['level'], $className, (string)$r['race']
    );
}

// Если есть ровно один точный (без учета регистра) матч — добавим краткое резюме
if (count($rows) === 1) {
    $r = $rows[0];
    echo "\nПерсонаж {$r['name']}: уровень {$r['level']}, класс: " . classNameById((int)$r['class']) . " (ID {$r['class']}).\n";
}
