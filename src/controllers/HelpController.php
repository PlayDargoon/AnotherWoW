<?php

class HelpController {
    public function handle() {
        $data = [
            'contentFile' => 'pages/help.html.php',
            'pageTitle' => 'Помощь новичкам'
        ];
        renderTemplate('layout.html.php', $data);
    }
}