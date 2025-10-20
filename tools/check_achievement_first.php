<?php
// tools/check_achievement_first.php
// Usage: php check_achievement_first.php --id=465 [--list] [--limit=10]

require_once __DIR__ . '/../src/services/DatabaseConnection.php';

// Простая обработка аргументов: --ids=1,2,3  --file=path --list --limit=N --out-file=out.json --format=json|csv
function parseArgs(array $argv) {
    $out = ['ids' => [], 'file' => null, 'list' => false, 'limit' => 10, 'out_file' => null, 'format' => 'json'];
    foreach ($argv as $arg) {
        if (strpos($arg, '--id=') === 0 || strpos($arg, '--ids=') === 0) {
            $val = preg_replace('/^--ids?=/', '', $arg);
            $parts = preg_split('/[,;:\s]+/', $val);
            foreach ($parts as $p) if (strlen($p)) $out['ids'][] = (int)$p;
        }
        if (strpos($arg, '--file=') === 0) $out['file'] = substr($arg, 7);
        if ($arg === '--list') $out['list'] = true;
        if (strpos($arg, '--limit=') === 0) $out['limit'] = (int)substr($arg, 8);
        if (strpos($arg, '--out-file=') === 0) $out['out_file'] = substr($arg, 11);
        if (strpos($arg, '--format=') === 0) $out['format'] = strtolower(substr($arg, 9));
    }
    return $out;
}

$opts = parseArgs($argv);

// Load IDs from file if provided
if ($opts['file']) {
    if (!file_exists($opts['file'])) {
        fwrite(STDERR, "Файл не найден: {$opts['file']}\n");
        exit(2);
    }
    $content = file_get_contents($opts['file']);
    // поддерживаем JSON array or newline/CSV list
    $maybe = json_decode($content, true);
    if (is_array($maybe)) {
        foreach ($maybe as $v) $opts['ids'][] = (int)$v;
    } else {
        $lines = preg_split('/[\r\n,;]+/', $content);
        foreach ($lines as $l) {
            $l = trim($l);
            if ($l === '') continue;
            if (preg_match('/^\d+$/', $l)) $opts['ids'][] = (int)$l;
        }
    }
}

// Deduplicate and sanitize
$opts['ids'] = array_values(array_filter(array_unique($opts['ids']), function($v){ return $v > 0; }));

if (empty($opts['ids'])) {
    echo "Использование: php tools/check_achievement_first.php --ids=465,466 --list --limit=10 --out-file=out.json --format=json\n";
    exit(1);
}

$pdo = DatabaseConnection::getCharactersConnection();

// Utility: detect columns available in character_achievement
function detectAchievementColumns(PDO $pdo) {
    $colsInfo = [];
    try {
        $res = $pdo->query("DESCRIBE character_achievement");
        if ($res) $colsInfo = $res->fetchAll(PDO::FETCH_COLUMN, 0);
    } catch (Exception $e) {
        // ignore and return minimal set
    }
    return $colsInfo;
}

$colsInfo = detectAchievementColumns($pdo);
$colsList = ['guid', 'achievement'];
if (in_array('data', $colsInfo)) $colsList[] = 'data';
if (in_array('time', $colsInfo)) $colsList[] = 'time';
if (in_array('date', $colsInfo)) $colsList[] = 'date';
$cols = implode(', ', $colsList);

// Parser (kept from original, extracted for reuse)
$parseAchievementData = function($data) {
    if ($data === null || $data === '') return null;
    $trim = trim($data);
    if (($trim[0] ?? '') === '{' || ($trim[0] ?? '') === '[') {
        $d = json_decode($data, true);
        if (is_array($d)) {
            $flat = new RecursiveIteratorIterator(new RecursiveArrayIterator($d));
            foreach ($flat as $v) {
                if (is_numeric($v) && (int)$v > 1000000000) return (int)$v;
            }
        }
    }
    if (preg_match('/^\d{9,10}$/', $trim)) return (int)$trim;
    try {
        if (strlen($data) >= 4) {
            $u32 = @unpack('V', substr($data, 0, 4));
            if (!empty($u32) && is_array($u32)) {
                $t = (int)current($u32);
                if ($t > 1000000000) return $t;
            }
        }
        if (strlen($data) >= 8) {
            $u64 = @unpack('P', substr($data, 0, 8));
            if (!empty($u64) && is_array($u64)) {
                $t = (int)current($u64);
                if ($t > 1000000000) return $t;
            }
        }
    } catch (Exception $e) {
        // ignore
    }
    if (preg_match('/(\d{9,10})/', $data, $m)) return (int)$m[1];
    return null;
};

// Prepare to collect results
$results = [];

// Load optional mapping file with achievement id -> title (one per line: id|title)
$mapFile = __DIR__ . '/opiums_firston_map.txt';
$achMap = [];
if (file_exists($mapFile)) {
    $lines = file($mapFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $ln) {
        $parts = explode('|', $ln, 2);
        if (count($parts) === 2) $achMap[(int)trim($parts[0])] = trim($parts[1]);
    }
}

