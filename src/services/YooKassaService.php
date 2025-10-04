<?php

class YooKassaService
{
    private string $shopId;
    private string $secretKey;
    private string $apiBase;
    private string $returnUrl;
    private string $errorUrl;

    public function __construct()
    {
        $cfg = require __DIR__ . '/../../config/yookassa.php';
        $this->shopId = $cfg['shop_id'];
        $this->secretKey = $cfg['secret_key'];
        $this->apiBase = rtrim($cfg['api_base'], '/');
        $this->returnUrl = $cfg['return_url'];
        $this->errorUrl = $cfg['error_url'];
    }

    public function createPayment(float $amount, string $description, array $metadata = []): array
    {
        $payload = [
            'amount' => [
                'value' => number_format($amount, 2, '.', ''),
                'currency' => 'RUB',
            ],
            'capture' => true,
            'confirmation' => [
                'type' => 'redirect',
                'return_url' => $this->returnUrl,
            ],
            'description' => mb_substr($description, 0, 128),
            'metadata' => $metadata,
        ];

        $idempotenceKey = bin2hex(random_bytes(16));

        $ch = curl_init($this->apiBase . '/payments');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_USERPWD => $this->shopId . ':' . $this->secretKey,
            CURLOPT_HTTPHEADER => [
                'Idempotence-Key: ' . $idempotenceKey,
                'Content-Type: application/json',
            ],
            CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE),
            CURLOPT_TIMEOUT => 15,
        ]);

        $response = curl_exec($ch);
        if ($response === false) {
            $err = curl_error($ch);
            curl_close($ch);
            throw new RuntimeException('YooKassa cURL error: ' . $err);
        }
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $data = json_decode($response, true);
        if ($code >= 400) {
            $msg = $data['description'] ?? ('HTTP ' . $code);
            throw new RuntimeException('YooKassa API error: ' . $msg);
        }

        return $data;
    }
}
