<?php
// database/migrations/CheckDatabase.php
// Проверка структуры баз данных для голосования

require_once __DIR__ . '/../../bootstrap.php';

echo "Проверка структуры баз данных...\n";

try {
    // Проверяем базу auth
    $authDb = DatabaseConnection::getAuthConnection();
    echo "✓ Подключение к базе auth успешно\n";
    
    $tables = $authDb->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "Таблицы в auth: " . implode(', ', $tables) . "\n";
    
    // Проверяем базу site
    $siteDb = DatabaseConnection::getSiteConnection();
    echo "✓ Подключение к базе site успешно\n";
    
    $tables = $siteDb->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "Таблицы в site: " . implode(', ', $tables) . "\n";
    
    // Проверяем базу characters если есть
    try {
        $charDb = DatabaseConnection::getCharactersConnection();
        echo "✓ Подключение к базе characters успешно\n";
        
        $tables = $charDb->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        echo "Таблицы в characters: " . count($tables) . " таблиц\n";
    } catch (Exception $e) {
        echo "Базы characters нет или недоступна\n";
    }
    
} catch (Exception $e) {
    echo "Ошибка: " . $e->getMessage() . "\n";
}