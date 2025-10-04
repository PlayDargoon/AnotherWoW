<?php
// src/models/VoteTop.php

class VoteTop
{
    private $sitePdo;
    private $authPdo;

    public function __construct(PDO $sitePdo, ?PDO $authPdo = null)
    {
        $this->sitePdo = $sitePdo;
        $this->authPdo = $authPdo ?: \DatabaseConnection::getAuthConnection();
    }

    /**
     * Получает топ голосующих пользователей за текущий месяц
     */
    public function getTopVoters($limit = 10)
    {
        try {
            // Получаем голоса только за текущий месяц
            $currentMonth = date('Y-m-01'); // Первое число текущего месяца
            $nextMonth = date('Y-m-01', strtotime('+1 month')); // Первое число следующего месяца
            
                $stmt = $this->sitePdo->prepare("
                    SELECT 
                        ac.account_id,
                        SUM(ac.coins) as total_coins,
                        COUNT(*) as vote_records,
                        MAX(ac.created_at) as last_vote
                    FROM account_coins ac
                    WHERE LOWER(ac.reason) LIKE '%mmotop%'
                        AND ac.coins > 0
                        AND ac.created_at >= :current_month
                        AND ac.created_at < :next_month
                        AND ac.created_at <= NOW()
                    GROUP BY ac.account_id
                    ORDER BY vote_records DESC, total_coins DESC
                ");
            
            $stmt->execute([
                'current_month' => $currentMonth,
                'next_month' => $nextMonth
            ]);
            $voteData = $stmt->fetchAll(PDO::FETCH_ASSOC);
                // Окно текущего месяца в UNIX time
                $startTs = strtotime(date('Y-m-01 00:00:00'));
                $endTs = strtotime(date('Y-m-01 00:00:00', strtotime('+1 month')));
                $nowTs = time();

                // Основной источник — vote_log (источник mmotop)
                $stmt = $this->sitePdo->prepare("
                    SELECT 
                        vl.user_id AS account_id,
                        SUM(vl.reward) AS total_coins,
                        COUNT(*) AS vote_records,
                        FROM_UNIXTIME(MAX(vl.vote_time)) AS last_vote
                    FROM vote_log vl
                    WHERE vl.source LIKE 'mmotop%'
                        AND vl.vote_time >= :start_ts
                        AND vl.vote_time < :end_ts
                        AND vl.vote_time <= :now_ts
                    GROUP BY vl.user_id
                    ORDER BY vote_records DESC, total_coins DESC
                ");
                $stmt->execute([
                    'start_ts' => $startTs,
                    'end_ts' => $endTs,
                    'now_ts' => $nowTs,
                ]);
                $voteData = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Fallback: если нет данных в vote_log, используем account_coins (MMOTOP, coins>0)
                if (empty($voteData)) {
                    $currentMonth = date('Y-m-01');
                    $nextMonth = date('Y-m-01', strtotime('+1 month'));
                    $stmt = $this->sitePdo->prepare("
                        SELECT 
                            ac.account_id,
                            SUM(ac.coins) as total_coins,
                            COUNT(*) as vote_records,
                            MAX(ac.created_at) as last_vote
                        FROM account_coins ac
                        WHERE LOWER(ac.reason) LIKE '%mmotop%'
                            AND ac.coins > 0
                            AND ac.created_at >= :current_month
                            AND ac.created_at < :next_month
                            AND ac.created_at <= NOW()
                        GROUP BY ac.account_id
                        ORDER BY vote_records DESC, total_coins DESC
                    ");
                    $stmt->execute([
                        'current_month' => $currentMonth,
                        'next_month' => $nextMonth
                    ]);
                    $voteData = $stmt->fetchAll(PDO::FETCH_ASSOC);
                }
            
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
                    'total_coins' => $vote['total_coins'],
                    'vote_records' => $vote['vote_records'],
                    'vote_count' => $vote['vote_records'], // Количество голосований за месяц
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
     * Получает топ голосующих пользователей за текущий месяц (альтернативный метод с прямым соединением)
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
            
            // Получаем статистику голосования только для существующих аккаунтов за текущий месяц
            $accountIds = array_keys($allAccounts);
            $placeholders = implode(',', array_fill(0, count($accountIds), '?'));
            $currentMonth = date('Y-m-01');
            $nextMonth = date('Y-m-01', strtotime('+1 month'));
            
                $stmt = $this->sitePdo->prepare("
                    SELECT 
                        account_id,
                        SUM(coins) as total_coins,
                        COUNT(*) as vote_records,
                        MAX(created_at) as last_vote
                    FROM account_coins
                    WHERE LOWER(reason) LIKE '%mmotop%'
                        AND coins > 0
                        AND account_id IN ($placeholders)
                        AND created_at >= :current_month
                        AND created_at < :next_month
                        AND created_at <= NOW()
                    GROUP BY account_id
                    ORDER BY vote_records DESC, total_coins DESC
                    LIMIT :limit
                ");
            
            // Привязываем параметры
            foreach ($accountIds as $index => $id) {
                $stmt->bindValue($index + 1, $id, PDO::PARAM_INT);
            }
            $stmt->bindValue(':current_month', $currentMonth, PDO::PARAM_STR);
            $stmt->bindValue(':next_month', $nextMonth, PDO::PARAM_STR);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            $voteData = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $startTs = strtotime(date('Y-m-01 00:00:00'));
                $endTs = strtotime(date('Y-m-01 00:00:00', strtotime('+1 month')));
                $nowTs = time();

                $stmt = $this->sitePdo->prepare("
                    SELECT 
                        user_id AS account_id,
                        SUM(reward) as total_coins,
                        COUNT(*) as vote_records,
                        FROM_UNIXTIME(MAX(vote_time)) as last_vote
                    FROM vote_log
                    WHERE source LIKE 'mmotop%'
                        AND user_id IN ($placeholders)
                        AND vote_time >= :start_ts
                        AND vote_time < :end_ts
                        AND vote_time <= :now_ts
                    GROUP BY user_id
                    ORDER BY vote_records DESC, total_coins DESC
                    LIMIT :limit
                ");
                // Привязываем параметры
                foreach ($accountIds as $index => $id) {
                    $stmt->bindValue($index + 1, $id, PDO::PARAM_INT);
                }
                $stmt->bindValue(':start_ts', $startTs, PDO::PARAM_INT);
                $stmt->bindValue(':end_ts', $endTs, PDO::PARAM_INT);
                $stmt->bindValue(':now_ts', $nowTs, PDO::PARAM_INT);
                $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
                $stmt->execute();
            
                $voteData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Объединяем с именами пользователей
            $result = [];
            foreach ($voteData as $vote) {
                $result[] = [
                    'account_id' => $vote['account_id'],
                    'username' => $allAccounts[$vote['account_id']],
                    'total_coins' => $vote['total_coins'],
                    'vote_records' => $vote['vote_records'],
                    'vote_count' => $vote['vote_records'], // Количество голосований за месяц
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
            
            // Получаем позицию в рейтинге за текущий месяц
            $currentMonth = date('Y-m-01');
            $nextMonth = date('Y-m-01', strtotime('+1 month'));
            
                $stmt = $this->sitePdo->prepare("
                    SELECT position FROM (
                        SELECT 
                            account_id,
                            SUM(coins) as total_coins,
                            COUNT(*) as vote_records,
                            ROW_NUMBER() OVER (ORDER BY COUNT(*) DESC, SUM(coins) DESC) as position
                        FROM account_coins
                        WHERE LOWER(reason) LIKE '%mmotop%'
                            AND coins > 0
                            AND created_at >= :current_month
                            AND created_at < :next_month
                            AND created_at <= NOW()
                        GROUP BY account_id
                    ) ranked
                    WHERE account_id = :account_id
                ");
            
            $stmt->execute([
                'account_id' => $accountId,
                'current_month' => $currentMonth,
                'next_month' => $nextMonth
            ]);
                // Позиция в рейтинге по данным vote_log
                $startTs = strtotime(date('Y-m-01 00:00:00'));
                $endTs = strtotime(date('Y-m-01 00:00:00', strtotime('+1 month')));
                $nowTs = time();

                $stmt = $this->sitePdo->prepare("
                    SELECT position FROM (
                        SELECT 
                            user_id,
                            SUM(reward) as total_coins,
                            COUNT(*) as vote_records,
                            ROW_NUMBER() OVER (ORDER BY COUNT(*) DESC, SUM(reward) DESC) as position
                        FROM vote_log
                        WHERE source LIKE 'mmotop%'
                            AND vote_time >= :start_ts
                            AND vote_time < :end_ts
                            AND vote_time <= :now_ts
                        GROUP BY user_id
                    ) ranked
                    WHERE user_id = :account_id
                ");
                $stmt->execute([
                    'account_id' => $accountId,
                    'start_ts' => $startTs,
                    'end_ts' => $endTs,
                    'now_ts' => $nowTs,
                ]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result ? $result['position'] : null;
            
        } catch (Exception $e) {
            error_log("Ошибка получения позиции пользователя: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Получает статистику голосования за текущий месяц
     */
    public function getMonthlyStatistics()
    {
        try {
            // Интервал текущего месяца в UNIX-времени
            $startTs = strtotime(date('Y-m-01 00:00:00'));
            $endTs = strtotime(date('Y-m-01 00:00:00', strtotime('+1 month')));
            $nowTs = time();

            // Основной источник — vote_log
            $stmt = $this->sitePdo->prepare("\n                SELECT \n                    COUNT(DISTINCT user_id) as total_voters,\n                    SUM(reward) as total_votes,\n                    COUNT(*) as total_vote_records,\n                    FROM_UNIXTIME(MIN(vote_time)) as first_vote,\n                    FROM_UNIXTIME(MAX(vote_time)) as last_vote\n                FROM vote_log\n                WHERE source LIKE 'mmotop%'\n                    AND vote_time >= :start_ts\n                    AND vote_time < :end_ts\n                    AND vote_time <= :now_ts\n            ");
            $stmt->execute([
                'start_ts' => $startTs,
                'end_ts' => $endTs,
                'now_ts' => $nowTs,
            ]);
            $stats = $stmt->fetch(PDO::FETCH_ASSOC);

            // Fallback — account_coins, если в vote_log нет данных
            if (!$stats || (int)($stats['total_vote_records'] ?? 0) === 0) {
                $currentMonth = date('Y-m-01');
                $nextMonth = date('Y-m-01', strtotime('+1 month'));
                $stmt = $this->sitePdo->prepare("\n                    SELECT \n                        COUNT(DISTINCT account_id) as total_voters,\n                        SUM(coins) as total_votes,\n                        COUNT(*) as total_vote_records,\n                        MIN(created_at) as first_vote,\n                        MAX(created_at) as last_vote\n                    FROM account_coins\n                    WHERE LOWER(reason) LIKE '%mmotop%'\n                        AND coins > 0\n                        AND created_at >= :current_month\n                        AND created_at < :next_month\n                        AND created_at <= NOW()\n                ");
                $stmt->execute([
                    'current_month' => $currentMonth,
                    'next_month' => $nextMonth
                ]);
                $stats = $stmt->fetch(PDO::FETCH_ASSOC);
            }

            return $stats ?: [
                'total_voters' => 0,
                'total_votes' => 0,
                'total_vote_records' => 0,
                'first_vote' => null,
                'last_vote' => null
            ];
        } catch (Exception $e) {
            error_log("Ошибка получения статистики месяца: " . $e->getMessage());
            return [
                'total_voters' => 0,
                'total_votes' => 0,
                'total_vote_records' => 0,
                'first_vote' => null,
                'last_vote' => null
            ];
        }
    }
}