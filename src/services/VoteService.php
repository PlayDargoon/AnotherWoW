<?php
// src/services/VoteService.php
// Единый сервис для обработки голосов с mmotop.ru

class VoteService {
    private $siteDb;
    private $authDb;
    private $userModel;
    private $coinsModel;
    private $voteLogModel;
    private $rewardModel;
    private $notificationModel;
    
    // URL файла с голосами mmotop
    private $mmotopUrl = 'https://mmotop.ru/votes/d2076181c455574872250afe4ec7fdbed943ce36.txt?3f91c4e49e449664db3da105befc39e7';
    
    public function __construct() {
        $this->siteDb = DatabaseConnection::getSiteConnection();
        $this->authDb = DatabaseConnection::getAuthConnection();
        
        require_once __DIR__ . '/../models/User.php';
        require_once __DIR__ . '/../models/AccountCoins.php';
        require_once __DIR__ . '/../models/VoteLog.php';
    require_once __DIR__ . '/../models/VoteReward.php';
    require_once __DIR__ . '/../models/Notification.php';

    $this->userModel = new User($this->authDb);
    $this->coinsModel = new AccountCoins($this->siteDb);
    $this->voteLogModel = new VoteLog($this->siteDb);
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
            
            // Проверяем существование пользователя
            $accountId = $this->userModel->getUserIdByUsername($voteData['username']);
            if (!$accountId) {
                $errors[] = "Пользователь не найден: " . $voteData['username'];
                continue;
            }
            
            // Проверяем дублирование
            if ($this->isDuplicateVote($accountId, $voteData['timestamp'])) {
                continue; // Пропускаем дубликат без ошибки
            }
            
            // Добавляем голос и начисляем монеты
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
        if (preg_match('/^(\d+)\s+(\d{2}\.\d{2}\.\d{4})\s+(\d{2}:\d{2}:\d{2})\s+(\S+)\s+(\S+)\s+(\d+)$/u', $line, $matches)) {
            $date = $matches[2] . ' ' . $matches[3];
            $username = $matches[5];
            
            // Конвертируем дату в timestamp
            $timestamp = $this->parseDate($date);
            if ($timestamp) {
                return [
                    'username' => $username,
                    'timestamp' => $timestamp,
                    'format' => 'space_separated'
                ];
            }
        }
        
        // Формат 2: табуляция "id date ip login result"
        if (preg_match('/^(\d+)\s+(.+?)\s+(\S+)\s+(\S+)\s+(.+)$/u', $line, $matches)) {
            $date = $matches[2];
            $username = $matches[4];
            
            // Конвертируем дату в timestamp
            $timestamp = $this->parseDate($date);
            if ($timestamp) {
                return [
                    'username' => $username,
                    'timestamp' => $timestamp,
                    'format' => 'tab_separated'
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
    private function isDuplicateVote($accountId, $timestamp) {
        // Проверяем в VoteLog по времени (±5 минут)
        $stmt = $this->siteDb->prepare("
            SELECT COUNT(*) FROM vote_log 
            WHERE user_id = ? AND ABS(vote_time - ?) < 300
        ");
        $stmt->execute([$accountId, $timestamp]);
        
        if ($stmt->fetchColumn() > 0) {
            return true;
        }
        
        // Проверяем в AccountCoins по дате
        $date = date('Y-m-d H:i:s', $timestamp);
        $stmt = $this->siteDb->prepare("
            SELECT COUNT(*) FROM account_coins 
            WHERE account_id = ? AND created_at = ? AND reason LIKE '%голос%'
        ");
        $stmt->execute([$accountId, $date]);
        
        return $stmt->fetchColumn() > 0;
    }
    
    /**
     * Добавление голоса и начисление монет
     */
    private function addVote($accountId, $voteData) {
        $username = $voteData['username'];
        $timestamp = $voteData['timestamp'];
        $format = $voteData['format'];
        
        // Количество монет зависит от формата или настроек
        $coinsReward = $this->getCoinsReward($format);
        
        // Добавляем в VoteLog
        $this->voteLogModel->add(
            $accountId, 
            $username, 
            $coinsReward, 
            'mmotop_' . $format
        );
        
        // Начисляем монеты через AccountCoins
        $this->coinsModel->add(
            $accountId, 
            $coinsReward, 
            'Голосование mmotop (' . $format . ')'
        );
        // Создаем уведомление, если пользователь в сессии
        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $accountId) {
            $this->notificationModel->createVoteRewardNotification($accountId, $coinsReward);
        }
        
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
    private function getCoinsReward($format) {
        // Можно настроить разные награды для разных форматов
        switch ($format) {
            case 'tab_separated':
                return 1; // Стандартная награда
            case 'pipe_separated':
                return 1; // Стандартная награда
            case 'username_only':
                return 5; // Повышенная награда для ручного голосования
            default:
                return 1;
        }
    }
    
    /**
     * Проверка возможности получения награды пользователем
     */
    public function canUserVote($username) {
        $accountId = $this->userModel->getUserIdByUsername($username);
        if (!$accountId) {
            return ['can_vote' => false, 'reason' => 'Пользователь не найден'];
        }
        
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