<?php
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/src/services/DatabaseConnection.php';
require_once __DIR__ . '/database/migrations/CreateNotificationsTable.php';

try {
    echo "Создание таблицы уведомлений...\n";
    
    $migration = new CreateNotificationsTable();
    $migration->up();
    
    echo "Миграция выполнена успешно!\n";
    
} catch (Exception $e) {
    echo "Ошибка: " . $e->getMessage() . "\n";
    echo "Трассировка: " . $e->getTraceAsString() . "\n";
}