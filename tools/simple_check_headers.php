<?php
/**
 * Простая проверка проблемных файлов (headers already sent)
 */

echo "=== Проверка проблем с заголовками ===\n\n";

// Список файлов для проверки (обновите пути при необходимости)
$filesToCheck = [
    __DIR__ . '/../src/models/User.php',
    __DIR__ . '/../src/controllers/LogoutController.php', 
    __DIR__ . '/../src/controllers/CharacterPageController.php',
    __DIR__ . '/../public/index.php',
    __DIR__ . '/../bootstrap.php',
    __DIR__ . '/../router.php'
];

$foundProblems = false;

foreach ($filesToCheck as $file) {
    if (!file_exists($file)) {
        continue;
    }
    
    $content = file_get_contents($file);
    $basename = basename($file);
    
    echo "🔍 Проверяю $basename...\n";
    
    // Проверка на символы перед открывающим тегом
    if (substr($content, 0, 5) !== '<?php') {
        echo "   ❌ Есть символы перед открывающим тегом PHP\n";
        $foundProblems = true;
        
        // Показываем первые 20 символов для диагностики
        $firstChars = substr($content, 0, 20);
        $firstChars = str_replace("\r", '[CR]', $firstChars);
        $firstChars = str_replace("\n", '[LF]', $firstChars);
        $firstChars = str_replace("\t", '[TAB]', $firstChars);
        echo "   📝 Первые символы: $firstChars\n";
    } else {
        echo "   ✅ Начинается корректно с <?php\n";
    }
    
    // Проверка на закрывающие теги в конце (для основных PHP файлов, не шаблонов)
    if (!strpos($file, 'template') && !strpos($file, 'html.php')) {
        if (preg_match('/\?>\s*$/', $content)) {
            echo "   ⚠️  Содержит закрывающий тег в конце (рекомендуется убрать)\n";
            $foundProblems = true;
        }
    }
    
    echo "\n";
}

if (!$foundProblems) {
    echo "✅ Серьезных проблем не найдено!\n\n";
} else {
    echo "❌ Найдены проблемы, которые могут вызывать 'headers already sent'\n\n";
}

echo "💡 Дополнительные рекомендации:\n";
echo "- Убедитесь, что файлы сохранены в UTF-8 без BOM\n";
echo "- Используйте буферизацию вывода ob_start() перед редиректами\n";
echo "- Проверьте, что не выводится HTML или пробелы до редиректа\n\n";

echo "=== Проверка завершена ===\n";

