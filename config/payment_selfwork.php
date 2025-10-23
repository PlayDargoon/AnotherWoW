<?php
// config/payment_selfwork.php
// Настройки интеграции с Selfwork (pro.selfwork.ru)
return [
    'enabled' => true,
    'debug' => false, // Отключаем debug для продакшена
    'base_url' => 'https://pro.selfwork.ru',
    
    // ID магазина (из раздела "Параметры магазина" в личном кабинете)
    'merchant_id' => '0806707',
    
    // Секретный ключ (из раздела "Параметры магазина" в личном кабинете)
    'secret_key' => 'ZRLrTFRkkm3JYLpZfUqh1fhmmh18oswi',
    
    // Базовый URL вашего сайта (для формирования ссылок редиректа/вебхука)
    'site_base' => 'https://azeroth.su',
    
    // Путь вебхука на вашем сайте
    'webhook_path' => '/selfwork/webhook',
    
    // Курс конвертации: 1 рубль = 1 бонус
    'rub_to_coins_rate' => 1.0,
];


