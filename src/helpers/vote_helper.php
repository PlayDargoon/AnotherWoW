<?php
// src/helpers/vote_helper.php
require_once __DIR__ . '/../services/DatabaseConnection.php';

function getVoteData($pollId = 2) {
    $pdo = DatabaseConnection::getSiteConnection();
    // Получить сам опрос
    $stmt = $pdo->prepare('SELECT * FROM polls WHERE id = ?');
    $stmt->execute([$pollId]);
    $poll = $stmt->fetch();
    if (!$poll) return null;
    // Получить варианты ответа
    $stmt = $pdo->prepare('SELECT * FROM poll_answer WHERE poll_id = ?');
    $stmt->execute([$pollId]);
    $answers = $stmt->fetchAll();
    // Получить общее число голосов
    $totalVotes = 0;
    foreach ($answers as $a) $totalVotes += $a['votes'];
    return [
        'poll' => $poll,
        'answers' => $answers,
        'totalVotes' => $totalVotes
    ];
}

function hasVoted($pollId = 2) {
    $pdo = DatabaseConnection::getSiteConnection();
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM poll_ip WHERE poll_id = ? AND ip = ?');
    $stmt->execute([$pollId, $ip]);
    return $stmt->fetchColumn() > 0;
}

function saveVote($pollId, $answerId) {
    $pdo = DatabaseConnection::getSiteConnection();
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    // Проверка на повторное голосование
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM poll_ip WHERE poll_id = ? AND ip = ?');
    $stmt->execute([$pollId, $ip]);
    if ($stmt->fetchColumn() > 0) return false;
    // Увеличить счетчик голосов
    $stmt = $pdo->prepare('UPDATE poll_answer SET votes = votes + 1 WHERE id = ? AND poll_id = ?');
    $stmt->execute([$answerId, $pollId]);
    // Сохранить ip
    $stmt = $pdo->prepare('INSERT INTO poll_ip (poll_id, ip, date) VALUES (?, ?, ?)');
    $stmt->execute([$pollId, $ip, time()]);
    return true;
}
