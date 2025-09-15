<?php
// src/controllers/IndexController.php

class IndexController
{
    private $characterModel;
    private $uptimeModel;

    public function __construct(Character $characterModel, Uptime $uptimeModel)
    {
        $this->characterModel = $characterModel;
        $this->uptimeModel = $uptimeModel;
    }

    public function index()
    {
        // Проверяем статус сервера
        $serverStatus = $this->checkServerStatus('91.199.149.28', 3724);

        // Определяем класс и иконку в зависимости от статуса сервера
        if ($serverStatus === 'Онлайн') {
            $statusClass = 'info _magic _pos1 _side-light';
            $iconPath = '/images/icons/portal_green.png';
        } else {
            $statusClass = 'info _strength _pos2 _side-dark';
            $iconPath = '/images/icons/portal_red.png';
        }

        // Получаем аптайм сервера
        $startTime = $this->uptimeModel->getLastStartTime();
        $uptime = $this->calculateUptime($startTime);

        // Получаем количество игроков и игроков онлайн
        $playerCounts = $this->characterModel->getPlayerCounts();
        $playerCountsByFaction = $this->characterModel->getPlayerCountsByFaction();

        // Получаем информацию о игровом мире
        $realmInfo = $this->uptimeModel->getRealmInfo();

        // Передаем данные в шаблон
        $data = [
            'pageTitle' => 'Главная страница',
            'contentFile' => 'pages/index.html.php', // Добавляем путь к шаблону
            'serverStatus' => $serverStatus,
            'statusClass' => $statusClass,
            'iconPath' => $iconPath,
            'uptime' => $uptime,
            'playerCounts' => $playerCounts,
            'realmInfo' => $realmInfo,
            'playerCountsByFaction' => $playerCountsByFaction,
        ];

        // Рендерим шаблон
        renderTemplate('layout.html.php', $data);
    }

    // Метод для проверки статуса сервера
    private function checkServerStatus($host, $port)
    {
        // Пытаемся установить соединение с сервером
        $connection = @fsockopen($host, $port, $errno, $errstr, 0.5); // 0.5 секунд таймаута

        if ($connection) {
            // Если соединение установлено, закрываем его и возвращаем "Онлайн"
            fclose($connection);
            return "Онлайн";
        } else {
            // Если соединение не установлено, возвращаем "Оффлайн"
            return "Оффлайн";
        }
    }

    // Метод для вычисления аптайма сервера
    private function calculateUptime($startTime)
    {
        $currentTime = time();
        $uptime = $currentTime - $startTime;

        $days = floor($uptime / (60 * 60 * 24));
        $hours = floor(($uptime % (60 * 60 * 24)) / (60 * 60));
        $minutes = floor(($uptime % (60 * 60)) / 60);
        $seconds = $uptime % 60;

        return [
            'days' => $days,
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds,
        ];
    }

    // ... (другие методы контроллера)
}