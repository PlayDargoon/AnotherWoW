<?php
// tools/test_selfwork_webhook.php
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

$ctx = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => "Content-Type: application/json\r\n",
        'content' => json_encode($payload, JSON_UNESCAPED_UNICODE),
        'timeout' => 5,
    ],
]);

$url = 'http://127.0.0.1:8000/selfwork/webhook';
$response = @file_get_contents($url, false, $ctx);
$http_response_header = $http_response_header ?? [];
echo "Response: ".$response."\n";
echo "Headers:\n".implode("\n", $http_response_header)."\n";