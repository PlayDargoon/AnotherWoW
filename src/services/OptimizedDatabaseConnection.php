<?php
// src/services/OptimizedDatabaseConnection.php
/**
 * Оптимизированное подключение к базе данных с кешированием и пулом соединений
 */
class OptimizedDatabaseConnection extends DatabaseConnection
{
    private static $queryCache = [];
    private static $preparedStatements = [];
    private static $connectionPool = [];
    private static $maxPoolSize = 5;
    private static $queryStats = [];
    
    /**
     * Выполнить кешированный запрос
     * @param PDO $connection - соединение с БД
     * @param string $query - SQL запрос
     * @param array $params - параметры запроса
     * @param int $cacheTtl - время кеширования в секундах
     * @param bool $useCache - использовать кеширование
     */
    public static function cachedQuery(
        PDO $connection, 
        string $query, 
        array $params = [], 
        int $cacheTtl = 300,
        bool $useCache = true
    ): array {
        $cacheKey = self::generateCacheKey($query, $params);
        
        // Увеличиваем счетчик запросов
        if (!isset(self::$queryStats[$query])) {
            self::$queryStats[$query] = ['count' => 0, 'cache_hits' => 0];
        }
        self::$queryStats[$query]['count']++;
        
        // Проверяем кеш только для SELECT запросов
        if ($useCache && stripos(trim($query), 'SELECT') === 0) {
            $cache = CacheService::getInstance();
            $cachedResult = $cache->get($cacheKey, $cacheTtl);
            
            if ($cachedResult !== null) {
                self::$queryStats[$query]['cache_hits']++;
                return $cachedResult;
            }
        }
        
        // Выполняем запрос
        $stmt = self::getPreparedStatement($connection, $query);
        $stmt->execute($params);
        $result = $stmt->fetchAll();
        
        // Кешируем результат только для SELECT запросов
        if ($useCache && stripos(trim($query), 'SELECT') === 0) {
            $cache = CacheService::getInstance();
            $cache->set($cacheKey, $result, $cacheTtl);
        }
        
        return $result;
    }
    
    /**
     * Выполнить единичный кешированный запрос (возвращает одну строку)
     */
    public static function cachedQuerySingle(
        PDO $connection, 
        string $query, 
        array $params = [], 
        int $cacheTtl = 300,
        bool $useCache = true
    ): ?array {
        $result = self::cachedQuery($connection, $query, $params, $cacheTtl, $useCache);
        return $result ? $result[0] : null;
    }
    
    /**
     * Выполнить запрос с автоматической инвалидацией кеша
     * @param PDO $connection
     * @param string $query
     * @param array $params
     * @param array $invalidateTables - таблицы для инвалидации кеша
     */
    public static function executeWithCacheInvalidation(
        PDO $connection, 
        string $query, 
        array $params = [], 
        array $invalidateTables = []
    ): bool {
        $stmt = self::getPreparedStatement($connection, $query);
        $result = $stmt->execute($params);
        
        // Инвалидируем кеш для затронутых таблиц
        if ($result && !empty($invalidateTables)) {
            self::invalidateCacheForTables($invalidateTables);
        }
        
        return $result;
    }
    
    /**
     * Получить подготовленное выражение (с кешированием)
     */
    private static function getPreparedStatement(PDO $connection, string $query): PDOStatement
    {
        $cacheKey = spl_object_hash($connection) . '_' . md5($query);
        
        if (!isset(self::$preparedStatements[$cacheKey])) {
            self::$preparedStatements[$cacheKey] = $connection->prepare($query);
        }
        
        return self::$preparedStatements[$cacheKey];
    }
    
    /**
     * Генерация ключа кеша для запроса
     */
    private static function generateCacheKey(string $query, array $params): string
    {
        return 'db_query_' . md5($query . serialize($params));
    }
    
    /**
     * Инвалидация кеша для таблиц
     */
    private static function invalidateCacheForTables(array $tables): void
    {
        $cache = CacheService::getInstance();
        
        foreach ($tables as $table) {
            // Очищаем кеш по паттерну таблицы
            $pattern = 'db_query_*' . $table . '*';
            // Простая реализация - можно улучшить
            $cache->delete($pattern);
        }
    }
    
    /**
     * Получить соединение с пулом
     */
    public static function getPooledConnection(string $database): PDO
    {
        // Проверяем доступные соединения в пуле
        if (isset(self::$connectionPool[$database]) && 
            count(self::$connectionPool[$database]) < self::$maxPoolSize) {
            
            return array_pop(self::$connectionPool[$database]);
        }
        
        // Создаем новое соединение
        $connection = parent::getConnection($database);
        
        // Настраиваем оптимизированные параметры
        $connection->setAttribute(PDO::ATTR_PERSISTENT, true);
        $connection->exec('SET SESSION query_cache_type = ON');
        $connection->exec('SET SESSION query_cache_size = 16777216'); // 16MB
        
        return $connection;
    }
    
    /**
     * Вернуть соединение в пул
     */
    public static function returnConnectionToPool(string $database, PDO $connection): void
    {
        if (!isset(self::$connectionPool[$database])) {
            self::$connectionPool[$database] = [];
        }
        
        if (count(self::$connectionPool[$database]) < self::$maxPoolSize) {
            self::$connectionPool[$database][] = $connection;
        }
        // Если пул полный, соединение просто закроется
    }
    
    /**
     * Получить статистику запросов
     */
    public static function getQueryStats(): array
    {
        $totalQueries = 0;
        $totalCacheHits = 0;
        
        foreach (self::$queryStats as $stats) {
            $totalQueries += $stats['count'];
            $totalCacheHits += $stats['cache_hits'];
        }
        
        $hitRatio = $totalQueries > 0 ? round(($totalCacheHits / $totalQueries) * 100, 2) : 0;
        
        return [
            'total_queries' => $totalQueries,
            'cache_hits' => $totalCacheHits,
            'hit_ratio_percent' => $hitRatio,
            'prepared_statements_count' => count(self::$preparedStatements),
            'query_details' => self::$queryStats
        ];
    }
    
    /**
     * Очистить статистику и кеш запросов
     */
    public static function clearQueryCache(): void
    {
        self::$queryCache = [];
        self::$queryStats = [];
        self::$preparedStatements = [];
    }
    
    /**
     * Метод для выполнения транзакций с кешированием
     */
    public static function transaction(PDO $connection, callable $callback, array $invalidateTables = []): mixed
    {
        $connection->beginTransaction();
        
        try {
            $result = $callback($connection);
            $connection->commit();
            
            // Инвалидируем кеш после успешной транзакции
            if (!empty($invalidateTables)) {
                self::invalidateCacheForTables($invalidateTables);
            }
            
            return $result;
        } catch (Exception $e) {
            $connection->rollBack();
            throw $e;
        }
    }
}