<?php
// cache_monitor.php
/**
 * Утилита для мониторинга производительности кеша и базы данных
 */
require_once __DIR__ . '/../../bootstrap.php';

echo "<h1>Мониторинг кеша и производительности базы данных</h1>\n";

// Статистика кеша
echo "<h2>Статистика кеша</h2>\n";
$cacheService = CacheService::getInstance();
$cacheStats = $cacheService->getStats();

echo "<table border='1' style='border-collapse: collapse;'>\n";
echo "<tr><th>Метрика</th><th>Значение</th></tr>\n";
echo "<tr><td>Общее количество файлов кеша</td><td>{$cacheStats['total_files']}</td></tr>\n";
echo "<tr><td>Размер кеша (MB)</td><td>{$cacheStats['total_size_mb']}</td></tr>\n";
echo "<tr><td>Устаревших файлов</td><td>{$cacheStats['expired_files']}</td></tr>\n";
echo "<tr><td>Элементов в памяти</td><td>{$cacheStats['memory_cache_items']}</td></tr>\n";
echo "</table>\n";

// Тест производительности
echo "<h2>Тест производительности</h2>\n";

if (isset($_SESSION['user_id'])) {
    $userModel = new User(DatabaseConnection::getAuthConnection());
    $userInfo = $userModel->getUserInfoByUsername($_SESSION['username'] ?? '');
    
    if ($userInfo) {
        $accountId = $userInfo['id'];
        
        // Тест без кеша
        $startTime = microtime(true);
        $regularModel = new AccountCoins(DatabaseConnection::getSiteConnection());
        $balance1 = $regularModel->getBalance($accountId);
        $history1 = $regularModel->getHistory($accountId, 10);
        $timeWithoutCache = microtime(true) - $startTime;
        
        // Тест с кешем (первый раз - заполнение кеша)
        $startTime = microtime(true);
        $cachedModel = new CachedAccountCoins(DatabaseConnection::getSiteConnection());
        $balance2 = $cachedModel->getBalance($accountId);
        $history2 = $cachedModel->getHistory($accountId, 10);
        $timeWithCache1 = microtime(true) - $startTime;
        
        // Тест с кешем (второй раз - чтение из кеша)
        $startTime = microtime(true);
        $balance3 = $cachedModel->getBalance($accountId);
        $history3 = $cachedModel->getHistory($accountId, 10);
        $timeWithCache2 = microtime(true) - $startTime;
        
        echo "<table border='1' style='border-collapse: collapse;'>\n";
        echo "<tr><th>Тип запроса</th><th>Время (сек)</th><th>Ускорение</th></tr>\n";
        echo "<tr><td>Без кеша</td><td>" . number_format($timeWithoutCache, 6) . "</td><td>-</td></tr>\n";
        echo "<tr><td>С кешем (первый запрос)</td><td>" . number_format($timeWithCache1, 6) . "</td><td>" . 
             number_format($timeWithoutCache / $timeWithCache1, 2) . "x</td></tr>\n";
        echo "<tr><td>С кешем (из кеша)</td><td>" . number_format($timeWithCache2, 6) . "</td><td>" . 
             number_format($timeWithoutCache / $timeWithCache2, 2) . "x</td></tr>\n";
        echo "</table>\n";
        
        echo "<p><strong>Баланс:</strong> {$balance1} (без кеша), {$balance2} (с кешем), {$balance3} (из кеша)</p>\n";
    } else {
        echo "<p>Пользователь не найден для тестирования.</p>\n";
    }
} else {
    echo "<p>Для тестирования производительности необходимо авторизоваться.</p>\n";
}

// Статистика базы данных
echo "<h2>Статистика запросов к базе данных</h2>\n";
$queryStats = OptimizedDatabaseConnection::getQueryStats();

echo "<table border='1' style='border-collapse: collapse;'>\n";
echo "<tr><th>Метрика</th><th>Значение</th></tr>\n";
echo "<tr><td>Общее количество запросов</td><td>{$queryStats['total_queries']}</td></tr>\n";
echo "<tr><td>Попаданий в кеш</td><td>{$queryStats['cache_hits']}</td></tr>\n";
echo "<tr><td>Процент попаданий</td><td>{$queryStats['hit_ratio_percent']}%</td></tr>\n";
echo "<tr><td>Подготовленных выражений</td><td>{$queryStats['prepared_statements_count']}</td></tr>\n";
echo "</table>\n";

if (!empty($queryStats['query_details'])) {
    echo "<h3>Детальная статистика запросов</h3>\n";
    echo "<table border='1' style='border-collapse: collapse;'>\n";
    echo "<tr><th>Запрос (первые 50 символов)</th><th>Количество</th><th>Попаданий в кеш</th><th>% попаданий</th></tr>\n";
    
    foreach ($queryStats['query_details'] as $query => $stats) {
        $shortQuery = substr($query, 0, 50) . (strlen($query) > 50 ? '...' : '');
        $hitRate = $stats['count'] > 0 ? round(($stats['cache_hits'] / $stats['count']) * 100, 1) : 0;
        
        echo "<tr>";
        echo "<td>" . htmlspecialchars($shortQuery) . "</td>";
        echo "<td>{$stats['count']}</td>";
        echo "<td>{$stats['cache_hits']}</td>";
        echo "<td>{$hitRate}%</td>";
        echo "</tr>\n";
    }
    echo "</table>\n";
}

// Действия управления кешем
echo "<h2>Управление кешем</h2>\n";
echo "<form method='post'>\n";
echo "<button type='submit' name='action' value='clear_cache'>Очистить весь кеш</button>\n";
echo "<button type='submit' name='action' value='clear_query_stats'>Очистить статистику запросов</button>\n";
echo "</form>\n";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'clear_cache':
            $cacheService->clear();
            echo "<p style='color: green;'>Кеш очищен!</p>\n";
            break;
            
        case 'clear_query_stats':
            OptimizedDatabaseConnection::clearQueryCache();
            echo "<p style='color: green;'>Статистика запросов очищена!</p>\n";
            break;
    }
}

echo "<h2>Рекомендации по оптимизации</h2>\n";

$recommendations = [];

if ($cacheStats['expired_files'] > 50) {
    $recommendations[] = "Много устаревших файлов кеша ({$cacheStats['expired_files']}). Рекомендуется очистить кеш.";
}

if ($cacheStats['total_size_mb'] > 100) {
    $recommendations[] = "Размер кеша превышает 100MB ({$cacheStats['total_size_mb']}MB). Возможно, стоит уменьшить TTL.";
}

if (isset($queryStats['hit_ratio_percent']) && $queryStats['hit_ratio_percent'] < 30) {
    $recommendations[] = "Низкий процент попаданий в кеш запросов ({$queryStats['hit_ratio_percent']}%). Возможно, стоит увеличить TTL для некоторых запросов.";
}

if (empty($recommendations)) {
    echo "<p style='color: green;'>Система кеширования работает оптимально!</p>\n";
} else {
    echo "<ul style='color: orange;'>\n";
    foreach ($recommendations as $rec) {
        echo "<li>{$rec}</li>\n";
    }
    echo "</ul>\n";
}

echo "<hr>\n";
echo "<p><small>Для получения точных данных о производительности рекомендуется запускать мониторинг под нагрузкой.</small></p>\n";
?>