<?php
// tools/add_achievement_to_db.php
require_once __DIR__ . '/../src/services/DatabaseConnection.php';

$charDb = DatabaseConnection::getCharactersConnection();

$characterName = 'Коллапс';
$achievementId = 465;
$characterGuid = 6509; // GUID персонажа Коллапс

echo "Добавляю достижение {$achievementId} персонажу {$characterName} (GUID: {$characterGuid})...\n\n";

try {
    // Проверяем, есть ли уже это достижение
    $stmt = $charDb->prepare('SELECT * FROM character_achievement WHERE guid = ? AND achievement = ?');
    $stmt->execute([$characterGuid, $achievementId]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($existing) {
        echo "⚠️ Достижение {$achievementId} уже есть у персонажа {$characterName}\n";
        print_r($existing);
        exit;
    }
    
    // Определяем структуру таблицы
    $columns = $charDb->query("DESCRIBE character_achievement")->fetchAll(PDO::FETCH_COLUMN, 0);
    echo "Доступные колонки в character_achievement: " . implode(', ', $columns) . "\n\n";
    
    // Добавляем достижение
    $timestamp = time();
    
    if (in_array('date', $columns)) {
        // Если есть поле date
        $stmt = $charDb->prepare('INSERT INTO character_achievement (guid, achievement, date) VALUES (?, ?, ?)');
        $stmt->execute([$characterGuid, $achievementId, $timestamp]);
    } else {
        // Минимальный набор полей
        $stmt = $charDb->prepare('INSERT INTO character_achievement (guid, achievement) VALUES (?, ?)');
        $stmt->execute([$characterGuid, $achievementId]);
    }
    
    echo "✅ Достижение {$achievementId} успешно добавлено персонажу {$characterName}!\n";
    echo "Дата: " . date('Y-m-d H:i:s', $timestamp) . "\n\n";
    
    // Проверяем результат
    $stmt = $charDb->prepare('SELECT * FROM character_achievement WHERE guid = ? AND achievement = ?');
    $stmt->execute([$characterGuid, $achievementId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "Проверка записи:\n";
    print_r($result);
    
} catch (Exception $e) {
    echo "❌ Ошибка: " . $e->getMessage() . "\n";
}

?>
