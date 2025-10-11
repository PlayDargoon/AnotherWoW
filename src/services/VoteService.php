<?php
// src/services/VoteService.php
// Единый сервис для обработки голосов с mmotop.ru

class VoteService {
    /**
     * Синхронизация голосов только для одного пользователя (быстро, с кешем)
     */
    public function syncVotesForUser($userId, ?string $username = null) {
        // Используем новый CacheService для кеширования mmotop-файла
        $cache = CacheService::getInstance();
        $cacheKey = 'mmotop_votes_content';
        
        $content = $cache->remember($cacheKey, function() {
            return $this->downloadContent($this->mmotopUrl);
        }, 120); // 2 минуты TTL
        
        if ($content === false || !$content) return ['error' => 'Не удалось загрузить файл с голосами'];

        // Получаем username если не передан
        if (!$username) {
            $username = $this->userModel->getUsernameById($userId);
        }
        if (!$username) return ['error' => 'Не найден username для user_id'];

        $lines = preg_split('/\r?\n/', $content, -1, PREG_SPLIT_NO_EMPTY);
        $processed = 0;
        $errors = [];
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            $voteData = $this->parseVoteLine($line);
            if (!$voteData) continue;
            // Только для нужного пользователя
            if (strcasecmp($voteData['username'], $username) !== 0) continue;
            if ($this->isDuplicateVote($userId, $voteData['timestamp'], $voteData['external_id'] ?? null)) continue;
            $this->addVote($userId, $voteData);
            $processed++;
        }
        return ['processed' => $processed, 'errors' => $errors];
    }
    private $siteDb;
    private $authDb;
    private $userModel;
    private $coinsModel;
    private $voteLogModel;
    private $rewardModel;
    private $notificationModel;
    
    // URL файла с голосами mmotop
    private $mmotopUrl;
    
    public function __construct() {
    $this->siteDb = DatabaseConnection::getSiteConnection();
    $this->authDb = DatabaseConnection::getAuthConnection();
        
        require_once __DIR__ . '/../models/User.php';
        require_once __DIR__ . '/../models/AccountCoins.php';
        require_once __DIR__ . '/../models/VoteLog.php';
    require_once __DIR__ . '/../models/VoteReward.php';
    require_once __DIR__ . '/../models/Notification.php';

    // Загружаем конфиг голосований
    $voteCfg = @include __DIR__ . '/../../config/vote.php';
    if (is_array($voteCfg) && !empty($voteCfg['mmotop_url'])) {
        $this->mmotopUrl = $voteCfg['mmotop_url'];
    }

    $this->userModel = new User($this->authDb);
    $this->coinsModel = new AccountCoins($this->siteDb);
    $this->voteLogModel = new VoteLog($this->siteDb);
    // Гарантируем актуальную схему для vote_log (external_id и индексы)
    try { $this->voteLogModel->migrate(); } catch (\Throwable $e) { /* ignore */ }
    // VoteReward работает с базой site для таблицы vote_rewards
    $this->rewardModel = new VoteReward($this->siteDb);
    $this->notificationModel = new Notification();
    }
    
    /**
     * Синхронизация голосов с mmotop.ru
     * Обрабатывает все форматы данных и предотвращает дублирование
     */
    public function syncVotes() {
        $content = $this->downloadContent($this->mmotopUrl);
        if ($content === false) {
            return ['error' => 'Не удалось загрузить файл с голосами'];
        }
        
        $lines = preg_split('/\r?\n/', $content, -1, PREG_SPLIT_NO_EMPTY);
        $processed = 0;
        $errors = [];
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            
            // Пробуем разные форматы
            $voteData = $this->parseVoteLine($line);
            if (!$voteData) {
                $errors[] = "Не удалось распарсить строку: " . substr($line, 0, 50);
                continue;
            }
            
            // Проверяем существование пользователя (по логину или имени персонажа)
            $accountInfo = $this->userModel->getAccountIdByUsernameOrCharacter($voteData['username']);
            if (!$accountInfo) {
                $errors[] = "Пользователь/персонаж не найден: " . $voteData['username'];
                continue;
            }
            
            $accountId = $accountInfo['account_id'];
            
            // Логируем способ поиска для отладки
            if ($accountInfo['method'] === 'character_name') {
                error_log("VoteService: Найден по персонажу '{$accountInfo['character_name']}' -> аккаунт '{$accountInfo['found_username']}' (ID: $accountId)");
            }
            
            // Проверяем дублирование
            if ($this->isDuplicateVote($accountId, $voteData['timestamp'], $voteData['external_id'] ?? null)) {
                continue; // Пропускаем дубликат без ошибки
            }
            
            // Добавляем голос и начисляем монеты (используем найденный логин аккаунта)
            $voteData['account_username'] = $accountInfo['found_username'];
            $voteData['search_method'] = $accountInfo['method'];
            $this->addVote($accountId, $voteData);
            $processed++;
        }
        
        return [
            'processed' => $processed,
            'errors' => $errors
        ];
    }
    
    /**
     * Парсинг строки с голосом
     * Поддерживает разные форматы
     */
    private function parseVoteLine($line) {
        // Формат 1: пробелы "id date time ip login result"
        if (preg_match('/^(\d+)\s+(\d{2}\.\d{2}\.\d{4})\s+(\d{2}:\d{2}:\d{2})\s+(\S+)\s+(\S+)\s+(\d)$/u', $line, $matches)) {
            $date = $matches[2] . ' ' . $matches[3];
            $username = $matches[5];
            $rewardCode = $matches[6];
            $externalId = $matches[1];
            // Конвертируем дату в timestamp
            $timestamp = $this->parseDate($date);
            if ($timestamp) {
                return [
                    'username' => $username,
                    'timestamp' => $timestamp,
                    'format' => 'space_separated',
                    'reward_code' => $rewardCode,
                    'external_id' => $externalId
                ];
            }
        }
        // Формат 2: табуляция "id date ip login result"
        if (preg_match('/^(\d+)\s+(.+?)\s+(\S+)\s+(\S+)\s+(\d)$/u', $line, $matches)) {
            $date = $matches[2];
            $username = $matches[4];
            $rewardCode = $matches[5];
            $externalId = $matches[1];
            // Конвертируем дату в timestamp
            $timestamp = $this->parseDate($date);
            if ($timestamp) {
                return [
                    'username' => $username,
                    'timestamp' => $timestamp,
                    'format' => 'tab_separated',
                    'reward_code' => $rewardCode,
                    'external_id' => $externalId
                ];
            }
        }
        
        // Формат 3: "username|timestamp"
        if (preg_match('/^(.+?)\|(\d+)$/u', $line, $matches)) {
            return [
                'username' => trim($matches[1]),
                'timestamp' => (int)$matches[2],
                'format' => 'pipe_separated'
            ];
        }
        
        // Формат 4: простой текст с именами пользователей
        if (preg_match('/^[a-zA-Z0-9_-]+$/u', $line)) {
            return [
                'username' => $line,
                'timestamp' => time(), // Используем текущее время
                'format' => 'username_only'
            ];
        }
        
        return null;
    }
    
    /**
     * Парсинг даты из разных форматов
     */
    private function parseDate($dateString) {
        // Формат: "DD.MM.YYYY HH:MM:SS"
        if (preg_match('/^(\d{2})\.(\d{2})\.(\d{4})\s+(\d{2}):(\d{2}):(\d{2})$/', $dateString, $m)) {
            return mktime((int)$m[4], (int)$m[5], (int)$m[6], (int)$m[2], (int)$m[1], (int)$m[3]);
        }
        
        // Формат: "DD.MM.YYYY" (только дата)
        if (preg_match('/^(\d{2})\.(\d{2})\.(\d{4})$/', $dateString, $m)) {
            return mktime(0, 0, 0, (int)$m[2], (int)$m[1], (int)$m[3]);
        }
        
        // Формат: timestamp (unix время)
        if (preg_match('/^\d{10}$/', $dateString)) {
            return (int)$dateString;
        }
        
        return null;
    }
    
    /**
     * Проверка на дублирование голоса
     */
    private function isDuplicateVote($accountId, $timestamp, $externalId = null) {
        // 1) По внешнему ID (если доступен) — самый надежный способ
        if ($externalId !== null) {
            try {
                // Проверяем по external_id глобально (даже если к другому user_id уже привязан)
                $stmt = $this->siteDb->prepare("SELECT COUNT(*) FROM vote_log WHERE external_id = ?");
                $stmt->execute([(string)$externalId]);
                if ((int)$stmt->fetchColumn() > 0) {
                    return true; // точно дубликат
                }
                // Если external_id не найден — считаем запись уникальной и НЕ применяем окно времени
                return false;
            } catch (\Throwable $e) {
                // Если не удалось проверить по external_id — упадем в проверку по времени
            }
        }

        // 2) По времени: проверяем в vote_log наличие записи для этого пользователя
        // в пределах ±5 минут от времени голоса (применяется только когда нет external_id)
        try {
            $from = (int)$timestamp - 300; // 5 минут назад
            $to = (int)$timestamp + 300;   // 5 минут вперед
            $stmt = $this->siteDb->prepare("SELECT COUNT(*) FROM vote_log WHERE user_id = ? AND vote_time BETWEEN ? AND ?");
            $stmt->execute([$accountId, $from, $to]);
            if ((int)$stmt->fetchColumn() > 0) {
                return true;
            }
        } catch (\Throwable $e) {
            // Если что-то пошло не так, просто переходим к проверке в account_coins
        }
        
        // 3) Проверяем в AccountCoins по точной дате (created_at)
        $date = date('Y-m-d H:i:s', $timestamp);
        $stmt = $this->siteDb->prepare("\n            SELECT COUNT(*) FROM account_coins \n            WHERE account_id = ? AND created_at = ? AND reason LIKE '%голос%'\n        ");
        $stmt->execute([$accountId, $date]);
        
        return ((int)$stmt->fetchColumn()) > 0;
    }
    
    /**
     * Добавление голоса и начисление монет
     */
    private function addVote($accountId, $voteData) {
        $username = $voteData['username']; // Оригинальное имя из голосования
        $accountUsername = isset($voteData['account_username']) ? $voteData['account_username'] : $username;
        $timestamp = $voteData['timestamp'];
        $format = $voteData['format'];
        $rewardCode = isset($voteData['reward_code']) ? $voteData['reward_code'] : null;
        $searchMethod = isset($voteData['search_method']) ? $voteData['search_method'] : 'account_login';

        // Количество монет зависит от кода награды
        $coinsReward = $this->getCoinsReward($rewardCode);

        // Добавляем в VoteLog (используем логин аккаунта для консистентности)
        $this->voteLogModel->add(
            $accountId,
            $accountUsername,
            $coinsReward,
            'mmotop_' . $format,
            $timestamp,
            $voteData['external_id'] ?? null
        );

        // Преобразуем timestamp в формат DATETIME для created_at
        $createdAt = date('Y-m-d H:i:s', $timestamp);

        // Начисляем монеты через AccountCoins с точной датой
        $reason = 'Голосование mmotop (' . $format . ')';
        if ($searchMethod === 'character_name') {
            $reason .= ' [найден по персонажу: ' . $username . ']';
        }
        
        $this->coinsModel->add(
            $accountId,
            $coinsReward,
            $reason,
            $createdAt
        );
        // Создаем уведомление всегда для получателя награды
        $this->notificationModel->createVoteRewardNotification($accountId, $coinsReward);

        // Обновляем время последнего голоса
        $this->rewardModel->setLastVoteTime($accountId, $timestamp);
    }
    
    /**
     * Безопасная загрузка содержимого URL
     */
    private function downloadContent($url) {
        // Пробуем cURL если доступен
        if (function_exists('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
            
            $content = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($content !== false && $httpCode == 200) {
                return $content;
            }
        }
        
        // Fallback: file_get_contents с контекстом
        $context = stream_context_create([
            'http' => [
                'timeout' => 10,
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
            ],
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false
            ]
        ]);
        
        return @file_get_contents($url, false, $context);
    }
    private function getCoinsReward($rewardCode) {
        // Загружаем конфиг кодов наград
        $rewardsCfg = @include __DIR__ . '/../../config/vote_rewards.php';
        $default = 1;
        if ($rewardCode && is_array($rewardsCfg) && isset($rewardsCfg[$rewardCode])) {
            return $rewardsCfg[$rewardCode];
        }
        return $default;
    }
    
    /**
     * Публичный метод для тестирования обработки голосов из контента
     */
    public function processVotesFromContent($content) {
        $lines = preg_split('/\r?\n/', $content, -1, PREG_SPLIT_NO_EMPTY);
        $processed = 0;
        $skipped = 0;
        $errors = [];
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            
            // Пробуем разные форматы
            $voteData = $this->parseVoteLine($line);
            if (!$voteData) {
                $errors[] = "Не удалось распарсить строку: " . substr($line, 0, 50);
                continue;
            }
            
            // Проверяем существование пользователя (по логину или персонажу)
            $accountInfo = $this->userModel->getAccountIdByUsernameOrCharacter($voteData['username']);
            if (!$accountInfo) {
                $errors[] = "Пользователь/персонаж не найден: " . $voteData['username'];
                continue;
            }
            
            $accountId = $accountInfo['account_id'];
            
            // Логируем способ поиска для отладки
            if ($accountInfo['method'] === 'character_name') {
                error_log("VoteService: Найден по персонажу '{$accountInfo['character_name']}' -> аккаунт '{$accountInfo['found_username']}' (ID: $accountId)");
            }
            
            // Проверяем дублирование (с использованием external_id, если есть)
            if ($this->isDuplicateVote($accountId, $voteData['timestamp'], $voteData['external_id'] ?? null)) {
                $skipped++;
                continue; // Пропускаем дубликат без ошибки
            }
            
            // Добавляем голос и начисляем монеты (используем найденный логин аккаунта)
            $voteData['account_username'] = $accountInfo['found_username'];
            $voteData['search_method'] = $accountInfo['method'];
            $this->addVote($accountId, $voteData);
            $processed++;
        }
        
        return [
            'processed' => $processed,
            'skipped' => $skipped,
            'errors' => $errors
        ];
    }
    
    /**
     * Проверка возможности получения награды пользователем
     */
    public function canUserVote($username) {
        $accountInfo = $this->userModel->getAccountIdByUsernameOrCharacter($username);
        if (!$accountInfo) {
            return ['can_vote' => false, 'reason' => 'Пользователь/персонаж не найден'];
        }
        
        $accountId = $accountInfo['account_id'];
        
        $lastVoteTime = $this->rewardModel->getLastVoteTime($accountId);
        $cooldownHours = 16; // 16 часов между голосованиями
        
        if (time() - $lastVoteTime < $cooldownHours * 3600) {
            $nextVoteTime = $lastVoteTime + ($cooldownHours * 3600);
            return [
                'can_vote' => false, 
                'reason' => 'Голосовать можно раз в ' . $cooldownHours . ' часов',
                'next_vote_time' => $nextVoteTime
            ];
        }
        
        return ['can_vote' => true];
    }
    
    /**
     * Ручное начисление награды за голосование
     */
    public function rewardManualVote($username) {
        $canVote = $this->canUserVote($username);
        if (!$canVote['can_vote']) {
            return $canVote;
        }
        
        // Получаем информацию об аккаунте
        $accountInfo = $this->userModel->getAccountIdByUsernameOrCharacter($username);
        if (!$accountInfo) {
            return ['success' => false, 'message' => 'Пользователь/персонаж не найден'];
        }
        
        // Проверяем наличие голоса в файле mmotop
        $content = $this->downloadContent($this->mmotopUrl);
        if ($content === false) {
            return ['success' => false, 'reason' => 'Не удалось проверить голосование'];
        }
        
        if (stripos($content, $username) === false) {
            return ['success' => false, 'reason' => 'Голос не найден'];
        }
        
        $accountId = $this->userModel->getUserIdByUsername($username);
        $this->addVote($accountId, [
            'username' => $username,
            'timestamp' => time(),
            'format' => 'manual_reward'
        ]);
        
        return ['success' => true, 'message' => 'Награда начислена!'];
    }
}