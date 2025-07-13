<?php
// src/controllers/CharacterPageController.php

class CharacterPageController
{
    private $characterModel;

    public function __construct(Character $characterModel)
    {
        $this->characterModel = $characterModel;
    }

    public function showCharacter($characterGuid)
    {
        // Получаем информацию о персонаже по GUID
        $character = $this->characterModel->getCharacterByGuid($characterGuid);

        if (!$character) {
            // Если персонаж не найден, показываем ошибку
            throw new Exception("Персонаж не найден.");
        }

        // Конвертируем деньги
        $currency = convertMoney($character['money']);

        // Передаем данные в шаблон
        $data = [
            'contentFile' => 'pages/character_page.html.php', // Передаем путь к шаблону
            'character' => $character,
            'currency' => $currency, // Передаем информацию о валюте
        ];

        // Рендерим шаблон
        renderTemplate('layout.html.php', $data);
    }
}