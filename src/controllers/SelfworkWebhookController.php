<?php
// src/controllers/SelfworkWebhookController.php
// Обработчик webhook-уведомлений от Selfwork

class SelfworkWebhookController
{
    public function handle(): void
    {
        $config = require __DIR__ . '/../../config/payment_selfwork.php';
        
        // Selfwork отправляет JSON, но нужно декодировать из php://input
        $_POST = json_decode(file_get_contents('php://input'), true);
        
        $payload = file_get_contents('php://input');
        $data = json_decode($payload, true);

        // Логируем webhook
        $this->logWebhook($payload);

        if (!is_array($data)) {
            http_response_code(400);
            echo json_encode(['ok' => false, 'error' => 'invalid_json']);
            return;
        }

        // Проверка IP-адреса Selfwork (опционально)
        $allowedIps = ['178.205.169.35', '81.23.144.157'];
        $clientIp = $_SERVER['REMOTE_ADDR'] ?? '';
        
        if ($config['debug'] === false && !in_array($clientIp, $allowedIps)) {
            $this->logWebhook('Webhook from unauthorized IP: ' . $clientIp);
            http_response_code(403);
            echo json_encode(['ok' => false, 'error' => 'forbidden']);
            return;
        }

        // Проверка подписи
        $orderId = $data['order_id'] ?? '';
        $amountKopecks = (int)($data['amount'] ?? 0);
        $signature = $data['signature'] ?? '';
        $status = $data['status'] ?? '';

        $service = new SelfworkService();
        if (!$service->verifyWebhookSignature($orderId, $amountKopecks, $signature)) {
            $this->logWebhook('Invalid signature for order: ' . $orderId);
            http_response_code(401);
            echo json_encode(['ok' => false, 'error' => 'invalid_signature']);
            return;
        }

        // Обрабатываем только успешные платежи
        if ($status !== 'succeeded') {
            $this->logWebhook('Payment not succeeded: ' . $orderId . ', status: ' . $status);
            http_response_code(200);
            echo json_encode(['ok' => true, 'message' => 'status_not_succeeded']);
            return;
        }

        try {
            $pdoSite = DatabaseConnection::getSiteConnection();
            $paymentModel = new Payment($pdoSite);

            // Находим платёж по order_id (хранится в yk_id)
            $existing = $paymentModel->findByYkId($orderId);
            
            if (!$existing) {
                // Платёж не найден в БД - создаём новый
                $this->logWebhook('Payment not found in DB, creating: ' . $orderId);
                
                $paymentModel->create([
                    'yk_id' => $orderId,
                    'user_id' => null, // извлечём из order_id если нужно
                    'amount' => $amountKopecks / 100, // копейки -> рубли
                    'currency' => $data['currency'] ?? 'RUB',
                    'status' => 'succeeded',
                    'description' => 'Selfwork payment',
                    'metadata' => $data,
                ]);
                
                $existing = $paymentModel->findByYkId($orderId);
            } else {
                // Обновляем статус
                $paymentModel->updateStatusByYkId($orderId, 'succeeded');
            }

            // Начисляем бонусы
            $userId = (int)$existing['user_id'];
            $amountRub = $amountKopecks / 100;
            $rate = (float)($config['rub_to_coins_rate'] ?? 1.0);
            $coins = (int)round($amountRub * $rate);

            if ($userId > 0 && $coins > 0) {
                require_once __DIR__ . '/../models/CachedAccountCoins.php';
                $coinsModel = new CachedAccountCoins($pdoSite);
                // Причина в истории — коротко: "Покупка"
                $coinsModel->add($userId, $coins, 'Покупка');
                
                $this->logWebhook("Credited $coins coins to user $userId for order $orderId");

                // Создаём внутреннее уведомление пользователю о зачислении
                try {
                    require_once __DIR__ . '/../models/Notification.php';
                    $notification = new Notification();
                    // Создаем уведомление в том же стиле, что и при голосовании
                    $notification->createPaymentCreditNotification($userId, $coins);
                } catch (Throwable $e) {
                    // Логируем, но не прерываем обработку вебхука
                    $this->logWebhook('Notification create error: ' . $e->getMessage());
                }
            } else {
                $this->logWebhook("No coins credited: userId=$userId, coins=$coins");
            }

            http_response_code(200);
            echo json_encode(['ok' => true]);

        } catch (Throwable $e) {
            $this->logWebhook('Error processing webhook: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['ok' => false, 'error' => 'internal_error']);
        }
    }

    private function logWebhook(string $message): void
    {
        $logFile = __DIR__ . '/../../cache/selfwork_webhook.log';
        $timestamp = date('Y-m-d H:i:s');
        file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
    }
}
