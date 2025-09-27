<?php
// create_vote_tables.php
// Создание таблиц для системы голосования через контроллер миграций
require_once __DIR__ . '/../../bootstrap.php';
require_once __DIR__ . '/src/controllers/MigrationController.php';

$controller = new MigrationController();
$controller->runMigration('CreateVoteTables.php');