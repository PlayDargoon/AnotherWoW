<?php
// migrate_vote_log.php
require_once __DIR__ . '/src/services/DatabaseConnection.php';
require_once __DIR__ . '/src/models/VoteLog.php';

$pdo = DatabaseConnection::getSiteConnection();
$voteLog = new VoteLog($pdo);
$voteLog->migrate();
echo "Таблица vote_log успешно создана (или уже существует)!\n";
