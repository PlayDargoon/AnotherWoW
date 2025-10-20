<?php
// tools/count_real_accounts.php
// Подсчет количества аккаунтов и реальных пользователей (email не пуст) в базе acore_auth.account

require_once __DIR__ . '/../bootstrap.php';

function println($s) { echo $s, PHP_EOL; }

try {
    $auth = DatabaseConnection::getAuthConnection();

    // Поддержка опциональных фильтров по дате (joindate) через CLI-параметры
    // Использование: php tools/count_real_accounts.php --from=2025-10-01 --to=2025-10-31
    $from = null; $to = null;
    $listLogged = false; // опциональный вывод списка тех, кто заходил
    $onlyLoggedCount = false; // режим: вывести только число с email+last_login
    foreach ($argv as $arg) {
        if (preg_match('/^--from=(\d{4}-\d{2}-\d{2})$/', $arg, $m)) { $from = $m[1]; }
        if (preg_match('/^--to=(\d{4}-\d{2}-\d{2})$/', $arg, $m)) { $to = $m[1]; }
        if ($arg === '--list-logged') { $listLogged = true; }
        if ($arg === '--only-logged-count') { $onlyLoggedCount = true; }
    }

    $where = [];
    $params = [];
    // Поле joindate в TrinityCore/azeroth обычно DATETIME
    if ($from) { $where[] = 'joindate >= :from'; $params['from'] = $from . ' 00:00:00'; }
    if ($to) { $where[] = 'joindate <= :to'; $params['to'] = $to . ' 23:59:59'; }
    $whereSql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

    // Общее количество аккаунтов
    $stmt = $auth->prepare("SELECT COUNT(*) FROM account $whereSql");
    $stmt->execute($params);
    $total = (int)$stmt->fetchColumn();

    // Реальные аккаунты: email не пустой и не NULL, и содержит хотя бы символ '@'
    $emailConds = $where; // начальные фильтры (даты)
    $emailConds[] = "email IS NOT NULL";
    $emailConds[] = "email != ''";
    $emailConds[] = "email LIKE '%@%'";
    $realWhereSql = 'WHERE ' . implode(' AND ', $emailConds);
    $stmt = $auth->prepare("SELECT COUNT(*) FROM account $realWhereSql");
    $stmt->execute($params);
    $real = (int)$stmt->fetchColumn();

    // Аккаунты с email и с заходом в игру (last_login установлен)
    $loggedConds = $emailConds;
    // В некоторых инсталляциях last_login может быть '0000-00-00 00:00:00' – исключаем такую "пустую" дату
    $loggedConds[] = "last_login IS NOT NULL";
    // Избегаем нулевой даты в строгом режиме MySQL, используем безопасную нижнюю границу
    $loggedConds[] = "last_login > '1900-01-01 00:00:00'";
    $loggedWhereSql = 'WHERE ' . implode(' AND ', $loggedConds);
    $stmt = $auth->prepare("SELECT COUNT(*) FROM account $loggedWhereSql");
    $stmt->execute($params);
    $withLogin = (int)$stmt->fetchColumn();

    $bots = max(0, $total - $real);
    $realPct = $total > 0 ? round($real * 100 / $total, 2) : 0;
    $botsPct = $total > 0 ? round($bots * 100 / $total, 2) : 0;
    $withLoginPct = $real > 0 ? round($withLogin * 100 / $real, 2) : 0;

    if ($onlyLoggedCount) {
        // Тихий вывод для автоматизаций
        println((string)$withLogin);
        return;
    }

    println("=== Отчет по аккаунтам (таблица account) ===");
    if ($from || $to) {
        println("Интервал: " . ($from ?: '—') . " .. " . ($to ?: '—'));
    }
    println("Всего аккаунтов: $total");
    println("Реальные (email заполнен): $real ({$realPct}%)");
    println("Из них заходили (last_login задан): $withLogin ({$withLoginPct}% от реальных)");
    println("Машины/пустой email: $bots ({$botsPct}%)");

    // Дополнительно: быстрый топ доменов email (первые 10)
    $topConds = $emailConds; // те же условия, что и для real
    $topWhereSql = 'WHERE ' . implode(' AND ', $topConds);
    $topSql = "SELECT LOWER(SUBSTRING_INDEX(email, '@', -1)) AS domain, COUNT(*) cnt
               FROM account
               $topWhereSql
               GROUP BY domain
               ORDER BY cnt DESC
               LIMIT 10";
    $stmt = $auth->prepare($topSql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($rows) {
        println("");
        println("Топ доменов email:");
        foreach ($rows as $r) {
            println(sprintf("- %s: %d", $r['domain'] ?: '(пусто)', (int)$r['cnt']));
        }
    }

    // По запросу выведем список аккаунтов с email и last_login, ограничим топ-100 чтобы не заспамить консоль
    if ($listLogged) {
        println("");
        println("Примеры аккаунтов с email и last_login (первые 100):");
        $listSql = "SELECT id, username, email, last_login
                    FROM account
                    $loggedWhereSql
                    ORDER BY last_login DESC
                    LIMIT 100";
        $stmt = $auth->prepare($listSql);
        $stmt->execute($params);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            println(sprintf("- #%d %s | %s | %s",
                (int)$row['id'],
                (string)$row['username'],
                (string)$row['email'],
                (string)$row['last_login']
            ));
        }
    }

} catch (Throwable $e) {
    fwrite(STDERR, "Ошибка: " . $e->getMessage() . PHP_EOL);
    fwrite(STDERR, "Проверьте настройки соединения в config/database.php и права к базе acore_auth.\n");
    exit(1);
}
