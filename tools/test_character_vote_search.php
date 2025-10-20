<?php
// test_character_vote_search.php - Тестируем поиск аккаунтов по логину и имени персонажа

require_once 'bootstrap.php';
require_once 'src/models/User.php';

try {
    echo "=== Тест поиска аккаунтов по логину и имени персонажа ===\n";
    
    $userModel = new User(\DatabaseConnection::getAuthConnection());
    
    // Тестовые данные для поиска
    $testCases = [
        'Admin',        // Логин аккаунта
        'Toxa65',       // Логин аккаунта  
        'cool',         // Логин аккаунта
        'TestCharacter', // Возможное имя персонажа
        'NonExistent',   // Несуществующий
    ];
    
    // Получаем несколько реальных персонажей для тестирования
    echo "1. Получаем примеры персонажей из базы:\n";
    try {
        $charactersDb = \DatabaseConnection::getCharactersConnection();
        $stmt = $charactersDb->query("SELECT name, account FROM characters LIMIT 5");
        $characters = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($characters as $character) {
            echo "   Персонаж: {$character['name']} -> Аккаунт ID: {$character['account']}\n";
            // Добавляем имена персонажей в тесты
            if (!in_array($character['name'], $testCases)) {
                $testCases[] = $character['name'];
            }
        }
    } catch (Exception $e) {
        echo "   ⚠️ Не удалось получить персонажей: " . $e->getMessage() . "\n";
    }
    
    echo "\n2. Тестируем поиск аккаунтов:\n";
    
    foreach ($testCases as $testName) {
        echo "\n--- Тест: '$testName' ---\n";
        
        // Старый метод (только по логину)
        $oldResult = $userModel->getUserIdByUsername($testName);
        echo "Старый метод (логин): " . ($oldResult ? "найден ID $oldResult" : "не найден") . "\n";
        
        // Новый метод (логин + персонаж)
        $newResult = $userModel->getAccountIdByUsernameOrCharacter($testName);
        if ($newResult) {
            echo "Новый метод: найден ID {$newResult['account_id']}\n";
            echo "  Способ поиска: {$newResult['method']}\n";
            echo "  Логин аккаунта: {$newResult['found_username']}\n";
            if (isset($newResult['character_name'])) {
                echo "  Имя персонажа: {$newResult['character_name']}\n";
            }
        } else {
            echo "Новый метод: не найден\n";
        }
    }
    
    echo "\n=== Тест завершен ===\n";
    
} catch (Exception $e) {
    echo "ОШИБКА: " . $e->getMessage() . "\n";
    echo "Трассировка: " . $e->getTraceAsString() . "\n";
}