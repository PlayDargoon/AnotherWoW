<?php
/**
 * Скрипт для автоматической проверки и исправления проблем "headers already sent"
 */

echo "=== Проверка проблем с заголовками ===\n\n";

function checkFileForWhitespace($filePath) {
    $content = file_get_contents($filePath);
    $issues = [];
    
    // Проверка на пробелы/символы перед <?php
    if (preg_match('/^[\s\r\n]+<\?php/', $content)) {
        $issues[] = "Найдены пробелы или переносы строк перед <?php";
    }
    
    // Проверка на символы после ?>
    if (preg_match('/\?>\s*[^\s]/', $content)) {
        $issues[] = "Найдены символы после ?>";
    }
    
    // Проверка на нежелательные закрывающие теги в конце
    if (preg_match('/\?>\s*$/', $content) && strpos($filePath, 'template') === false) {
        $issues[] = "Файл содержит закрывающий тег ?> в конце (рекомендуется убрать для PHP файлов)";
    }
    
    return $issues;
}

function scanDirectory($directory) {
    $problems = [];
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($directory),
        RecursiveIteratorIterator::SELF_FIRST
    );
    
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $filePath = $file->getRealPath();
            
            // Пропускаем библиотеки
            if (strpos($filePath, 'phpmailer') !== false || strpos($filePath, 'wireframe') !== false) {
                continue;
            }
            
            $issues = checkFileForWhitespace($filePath);
            if (!empty($issues)) {
                $problems[$filePath] = $issues;
            }
        }
    }
    
    return $problems;
}

// Проверяем основные директории
$directories = [
    __DIR__ . '/../src',
    __DIR__ . '/../public',
    __DIR__ . '/../'
];

$allProblems = [];
foreach ($directories as $dir) {
    if (is_dir($dir)) {
        $problems = scanDirectory($dir);
        $allProblems = array_merge($allProblems, $problems);
    }
}

if (empty($allProblems)) {
    echo "✅ Проблем с заголовками не найдено!\n";
} else {
    echo "❌ Найдены проблемы в следующих файлах:\n\n";
    foreach ($allProblems as $file => $issues) {
        echo "📁 " . basename($file) . "\n";
        foreach ($issues as $issue) {
            echo "   ⚠️  $issue\n";
        }
        echo "\n";
    }
    
    echo "\n💡 Рекомендации:\n";
    echo "1. Убедитесь, что нет пробелов или переносов перед открывающим тегом\n";
    echo "2. Удалите закрывающие теги в конце PHP файлов\n";  
    echo "3. Используйте буферизацию вывода ob_start() перед отправкой заголовков\n";
    echo "4. Проверьте кодировку файлов (должна быть UTF-8 без BOM)\n";
}

echo "\n=== Проверка завершена ===\n";

