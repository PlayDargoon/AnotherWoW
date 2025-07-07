<?php
// src/services/DatabaseConnection.php

class DatabaseConnection
{
    private static $connections = [];

    private function __construct() {} // Предотвращаем создание экземпляра класса

    // Получаем экземпляр подключения к базе данных auth
    public static function getAuthConnection(): PDO
    {
        return self::getConnection('acore_auth');
    }

    // Получаем экземпляр подключения к базе данных world
    public static function getWorldConnection(): PDO
    {
        return self::getConnection('acore_world');
    }

    // Получаем экземпляр подключения к базе данных characters
    public static function getCharactersConnection(): PDO
    {
        return self::getConnection('acore_characters');
    }

    // Внутренний метод для получения подключения
    private static function getConnection(string $database): PDO
    {
        if (!isset(self::$connections[$database])) {
            $config = require __DIR__ . '/../../config/database.php';

            $dsn = "{$config['driver']}:host={$config['host']};dbname=$database";

            self::$connections[$database] = new PDO(
                $dsn,
                $config['username'],
                $config['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        }

        return self::$connections[$database];
    }
}