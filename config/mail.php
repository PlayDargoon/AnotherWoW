<?php
// config/mail.php
// Конфигурация почтовой системы
// Заполните значения под ваш SMTP. Если smtp.enabled=false, будет использоваться mail() PHP.

return [
    // Основные адреса
    'support_email' => 'support@azeroth.su',
    'from_email'    => 'no-reply@azeroth.su',
    'from_name'     => 'Azeroth Support',

    // SMTP-настройки
    'smtp' => [
        'enabled'   => false,           // true чтобы отправлять через SMTP (рекомендуется)
        'host'      => 'smtp.example.com',
        'port'      => 587,
        'username'  => 'smtp-user',
        'password'  => 'smtp-password',
        'encryption'=> 'tls',           // tls | ssl | ''
        'auth'      => true
    ],
];