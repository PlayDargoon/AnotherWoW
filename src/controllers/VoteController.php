<?php
// src/controllers/VoteController.php
require_once __DIR__ . '/../helpers/vote_helper.php';

class VoteController {
    public static function handle() {
        $pollId = 2; // id для "Нравится ли вам наш сайт"
        $hasVoted = hasVoted($pollId);
        $voteData = getVoteData($pollId);
        $message = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vote_answer']) && !$hasVoted) {
            $answerId = (int)$_POST['vote_answer'];
            if (saveVote($pollId, $answerId)) {
                header('Location: ' . $_SERVER['REQUEST_URI']);
                exit;
            } else {
                $message = 'Вы уже голосовали!';
            }
        }
        // Возвращаем данные для шаблона
        return [
            'voteData' => $voteData,
            'hasVoted' => hasVoted($pollId),
            'message' => $message
        ];
    }
}