// We'll fetch achievements one-by-one but batch character lookups
foreach ($opts['ids'] as $aid) {
    // Заголовок будем выводить только если найдутся записи
    $title = $achMap[(int)$aid] ?? null;
    $sqlOrder = '';
    if (strpos($cols, 'time') !== false) $sqlOrder = 'time ASC';
    elseif (strpos($cols, 'date') !== false) $sqlOrder = 'date ASC';
    else $sqlOrder = 'guid ASC';

    $limit = $opts['list'] ? max(1, (int)$opts['limit']) : 1;
    $sql = "SELECT {$cols} FROM character_achievement WHERE achievement = :aid ORDER BY {$sqlOrder} LIMIT {$limit}";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':aid' => $aid]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        fwrite(STDERR, "Ошибка запроса character_achievement для {$aid}: " . $e->getMessage() . PHP_EOL);
        continue;
    }
    if (empty($rows)) {
        // Пропускаем без вывода, если записей нет
        $results[$aid] = [];
        continue;
    }

    // Выводим заголовок только если нашлись записи
    if ($title) {
        echo "\n$title\n";
    } else {
        echo "\n=== Achievement ID: {$aid} ===\n";
    }
    
    // Соберём GUIDs и затем запросим всех персонажей одной пачкой
    $guids = array_values(array_unique(array_map(function($r){ return (int)$r['guid']; }, $rows)));
    $chars = [];
    if (!empty($guids)) {
        $placeholders = implode(',', array_fill(0, count($guids), '?'));
        $csql = "SELECT guid, name, class, level FROM characters WHERE guid IN ({$placeholders})";
        try {
            $cstmt = $pdo->prepare($csql);
            $cstmt->execute($guids);
            $f = $cstmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($f as $row) $chars[(int)$row['guid']] = $row;
        } catch (Exception $e) {
            // If characters table missing or query fails, we'll handle per-guid fallback later
            fwrite(STDERR, "Warning: could not batch-fetch characters: " . $e->getMessage() . PHP_EOL);
        }
    }

    $results[$aid] = [];
    foreach ($rows as $r) {
        $guid = (int)$r['guid'];
        $t = null;
        if (isset($r['time']) && $r['time']) {
            $t = (int)$r['time'];
        }
        elseif (isset($r['date']) && $r['date']) {
            // Поле date содержит unix timestamp напрямую
            $t = (int)$r['date'];
        }
        elseif (isset($r['data'])) {
            $t = $parseAchievementData($r['data']);
        }

        $char = $chars[$guid] ?? null;
        if (!$char) {
            // fallback per-guid lookup
            try {
                $cstmt = $pdo->prepare('SELECT guid, name, class, level FROM characters WHERE guid = :g LIMIT 1');
                $cstmt->execute([':g' => $guid]);
                $char = $cstmt->fetch(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                $char = null;
            }
        }

        $when = $t ? date('Y-m-d H:i:s', $t) : null;
        $entry = ['guid' => $guid, 'when_unix' => $t, 'when' => $when, 'character' => $char];
        if (!$t && isset($r['data'])) {
            $entry['data_preview_hex'] = substr(bin2hex($r['data']), 0, 160);
        }
        $results[$aid][] = $entry;

        // Печатать человекочитаемую строку на русском
        if ($char) {
            $dateStr = $t ? date('d.m.Y H:i', $t) : 'дата неизвестна';
            printf("%s %s, %s\n", $char['name'], $char['level'], $dateStr);
        } else {
            echo "персонаж не найден\n";
        }
        if (!$opts['list']) break;
    }
}

// Output to file if requested
if ($opts['out_file']) {
    $fmt = $opts['format'];
    if ($fmt === 'json') {
        file_put_contents($opts['out_file'], json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        echo "\nЗаписано в файл: {$opts['out_file']}\n";
    } elseif ($fmt === 'csv') {
        $fp = fopen($opts['out_file'], 'w');
        if ($fp) {
            // header (на русском)
            fputcsv($fp, ['achievement_id','guid','when_unix','when','имя','класс','уровень','data_preview_hex']);
            foreach ($results as $aid => $rows) {
                foreach ($rows as $r) {
                    $char = $r['character'] ?? [];
                    fputcsv($fp, [$aid, $r['guid'] ?? '', $r['when_unix'] ?? '', $r['when'] ?? '', $char['name'] ?? '', $char['class'] ?? '', $char['level'] ?? '', $r['data_preview_hex'] ?? '']);
                }
            }
            fclose($fp);
            echo "\nЗаписано CSV в файл: {$opts['out_file']}\n";
        } else {
            fwrite(STDERR, "Не удалось открыть {$opts['out_file']} для записи\n");
        }
    } else {
        fwrite(STDERR, "Неизвестный формат {$fmt}\n");
    }
}

echo "\nDone.\n";

?>
