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
        // Кэширование главной страницы
        $cacheFile = __DIR__ . '/../../cache/index.cache.html';
        if (file_exists($cacheFile) && filemtime($cacheFile) > time() - 60) {
            // Если кэш актуален, просто выводим его
            echo file_get_contents($cacheFile);
            return;
        }

        // Генерируем данные для шаблона
        $serverStatus = $this->checkServerStatus('91.199.149.28', 3724);
        if ($serverStatus === 'Онлайн') {
            $statusClass = 'info _magic _pos1 _side-light';
            $iconPath = '/images/icons/portal_green.png';
        } else {
            $statusClass = 'info _strength _pos2 _side-dark';
            $iconPath = '/images/icons/portal_red.png';
        }
        $startTime = $this->uptimeModel->getLastStartTime();
        $uptime = $this->calculateUptime($startTime);
        $playerCounts = $this->characterModel->getPlayerCounts();
        $playerCountsByFaction = $this->characterModel->getPlayerCountsByFaction();
        $realmInfo = $this->uptimeModel->getRealmInfo();

        $data = [
            'pageTitle' => 'Главная страница',
            'contentFile' => 'pages/index.html.php',
            'serverStatus' => $serverStatus,
            'statusClass' => $statusClass,
            'iconPath' => $iconPath,
            'uptime' => $uptime,
            'playerCounts' => $playerCounts,
            'playerCount' => isset($playerCounts['total_players']) ? $playerCounts['total_players'] : 0,
            'realmInfo' => $realmInfo,
            'playerCountsByFaction' => $playerCountsByFaction,
        ];

        // Рендерим шаблон и кэшируем результат
        ob_start();
        renderTemplate('layout.html.php', $data);
        $content = ob_get_clean();
        file_put_contents($cacheFile, $content);
        echo $content;
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