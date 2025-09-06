<?php
// models/Site.php

namespace App\Models;

use PDO;

class Site
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Пример метода для выборки всей информации из таблицы site_info
    public function getSiteInfo()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM site_info");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Остальные методы...
}