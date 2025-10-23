<?php
// tools/selfwork_webhook_cli.php
// Локальный тест: вызывает контроллер SelfworkWebhookController в режиме отладки

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../src/services/DatabaseConnection.php';
require_once __DIR__ . '/../src/models/Payment.php';
require_once __DIR__ . '/../src/models/CachedAccountCoins.php';
require_once __DIR__ . '/../src/controllers/SelfworkWebhookController.php';

$payload = [
    'event' => 'payment.succeeded',
    'object' => [
        'id' => 'SW_TEST_001',
        'amount' => 150.00,
        'currency' => 'RUB',
        'metadata' => [
            'user_id' => 123,
            'coins' => 150,
        ],
    ],
];

// Симулируем GET запрос с payload, чтобы контроллер принял данные без php://input
$_SERVER['REQUEST_METHOD'] = 'GET';
$_GET['payload'] = json_encode($payload, JSON_UNESCAPED_UNICODE);

$controller = new SelfworkWebhookController();
$controller->handle();
