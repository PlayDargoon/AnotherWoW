<?php
// src/controllers/MaintenanceController.php

class MaintenanceController
{
    /**
     * Метод отображения страницы технического обслуживания
     */
    public function index()
    {
        // Определяем заголовок страницы
        $pageTitle = 'Техническое обслуживание';

        

        

         // Получаем количество игроков и игроков онлайн
        $characterModel = new Character(DatabaseConnection::getCharactersConnection());
        $playerCounts = $characterModel->getPlayerCounts();
        $playerCountsByFaction = $characterModel->getPlayerCountsByFaction();

        // Получаем информацию о игровом мире
        $realmInfo = $uptimeModel->getRealmInfo();

        // Передаем данные в шаблон
        $data = [
            'pageTitle' => $pageTitle,
            'contentFile' => 'pages/Maintenance.html.php', // Добавляем путь к шаблону
           
        
            
        ];

        // Рендерим шаблон
        renderTemplate('layout.html.php', $data);
    }

   

    
}
