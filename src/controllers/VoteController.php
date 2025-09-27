<?php
// src/controllers/VoteController.php
require_once __DIR__ . '/../helpers/vote_helper.php';
require_once __DIR__ . '/../services/VoteService.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/AccountCoins.php';

class VoteController {
    public function index() {
        $message = '';
        $coins = 0;
        $isLoggedIn = false;
        
        // Проверяем, залогинен ли пользователь, и получаем баланс монет
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] && isset($_SESSION['username'])) {
            $isLoggedIn = true;
            $userModel = new User(\DatabaseConnection::getAuthConnection()); // Используем AUTH соединение для таблицы account
            $userInfo = $userModel->getUserInfoByUsername($_SESSION['username']);
            if ($userInfo) {
                $coinsModel = new \AccountCoins(\DatabaseConnection::getSiteConnection()); // SITE соединение для таблицы account_coins
                $coins = $coinsModel->getBalance($userInfo['id']);
            }
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['username'])) {
            $username = trim($_POST['username']);
            $voteService = new VoteService();
            
            $result = $voteService->rewardManualVote($username);
            
            if ($result['success']) {
                $message = $result['message'];
            } else {
                $message = $result['reason'];
            }
        }
        
        renderTemplate('layout.html.php', [
            'contentFile' => 'pages/vote.html.php',
            'message' => $message,
            'coins' => $coins,
            'isLoggedIn' => $isLoggedIn,
            'pageTitle' => 'Голосование за сервер',
        ]);
    }
}
