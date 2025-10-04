<!-- templates/pages/payment_error.html.php -->
<div class="body">
    <h2 class="section-title">Ошибка при оплате</h2>

    <div class="bluepost">
        <p>
            Произошла ошибка при обработке платежа. Пожалуйста, свяжитесь с нами с помощью кнопки
            <strong>«Сообщить об ошибке»</strong>, расположенной ниже.
        </p>
        <p>
            Также вы всегда можете обратиться в службу поддержки игроков, воспользовавшись кнопкой
            <strong>«Поддержка»</strong> в левом нижнем углу экрана.
        </p>

        <?php if (!empty($paymentId) || !empty($errorCode)): ?>
            <div class="pt">
                <span class="info">Детали для диагностики</span>
                <div>
                    <?php if (!empty($paymentId)): ?>
                        <div><strong class="gold">ID платежа:</strong> <?= htmlspecialchars($paymentId) ?></div>
                    <?php endif; ?>
                    <?php if (!empty($errorCode)): ?>
                        <div><strong class="gold">Код ошибки:</strong> <?= htmlspecialchars($errorCode) ?></div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

    <div class="pt">
            <?php
                // Подготовим ссылку для быстрого обращения в поддержку с префиллом темы и сообщения
                $prefillSubject = rawurlencode('Ошибка оплаты');
                $prefillMessage = 'Опишите, пожалуйста, что произошло. Если видите ID платежа/код ошибки — приложите их.\n\n' .
                    (!empty($paymentId) ? ('ID платежа: ' . $paymentId . "\n") : '') .
                    (!empty($errorCode) ? ('Код ошибки: ' . $errorCode . "\n") : '');
                $prefillMessage = rawurlencode($prefillMessage);
                $supportUrl = "/support?subject={$prefillSubject}&message={$prefillMessage}";
            ?>
            <a class="headerButton _c-pointer" href="<?= $supportUrl ?>">
                Сообщить об ошибке
            </a>
            <div class="pt">
                <a class="headerButton _c-pointer" href="/support">
                    Открыть поддержку
                </a>
            </div>
        </div>
    </div>

    <div class="bluepost">
        <h3 style="color:#ffff33;">Что можно попробовать</h3>
        <ul>
            <li>Проверьте, что у вас стабильное интернет-соединение.</li>
            <li>Попробуйте выполнить оплату повторно через 1–2 минуты.</li>
            <li>Если оплата уже списалась, но статус на сайте не обновился — просто подождите: зачёт может занять до 5 минут.</li>
        </ul>
    </div>

    
</div>
<div class="footer nav block-border-top">
        <ol>
            <li>
                <img src="/images/icons/home.png" alt="." width="12" height="12" class="i12img">
                <a href="/">На главную</a>
            </li>
            <li>
                <img src="/images/icons/question_blue.png" alt="." width="12" height="12" class="i12img">
                <a href="/support">Поддержка</a>
            </li>
        </ol>
    </div>