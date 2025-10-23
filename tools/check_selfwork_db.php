<?php
require_once __DIR__ . '/../src/services/DatabaseConnection.php';

$pdo = DatabaseConnection::getSiteConnection();

$row = $pdo->query("SELECT yk_id,status,amount FROM payments ORDER BY id DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
echo "Last payment: ".json_encode($row, JSON_UNESCAPED_UNICODE)."\n";

$s = $pdo->prepare('SELECT SUM(coins) as c FROM account_coins WHERE account_id = ?');
$s->execute([123]);
$coins = (int)($s->fetch(PDO::FETCH_ASSOC)['c'] ?? 0);
echo "Coins for account 123: {$coins}\n";
