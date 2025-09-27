<?php
// src/models/VoteTop.php

class VoteTop
{
    private $sitePdo;
    private $authPdo;

    public function __construct(PDO $sitePdo, PDO $authPdo = null)
    {
        $this->sitePdo = $sitePdo;
        $this->authPdo = $authPdo ?: \DatabaseConnection::getAuthConnection();
    }

    /**
     * Получает топ голосующих пользователей
     */
    public function getTopVoters($limit = 10)
    {
        try {
            // Получаем только существующие аккаунты с их голосами через JOIN
            $stmt = $this->sitePdo->prepare("
                SELECT 
                    ac.account_id,
                    SUM(ac.coins) as total_votes,
                    COUNT(*) as vote_count,
                    MAX(ac.created_at) as last_vote
                FROM account_coins ac
                WHERE (ac.reason LIKE '%голос%' OR ac.reason LIKE '%vote%' OR ac.reason LIKE '%MMOTOP%')
                GROUP BY ac.account_id
                ORDER BY total_votes DESC, vote_count DESC
            ");
            
            $stmt->execute();
            $voteData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($voteData)) {
                return [];
            }
            
            // Получаем имена пользователей из базы auth только для существующих аккаунтов
            $accountIds = array_column($voteData, 'account_id');
            $placeholders = implode(',', array_fill(0, count($accountIds), '?'));
            
            $stmt = $this->authPdo->prepare("
                SELECT id, username 
                FROM account 
                WHERE id IN ($placeholders)
            ");
            $stmt->execute($accountIds);
            $accountNames = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
            
            // Объединяем данные только для существующих аккаунтов
            $result = [];
            foreach ($voteData as $vote) {
                // Пропускаем аккаунты, которых нет в базе auth
                if (!isset($accountNames[$vote['account_id']])) {
                    continue;
                }
                
                $result[] = [
                    'account_id' => $vote['account_id'],
                    'username' => $accountNames[$vote['account_id']],
                    'total_votes' => $vote['total_votes'],
                    'vote_count' => $vote['vote_count'],
                    'last_vote' => $vote['last_vote']
                ];
            }
            
            // Применяем лимит после фильтрации
            return array_slice($result, 0, $limit);
            
        } catch (Exception $e) {
            error_log("Ошибка получения топа голосующих: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Получает топ голосующих пользователей (альтернативный метод с прямым соединением)
     */
    public function getTopVotersWithJoin($limit = 10)
    {
        try {
            // Сначала получаем все существующие аккаунты
            $stmt = $this->authPdo->query("SELECT id, username FROM account");
            $allAccounts = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
            
            if (empty($allAccounts)) {
                return [];
            }
            
            // Получаем статистику голосования только для существующих аккаунтов
            $accountIds = array_keys($allAccounts);
            $placeholders = implode(',', array_fill(0, count($accountIds), '?'));
            
            $stmt = $this->sitePdo->prepare("
                SELECT 
                    account_id,
                    SUM(coins) as total_votes,
                    COUNT(*) as vote_count,
                    MAX(created_at) as last_vote
                FROM account_coins
                WHERE (reason LIKE '%голос%' OR reason LIKE '%vote%' OR reason LIKE '%MMOTOP%')
                    AND account_id IN ($placeholders)
                GROUP BY account_id
                ORDER BY total_votes DESC, vote_count DESC
                LIMIT :limit
            ");
            
            // Привязываем параметры
            foreach ($accountIds as $index => $id) {
                $stmt->bindValue($index + 1, $id, PDO::PARAM_INT);
            }
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            $voteData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Объединяем с именами пользователей
            $result = [];
            foreach ($voteData as $vote) {
                $result[] = [
                    'account_id' => $vote['account_id'],
                    'username' => $allAccounts[$vote['account_id']],
                    'total_votes' => $vote['total_votes'],
                    'vote_count' => $vote['vote_count'],
                    'last_vote' => $vote['last_vote']
                ];
            }
            
            return $result;
            
        } catch (Exception $e) {
            error_log("Ошибка получения топа голосующих (JOIN): " . $e->getMessage());
            return [];
        }
    }
    public function getUserPosition($username)
    {
        try {
            // Сначала получаем account_id по username из базы auth
            $stmt = $this->authPdo->prepare("SELECT id FROM account WHERE username = :username");
            $stmt->execute(['username' => $username]);
            $accountData = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$accountData) {
                return null;
            }
            
            $accountId = $accountData['id'];
            
            // Получаем позицию в рейтинге
            $stmt = $this->sitePdo->prepare("
                SELECT position FROM (
                    SELECT 
                        account_id,
                        SUM(coins) as total_votes,
                        ROW_NUMBER() OVER (ORDER BY SUM(coins) DESC, COUNT(*) DESC) as position
                    FROM account_coins
                    WHERE reason LIKE '%голос%' OR reason LIKE '%vote%' OR reason LIKE '%MMOTOP%'
                    GROUP BY account_id
                ) ranked
                WHERE account_id = :account_id
            ");
            
            $stmt->execute(['account_id' => $accountId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result ? $result['position'] : null;
            
        } catch (Exception $e) {
            error_log("Ошибка получения позиции пользователя: " . $e->getMessage());
            return null;
        }
    }
}