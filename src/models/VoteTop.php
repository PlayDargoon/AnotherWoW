<?php
// src/models/VoteTop.php

class VoteTop
{
    private $sitePdo;
    private $authPdo;
    private $mmotopUrl;
    private $nameCache = [];

    public function __construct(PDO $sitePdo, ?PDO $authPdo = null)
    {
        $this->sitePdo = $sitePdo;
        $this->authPdo = $authPdo ?: \DatabaseConnection::getAuthConnection();
        // Загружаем URL файла голосов из конфига
        $cfg = @include __DIR__ . '/../../config/vote.php';
        if (is_array($cfg) && !empty($cfg['mmotop_url'])) {
            $this->mmotopUrl = $cfg['mmotop_url'];
        }
    }

    /**
     * Загрузка содержимого файла MMOTOP с кешированием
     */
    private function loadMmotopContent()
    {
        if (!$this->mmotopUrl) return false;
        // Используем CacheService если доступен
        try {
            if (class_exists('CacheService')) {
                $cache = \CacheService::getInstance();
                $key = 'mmotop_votes_content';
                return $cache->remember($key, function () {
                    return $this->downloadContent($this->mmotopUrl);
                }, 120);
            }
        } catch (\Throwable $e) { /* ignore cache errors */ }
        // Fallback: прямая загрузка без кеша
        return $this->downloadContent($this->mmotopUrl);
    }

