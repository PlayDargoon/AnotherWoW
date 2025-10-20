<?php
$config = include 'config/database.php';
try {
    // Пробуем разные базы данных
    $databases = ['acore_site', 'acore_world', 'acore_auth'];
    $siteDb = null;
    
    foreach ($databases as $dbname) {
        try {
            $testDb = new PDO(
                'mysql:host=' . $config['host'] . ';port=' . $config['port'][$dbname] . ';dbname=' . $dbname . ';charset=utf8',
                $config['username'],
                $config['password']
            );
            $testDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Проверяем существование таблицы votes
            $checkQuery = "SHOW TABLES LIKE 'votes'";
            $stmt = $testDb->prepare($checkQuery);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                echo "Таблица votes найдена в базе: " . $dbname . PHP_EOL;
                $siteDb = $testDb;
                break;
            }
        } catch (Exception $e) {
            continue;
        }
    }
    
    if (!$siteDb) {
        echo "Таблица votes не найдена ни в одной базе данных" . PHP_EOL;
        return;
    }
    
    $query = "SELECT COUNT(*) as total FROM votes WHERE YEAR(vote_date) = 2025 AND MONTH(vote_date) = 10";
    $stmt = $siteDb->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo 'Всего голосов в БД за октябрь: ' . $result['total'] . PHP_EOL;
    
    $query2 = "SELECT username, COUNT(*) as votes, SUM(coin_reward) as coins FROM votes WHERE YEAR(vote_date) = 2025 AND MONTH(vote_date) = 10 GROUP BY username ORDER BY coins DESC";
    $stmt2 = $siteDb->prepare($query2);
    $stmt2->execute();
    $users = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Топ пользователей:\n";
    $totalVotes = 0;
    foreach ($users as $user) {
        echo $user['username'] . ': ' . $user['votes'] . ' голосов, ' . $user['coins'] . ' монет' . PHP_EOL;
        $totalVotes += $user['votes'];
    }
    echo "Сумма голосов по пользователям: " . $totalVotes . PHP_EOL;
    
    // Проверим последние записи
    echo "\nПоследние 15 записей:\n";
    $query3 = "SELECT id, vote_date, username, coin_reward FROM votes WHERE YEAR(vote_date) = 2025 AND MONTH(vote_date) = 10 ORDER BY vote_date DESC LIMIT 15";
    $stmt3 = $siteDb->prepare($query3);
    $stmt3->execute();
    $recent = $stmt3->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($recent as $vote) {
        echo $vote['id'] . "\t" . $vote['vote_date'] . "\t" . $vote['username'] . "\t" . $vote['coin_reward'] . PHP_EOL;
    }
    
} catch (Exception $e) {
    echo 'Ошибка: ' . $e->getMessage() . PHP_EOL;
}
?>