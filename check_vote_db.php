<?php
// check_vote_db.php
// Проверка структуры баз данных через контроллер миграций
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/src/controllers/MigrationController.php';

$controller = new MigrationController();
$controller->checkDatabase();