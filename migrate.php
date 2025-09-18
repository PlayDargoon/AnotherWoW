<?php
// migrate.php
// Универсальный скрипт для управления миграциями
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/src/services/DatabaseConnection.php';
require_once __DIR__ . '/src/controllers/MigrationController.php';

$controller = new MigrationController();

if ($argc < 2) {
    echo "Использование:\n";
    echo "  php migrate.php run-all              - Запустить все миграции\n";
    echo "  php migrate.php run <название>       - Запустить конкретную миграцию\n";
    echo "  php migrate.php check                - Проверить состояние БД\n";
    echo "  php migrate.php create <название>    - Создать новую миграцию\n";
    exit(1);
}

$command = $argv[1];

switch ($command) {
    case 'run-all':
        $controller->runAll();
        break;
        
    case 'run':
        if ($argc < 3) {
            echo "Ошибка: укажите название миграции\n";
            exit(1);
        }
        $fileName = $argv[2];
        if (substr($fileName, -4) !== '.php') {
            $fileName .= '.php';
        }
        $controller->runMigration($fileName);
        break;
        
    case 'check':
        $controller->checkDatabase();
        break;
        
    case 'create':
        if ($argc < 3) {
            echo "Ошибка: укажите название миграции\n";
            exit(1);
        }
        $name = implode(' ', array_slice($argv, 2));
        $controller->createMigration($name);
        break;
        
    default:
        echo "Неизвестная команда: $command\n";
        exit(1);
}