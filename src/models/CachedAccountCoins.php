<?php
// src/models/CachedAccountCoins.php
require_once __DIR__ . '/../services/CachedModel.php';

/**
 * Оптимизированная модель для работы с начислениями бонусов с кешированием
 */
class CachedAccountCoins extends CachedModel
{
    public function __construct($db) 
    {
        parent::__construct($db, 'account_coins');
        $this->setCacheTtl(600); // 10 минут для финансовых данных
    }
    
    /**
     * Добавить начисление бонусов (с инвалидацией кеша)
     */
    public function subtract($accountId, $coins, ?string $reason = null, ?string $createdAt = null): bool
    {
        $data = [
            'account_id' => $accountId,
            'coins' => $coins,
            'reason' => $reason
        ];
        
        if ($createdAt) {
            $data['created_at'] = $createdAt;
        }
        
        $result = $this->saveCached('account_coins', $data, null, ['account_coins']);
        
        // Инвалидируем кеш баланса для конкретного аккаунта
        $this->cache->delete($this->getCacheKey("balance_{$accountId}"));
        
        return $result;
    }
    
    /**
     * Получить баланс с кешированием
     */
    public function getBalance($accountId): int
    {
        $cacheKey = $this->getCacheKey("balance_{$accountId}");
        
        return $this->cache->remember($cacheKey, function() use ($accountId) {
            $stmt = $this->db->prepare("SELECT SUM(coins) as total FROM account_coins WHERE account_id = ?");
            $stmt->execute([$accountId]);
            $row = $stmt->fetch();
            return $row && $row['total'] !== null ? (int)$row['total'] : 0;
        }, $this->defaultCacheTtl);
    }
    
    /**
     * Получить баланс от голосования с кешированием
     */
    public function getVoteBalance($accountId): int
    {
        $cacheKey = $this->getCacheKey("vote_balance_{$accountId}");
        
        return $this->cache->remember($cacheKey, function() use ($accountId) {
            $stmt = $this->db->prepare("
                SELECT SUM(coins) as total 
                FROM account_coins 
                WHERE account_id = ? AND (
                    reason LIKE '%голос%' OR 
                    reason LIKE '%vote%' OR 
                    reason LIKE '%MMOTOP%'
                )
            ");
            $stmt->execute([$accountId]);
            $row = $stmt->fetch();
            return $row && $row['total'] !== null ? (int)$row['total'] : 0;
        }, $this->defaultCacheTtl);
    }
    
    /**
     * Получить историю начислений с кешированием
     */
    public function getHistory($accountId, $limit = 20): array
    {
        $cacheKey = $this->getCacheKey("history_{$accountId}_{$limit}");
        
        return $this->cache->remember($cacheKey, function() use ($accountId, $limit) {
            $stmt = $this->db->prepare("
                SELECT * FROM account_coins 
                WHERE account_id = ? 
                ORDER BY created_at DESC 
                LIMIT ?
            ");
            $stmt->execute([$accountId, $limit]);
            return $stmt->fetchAll();
        }, 300); // История кешируется на 5 минут
    }
    
    /**
     * Получить топ пользователей по монетам
     */
    public function getTopUsers($limit = 10): array
    {
        $cacheKey = $this->getCacheKey("top_users_{$limit}");
        
        return $this->cache->remember($cacheKey, function() use ($limit) {
            $stmt = $this->db->prepare("
                SELECT 
                    account_id,
                    SUM(coins) as total_coins,
                    COUNT(*) as total_transactions
                FROM account_coins 
                GROUP BY account_id 
                ORDER BY total_coins DESC 
                LIMIT ?
            ");
            $stmt->execute([$limit]);
            return $stmt->fetchAll();
        }, 1800); // Топ кешируется на 30 минут
    }
    
    /**
     * Получить статистику по монетам
     */
    public function getStats(): array
    {
        $cacheKey = $this->getCacheKey("stats");
        
        return $this->cache->remember($cacheKey, function() {
            $stmt = $this->db->prepare("
                SELECT 
                    COUNT(*) as total_transactions,
                    COUNT(DISTINCT account_id) as unique_accounts,
                    SUM(coins) as total_coins,
                    AVG(coins) as avg_coins_per_transaction,
                    SUM(CASE WHEN reason LIKE '%голос%' OR reason LIKE '%vote%' OR reason LIKE '%MMOTOP%' THEN coins ELSE 0 END) as vote_coins
                FROM account_coins
            ");
            $stmt->execute();
            return $stmt->fetch() ?: [];
        }, 3600); // Статистика кешируется на 1 час
    }
    
    /**
     * Проверить существование начисления (дубликат)
     */
    public function isDuplicate($accountId, $reason, $timeWindow = 3600): bool
    {
        $cacheKey = $this->getCacheKey("duplicate_check_{$accountId}_" . md5($reason));
        
        return $this->cache->remember($cacheKey, function() use ($accountId, $reason, $timeWindow) {
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as count 
                FROM account_coins 
                WHERE account_id = ? AND reason = ? AND created_at >= DATE_SUB(NOW(), INTERVAL ? SECOND)
            ");
            $stmt->execute([$accountId, $reason, $timeWindow]);
            $result = $stmt->fetch();
            return (int)($result['count'] ?? 0) > 0;
        }, 60); // Проверка дубликатов кешируется на 1 минуту
    }
    
    /**
     * Массовое добавление начислений (для миграций и синхронизации)
     */
    public function addBulk(array $records): bool
    {
        if (empty($records)) {
            return true;
        }
        
        try {
            $this->db->beginTransaction();
            
            $stmt = $this->db->prepare("
                INSERT INTO account_coins (account_id, coins, reason, created_at) 
                VALUES (?, ?, ?, ?)
            ");
            
            $affectedAccounts = [];
            
            foreach ($records as $record) {
                $stmt->execute([
                    $record['account_id'],
                    $record['coins'],
                    $record['reason'] ?? null,
                    $record['created_at'] ?? date('Y-m-d H:i:s')
                ]);
                
                $affectedAccounts[] = $record['account_id'];
            }
            
            $this->db->commit();
            
            // Инвалидируем кеш для всех затронутых аккаунтов
            foreach (array_unique($affectedAccounts) as $accountId) {
                $this->cache->delete($this->getCacheKey("balance_{$accountId}"));
                $this->cache->delete($this->getCacheKey("vote_balance_{$accountId}"));
            }
            
            // Инвалидируем общую статистику
            $this->cache->delete($this->getCacheKey("stats"));
            $this->cache->delete($this->getCacheKey("top_users_10"));
            
            return true;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}