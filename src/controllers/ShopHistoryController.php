<?php
// src/controllers/ShopHistoryController.php

class ShopHistoryController
{
    private $db;

    public function __construct()
    {
        $this->db = DatabaseConnection::getSiteConnection();
    }

    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            safeRedirect('/login');
            return;
        }

        $userId = $_SESSION['user_id'];
        
        // Получаем историю покупок из базы данных
        $stmt = $this->db->prepare("SELECT created_at, reason, coins FROM account_coins WHERE account_id = ? AND reason LIKE 'shop:%' ORDER BY created_at DESC LIMIT 100");
        $stmt->execute([$userId]);
        $history = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Получаем информацию о сервере для передачи в layout
        $uptimeModel = new Uptime(DatabaseConnection::getAuthConnection());
        $siteModel = new Site(DatabaseConnection::getSiteConnection());
        $serverInfo = getServerInfo($uptimeModel, $siteModel);

        // Рендерим шаблон с данными
        renderTemplate('layout.html.php', [
            'contentFile' => 'pages/shop_history.html.php',
            'pageTitle' => 'История покупок',
            'serverInfo' => $serverInfo,
            'history' => $history,
        ]);
    }
}
