<?php
// src/controllers/MigrationController.php
// Контроллер для управления миграциями базы данных

class MigrationController {
    
    /**
     * Запуск всех миграций
     */
    public function runAll() {
        echo "Запуск всех миграций...\n";
        
        $migrations = [
            'CreateVoteTables.php' => 'Создание таблиц голосования',
        ];
        
        foreach ($migrations as $file => $description) {
            echo "\n--- $description ---\n";
            $this->runMigration($file);
        }
        
        echo "\nВсе миграции выполнены!\n";
    }
    
    /**
     * Запуск конкретной миграции
     */
    public function runMigration($fileName) {
        $migrationPath = __DIR__ . '/../../database/migrations/' . $fileName;
        
        if (!file_exists($migrationPath)) {
            echo "Ошибка: файл миграции $fileName не найден!\n";
            return false;
        }
        
        try {
            include $migrationPath;
            return true;
        } catch (Exception $e) {
            echo "Ошибка выполнения миграции $fileName: " . $e->getMessage() . "\n";
            return false;
        }
    }
    
    /**
     * Проверка состояния базы данных
     */
    public function checkDatabase() {
        $migrationPath = __DIR__ . '/../../database/migrations/CheckDatabase.php';
        include $migrationPath;
    }
    
    /**
     * Создание новой миграции
     */
    public function createMigration($name) {
        $timestamp = date('Y_m_d_His');
        $className = ucfirst(str_replace(' ', '', $name));
        $fileName = $timestamp . '_' . $className . '.php';
        $filePath = __DIR__ . '/../../database/migrations/' . $fileName;
        
        $template = "<?php\n// database/migrations/$fileName\n// $name\n\nrequire_once __DIR__ . '/../../bootstrap.php';\n\necho \"Выполнение миграции: $name...\\n\";\n\ntry {\n    \$siteDb = DatabaseConnection::getSiteConnection();\n    \n    // TODO: Добавить код миграции здесь\n    \n    echo \"✓ Миграция выполнена успешно\\n\";\n    \n} catch (Exception \$e) {\n    echo \"Ошибка миграции: \" . \$e->getMessage() . \"\\n\";\n    exit(1);\n}\n";
        
        file_put_contents($filePath, $template);
        echo "Создана миграция: $fileName\n";
        echo "Путь: $filePath\n";
        
        return $fileName;
    }
}