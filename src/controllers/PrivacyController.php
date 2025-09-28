<?php

class PrivacyController {
    public function handle() {
        // Передаем данные в шаблон
        $data = [
            'contentFile' => 'pages/privacy.html.php',
            'pageTitle' => 'Политика конфиденциальности - Azeroth',
        ];
        
        renderTemplate('layout.html.php', $data);
    }
}