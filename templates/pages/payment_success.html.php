<!-- templates/pages/payment_success.html.php -->
<div class="cabinet-page">
    <h1>Оплата успешно выполнена</h1>

    <div class="cabinet-card">
        <div class="cabinet-card-title">
            <img src="/images/icons/tick.png" alt="✓" width="20" height="20">
            Платёж подтверждён
        </div>

        <?php
        // Selfwork передаёт: ?success&id=ORDER_ID или ?error&id=ORDER_ID
        $isSuccess = isset($_GET['success']);
        $isError = isset($_GET['error']);
        $orderId = htmlspecialchars($_GET['id'] ?? ($_GET['payment_id'] ?? ''), ENT_QUOTES, 'UTF-8');
        
        // Пытаемся получить данные платежа из БД
        $amount = '';
        $currency = 'RUB';
        
        if ($orderId) {
            try {
                $siteDb = DatabaseConnection::getSiteConnection();
                $paymentModel = new Payment($siteDb);
                $payment = $paymentModel->findByYkId($orderId);
                if ($payment) {
                    $amount = $payment['amount'];
                    $currency = $payment['currency'] ?? 'RUB';
                }
            } catch (Throwable $e) {
                // Игнорируем ошибки
            }
        }
        ?>
        <div class="info-main-text" style="margin-top:6px;">
            <?php if ($isSuccess): ?>
                Спасибо за поддержку проекта! Ваш платёж успешно обработан.
            <?php elseif ($isError): ?>
                <span style="color: #ff6b6b;">Произошла ошибка при обработке платежа. Пожалуйста, попробуйте снова или обратитесь в поддержку.</span>
            <?php else: ?>
                Спасибо за поддержку проекта!
            <?php endif; ?>
        </div>

        <div class="login-success" style="margin-top:10px;">
            <?php if ($orderId): ?>
                <div><strong>ID платежа:</strong> <?= $orderId ?></div>
            <?php endif; ?>
            <?php if ($amount): ?>
                <div><strong>Сумма:</strong> <?= number_format($amount, 2, '.', '') ?> <?= $currency ?></div>
            <?php endif; ?>
            <?php if ($isSuccess): ?>
                <div>Бонусы будут зачислены автоматически после получения уведомления от платёжной системы (обычно в течение 1–5 минут).</div>
            <?php endif; ?>
        </div>

        <div class="login-links" style="margin-top:12px;">
            <a class="link-item" href="/cabinet"><img class="i12img" src="/images/icons/menialo.png" alt="." width="12" height="12"> В кабинет</a>
            <a class="link-item" href="/"><img class="i12img" src="/images/icons/home.png" alt="." width="12" height="12"> На главную</a>
            <a class="link-item" href="/support"><img class="i12img" src="/images/icons/question_blue.png" alt="." width="12" height="12"> Поддержка</a>
        </div>
    </div>
</div>
