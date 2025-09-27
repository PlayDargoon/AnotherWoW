<?php
// src/services/CachedModel.php
/**
 * Базовый класс для моделей с автоматическим кешированием
 */
abstract class CachedModel
{
    protected $db;
    protected $cache;
    protected $cachePrefix = '';
    protected $defaultCacheTtl = 300; // 5 минут
    
    public function __construct(PDO $db, string $cachePrefix = '')
    {
        $this->db = $db;
        $this->cache = CacheService::getInstance();
        $this->cachePrefix = $cachePrefix ?: static::class;
    }
    
    /**
     * Кешированный поиск по ID
     */
    protected function findCached(string $table, int $id, ?int $cacheTtl = null): ?array
    {
        $cacheTtl = $cacheTtl ?? $this->defaultCacheTtl;
        $cacheKey = $this->getCacheKey("find_{$table}_{$id}");
        
        return $this->cache->remember($cacheKey, function() use ($table, $id) {
            $stmt = $this->db->prepare("SELECT * FROM {$table} WHERE id = ? LIMIT 1");
            $stmt->execute([$id]);
            return $stmt->fetch() ?: null;
        }, $cacheTtl);
    }
    
    /**
     * Кешированный поиск по условию
     */
    protected function findByCached(string $table, array $conditions, ?int $cacheTtl = null): ?array
    {
        $cacheTtl = $cacheTtl ?? $this->defaultCacheTtl;
        $cacheKey = $this->getCacheKey("findby_{$table}_" . md5(serialize($conditions)));
        
        return $this->cache->remember($cacheKey, function() use ($table, $conditions) {
            $where = [];
            $params = [];
            foreach ($conditions as $key => $value) {
                $where[] = "{$key} = ?";
                $params[] = $value;
            }
            $whereClause = implode(' AND ', $where);
            
            $stmt = $this->db->prepare("SELECT * FROM {$table} WHERE {$whereClause} LIMIT 1");
            $stmt->execute($params);
            return $stmt->fetch() ?: null;
        }, $cacheTtl);
    }
    
    /**
     * Кешированный список записей
     */
    protected function getAllCached(string $table, array $conditions = [], string $orderBy = '', int $limit = 0, ?int $cacheTtl = null): array
    {
        $cacheTtl = $cacheTtl ?? $this->defaultCacheTtl;
        $cacheKey = $this->getCacheKey("getall_{$table}_" . md5(serialize($conditions) . $orderBy . $limit));
        
        return $this->cache->remember($cacheKey, function() use ($table, $conditions, $orderBy, $limit) {
            $where = [];
            $params = [];
            
            foreach ($conditions as $key => $value) {
                $where[] = "{$key} = ?";
                $params[] = $value;
            }
            
            $query = "SELECT * FROM {$table}";
            if (!empty($where)) {
                $query .= ' WHERE ' . implode(' AND ', $where);
            }
            if ($orderBy) {
                $query .= " ORDER BY {$orderBy}";
            }
            if ($limit > 0) {
                $query .= " LIMIT {$limit}";
            }
            
            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll();
        }, $cacheTtl);
    }
    
    /**
     * Кешированный подсчет записей
     */
    protected function countCached(string $table, array $conditions = [], ?int $cacheTtl = null): int
    {
        $cacheTtl = $cacheTtl ?? $this->defaultCacheTtl;
        $cacheKey = $this->getCacheKey("count_{$table}_" . md5(serialize($conditions)));
        
        return $this->cache->remember($cacheKey, function() use ($table, $conditions) {
            $where = [];
            $params = [];
            
            foreach ($conditions as $key => $value) {
                $where[] = "{$key} = ?";
                $params[] = $value;
            }
            
            $query = "SELECT COUNT(*) as count FROM {$table}";
            if (!empty($where)) {
                $query .= ' WHERE ' . implode(' AND ', $where);
            }
            
            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
            $result = $stmt->fetch();
            return (int)($result['count'] ?? 0);
        }, $cacheTtl);
    }
    
    /**
     * Инвалидация кеша для таблицы
     */
    protected function invalidateTableCache(string $table): void
    {
        $pattern = $this->getCacheKey("{$table}_*");
        // Простая реализация - в идеале нужно улучшить для поддержки wildcards
        $this->cache->delete($pattern);
    }
    
    /**
     * Создание/обновление записи с инвалидацией кеша
     */
    protected function saveCached(string $table, array $data, ?int $id = null, ?array $cacheTables = null): bool
    {
        $cacheTables = $cacheTables ?? [$table];
        
        if ($id) {
            // Обновление
            $fields = [];
            $params = array_values($data);
            foreach (array_keys($data) as $key) {
                $fields[] = "{$key} = ?";
            }
            $params[] = $id;
            
            $query = "UPDATE {$table} SET " . implode(', ', $fields) . " WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $result = $stmt->execute($params);
        } else {
            // Создание
            $fields = array_keys($data);
            $placeholders = array_fill(0, count($data), '?');
            
            $query = "INSERT INTO {$table} (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $placeholders) . ")";
            $stmt = $this->db->prepare($query);
            $result = $stmt->execute(array_values($data));
        }
        
        // Инвалидируем кеш для затронутых таблиц
        if ($result) {
            foreach ($cacheTables as $cacheTable) {
                $this->invalidateTableCache($cacheTable);
            }
        }
        
        return $result;
    }
    
    /**
     * Удаление записи с инвалидацией кеша
     */
    protected function deleteCached(string $table, int $id, ?array $cacheTables = null): bool
    {
        $cacheTables = $cacheTables ?? [$table];
        
        $stmt = $this->db->prepare("DELETE FROM {$table} WHERE id = ?");
        $result = $stmt->execute([$id]);
        
        // Инвалидируем кеш для затронутых таблиц
        if ($result) {
            foreach ($cacheTables as $cacheTable) {
                $this->invalidateTableCache($cacheTable);
            }
        }
        
        return $result;
    }
    
    /**
     * Генерация ключа кеша
     */
    protected function getCacheKey(string $key): string
    {
        return $this->cachePrefix . '_' . $key;
    }
    
    /**
     * Установить TTL по умолчанию для модели
     */
    public function setCacheTtl(int $ttl): void
    {
        $this->defaultCacheTtl = $ttl;
    }
    
    /**
     * Очистить весь кеш модели
     */
    public function clearModelCache(): void
    {
        // Простая реализация - удаляем кеш с префиксом модели
        // В идеале нужно сканировать все ключи с нашим префиксом
        $this->cache->clear();
    }
}