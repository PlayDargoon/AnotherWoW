<?php
// controllers/SiteController.php

namespace App\Controllers;

use App\Models\Site;

class SiteController
{
    private $siteModel;

    public function __construct(Site $siteModel)
    {
        $this->siteModel = $siteModel;
    }

    public function showSiteInfo()
    {
        $siteInfo = $this->siteModel->getSiteInfo(); // Получаем данные из модели
        var_dump($siteInfo); // Выведем данные на экран
    }
}