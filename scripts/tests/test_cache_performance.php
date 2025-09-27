<?php
// test_cache_performance.php
/**
 * Тест производительности кеширования
 */
require_once __DIR__ . '/../../bootstrap.php';

echo "=== ТЕСТ ПРОИЗВОДИТЕЛЬНОСТИ СИСТЕМЫ КЕШИРОВАНИЯ ===\n\n";

// Конфигурация теста
$testAccountId = 1; // ID тестового аккаунта
$testIterations = 100; // Количество итераций для теста
$queryTypes = [
    'simple_select' => 'SELECT * FROM account_coins WHERE account_id = ? LIMIT 1',
    'aggregate' => 'SELECT COUNT(*) as count, SUM(coins) as total FROM account_coins WHERE account_id = ?',
    'complex_join' => 'SELECT ac.*, u.username FROM account_coins ac JOIN acore_auth.account u ON ac.account_id = u.id WHERE ac.account_id = ? LIMIT 5'
];

echo "Подготовка тестовых данных...\n";

// Создаем тестовые данные если их нет
$siteDb = DatabaseConnection::getSiteConnection();
$checkStmt = $siteDb->prepare("SELECT COUNT(*) as count FROM account_coins WHERE account_id = ?");
$checkStmt->execute([$testAccountId]);
$existingRecords = $checkStmt->fetch()['count'];

if ($existingRecords < 10) {
    echo "Создаем тестовые записи для аккаунта {$testAccountId}...\n";
    $insertStmt = $siteDb->prepare("INSERT INTO account_coins (account_id, coins, reason, created_at) VALUES (?, ?, ?, NOW())");
    
    for ($i = 0; $i < 20; $i++) {
        $insertStmt->execute([$testAccountId, rand(1, 5), "Тестовое начисление #" . ($i + 1)]);
    }
    echo "Создано 20 тестовых записей.\n\n";
}

echo "=== ТЕСТ 1: ПРЯМЫЕ ЗАПРОСЫ К БД (БЕЗ КЕША) ===\n";

$results = [];

foreach ($queryTypes as $name => $query) {
    echo "Тест запроса: {$name}...\n";
    
    $startTime = microtime(true);
    $memoryStart = memory_get_usage();
    
    for ($i = 0; $i < $testIterations; $i++) {
        $stmt = $siteDb->prepare($query);
        $stmt->execute([$testAccountId]);
        $result = $stmt->fetchAll();
    }
    
    $endTime = microtime(true);
    $memoryEnd = memory_get_usage();
    
    $results[$name]['no_cache'] = [
        'time' => $endTime - $startTime,
        'memory' => $memoryEnd - $memoryStart,
        'avg_time' => ($endTime - $startTime) / $testIterations
    ];
    
    echo "  Время: " . number_format($endTime - $startTime, 4) . " сек\n";
    echo "  Память: " . number_format(($memoryEnd - $memoryStart) / 1024, 2) . " KB\n";
    echo "  Среднее время на запрос: " . number_format(($endTime - $startTime) / $testIterations * 1000, 2) . " мс\n\n";
}

echo "=== ТЕСТ 2: КЕШИРОВАННЫЕ ЗАПРОСЫ (ПЕРВЫЙ ПРОХОД - ЗАПОЛНЕНИЕ КЕША) ===\n";

foreach ($queryTypes as $name => $query) {
    echo "Тест кешированного запроса: {$name}...\n";
    
    $startTime = microtime(true);
    $memoryStart = memory_get_usage();
    
    for ($i = 0; $i < $testIterations; $i++) {
        $result = OptimizedDatabaseConnection::cachedQuery($siteDb, $query, [$testAccountId], 600, true);
    }
    
    $endTime = microtime(true);
    $memoryEnd = memory_get_usage();
    
    $results[$name]['first_cache'] = [
        'time' => $endTime - $startTime,
        'memory' => $memoryEnd - $memoryStart,
        'avg_time' => ($endTime - $startTime) / $testIterations
    ];
    
    echo "  Время: " . number_format($endTime - $startTime, 4) . " сек\n";
    echo "  Память: " . number_format(($memoryEnd - $memoryStart) / 1024, 2) . " KB\n\n";
}

echo "=== ТЕСТ 3: КЕШИРОВАННЫЕ ЗАПРОСЫ (ВТОРОЙ ПРОХОД - ЧТЕНИЕ ИЗ КЕША) ===\n";

foreach ($queryTypes as $name => $query) {
    echo "Тест чтения из кеша: {$name}...\n";
    
    $startTime = microtime(true);
    $memoryStart = memory_get_usage();
    
    for ($i = 0; $i < $testIterations; $i++) {
        $result = OptimizedDatabaseConnection::cachedQuery($siteDb, $query, [$testAccountId], 600, true);
    }
    
    $endTime = microtime(true);
    $memoryEnd = memory_get_usage();
    
    $results[$name]['cached'] = [
        'time' => $endTime - $startTime,
        'memory' => $memoryEnd - $memoryStart,
        'avg_time' => ($endTime - $startTime) / $testIterations
    ];
    
    echo "  Время: " . number_format($endTime - $startTime, 4) . " сек\n";
    echo "  Память: " . number_format(($memoryEnd - $memoryStart) / 1024, 2) . " KB\n\n";
}

