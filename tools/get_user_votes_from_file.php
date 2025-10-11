<?php
// tools/get_user_votes_from_file.php
// Использование (CLI): php tools/get_user_votes_from_file.php Admin

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../src/models/VoteTop.php';

$username = $argv[1] ?? null;
if (!$username) {
    fwrite(STDERR, "Usage: php tools/get_user_votes_from_file.php <username>\n");
    exit(1);
}

$vt = new VoteTop(DatabaseConnection::getSiteConnection(), DatabaseConnection::getAuthConnection());
$list = $vt->getTopVotersFromFile(100000); // большой лимит по количеству аккаунтов

$needle = mb_strtolower($username, 'UTF-8');
$found = null;
foreach ($list as $row) {
    if (mb_strtolower($row['username'], 'UTF-8') === $needle) {
        $found = $row; break;
    }
}

if (!$found) {
    echo "User '{$username}' not found in current month file.\n";
    exit(0);
}

echo "Username: {$found['username']}\n";
echo "Account ID: {$found['account_id']}\n";
echo "Vote count (this month): {$found['vote_count']}\n";
echo "Last vote: " . ($found['last_vote'] ?? '-') . "\n";
