<?php
// cache_cleanup.php
/**
 * Скрипт для очистки устаревшего кеша
 * Рекомендуется запускать через cron каждые 30 минут
 */
require_once __DIR__ . '/../../bootstrap.php';

$cache = CacheService::getInstance();
$cacheDir = __DIR__ . '/../../cache';

echo "Запуск очистки кеша...\n";

$cleaned = 0;
$totalSize = 0;

// Получаем все файлы кеша
$cacheFiles = glob($cacheDir . '/cache_*.php');

foreach ($cacheFiles as $file) {
    $fileTime = filemtime($file);
    $fileSize = filesize($file);
    $totalSize += $fileSize;
    
    // Проверяем, если файл старше 1 дня - удаляем
    if ($fileTime < time() - 86400) {
        unlink($file);
        $cleaned++;
        echo "Удален устаревший кеш: " . basename($file) . " (возраст: " . 
             round((time() - $fileTime) / 3600) . " часов)\n";
    }
}

// Дополнительная очистка специфичных кешей
$specificCacheFiles = [
    $cacheDir . '/index.cache.html',
    $cacheDir . '/news_list.cache.html',
    sys_get_temp_dir() . '/mmotop_votes_cache.txt',
    sys_get_temp_dir() . '/awow_serverinfo_cache.json'
];

foreach ($specificCacheFiles as $file) {
    if (file_exists($file)) {
        $fileTime = filemtime($file);
        $fileAge = time() - $fileTime;
        
        // Различные TTL для разных типов кешей
        $maxAge = match(true) {
            str_contains($file, 'index.cache') => 300,    // 5 минут для главной страницы
            str_contains($file, 'news_list') => 600,      // 10 минут для новостей  
            str_contains($file, 'mmotop_votes') => 7200,  // 2 часа для голосов
            str_contains($file, 'serverinfo') => 1800,    // 30 минут для информации сервера
            default => 3600                               // 1 час по умолчанию
        };
        
        if ($fileAge > $maxAge) {
            unlink($file);
            $cleaned++;
            echo "Удален устаревший специфичный кеш: " . basename($file) . "\n";
        }
    }
}

$stats = $cache->getStats();
$remainingFiles = $stats['total_files'];
$remainingSize = $stats['total_size_mb'];

echo "\nОчистка завершена!\n";
echo "Удалено файлов: {$cleaned}\n";
echo "Осталось файлов кеша: {$remainingFiles}\n";
echo "Размер кеша: {$remainingSize} MB\n";

// Логируем в файл
$logEntry = date('Y-m-d H:i:s') . " - Очищено: {$cleaned} файлов, Осталось: {$remainingFiles} файлов ({$remainingSize} MB)\n";
file_put_contents($cacheDir . '/cleanup.log', $logEntry, FILE_APPEND | LOCK_EX);

echo "Лог записан в " . $cacheDir . "/cleanup.log\n";
?>