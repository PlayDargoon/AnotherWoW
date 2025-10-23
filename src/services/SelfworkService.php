<?php
// src/services/SelfworkService.php
// Сервис для создания платежей через Selfwork API

class SelfworkService
{
    private array $config;

    public function __construct()
    {
        $this->config = require __DIR__ . '/../../config/payment_selfwork.php';
    }

    /**
     * Создать платёж через Selfwork и получить HTML-страницу оплаты
     *
     * @param int $userId ID пользователя
     * @param float $amount Сумма в рублях
     * @param string $description Описание платежа (будет в чеке)
     * @return array ['order_id' => string, 'payment_url' => string] или ['error' => string]
     */
    public function createPayment(int $userId, float $amount, string $description = 'Пополнение баланса'): array
    {
        // Генерируем уникальный order_id (макс 35 символов)
        $orderId = 'AZ_' . $userId . '_' . time() . '_' . substr(md5(uniqid()), 0, 8);
        
        // Сумма в копейках
        $amountKopecks = (int)round($amount * 100);
        
        // Формируем данные для товара (для чека)
        $info = [
            0 => [
                'name' => $description,
                'quantity' => 1,
                'amount' => $amountKopecks,
            ]
        ];
        
        // Создаём подпись (SHA-256)
        $signature = $this->generateSignature($orderId, $amountKopecks, $info);
        
        // Формируем тело запроса (application/x-www-form-urlencoded)
        $postData = http_build_query([
            'order_id' => $orderId,
            'amount' => (string)$amountKopecks,
            'signature' => $signature,
            'info' => $info,
        ]);
        
        $url = $this->config['base_url'] . '/merchant/v1/init';
        
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded',
                'Origin: ' . $this->config['site_base'],
                'Referer: ' . $this->config['site_base'],
            ],
            CURLOPT_TIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => false, // Для теста - на проде включить!
            CURLOPT_SSL_VERIFYHOST => false,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            return ['error' => 'CURL Error: ' . $curlError];
        }

        if ($httpCode !== 200) {
            return ['error' => 'Ошибка API Selfwork: HTTP ' . $httpCode . ' | ' . substr($response, 0, 500)];
        }

        // Selfwork возвращает HTML-страницу оплаты - возвращаем её напрямую
        return [
            'order_id' => $orderId,
            'html_content' => $response,
        ];
    }

    /**
     * Генерация подписи для запроса
     * 
     * @param string $orderId
     * @param int $amountKopecks
     * @param array $info
     * @return string SHA-256 hash (hex lowercase)
     */
    private function generateSignature(string $orderId, int $amountKopecks, array $info): string
    {
        // Формируем строку: order_id + amount + info[0-5][name+quantity+amount] + secret_key
        $signatureString = $orderId . $amountKopecks;
        
        foreach ($info as $item) {
            $signatureString .= $item['name'] . $item['quantity'] . $item['amount'];
        }
        
        $signatureString .= $this->config['secret_key'];
        
        // SHA-256 в HEX (lowercase)
        return hash('sha256', $signatureString);
    }

    /**
     * Проверка статуса платежа (требует Basic Auth)
     * 
     * @param string $orderId
     * @return array|null
     */
    public function getPaymentStatus(string $orderId): ?array
    {
        $url = $this->config['base_url'] . '/merchant/v1/status?order_id=' . urlencode($orderId);
        
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_USERPWD => $this->config['merchant_id'] . ':' . $this->config['secret_key'],
            CURLOPT_TIMEOUT => 10,
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode !== 200) {
            return null;
        }
        
        return json_decode($response, true);
    }

    /**
     * Проверка подписи входящего webhook
     * 
     * @param string $orderId
     * @param int $amountKopecks
     * @param string $receivedSignature
     * @return bool
     */
    public function verifyWebhookSignature(string $orderId, int $amountKopecks, string $receivedSignature): bool
    {
        // Формируем строку: order_id + amount + secret_key
        $signatureString = $orderId . $amountKopecks . $this->config['secret_key'];
        $expectedSignature = hash('sha256', $signatureString);
        
        return hash_equals($expectedSignature, $receivedSignature);
    }
}
