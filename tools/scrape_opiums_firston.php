<?php
// tools/scrape_opiums_firston.php
// Скрипт скачивает страницы базы достижений и ищет ID достижений с текстом "1-й на сервере" или "Впервые на сервере"

function fetchPage($url) {
    $opts = [
        'http' => [
            'method' => 'GET',
            'header' => "User-Agent: Mozilla/5.0 (compatible; scraper/1.0)\r\n",
            'timeout' => 10,
        ]
    ];
    $ctx = stream_context_create($opts);
    return @file_get_contents($url, false, $ctx);
}

$base = 'https://base.opiums.eu/?achievements=81';
$pages = [$base, $base . '&start=100'];
$found = [];

foreach ($pages as $url) {
    echo "Fetching: $url\n";
    $html = fetchPage($url);
    if (!$html) {
        echo "Failed to fetch $url\n";
        continue;
    }

    // Найти все элементы таблицы — простая обработка: найдём строки, содержащие "1-й на сервере" или "Впервые на сервере"
    // Затем попробуем из той же строки извлечь achievement id из href
    $lines = preg_split('/\r?\n/', $html);
    foreach ($lines as $ln) {
        if (mb_stripos($ln, '1-й на сервере') !== false || mb_stripos($ln, 'Впервые на сервере') !== false) {
            // Ищем ссылку с параметром achievement=ID
            if (preg_match_all('/[?&]achievement=(\d+)/', $ln, $m)) {
                foreach ($m[1] as $id) {
                    $title = strip_tags($ln);
                    $title = preg_replace('/\s+/', ' ', trim($title));
                    $found[(int)$id] = $title;
                }
                continue;
            }
            // Ищем /achievement/ID
            if (preg_match_all('/\/achievement\/(\d+)/', $ln, $m2)) {
                foreach ($m2[1] as $id) {
                    $title = strip_tags($ln);
                    $title = preg_replace('/\s+/', ' ', trim($title));
                    $found[(int)$id] = $title;
                }
                continue;
            }
            // Если id не найдено в ссылках, попробуем найти data-id="" или data-achievement
            if (preg_match_all('/data[-_]id="?(\d+)"?/', $ln, $m3)) {
                foreach ($m3[1] as $id) {
                    $title = strip_tags($ln);
                    $title = preg_replace('/\s+/', ' ', trim($title));
                    $found[(int)$id] = $title;
                }
            }
        }
    }
}

if (empty($found)) {
    echo "No IDs found on pages (static HTML may not contain links). Try opening site in a browser or provide sample HTML.\n";
    exit(1);
}

ksort($found);
echo "Found achievements (id => title preview):\n";
foreach ($found as $id => $title) {
    echo "$id => $title\n";
}

echo "\nIDs only:\n" . implode(', ', array_keys($found)) . "\n";

?>
