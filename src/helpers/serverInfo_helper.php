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

    // Получаем адрес реальма из БД если есть
    $realmInfo = $uptimeModel->getRealmInfo();
    $defaultHost = '91.199.149.28';
    $defaultPort = 3724;
    $host = $defaultHost;
    $port = $defaultPort;
    if (is_array($realmInfo) && !empty($realmInfo['address'])) {
        // Поддерживаем формат 'host:port' или просто 'host'
        if (strpos($realmInfo['address'], ':') !== false) {
            list($h, $p) = explode(':', $realmInfo['address'], 2);
            $host = $h ?: $defaultHost;
            $port = is_numeric($p) ? (int)$p : $defaultPort;
        } else {
            $host = $realmInfo['address'];
        }
    }

    // Проверяем статус сервера (увеличенный таймаут по умолчанию)
    $serverStatus = checkServerStatus($host, $port, 2.0);
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
    // уже получали $realmInfo выше
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

function checkServerStatus($host, $port, $timeout = 2.0) {
    $start = microtime(true);
    $connection = @fsockopen($host, $port, $errno, $errstr, $timeout);
    $duration = microtime(true) - $start;

    $status = $connection ? "Онлайн" : "Оффлайн";

    // Простое логирование для диагностики
    try {
        $logFile = sys_get_temp_dir() . '/awow_server_status.log';
        $line = sprintf("%s | host=%s port=%s status=%s errno=%s errstr=%s dur=%.3f\n",
            date('c'), $host, $port, $status, $errno ?? 0, str_replace("\n", ' ', $errstr ?? ''), $duration);
        @file_put_contents($logFile, $line, FILE_APPEND | LOCK_EX);
    } catch (Exception $e) {
        // ничего — логирование не критично
    }

    if ($connection) {
        fclose($connection);
    }

    return $status;
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
