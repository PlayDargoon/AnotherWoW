<?php
// src/controllers/VoteController.php
require_once __DIR__ . '/../helpers/vote_helper.php';
require_once __DIR__ . '/../services/VoteService.php';

class VoteController {
    public function index() {
        $message = '';
        $isLoggedIn = false;
        
        // Проверяем, залогинен ли пользователь
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] && isset($_SESSION['username'])) {
            $isLoggedIn = true;
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
            'isLoggedIn' => $isLoggedIn,
            'pageTitle' => 'Голосование за сервер',
        ]);
    }
}
