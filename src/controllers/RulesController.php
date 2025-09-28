<?php
// src/controllers/RulesController.php

class RulesController
{
    /**
     * Отображает страницу правил игровых миров
     */
    public function index()
    {
        // Передаем данные в шаблон
        $data = [
            'contentFile' => 'pages/rules.html.php',
            'pageTitle' => 'Правила игровых миров',
        ];
        renderTemplate('layout.html.php', $data);
    }
}