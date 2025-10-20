<?php
// tools/check_paladins.php
require_once __DIR__ . '/../src/services/DatabaseConnection.php';

$pdo = DatabaseConnection::getCharactersConnection();

// Класс 2 = Паладин
$stmt = $pdo->query('SELECT name, level, race, class FROM characters WHERE class = 2 AND level = 80 ORDER BY name LIMIT 50');
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($rows)) {
    echo "Паладинов 80 уровня на сервере нет\n";
} else {
    echo "Найдено паладинов 80 уровня: " . count($rows) . "\n\n";
    foreach ($rows as $r) {
        echo sprintf("%-20s - уровень %d, раса %d\n", $r['name'], $r['level'], $r['race']);
    }
}

// Также проверим общее количество паладинов
$stmt2 = $pdo->query('SELECT COUNT(*) as total FROM characters WHERE class = 2');
$total = $stmt2->fetch(PDO::FETCH_ASSOC);
echo "\nВсего паладинов на сервере: " . $total['total'] . "\n";
?>
