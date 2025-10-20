<?php
// tools/check_achievement_465.php
require_once __DIR__ . '/../src/services/DatabaseConnection.php';

$pdo = DatabaseConnection::getCharactersConnection();

// Проверяем таблицу character_achievement для достижения 465
echo "=== Проверка достижения ID 465 ===\n\n";

$stmt = $pdo->prepare('SELECT * FROM character_achievement WHERE achievement = 465 LIMIT 10');
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($rows)) {
    echo "Достижение 465 не найдено ни у кого\n\n";
} else {
    echo "Найдено записей: " . count($rows) . "\n";
    foreach ($rows as $r) {
        print_r($r);
    }
}

// Проверяем первого паладина 80 уровня
echo "=== Первый паладин 80 уровня ===\n\n";
$stmt2 = $pdo->query('SELECT guid, name, level, race, class FROM characters WHERE class = 2 AND level = 80 ORDER BY guid ASC LIMIT 1');
$paladin = $stmt2->fetch(PDO::FETCH_ASSOC);

if ($paladin) {
    echo "Имя: {$paladin['name']}\n";
    echo "GUID: {$paladin['guid']}\n";
    echo "Уровень: {$paladin['level']}\n";
    echo "Раса: {$paladin['race']}\n";
    echo "Класс: {$paladin['class']} (Паладин)\n\n";
    
    // Проверяем достижения этого паладина
    echo "=== Достижения этого персонажа (первые 20) ===\n";
    $stmt3 = $pdo->prepare('SELECT achievement FROM character_achievement WHERE guid = ? ORDER BY achievement LIMIT 20');
    $stmt3->execute([$paladin['guid']]);
    $achievements = $stmt3->fetchAll(PDO::FETCH_COLUMN);
    echo "Всего достижений: " . count($achievements) . "\n";
    echo "ID достижений: " . implode(', ', $achievements) . "\n";
}

?>