    private function downloadContent($url)
    {
        // cURL
        if (function_exists('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
            $content = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            if ($content !== false && $httpCode == 200) return $content;
        }
        // file_get_contents
        $context = stream_context_create([
            'http' => ['timeout' => 10, 'user_agent' => 'Mozilla/5.0'],
            'ssl' => ['verify_peer' => false, 'verify_peer_name' => false]
        ]);
        return @file_get_contents($url, false, $context);
    }

    /**
     * Парсинг строки голосования из файла mmotop
     */
    private function parseVoteLineFromFile($line)
    {
        $line = trim($line);
        if ($line === '') return null;
        // Формат 1: id date time ip login result
        if (preg_match('/^(\d+)\s+(\d{2}\.\d{2}\.\d{4})\s+(\d{2}:\d{2}:\d{2})\s+(\S+)\s+(\S+)\s+(\d)$/u', $line, $m)) {
            $date = $m[2] . ' ' . $m[3];
            $username = $m[5];
            $rewardCode = $m[6];
            $externalId = $m[1];
            $timestamp = $this->parseDate($date);
            if ($timestamp) return compact('username', 'rewardCode', 'externalId', 'timestamp');
        }
        // Формат 2: id date ip login result (одним пробелом/табом)
        if (preg_match('/^(\d+)\s+(.+?)\s+(\S+)\s+(\S+)\s+(\d)$/u', $line, $m)) {
            $date = $m[2];
            $username = $m[4];
            $rewardCode = $m[5];
            $externalId = $m[1];
            $timestamp = $this->parseDate($date);
            if ($timestamp) return compact('username', 'rewardCode', 'externalId', 'timestamp');
        }
        return null;
    }

    private function parseDate($dateString)
    {
        if (preg_match('/^(\d{2})\.(\d{2})\.(\d{4})\s+(\d{2}):(\d{2}):(\d{2})$/', $dateString, $m)) {
            return mktime((int)$m[4], (int)$m[5], (int)$m[6], (int)$m[2], (int)$m[1], (int)$m[3]);
        }
        if (preg_match('/^(\d{2})\.(\d{2})\.(\d{4})$/', $dateString, $m)) {
            return mktime(0, 0, 0, (int)$m[2], (int)$m[1], (int)$m[3]);
        }
        if (preg_match('/^\d{10}$/', $dateString)) {
            return (int)$dateString;
        }
        return null;
    }

    /**
     * Возвращает топ голосующих за текущий месяц по данным из файла MMOTOP
     */
    public function getTopVotersFromFile($limit = 10)
    {
        $content = $this->loadMmotopContent();
        if ($content === false || $content === null) return [];

        $lines = preg_split('/\r?\n/', $content, -1, PREG_SPLIT_NO_EMPTY);
        $startTs = strtotime(date('Y-m-01 00:00:00'));
        $endTs = strtotime(date('Y-m-01 00:00:00', strtotime('+1 month')));
        $nowTs = time();

        // Карта внешних id для защиты от дублей
        $seenIds = [];
        // Агрегация по account_id
        $agg = [];

        // Маппинг кодов в очки
        $rewardsCfg = @include __DIR__ . '/../../config/vote_rewards.php';
        $defaultReward = 1;

        // Модель пользователей для маппинга логина/персонажа → аккаунт
        require_once __DIR__ . '/User.php';
        $userModel = new \User($this->authPdo);
        $resolve = function($name) use ($userModel) {
            if (array_key_exists($name, $this->nameCache)) return $this->nameCache[$name];
            $info = $userModel->getAccountIdByUsernameOrCharacter($name);
            $this->nameCache[$name] = $info ?: null;
            return $this->nameCache[$name];
        };

        $maxLines = 20000; // защитный лимит от очень больших файлов
        $count = 0;
        foreach ($lines as $line) {
            if (++$count > $maxLines) break;
            $v = $this->parseVoteLineFromFile($line);
            if (!$v) continue;
            $ts = (int)$v['timestamp'];
            if ($ts < $startTs || $ts >= $endTs || $ts > $nowTs) continue; // только текущий месяц
            $extId = (string)($v['externalId'] ?? '');
            if ($extId !== '' && isset($seenIds[$extId])) continue; // дубликат
            if ($extId !== '') $seenIds[$extId] = true;

            $username = $v['username'];
            $rewardCode = (string)($v['rewardCode'] ?? '');
            $reward = $defaultReward;
            if ($rewardCode !== '' && is_array($rewardsCfg) && isset($rewardsCfg[$rewardCode])) {
                $reward = (int)$rewardsCfg[$rewardCode];
            }

            // Находим аккаунт
            $accountInfo = $resolve($username);
            if (!$accountInfo) continue; // пропускаем неизвестных
            $accountId = (int)$accountInfo['account_id'];
            $accountLogin = $accountInfo['found_username'] ?? $username;

            if (!isset($agg[$accountId])) {
                $agg[$accountId] = [
                    'account_id' => $accountId,
                    'username' => $accountLogin,
                    'vote_records' => 0,
                    'total_coins' => 0,
                    'last_vote' => null,
                ];
            }
            $agg[$accountId]['vote_records'] += 1;
            $agg[$accountId]['total_coins'] += $reward;
            $lastStr = isset($agg[$accountId]['last_vote']) ? (string)$agg[$accountId]['last_vote'] : '';
            $last = $lastStr !== '' ? strtotime($lastStr) : 0;
            if ($ts > $last) {
                $agg[$accountId]['last_vote'] = date('Y-m-d H:i:s', $ts);
            }
        }

        if (empty($agg)) return [];
        // Преобразуем в список и сортируем: сначала по голосованиям, затем по очкам
        $list = array_values($agg);
        usort($list, function ($a, $b) {
            if ($a['vote_records'] === $b['vote_records']) {
                return $b['total_coins'] <=> $a['total_coins'];
            }
            return $b['vote_records'] <=> $a['vote_records'];
        });
        // Добавим алиас vote_count
        foreach ($list as &$row) { $row['vote_count'] = $row['vote_records']; }
        unset($row);
        return array_slice($list, 0, $limit);
    }

    /**
     * Статистика месяца на основе файла MMOTOP
     */
    public function getMonthlyStatisticsFromFile()
    {
        $content = $this->loadMmotopContent();
        if ($content === false || $content === null) {
            return [
                'total_voters' => 0,
                'total_votes' => 0,
                'total_vote_records' => 0,
                'first_vote' => null,
                'last_vote' => null
            ];
        }
        $lines = preg_split('/\r?\n/', $content, -1, PREG_SPLIT_NO_EMPTY);
        $startTs = strtotime(date('Y-m-01 00:00:00'));
        $endTs = strtotime(date('Y-m-01 00:00:00', strtotime('+1 month')));
        $nowTs = time();
        $rewardsCfg = @include __DIR__ . '/../../config/vote_rewards.php';
        $defaultReward = 1;

        // Модель пользователей для маппинга
        require_once __DIR__ . '/User.php';
        $userModel = new \User($this->authPdo);
        $resolve = function($name) use ($userModel) {
            if (array_key_exists($name, $this->nameCache)) return $this->nameCache[$name];
            $info = $userModel->getAccountIdByUsernameOrCharacter($name);
            $this->nameCache[$name] = $info ?: null;
            return $this->nameCache[$name];
        };

        $seenIds = [];
        $voters = [];
        $totalVotes = 0; // сумма очков
        $totalRecords = 0; // количество голосований
        $first = null; $last = null;

        $maxLines = 20000; $count = 0;
        foreach ($lines as $line) {
            if (++$count > $maxLines) break;
            $v = $this->parseVoteLineFromFile($line);
            if (!$v) continue;
            $ts = (int)$v['timestamp'];
            if ($ts < $startTs || $ts >= $endTs || $ts > $nowTs) continue;
            $extId = (string)($v['externalId'] ?? '');
            if ($extId !== '' && isset($seenIds[$extId])) continue;
            if ($extId !== '') $seenIds[$extId] = true;

            $username = $v['username'];
            $rewardCode = (string)($v['rewardCode'] ?? '');
            $reward = $defaultReward;
            if ($rewardCode !== '' && is_array($rewardsCfg) && isset($rewardsCfg[$rewardCode])) {
                $reward = (int)$rewardsCfg[$rewardCode];
            }

            $accountInfo = $resolve($username);
            if (!$accountInfo) continue;
            $accountId = (int)$accountInfo['account_id'];
            $voters[$accountId] = true;
            $totalVotes += $reward;
            $totalRecords++;
            if ($first === null || $ts < $first) $first = $ts;
            if ($last === null || $ts > $last) $last = $ts;
        }

        return [
            'total_voters' => count($voters),
            'total_votes' => $totalVotes,
            'total_vote_records' => $totalRecords,
            'first_vote' => $first ? date('Y-m-d H:i:s', $first) : null,
            'last_vote' => $last ? date('Y-m-d H:i:s', $last) : null,
        ];
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