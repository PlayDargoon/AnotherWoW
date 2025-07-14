<?php
// src/controllers/CharacterPageController.php

class CharacterPageController
{
    private $characterModel;
    private $userModel;

    public function __construct(Character $characterModel, User $userModel)
    {
        $this->characterModel = $characterModel;
        $this->userModel = $userModel;
    }

    public function showCharacter($characterGuid)
    {
        // Получаем информацию о персонаже по GUID
        $character = $this->characterModel->getCharacterByGuid($characterGuid);

        if (!$character) {
            // Если персонаж не найден, показываем ошибку
            throw new Exception("Персонаж не найден.");
        }

        // Получаем название сервера из базы данных
        $serverName = $this->userModel->getDefaultRealmName();

        // Конвертируем деньги
        $currency = convertMoney($character['money']);

        // Массивы для отображения расы и класса
        $races = [
            1 => 'Человек',
            2 => 'Орк',
            3 => 'Дварф',
            4 => 'Ночной Эльф',
            5 => 'Нежить',
            6 => 'Таурен',
            7 => 'Гном',
            8 => 'Тролль',
            10 => 'Эльф Крови',
            11 => 'Дреней',
        ];

        $classes = [
            1 => 'Воин',
            2 => 'Паладин',
            3 => 'Охотник',
            4 => 'Разбойник',
            5 => 'Жрец',
            6 => 'Рыцарь Смерти',
            7 => 'Шаман',
            8 => 'Маг',
            9 => 'Чернокнижник',
            11 => 'Друид',
        ];

        // Определяем изображение фракции
        $factionImage = getFactionImage($character['race']);

        // Получаем статистику персонажа
        $stats = $this->characterModel->getStatsByGuid($characterGuid);

        // Обновляем уровень GM персонажа из таблицы account_access
        $gmLevel = $this->userModel->getGmLevelForCharacter($character['name']);
        $roleText = getGMRole($gmLevel);

        

        // Передаем данные в шаблон
        $data = [
            'contentFile' => 'pages/character_page.html.php', // Передаем путь к шаблону
            'character' => $character,
            'currency' => $currency,
            'serverName' => $serverName, // Передаем название сервера
            'races' => $races, // Передаем массив расы
            'classes' => $classes, // Передаем массив класса
            'factionImage' => $factionImage, // Передаем изображение фракции
            'roleText' => $roleText, // Передаем текст роли
            'stats' => $stats, // Передаем статистику персонажа
        ];

        // Рендерим шаблон
        renderTemplate('layout.html.php', $data);
    }
}