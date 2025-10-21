<!-- templates/pages/payment_error.html.php -->
<div class="cabinet-page">
    <h1>Ошибка при оплате</h1>

    <div class="cabinet-card" style="margin-bottom:12px;">
        <div class="cabinet-card-title">
            <img src="/images/icons/money.png" alt="*" width="20" height="20">
            Платёж не завершён
        </div>
        <div class="info-main-text" style="margin-top:6px;">
            Произошла ошибка при обработке платежа. Вы можете написать нам — подготовим разбор и поможем.
        </div>

        <?php if (!empty($paymentId) || !empty($errorCode)): ?>
            <div class="cabinet-info-list" style="margin-top:8px;">
                <div class="info-row">
                    <span class="info-label">Диагностика</span>
                    <span class="info-value">Укажите эти данные при обращении:</span>
                </div>
                <?php if (!empty($paymentId)): ?>
                <div class="info-row">
                    <span class="info-label">ID платежа</span>
                    <span class="info-value"><?= htmlspecialchars($paymentId) ?></span>
                </div>
                <?php endif; ?>
                <?php if (!empty($errorCode)): ?>
                <div class="info-row">
                    <span class="info-label">Код ошибки</span>
                    <span class="info-value"><?= htmlspecialchars($errorCode) ?></span>
                </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="login-links" style="margin-top:10px; gap:10px; flex-wrap:wrap;">
            <?php
                $prefillSubject = rawurlencode('Ошибка оплаты');
                $prefillMessage = 'Опишите, пожалуйста, что произошло. Если видите ID платежа/код ошибки — приложите их.\n\n' .
                    (!empty($paymentId) ? ('ID платежа: ' . $paymentId . "\n") : '') .
                    (!empty($errorCode) ? ('Код ошибки: ' . $errorCode . "\n") : '');
                $prefillMessage = rawurlencode($prefillMessage);
                $supportUrl = "/support?subject={$prefillSubject}&message={$prefillMessage}";
            ?>
            <a class="link-item" href="<?= $supportUrl ?>">Сообщить об ошибке</a>
            <a class="link-item" href="/support">Открыть поддержку</a>
            <a class="link-item" href="/"><img class="i12img" src="/images/icons/home.png" alt="." width="12" height="12"> На главную</a>
        </div>
    </div>

    <div class="cabinet-card">
        <div class="cabinet-card-title">
            <img src="/images/icons/attention_gold.png" alt="!" width="20" height="20">
            Что можно попробовать
        </div>
        <ul class="document-list" style="margin-top:6px;">
            <li>Проверьте стабильность интернет-соединения.</li>
            <li>Повторите оплату через 1–2 минуты.</li>
            <li>Если средства списались, но статус не обновился — подождите до 5 минут: зачисление может задержаться.</li>
        </ul>
    </div>
</div>