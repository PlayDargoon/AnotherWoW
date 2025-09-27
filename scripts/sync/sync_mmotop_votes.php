<?php
// sync_mmotop_votes.php
// Синхронизация голосов с mmotop.ru через контроллер
require_once __DIR__ . '/../../bootstrap.php';
require_once __DIR__ . '/src/controllers/VoteSyncController.php';

$controller = new VoteSyncController();
$success = $controller->syncFromCli();

exit($success ? 0 : 1);
