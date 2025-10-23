<?php
// tools/rename_selfwork_reasons.php
// Скрипт для ретро-замены причины начисления монет после миграции на Selfwork

require_once __DIR__ . '/../bootstrap.php';

try {
    $pdo = DatabaseConnection::getSiteConnection();

    // Подсчет затрагиваемых записей
    $stmt1 = $pdo->prepare("SELECT COUNT(*) FROM account_coins WHERE reason LIKE 'Пополнение Selfwork (%'");
    $stmt1->execute();
    $count1 = (int)$stmt1->fetchColumn();

    $stmt2 = $pdo->prepare("SELECT COUNT(*) FROM account_coins WHERE reason = 'Покупка бонусов'");
    $stmt2->execute();
    $count2 = (int)$stmt2->fetchColumn();

    $total = $count1 + $count2;
    echo "Найдено записей для обновления: $total (из них старый формат: $count1, временный формат: $count2)\n";
    if ($total === 0) exit(0);

    // Обновление причин на лаконичное 'Покупка'
    $upd1 = $pdo->prepare("UPDATE account_coins SET reason = 'Покупка' WHERE reason LIKE 'Пополнение Selfwork (%'");
    $upd1->execute();
    echo "Обновлено строк (старый формат): " . $upd1->rowCount() . "\n";

    $upd2 = $pdo->prepare("UPDATE account_coins SET reason = 'Покупка' WHERE reason = 'Покупка бонусов'");
    $upd2->execute();
    echo "Обновлено строк (временный формат): " . $upd2->rowCount() . "\n";

    echo "Готово.\n";
} catch (Throwable $e) {
    echo "Ошибка: " . $e->getMessage() . "\n";
    exit(1);
}
