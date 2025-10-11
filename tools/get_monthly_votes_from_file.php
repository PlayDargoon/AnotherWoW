<?php
// tools/get_monthly_votes_from_file.php
// Выводит количество голосований за текущий месяц по файлу MMOTOP

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../src/models/VoteTop.php';

$vt = new VoteTop(DatabaseConnection::getSiteConnection(), DatabaseConnection::getAuthConnection());
$stats = $vt->getMonthlyStatisticsFromFile();

$count = (int)($stats['total_vote_records'] ?? 0);
echo $count, "\n";
