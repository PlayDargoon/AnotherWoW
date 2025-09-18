<?php
// migrate_vote_log.php
// Тестирование системы голосования с локальным файлом
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/src/services/VoteService.php';

echo "Тестирование системы голосования с локальным файлом...\n";

// Временно меняем downloadContent в VoteService для тестирования
$reflectionClass = new ReflectionClass('VoteService');
$method = $reflectionClass->getMethod('downloadContent');
$method->setAccessible(true);

try {
    $voteService = new VoteService();
    
    // Тестируем с локальным файлом
    $localFile = __DIR__ . '/cache/test_votes.txt';
    $content = file_get_contents($localFile);
    
    echo "Загружен тестовый файл, размер: " . strlen($content) . " байт\n";
    echo "Содержимое:\n" . $content . "\n\n";
    
    // Создаем временный VoteService для обработки контента
    echo "Тестируем парсинг голосов...\n";
    
    $lines = preg_split('/\r?\n/', $content, -1, PREG_SPLIT_NO_EMPTY);
    $processed = 0;
    
    foreach ($lines as $line) {
        echo "Строка: $line\n";
        if (preg_match('/^(\d+)\s+(\d{2}\.\d{2}\.\d{4})\s+(\d{2}:\d{2}:\d{2})\s+(\S+)\s+(\S+)\s+(\d+)$/u', $line, $matches)) {
            echo "  ✓ Парсинг успешен: логин={$matches[5]}, дата={$matches[2]} {$matches[3]}\n";
            $processed++;
        } else {
            echo "  ❌ Не удалось распарсить\n";
        }
    }
    
    echo "\nУспешно распарсено: $processed строк\n";
    
} catch (Exception $e) {
    echo "Ошибка: " . $e->getMessage() . "\n";
}
