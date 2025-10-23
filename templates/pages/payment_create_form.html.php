<!-- templates/pages/payment_create_form.html.php -->
<div class="cabinet-page">
    <h1>Пополнение баланса</h1>

    <div class="cabinet-hero block-border" style="text-align:center; margin-bottom: 10px;">
        <img src="/images/kollekzioner_310_blue.jpg" alt="Пополнение баланса">
    </div>

    <div class="cabinet-card">
        <div class="cabinet-card-title">
            <img src="/images/icons/money.png" width="24" height="24" alt="*">
            Оплата через Selfwork
        </div>
        <div class="info-main-text" style="margin-bottom:12px;">
            Пополните баланс бонусов для покупок в игровом магазине. После оплаты бонусы автоматически зачислятся на ваш аккаунт.
        </div>

        <form method="post" action="/payment/create" class="login-form" style="margin-top:8px;" target="_blank">
            <label for="amount" class="form-label" style="display:block; margin-bottom:6px; color:#c9d1ff;">
                Сумма пополнения (RUB)
            </label>
            <input id="amount" class="form-input" type="number" step="0.01" min="1" name="amount" value="100.00" style="max-width:240px;">

            <div class="form-hint">1 RUB = 1 бонус. Безопасная оплата через Selfwork.</div>

            <button type="submit" class="restore-button" style="margin-top:10px;">
                <img src="/images/icons/money.png" width="16" height="16" alt="*" style="vertical-align: middle; margin-right: 6px;">
                Перейти к оплате
            </button>
        </form>

        <div class="restore-info" style="margin-top:12px;">
            После успешной оплаты бонусы поступят на ваш счет автоматически в течение нескольких минут.
        </div>
    </div>

    <div class="login-links" style="margin-top:16px">
        <a href="/" class="link-item"><img class="i12img" src="/images/icons/home.png" alt="." width="12" height="12"> На главную</a>
        <a href="/cabinet" class="link-item"><img src="/images/icons/menialo.png" alt="*" width="12" height="12"> В кабинет</a>
        <a href="/shop" class="link-item"><img src="/images/icons/shop.png" alt="*" width="12" height="12"> В магазин</a>
    </div>
</div>