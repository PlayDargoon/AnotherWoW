<?php
// src/controllers/AdminOnlineController.php

class AdminOnlineController
{
    public function index()
    {
        // Получаем подключения к базам
        $authPdo = DatabaseConnection::getAuthConnection();
        $charPdo = DatabaseConnection::getCharactersConnection();

        // 1. Получаем аккаунты с email и IP != 127.0.0.1
        $accountsStmt = $authPdo->prepare("SELECT id, username, email, last_ip FROM account WHERE email IS NOT NULL AND email != '' AND last_ip != '127.0.0.1'");
        $accountsStmt->execute();
        $accounts = $accountsStmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($accounts)) {
            renderTemplate('layout.html.php', [
                'contentFile' => 'pages/admin_online.html.php',
                'accountsOnline' => [],
            ]);
            return;
        }

        // 2. Получаем всех онлайн-персонажей для этих аккаунтов одним запросом
        $accountIds = array_column($accounts, 'id');
        $in = str_repeat('?,', count($accountIds) - 1) . '?';
    $charsStmt = $charPdo->prepare("SELECT guid, name, race, class, gender, level, account, totaltime FROM characters WHERE online = 1 AND account IN ($in)");
    $charsStmt->execute($accountIds);
    $characters = $charsStmt->fetchAll(PDO::FETCH_ASSOC);

        // 3. Группируем персонажей по аккаунтам
        $charsByAccount = [];
        foreach ($characters as $char) {
            $charsByAccount[$char['account']][] = $char;
        }

        // 4. Собираем результат
        $result = [];
        foreach ($accounts as $acc) {
            $accId = $acc['id'];
            if (!empty($charsByAccount[$accId])) {
                $acc['characters'] = $charsByAccount[$accId];
                $result[] = $acc;
            }
        }

        renderTemplate('layout.html.php', [
            'contentFile' => 'pages/admin_online.html.php',
            'accountsOnline' => $result,
        ]);
    }
}
