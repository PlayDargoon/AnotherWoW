<?php
// src/controllers/TermsController.php

class TermsController
{
    /**
     * Отображает страницу пользовательского соглашения
     */
    public function index()
    {
        // Передаем данные в шаблон
        $data = [
            'contentFile' => 'pages/terms.html.php',
            'pageTitle' => 'Пользовательское соглашение',
        ];
        renderTemplate('layout.html.php', $data);
    }
}