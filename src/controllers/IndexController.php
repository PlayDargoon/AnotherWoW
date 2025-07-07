<?php
// src/controllers/IndexController.php

class IndexController
{
    private $characterModel;

    public function __construct(Character $characterModel)
    {
        $this->characterModel = $characterModel;
    }

    /**
     * Метод отображения главной страницы
     */
    public function index()
    {
        // Получаем количество персонажей
        $playerCount = $this->characterModel->getPlayerCount();

        // Определяем заголовок страницы
        $pageTitle = 'Главная страница';

        // Передаем данные в шаблон
        $data = [
            'pageTitle' => $pageTitle,
            'contentFile' => 'pages/index.html.php', // Добавляем путь к шаблону
            'playerCount' => $playerCount, // Передаем количество персонажей
        ];

        // Рендерим шаблон
        renderTemplate('layout.html.php', $data);
    }
}