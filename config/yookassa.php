<?php
// config/yookassa.php
return [
    'shop_id' => getenv('YOOKASSA_SHOP_ID') ?: 'your_shop_id',
    'secret_key' => getenv('YOOKASSA_SECRET_KEY') ?: 'your_secret_key',
    'api_base' => 'https://api.yookassa.ru/v3',
    'test_mode' => true,
    // Курс конвертации: 1 RUB -> N монет (по умолчанию 1:1)
    'rub_to_coins_rate' => (float)(getenv('YOOKASSA_RUB_TO_COINS_RATE') ?: 1),
    // URLы возврата и ошибки
    'return_url' => getenv('YOOKASSA_RETURN_URL') ?: 'http://localhost:8000/payment/return',
    'error_url' => getenv('YOOKASSA_ERROR_URL') ?: 'http://localhost:8000/payment/error',
    // Разрешённые подсети ЮKassa для вебхуков (можете скорректировать по документации)
    'allowed_cidrs' => [
        '185.71.76.0/27',
        '185.71.77.0/27',
        '77.75.153.0/25',
        '77.75.156.11/32',
        '77.75.156.35/32',
        '77.75.154.128/25',
        '2a02:5180:0:1509::/64',
        '2a02:5180:0:2655::/64',
        '2a02:5180:0:1533::/64',
        '2a02:5180:0:2669::/64',
    ],
];
