<?php
// src/controllers/VoteSyncController.php
// Контроллер для синхронизации голосов с mmotop.ru

class VoteSyncController {
    private $voteService;
    
    public function __construct() {
        require_once __DIR__ . '/../services/VoteService.php';
        $this->voteService = new VoteService();
    }
    
    /**
     * Синхронизация голосов из командной строки
     */
    public function syncFromCli() {
        echo "Начало синхронизации голосов с mmotop.ru...\n";
        
        try {
            $result = $this->voteService->syncVotes();
            
            if (isset($result['error'])) {
                echo "Ошибка: " . $result['error'] . "\n";
                return false;
            }
            
            echo "Обработано голосов: " . $result['processed'] . "\n";
            
            if (!empty($result['errors'])) {
                echo "Ошибки:\n";
                foreach ($result['errors'] as $error) {
                    echo "- " . $error . "\n";
                }
            }
            
            echo "Синхронизация завершена успешно!\n";
            return true;
            
        } catch (Exception $e) {
            echo "Критическая ошибка синхронизации: " . $e->getMessage() . "\n";
            return false;
        }
    }
    
    /**
     * Автоматическая синхронизация (вызывается из шаблонов)
     */
    public function autoSync() {
        try {
            $result = $this->voteService->syncVotes();
            
            // В автоматическом режиме логируем только критические ошибки
            if (isset($result['error'])) {
                error_log('Vote sync error: ' . $result['error']);
                return false;
            }
            
            // Опционально можно логировать успешные синхронизации
            if ($result['processed'] > 0) {
                error_log("Vote sync: processed {$result['processed']} votes");
            }
            
            return true;
            
        } catch (Exception $e) {
            error_log('Vote sync critical error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Получение статистики голосований
     */
    public function getStats() {
        try {
            $siteDb = DatabaseConnection::getSiteConnection();
            
            $voteCount = $siteDb->query("SELECT COUNT(*) FROM vote_log")->fetchColumn();
            $coinsCount = $siteDb->query("SELECT COUNT(*) FROM account_coins WHERE reason LIKE '%голос%'")->fetchColumn();
            $totalCoins = $siteDb->query("SELECT SUM(coins) FROM account_coins WHERE reason LIKE '%голос%'")->fetchColumn();
            
            return [
                'total_votes' => $voteCount,
                'coin_transactions' => $coinsCount,
                'total_coins_awarded' => $totalCoins ?: 0
            ];
            
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
    
    /**
     * Получение последних голосов
     */
    public function getRecentVotes($limit = 10) {
        try {
            $siteDb = DatabaseConnection::getSiteConnection();
            
            $stmt = $siteDb->prepare("
                SELECT * FROM vote_log 
                ORDER BY vote_time DESC 
                LIMIT ?
            ");
            $stmt->execute([$limit]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}