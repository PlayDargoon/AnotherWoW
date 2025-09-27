<?php
// src/controllers/VoteTopController.php
require_once __DIR__ . '/../models/VoteTop.php';

class VoteTopController {
    public function index() {
        $voteTopModel = new VoteTop(
            \DatabaseConnection::getSiteConnection(),
            \DatabaseConnection::getAuthConnection()
        );
        
        // Используем улучшенный метод, который показывает только существующие аккаунты
        $topVoters = $voteTopModel->getTopVoters(10); // Получаем топ 10 голосующих
        
        renderTemplate('layout.html.php', [
            'contentFile' => 'pages/vote-top.html.php',
            'topVoters' => $topVoters,
            'pageTitle' => 'Топ голосующих',
        ]);
    }
}