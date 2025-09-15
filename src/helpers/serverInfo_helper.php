<?php
// src/helpers/serverInfo_helper.php

require_once __DIR__ . '/../models/Character.php';
require_once __DIR__ . '/../models/Uptime.php';

function getServerInfo($characterModel, $uptimeModel) {
    $cacheFile = sys_get_temp_dir() . '/awow_serverinfo_cache.json';
    $cacheTtl = 30; // секунд
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheTtl)) {
        $data = json_decode(file_get_contents($cacheFile), true);
        if (is_array($data)) return $data;
    }

    // Проверяем статус сервера
    $serverStatus = checkServerStatus('91.199.149.28', 3724);
    if ($serverStatus === 'Онлайн') {
        $statusClass = 'info _magic _pos1 _side-light';
        $iconPath = '/images/icons/portal_green.png';
    } else {
        $statusClass = 'info _strength _pos2 _side-dark';
        $iconPath = '/images/icons/portal_red.png';
    }
    $startTime = $uptimeModel->getLastStartTime();
    $uptime = calculateUptime($startTime);
    $playerCounts = $characterModel->getPlayerCounts();
    $playerCountsByFaction = $characterModel->getPlayerCountsByFaction();
    $realmInfo = $uptimeModel->getRealmInfo();
    $result = [
        'serverStatus' => $serverStatus,
        'statusClass' => $statusClass,
        'iconPath' => $iconPath,
        'uptime' => $uptime,
        'playerCounts' => $playerCounts,
        'playerCountsByFaction' => $playerCountsByFaction,
        'realmInfo' => $realmInfo,
    ];
    file_put_contents($cacheFile, json_encode($result));
    return $result;
}

function checkServerStatus($host, $port) {
    $connection = @fsockopen($host, $port, $errno, $errstr, 0.5);
    if ($connection) {
        fclose($connection);
        return "Онлайн";
    } else {
        return "Оффлайн";
    }
}

function calculateUptime($startTime) {
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
