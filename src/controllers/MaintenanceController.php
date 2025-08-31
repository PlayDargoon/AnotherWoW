<?php
// src/controllers/MaintenanceController.php

class MaintenanceController
{
    /**
     * Метод отображения страницы технического обслуживания
     */
    public function index()
    {
        // Определяем заголовок страницы
        $pageTitle = 'Техническое обслуживание';

        // Проверяем статус сервера
        $serverStatus = $this->checkServerStatus('188.113.169.185', 3724);

        // Определяем класс и иконку в зависимости от статуса сервера
        if ($serverStatus === 'Онлайн') {
            $statusClass = 'info _magic _pos1 _side-light';
            $iconPath = '/images/icons/portal_green.png';
        } else {
            $statusClass = 'info _strength _pos2 _side-dark';
            $iconPath = '/images/icons/portal_red.png';
        }

        // Получаем аптайм сервера
        $db = DatabaseConnection::getAuthConnection();
        $uptimeModel = new Uptime($db);
        $startTime = $uptimeModel->getLastStartTime();
        $uptime = $this->calculateUptime($startTime);

          // Получаем количество игроков и игроков онлайн
        $characterModel = new Character(DatabaseConnection::getCharactersConnection());
        $playerCounts = $characterModel->getPlayerCounts();

        // Получаем информацию о игровом мире
        $realmInfo = $uptimeModel->getRealmInfo();

        // Передаем данные в шаблон
        $data = [
            'pageTitle' => $pageTitle,
            'contentFile' => 'pages/Maintenance.html.php', // Добавляем путь к шаблону
            'serverStatus' => $serverStatus, // Передаем статус сервера
            'statusClass' => $statusClass, // Передаем класс для отображения
            'iconPath' => $iconPath, // Передаем путь к иконке
            'uptime' => $uptime, // Передаем аптайм сервера
            'playerCounts' => $playerCounts, // Передаем количество игроков и игроков онлайн
            'realmInfo' => $realmInfo, // Передаем информацию о игровом мире
            
        ];

        // Рендерим шаблон
        renderTemplate('layout.html.php', $data);
    }

    /**
     * Метод проверки статуса сервера
     */
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

    /**
     * Метод вычисления аптайма сервера
     */
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
}