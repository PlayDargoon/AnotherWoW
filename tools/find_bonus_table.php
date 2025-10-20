<?php
require_once __DIR__ . '/../bootstrap.php';

try {
    echo "=== ПОИСК ТАБЛИЦ С БАЛАНСОМ БОНУСОВ ===\n\n";
    
    // Проверим сайтовую БД
    $sitePdo = DatabaseConnection::getSiteConnection();
    
    // Получим список всех таблиц в сайтовой БД
    $stmt = $sitePdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "Таблицы в сайтовой базе данных:\n";
    echo "===============================\n";
    foreach ($tables as $table) {
        echo "- $table\n";
    }
    
    // Поиск таблиц, которые могут содержать баланс
    echo "\n=== АНАЛИЗ ТАБЛИЦ С ВОЗМОЖНЫМ БАЛАНСОМ ===\n";
    
    $possibleTables = [];
    foreach ($tables as $table) {
        if (stripos($table, 'coin') !== false || 
            stripos($table, 'balance') !== false || 
            stripos($table, 'money') !== false || 
            stripos($table, 'vote') !== false ||
            stripos($table, 'user') !== false ||
            stripos($table, 'account') !== false) {
            $possibleTables[] = $table;
        }
    }
    
    foreach ($possibleTables as $table) {
        echo "\n📊 Анализ таблицы: $table\n";
        echo "=" . str_repeat("=", strlen($table) + 16) . "\n";
        
        try {
            // Структура таблицы
            $stmt = $sitePdo->query("DESCRIBE `$table`");
            $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo "Структура:\n";
            foreach ($columns as $column) {
                echo "  - " . $column['Field'] . " (" . $column['Type'] . ")\n";
            }
            
            // Количество записей
            $stmt = $sitePdo->query("SELECT COUNT(*) as count FROM `$table`");
            $count = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "Записей: " . $count['count'] . "\n";
            
            // Примеры данных (если есть записи)
            if ($count['count'] > 0) {
                $stmt = $sitePdo->query("SELECT * FROM `$table` LIMIT 3");
                $examples = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                echo "Примеры данных:\n";
                foreach ($examples as $i => $row) {
                    echo "  Запись " . ($i + 1) . ": ";
                    $parts = [];
                    foreach ($row as $key => $value) {
                        $parts[] = "$key=$value";
                    }
                    echo implode(", ", array_slice($parts, 0, 4)) . "\n";
                }
            }
            
        } catch (Exception $e) {
            echo "Ошибка анализа таблицы $table: " . $e->getMessage() . "\n";
        }
    }
    
    // Поиск записей с балансом 28
    echo "\n=== ПОИСК ЗАПИСЕЙ С БАЛАНСОМ 28 ===\n";
    foreach ($possibleTables as $table) {
        try {
            $stmt = $sitePdo->query("DESCRIBE `$table`");
            $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            // Ищем числовые поля
            foreach ($columns as $column) {
                try {
                    $stmt = $sitePdo->prepare("SELECT * FROM `$table` WHERE `$column` = 28 LIMIT 3");
                    $stmt->execute();
                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    if (!empty($results)) {
                        echo "\n🎯 Найдены записи со значением 28 в таблице $table, колонка $column:\n";
                        foreach ($results as $result) {
                            $parts = [];
                            foreach ($result as $key => $value) {
                                $parts[] = "$key=$value";
                            }
                            echo "  " . implode(", ", array_slice($parts, 0, 5)) . "\n";
                        }
                    }
                } catch (Exception $e) {
                    // Игнорируем ошибки типов данных
                }
            }
        } catch (Exception $e) {
            echo "Ошибка поиска в таблице $table: " . $e->getMessage() . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "Общая ошибка: " . $e->getMessage() . "\n";
}

