<?php
// src/controllers/VoteTopController.php
require_once __DIR__ . '/../models/VoteTop.php';

class VoteTopController {
    public function index() {
        $voteTopModel = new VoteTop(
            \DatabaseConnection::getSiteConnection(),
            \DatabaseConnection::getAuthConnection()
        );
        
    // Получаем топ и статистику за текущий месяц ТОЛЬКО из файла MMOTOP
    $topVoters = $voteTopModel->getTopVotersFromFile(10);
    $monthlyStats = $voteTopModel->getMonthlyStatisticsFromFile();
        
        // Для отладки: логируем количество найденных голосующих
        error_log("VoteTopController: Found " . count($topVoters) . " voters this month");
        
        renderTemplate('layout.html.php', [
            'contentFile' => 'pages/vote-top.html.php',
            'topVoters' => $topVoters,
            'monthlyStats' => $monthlyStats,
            'pageTitle' => 'Топ голосующих за месяц',
        ]);
    }
}