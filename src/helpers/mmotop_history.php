<?php
// src/helpers/mmotop_history.php
// Парсинг истории голосов из файла mmotop
function getMmotopHistoryForUser($username, $filePath, $limit = 50) {
    if (!file_exists($filePath)) return [];
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $history = [];
    foreach (array_reverse($lines) as $line) {
        $parts = preg_split('/\t+/', $line);
        if (count($parts) < 5) continue;
        // Формат: ID\tДата\tIP\tЛогин\tРезультат
        list($id, $date, $ip, $login, $result) = $parts;
        if (strcasecmp($login, $username) === 0) {
            $history[] = [
                'date' => $date,
                'ip' => $ip,
                'result' => $result,
            ];
            if (count($history) >= $limit) break;
        }
    }
    return $history;
}
