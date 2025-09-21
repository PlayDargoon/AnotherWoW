<?php
// src/controllers/NewsListController.php

class NewsListController {
    public function handle() {
    require_once __DIR__ . '/../services/DatabaseConnection.php';
    $newsModel = new News(DatabaseConnection::getSiteConnection());
        $newsList = $newsModel->getAll();
    $pageTitle = 'Новости';
    $contentFile = 'pages/news_list.html.php';
    require __DIR__ . '/../../templates/layout.html.php';
    }
}