echo "=== ТЕСТ 4: КЕШИРОВАННЫЕ МОДЕЛИ ===\n";

$cachedModel = new CachedAccountCoins($siteDb);

echo "Тест CachedAccountCoins (первый вызов)...\n";
$startTime = microtime(true);
$memoryStart = memory_get_usage();

for ($i = 0; $i < $testIterations; $i++) {
    $balance = $cachedModel->getBalance($testAccountId);
    $history = $cachedModel->getHistory($testAccountId, 5);
}

$endTime = microtime(true);
$memoryEnd = memory_get_usage();

$results['model']['first'] = [
    'time' => $endTime - $startTime,
    'memory' => $memoryEnd - $memoryStart,
    'avg_time' => ($endTime - $startTime) / $testIterations
];

echo "  Время: " . number_format($endTime - $startTime, 4) . " сек\n";
echo "  Память: " . number_format(($memoryEnd - $memoryStart) / 1024, 2) . " KB\n\n";

echo "Тест CachedAccountCoins (из кеша)...\n";
$startTime = microtime(true);
$memoryStart = memory_get_usage();

for ($i = 0; $i < $testIterations; $i++) {
    $balance = $cachedModel->getBalance($testAccountId);
    $history = $cachedModel->getHistory($testAccountId, 5);
}

$endTime = microtime(true);
$memoryEnd = memory_get_usage();

$results['model']['cached'] = [
    'time' => $endTime - $startTime,
    'memory' => $memoryEnd - $memoryStart,
    'avg_time' => ($endTime - $startTime) / $testIterations
];

echo "  Время: " . number_format($endTime - $startTime, 4) . " сек\n";
echo "  Память: " . number_format(($memoryEnd - $memoryStart) / 1024, 2) . " KB\n\n";

echo "=== СВОДНАЯ ТАБЛИЦА РЕЗУЛЬТАТОВ ===\n";
echo str_pad("Запрос", 20) . str_pad("Без кеша", 12) . str_pad("1-й кеш", 12) . str_pad("Из кеша", 12) . str_pad("Ускорение", 12) . "\n";
echo str_repeat("-", 68) . "\n";

foreach ($results as $name => $data) {
    if ($name === 'model') continue;
    
    $speedup = $data['no_cache']['time'] / $data['cached']['time'];
    
    echo str_pad($name, 20) . 
         str_pad(number_format($data['no_cache']['time'], 4), 12) . 
         str_pad(number_format($data['first_cache']['time'], 4), 12) . 
         str_pad(number_format($data['cached']['time'], 4), 12) . 
         str_pad(number_format($speedup, 1) . "x", 12) . "\n";
}

if (isset($results['model'])) {
    $modelSpeedup = $results['model']['first']['time'] / $results['model']['cached']['time'];
    echo str_pad("cached_model", 20) . 
         str_pad("-", 12) . 
         str_pad(number_format($results['model']['first']['time'], 4), 12) . 
         str_pad(number_format($results['model']['cached']['time'], 4), 12) . 
         str_pad(number_format($modelSpeedup, 1) . "x", 12) . "\n";
}

echo "\n=== СТАТИСТИКА КЕША ===\n";
$cacheStats = CacheService::getInstance()->getStats();
$queryStats = OptimizedDatabaseConnection::getQueryStats();

echo "Файлов кеша: {$cacheStats['total_files']}\n";
echo "Размер кеша: {$cacheStats['total_size_mb']} MB\n";
echo "Элементов в памяти: {$cacheStats['memory_cache_items']}\n";
echo "Всего запросов к БД: {$queryStats['total_queries']}\n";
echo "Попаданий в кеш: {$queryStats['cache_hits']}\n";
echo "Процент попаданий: {$queryStats['hit_ratio_percent']}%\n";

echo "\n=== РЕКОМЕНДАЦИИ ===\n";
$avgSpeedup = 0;
$count = 0;
foreach ($results as $name => $data) {
    if ($name !== 'model' && isset($data['cached'])) {
        $avgSpeedup += $data['no_cache']['time'] / $data['cached']['time'];
        $count++;
    }
}
$avgSpeedup = $count > 0 ? $avgSpeedup / $count : 0;

if ($avgSpeedup > 10) {
    echo "✅ Отличная производительность кеша! Среднее ускорение: " . number_format($avgSpeedup, 1) . "x\n";
} elseif ($avgSpeedup > 5) {
    echo "✅ Хорошая производительность кеша. Среднее ускорение: " . number_format($avgSpeedup, 1) . "x\n";
} elseif ($avgSpeedup > 2) {
    echo "⚠️ Умеренная производительность кеша. Среднее ускорение: " . number_format($avgSpeedup, 1) . "x\n";
} else {
    echo "❌ Низкая эффективность кеша. Возможно, стоит пересмотреть настройки.\n";
}

echo "\nТест завершен. Итерации: {$testIterations}\n";
?>