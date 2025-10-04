<?php

class PaymentController {
    /**
     * Страница выбора/создания платежа (минимальный MVP: создать фиксированный платёж)
     */
    public function create() {
        safeSessionStart();
        try {
            $amount = isset($_POST['amount']) ? (float)$_POST['amount'] : 100.00; // по умолчанию 100 RUB
            $description = 'Покупка монет на Azeroth';

            $service = new YooKassaService();
            $payment = $service->createPayment($amount, $description, [
                'user_id' => $_SESSION['user_id'] ?? null,
            ]);

            // Сохраним в БД
            $siteDb = DatabaseConnection::getSiteConnection();
            $model = new Payment($siteDb);
            $model->create([
                'yk_id' => $payment['id'],
                'user_id' => $_SESSION['user_id'] ?? null,
                'amount' => (float)$payment['amount']['value'],
                'currency' => $payment['amount']['currency'] ?? 'RUB',
                'status' => $payment['status'] ?? 'pending',
                'description' => $description,
            ]);

            $confirmationUrl = $payment['confirmation']['confirmation_url'] ?? null;
            if ($confirmationUrl) {
                safeRedirect($confirmationUrl);
            }

            // Если по какой-то причине нет URL подтверждения — отправим на страницу ошибки оплаты
            safeRedirect('/payment/error');
        } catch (Throwable $e) {
            error_log('Payment create error: ' . $e->getMessage());
            safeRedirect('/payment/error?code=create_failed');
        }
    }

    /**
     * Возврат с ЮKassa (return_url): показываем промежуточную страницу и советуем дождаться вебхука
     */
    public function return() {
        $data = [
            'contentFile' => 'pages/payment_return.html.php',
            'pageTitle' => 'Возврат с оплаты',
        ];
        renderTemplate('layout.html.php', $data);
    }
    /**
     * Страница ошибки оплаты для редиректа из ЮKassa
     */
    public function error() {
        // Можно прокинуть идентификатор платежа и код ошибки из GET, если они передаются
        $paymentId = isset($_GET['payment_id']) ? (string)$_GET['payment_id'] : null;
        $errorCode = isset($_GET['code']) ? (string)$_GET['code'] : null;

        $data = [
            'contentFile' => 'pages/payment_error.html.php',
            'pageTitle' => 'Ошибка оплаты',
            'paymentId' => $paymentId,
            'errorCode' => $errorCode,
        ];
        renderTemplate('layout.html.php', $data);
    }
}
