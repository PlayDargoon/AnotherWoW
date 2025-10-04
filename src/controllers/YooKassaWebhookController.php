<?php

class YooKassaWebhookController
{
    public function handle()
    {
        // Валидация IP
        $cfg = require __DIR__ . '/../../config/yookassa.php';
        $allowedCidrs = $cfg['allowed_cidrs'] ?? [];
        $clientIp = $_SERVER['REMOTE_ADDR'] ?? '';
        $isLocal = in_array($clientIp, ['127.0.0.1', '::1']);

        $allowed = $isLocal; // в локальном режиме пропускаем IP проверку
        if (!$allowed) {
            foreach ($allowedCidrs as $cidr) {
                if (ipInCidr($clientIp, $cidr)) { $allowed = true; break; }
            }
        }

        if (!$allowed) {
            http_response_code(403);
            echo 'Forbidden';
            return;
        }

        // Чтение JSON
        $raw = file_get_contents('php://input');
        $json = json_decode($raw, true);
        if (!$json || empty($json['event']) || empty($json['object'])) {
            http_response_code(400);
            echo 'Bad Request';
            return;
        }

        $event = $json['event'];
        $obj = $json['object'];
        $ykId = $obj['id'] ?? null;

        if (!$ykId) {
            http_response_code(400);
            echo 'No payment id';
            return;
        }

        $siteDb = DatabaseConnection::getSiteConnection();
        $paymentModel = new Payment($siteDb);

        switch ($event) {
            case 'payment.succeeded':
                $paymentModel->updateStatusByYkId($ykId, 'succeeded');
                // Начислить монеты
                $payment = $paymentModel->findByYkId($ykId);
                if ($payment) {
                    $userId = (int)($payment['user_id'] ?? 0);
                    $amount = (float)$payment['amount'];
                    $rate = (float)($cfg['rub_to_coins_rate'] ?? 1);
                    $coins = max(0, (int)round($amount * $rate));

                    // В нашей модели user_id совпадает с account_id
                    $accountId = $userId;
                    if ($accountId > 0 && $coins > 0) {
                        require_once __DIR__ . '/../models/CachedAccountCoins.php';
                        $coinsModel = new CachedAccountCoins(DatabaseConnection::getSiteConnection());
                        $coinsModel->add($accountId, $coins, 'Пополнение через YooKassa (' . $ykId . ')');
                    }
                }
                break;

            case 'payment.canceled':
                $paymentModel->updateStatusByYkId($ykId, 'canceled');
                break;

            case 'payment.waiting_for_capture':
                $paymentModel->updateStatusByYkId($ykId, 'waiting_for_capture');
                break;
        }

        http_response_code(200);
        echo 'OK';
    }
}
