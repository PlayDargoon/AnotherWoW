<?php
// sync_votes.php — автоматическая синхронизация голосов с mmotop
require_once __DIR__ . '/src/services/DatabaseConnection.php';
require_once __DIR__ . '/src/models/User.php';
require_once __DIR__ . '/src/models/VoteLog.php';
require_once __DIR__ . '/src/models/VoteReward.php';

$mmotopUrl = 'https://mmotop.ru/votes/d2076181c455574872250afe4ec7fdbed943ce36.txt?775f2967455486d6b7b821ad794dfc15';
$data = @file_get_contents($mmotopUrl);
if (!$data) {
    echo "Не удалось получить данные с mmotop\n";
    exit(1);
}

$sitePdo = DatabaseConnection::getSiteConnection();
$authPdo = DatabaseConnection::getAuthConnection();
$userModel = new User($authPdo);
$voteLog = new VoteLog($sitePdo);
$rewardModel = new VoteReward($sitePdo);
$authReward = new VoteReward($authPdo);

$lines = explode("\n", $data);
$added = 0;
foreach ($lines as $line) {
    $line = trim($line);
    if (!$line) continue;
    // Формат: username|timestamp
    $parts = explode('|', $line);
    if (count($parts) < 2) continue;
    $username = trim($parts[0]);
    $voteTime = (int)trim($parts[1]);
    $userId = $userModel->getUserIdByUsername($username);
    if (!$userId) continue;
    // Проверяем, есть ли уже такой голос в базе (по user_id и времени)
    $exists = $sitePdo->prepare('SELECT COUNT(*) FROM vote_log WHERE user_id = ? AND vote_time = ?');
    $exists->execute([$userId, $voteTime]);
    if ($exists->fetchColumn() > 0) continue;
    // Добавляем запись в лог и начисляем монету
    $voteLog->add($userId, $username, 1, 'mmotop');
    $authReward->addCoins($userId, 1);
    $rewardModel->setLastVoteTime($userId, $voteTime);
    $added++;
}
echo "Добавлено голосов: $added\n";
