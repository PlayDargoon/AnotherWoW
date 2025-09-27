<?php
// sync_votes.php — автоматическая синхронизация голосов с mmotop через контроллер
require_once __DIR__ . '/../../bootstrap.php';
require_once __DIR__ . '/src/controllers/VoteSyncController.php';

echo "Запуск синхронизации голосов...\n";

$controller = new VoteSyncController();
$success = $controller->syncFromCli();

if ($success) {
    echo "Синхронизация завершена успешно.\n";
} else {
    echo "Синхронизация завершена с ошибками.\n";
    exit(1);
}
