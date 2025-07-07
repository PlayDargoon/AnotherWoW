<?php
// src/controllers/ErrorController.php
class ErrorController
{
    public function notFound()
    {
        $data = [
            'pageTitle' => 'Ошибка 404',
            'contentFile' => 'pages/404.html.php'
        ];
        renderTemplate('layout.html.php', $data);
    }
}