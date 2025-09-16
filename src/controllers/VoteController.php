<?php
// src/controllers/VoteController.php
require_once __DIR__ . '/../helpers/vote_helper.php';

class VoteController {
    public function index() {
    $message = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['username'])) {
            $username = trim($_POST['username']);
            $userModel = new User(\DatabaseConnection::getAuthConnection());
            $userId = $userModel->getUserIdByUsername($username);
            if (!$userId) {
                $message = 'Пользователь не найден.';
            } else {
                require_once __DIR__ . '/../models/VoteReward.php';
                $rewardModel = new \VoteReward(\DatabaseConnection::getSiteConnection());
                $lastVote = $rewardModel->getLastVoteTime($userId);
                $now = time();
                if ($now - $lastVote < 16*3600) {
                    $message = 'Вы уже получали награду за голосование менее 16 часов назад.';
                } else {
                    // Проверяем наличие логина в mmotop-файле
                    $mmotopUrl = 'https://mmotop.ru/votes/d2076181c455574872250afe4ec7fdbed943ce36.txt?775f2967455486d6b7b821ad794dfc15';
                    $mmotopData = @file_get_contents($mmotopUrl);
                    if ($mmotopData && stripos($mmotopData, $username) !== false) {
                        $rewardModel->setLastVoteTime($userId, $now);
                        // Начисляем 5 монет через AccountCoins
                        require_once __DIR__ . '/../models/AccountCoins.php';
                        $coinsModel = new \AccountCoins(\DatabaseConnection::getSiteConnection());
                        $coinsModel->add($userId, 5, 'mmotop vote');
                        // Логируем голосование
                        require_once __DIR__ . '/../models/VoteLog.php';
                        $voteLog = new \VoteLog(\DatabaseConnection::getSiteConnection());
                        $voteLog->add($userId, $username, 5, 'mmotop');
                        $message = 'Спасибо за голос! Вам начислено 5 монет.';
                    } else {
                        $message = 'Голос не найден. Убедитесь, что вы проголосовали и подождали обновления.';
                    }
                }
            }
        }
        renderTemplate('layout.html.php', [
            'contentFile' => 'pages/vote.html.php',
            'message' => $message
        ]);
    }
}
