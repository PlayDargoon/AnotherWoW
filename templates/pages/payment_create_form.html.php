<!-- templates/pages/payment_create_form.html.php -->
<div class="body">
    <h2 class="section-title">Пополнение баланса</h2>
    <div class="bluepost">
        <form method="post" action="/payment/create">
            <div class="pt">
                <label><span class="info">Сумма (RUB)</span></label><br>
                <input type="number" step="0.01" min="1" name="amount" value="100.00">
            </div>
            <div class="pt">
                <input type="submit" class="headerButton _c-pointer" value="Оплатить">
            </div>
        </form>
    </div>
    <div class="footer nav block-border-top">
        <ol>
            <li>
                <img src="/images/icons/home.png" alt="." width="12" height="12" class="i12img">
                <a href="/">На главную</a>
            </li>
        </ol>
    </div>
</div>
