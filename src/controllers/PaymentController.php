<?php

class PaymentController {
    /**
     * Страница выбора/создания платежа через Selfwork
     */
    public function create() {
        safeSessionStart();
        try {
            $amount = isset($_POST['amount']) ? (float)$_POST['amount'] : 100.00; // по умолчанию 100 RUB
            $description = 'Пополнение баланса Azeroth';

            $service = new SelfworkService();
            $result = $service->createPayment($_SESSION['user_id'] ?? 0, $amount, $description);

            if (isset($result['error'])) {
                error_log('Selfwork payment creation error: ' . $result['error']);
                safeRedirect('/payment/error?code=create_failed');
                return;
            }

            // Сохраняем в БД
            $siteDb = DatabaseConnection::getSiteConnection();
            $model = new Payment($siteDb);
            $model->create([
                'yk_id' => $result['order_id'], // используем yk_id для хранения Selfwork order_id
                'user_id' => $_SESSION['user_id'] ?? null,
                'amount' => $amount,
                'currency' => 'RUB',
                'status' => 'pending',
                'description' => $description,
            ]);

            // Выводим HTML-страницу оплаты напрямую
            if (!empty($result['html_content'])) {
                echo $result['html_content'];
                exit;
            } else {
                error_log('Selfwork HTML content missing');
                safeRedirect('/payment/error?code=no_payment_html');
            }
        } catch (Throwable $e) {
            error_log('Payment create error: ' . $e->getMessage());
            safeRedirect('/payment/error?code=create_failed');
        }
    }

    /**
     * Возврат с Selfwork (return_url): показываем промежуточную страницу
     */
    public function return() {
        $data = [
            'contentFile' => 'pages/payment_return.html.php',
            'pageTitle' => 'Возврат с оплаты',
        ];
        renderTemplate('layout.html.php', $data);
    }
    /**
     * Страница ошибки оплаты
     */
    public function error() {
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
