<?php
// check-database.php

// Подключаем службу подключения к базе данных
require_once __DIR__ . '/src/services/DatabaseConnection.php';

// Список баз данных для проверки
$databasesToCheck = ['acore_auth', 'acore_world', 'acore_characters'];

foreach ($databasesToCheck as $database) {
    try {
        // Получаем подключение к базе данных
        $pdo = DatabaseConnection::getConnection($database);

        // Получаем информацию о подключении
        echo "Подключение к базе данных \"$database\": ";
        var_dump($pdo->getAttribute(PDO::ATTR_CONNECTION_STATUS));
        echo "\n";

        // Выполняем простой запрос
        $statement = $pdo->query("SELECT 1 AS result");
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result !== false && isset($result['result']) && $result['result'] === 1) {
            echo "✅ Подключение к базе данных \"$database\" успешно.\n";
        } else {
            echo "❌ Ошибка при выполнении запроса к базе данных \"$database\": результат не соответствует ожидаемому.\n";
        }
    } catch (PDOException $e) {
        echo "❌ Ошибка при подключении к базе данных \"$database\": " . $e->getMessage() . "\n";
    }
}