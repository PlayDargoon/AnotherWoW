<?php
// sync_mmotop_votes.php
// Синхронизация голосов из mmotop.txt в account_coins
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/src/helpers/mmotop_history.php';
require_once __DIR__ . '/src/models/AccountCoins.php';
require_once __DIR__ . '/src/models/User.php';


$mmotopUrl = 'https://mmotop.ru/votes/d2076181c455574872250afe4ec7fdbed943ce36.txt?cc6d51c7ef76769b41a14606f3e0a6ed';
$siteDb = DatabaseConnection::getSiteConnection();
$authDb = DatabaseConnection::getAuthConnection();
$userModel = new User($authDb);
$coinsModel = new AccountCoins($siteDb);

$txt = @file_get_contents($mmotopUrl);
if ($txt === false) {
    echo "Ошибка загрузки файла mmotop.txt по URL!\n";
    exit(1);
}
$lines = preg_split('/\r?\n/', $txt, -1, PREG_SPLIT_NO_EMPTY);
$imported = 0;
foreach ($lines as $line) {
    $parts = preg_split('/\t+/', $line);
    if (count($parts) < 5) continue;
    list($id, $date, $ip, $login, $result) = $parts;
    $accountId = $userModel->getUserIdByUsername($login);
    if (!$accountId) continue;
    // Преобразуем дату в формат MySQL
    $dateMysql = null;
    if (preg_match('/^(\d{2})\.(\d{2})\.(\d{4}) (\d{2}):(\d{2}):(\d{2})$/', $date, $m)) {
        $dateMysql = "$m[3]-$m[2]-$m[1] $m[4]:$m[5]:$m[6]";
    } else {
        // Если формат не совпал, пропускаем
        continue;
    }
    // Проверяем, есть ли уже такая запись (по account_id и дате)
    $stmt = $siteDb->prepare("SELECT COUNT(*) FROM account_coins WHERE account_id = ? AND created_at = ?");
    $stmt->execute([$accountId, $dateMysql]);
    if ($stmt->fetchColumn() > 0) continue;
    // Добавляем начисление
    $stmt = $siteDb->prepare("INSERT INTO account_coins (account_id, coins, reason, created_at) VALUES (?, ?, ?, ?)");
    $stmt->execute([$accountId, 1, 'Голос (mmotop.txt)', $dateMysql]);
    $imported++;
}
echo "Импортировано новых голосов: $imported\n";
