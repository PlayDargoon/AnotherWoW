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

        // 4. Собираем результат с форматированием персонажей
        $classColors = [
            1 => '#C69B6D', // Воин
            2 => '#F48CBA', // Паладин
            3 => '#AAD372', // Охотник
            4 => '#FFF468', // Разбойник
            5 => '#FFFFFF', // Жрец
            6 => '#C41E3A', // Рыцарь Смерти
            7 => '#0070DD', // Шаман
            8 => '#3FC7EB', // Маг
            9 => '#8788EE', // Чернокнижник
            10 => '#00FF98', // Монах
            11 => '#FF7C0A', // Друид
            12 => '#A330C9', // Охотник на Демонов
            13 => '#33937F', // Пробуждающий (Evoker)
        ];
        $result = [];
        foreach ($accounts as $acc) {
            $accId = $acc['id'];
            if (!empty($charsByAccount[$accId])) {
                $chars = [];
                foreach ($charsByAccount[$accId] as $char) {
                    $char['classColor'] = isset($classColors[$char['class']]) ? $classColors[$char['class']] : '#FFF';
                    $tt = isset($char['totaltime']) ? (int)$char['totaltime'] : 0;
                    $days = floor($tt / 86400);
                    $hours = floor(($tt % 86400) / 3600);
                    $minutes = floor(($tt % 3600) / 60);
                    $char['playtime'] = ($days > 0 ? $days.'д ' : '') . ($hours > 0 ? $hours.'ч ' : '') . $minutes.'м';
                    $chars[] = $char;
                }
                $acc['characters'] = $chars;
                $result[] = $acc;
            }
        }

        renderTemplate('layout.html.php', [
            'contentFile' => 'pages/admin_online.html.php',
            'accountsOnline' => $result,
        ]);
    }
}
